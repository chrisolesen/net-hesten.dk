<?php

class auctions
{

	public static function list_bids($attr = [])
	{
		global $link_new;
		$return_data = [];
		$defaults = [];
		foreach ($defaults as $key => $value) {
			isset($attr[$key]) ?: $attr[$key] = $value;
		}
		foreach ($attr as $key => $value) {
			$attr[$key] = $link_new->real_escape_string($value);
		}
		$sql =  'SELECT '
			. 'a.object_id AS horse_id, '
			. 'a.status_code AS auction_status, '
			. 'b.bid_amount, '
			. 'b.bid_date, '
			. 'b.status_code AS bid_status, '
			. 'b.auction, '
			. 'b.creator '
			. 'FROM game_data_auction_bids AS b '
			. 'LEFT JOIN game_data_auctions AS a '
			. 'ON a.id = b.auction '
			. "WHERE b.creator = {$_SESSION['user_id']} "
			. "ORDER BY b.auction DESC, b.bid_amount DESC";

		$result = $link_new->query($sql);
		if ($result) {
			while ($data = $result->fetch_assoc()) {
				$return_data[] = [
					'bid_amount' => $data['bid_amount'],
					'bid_date' => $data['bid_date'],
					'auction_status_code' => $data['auction_status'],
					'status_code' => $data['bid_status'],
					'auction' => $data['auction'],
					'creator' => $data['creator'],
					'horse_id' => $data['horse_id']
				];
			}
			return $return_data;
		} else {
			return false;
		}
	}

	public static function get_highest_bid($attr = ['auction_id'])
	{
		global $link_new;
		$return_data = [];
		$defaults = [];
		foreach ($defaults as $key => $value) {
			isset($attr[$key]) ?: $attr[$key] = $value;
		}
		foreach ($attr as $key => $value) {
			$attr[$key] = $link_new->real_escape_string($value);
		}
		$sql = ''
			. 'SELECT '
			. 'highest_bid, '
			. 'highest_bidder '
			. 'FROM game_data_auctions '
			. "WHERE id = {$attr['auction_id']} ";

		$result = $link_new->query($sql);
		if ($result) {
			while ($data = $result->fetch_assoc()) {
				$return_data = ['bid_amount' => $data['highest_bid'], 'creator' => $data['highest_bidder']];
			}
			return $return_data;
		} else {
			return false;
		}
	}

	public static function place_bid($attr = [])
	{
		global $link_new;
		$return_data = [];
		$defaults = [];
		$attr['bid_amount'] = str_replace('.', '', $attr['bid_amount']);
		foreach ($defaults as $key => $value) {
			isset($attr[$key]) ?: $attr[$key] = $value;
		}
		foreach ($attr as $key => $value) {
			$attr[$key] = $link_new->real_escape_string($value);
		}

		$auction_data = ($link_new->query("SELECT id, object_id, creator, status_code, minimum_price, instant_price, highest_bidder, highest_bid, end_date FROM {$GLOBALS['DB_NAME_NEW']}.game_data_auctions WHERE id = {$attr['auction_id']} AND status_code = 1 LIMIT 1"))->fetch_assoc();
		if (!$auction_data['highest_bidder']) {
			$auction_data['highest_bidder'] = null;
		}
		if (!$auction_data['highest_bid']) {
			$auction_data['highest_bid'] = null;
		}


		if ($auction_data) {

			if ($attr['mode'] == 'buy_now') {
				$bid_date = new DateTime('now');
				$sql = "SELECT stutteri, penge FROM {$GLOBALS['DB_NAME_OLD']}.Brugere WHERE id = {$_SESSION['user_id']} LIMIT 1";
				$result = $link_new->query($sql);
				if ($result) {
					$data = $result->fetch_assoc();
					$temp_user_data = ['money' => $data['penge'], 'username' => $data['stutteri']];
					if (!($temp_user_data['money'] >= $auction_data['instant_price'])) {
						return ["Du har kun {$temp_user_data['money']}wkr, hesten koster {$auction_data['instant_price']}.", 'warning'];
					}
				} else {
					return ['Kritisk fejl: #4, prøv igen eller kontakt en admin evt. på admin@net-hesten.dk', 'error'];
				}
				if ($auction_data['status_code'] != 1 || $auction_data['end_date'] < $bid_date->format('Y-m-d H:i:s')) {
					return ["Denne auktion er desværre slut.", 'error'];
				}

				if (is_null($auction_data['highest_bid'])) {
					$link_new->query("UPDATE game_data_auctions SET highest_bidder = {$_SESSION['user_id']}, highest_bid = {$auction_data['instant_price']} WHERE  id = {$attr['auction_id']} AND status_code = 1 LIMIT 1");
				} else {
					$link_new->query("UPDATE game_data_auctions SET highest_bidder = {$_SESSION['user_id']}, highest_bid = {$auction_data['instant_price']} WHERE highest_bidder = {$auction_data['highest_bidder']} AND highest_bid = {$auction_data['highest_bid']} AND id = {$attr['auction_id']} AND status_code = 1 LIMIT 1");
				}

				if (mysqli_affected_rows($link_new) != 0) {
					if (!is_null($auction_data['highest_bid'])) {
						$link_new->query("UPDATE game_data_auction_bids SET status_code = 5 WHERE bid_amount = {$auction_data['highest_bid']} AND creator = {$auction_data['highest_bidder']} AND auction = {$auction_data['id']}");
						$sql = "INSERT INTO game_data_private_messages "
							. "(status_code, origin, target, date, message) "
							. "VALUES "
							. "(17, 52745, {$auction_data['highest_bidder']}, NOW(), 'Hesten med ID:{$auction_data['object_id']} er desværre blevet købt på auktionshuset, pengene {$auction_data['highest_bid']}wkr er returneret til din konto.')";
						$link_new->query($sql);
						accounting::add_entry(['amount' => $auction_data['highest_bid'], 'line_text' => "Overbudt på auktion", 'mode' => '+', 'user_id' => $auction_data['highest_bidder']]);
					}

					$link_new->query("INSERT INTO game_data_auction_bids (creator, auction, bid_amount, bid_date, status_code) VALUES ({$_SESSION['user_id']}, {$attr['auction_id']}, {$auction_data['instant_price']}, '{$bid_date->format('Y-m-d H:i:s')}', 4)");
					
					$auction_fee = round(max(500, ($attr['instant_price'] * 0.005)), 0);
					accounting::add_entry(['amount' => $auction_data['instant_price'], 'line_text' => "Din hest er solgt på auktion", 'mode' => '+', 'user_id' => $auction_data['creator']]);
					accounting::add_entry(['amount' => $auction_fee, 'line_text' => "Auktionshus gebyr", 'user_id' => $auction_data['creator']]);
									
					
					accounting::add_entry(['user_id' => $_SESSION['user_id'], 'amount' => (int) $attr['instant_price'], 'line_text' => "Kbt hest på auktion"]);
					horses::change_owner(['new_owner' => $_SESSION['user_id'], 'old_owner' => 'Auktionshuset', 'horse_id' => $auction_data['object_id']]);
					$link_new->query("UPDATE game_data_auctions SET status_code = 2 WHERE  id = {$attr['auction_id']} AND status_code = 1 LIMIT 1");

					return ["Du har købt hesten.", 'success'];
				}
			}

			if ($attr['mode'] == 'place_bid') {
				$bid_date = new DateTime('now');
				$sql = "SELECT stutteri, penge FROM {$GLOBALS['DB_NAME_OLD']}.Brugere WHERE id = {$_SESSION['user_id']} LIMIT 1";
				$result = $link_new->query($sql);
				if ($result) {
					$data = $result->fetch_assoc();
					$temp_user_data = ['money' => $data['penge'], 'username' => $data['stutteri']];
					if (!($temp_user_data['money'] >= $attr['bid_amount'])) {
						return ["Du kan ikke byde {$attr['bid_amount']}, du har kun {$temp_user_data['money']}.", 'warning'];
					}
				} else {
					return ['Kritisk fejl: #4, prøv igen eller kontakt en admin evt. på admin@net-hesten.dk', 'error'];
				}
				if ($auction_data['status_code'] != 1 || $auction_data['end_date'] < $bid_date->format('Y-m-d H:i:s')) {
					return ["Denne auktion er desværre slut.", 'error'];
				}

				$minimum_bid = max($auction_data['minimum_price'], ($auction_data['highest_bid'] * 1.01));
				if (!($attr['bid_amount'] >= $minimum_bid)) {
					return ["Dit bud er for lavt, mindste bud på denne auktion er {$minimum_bid}.", 'warning'];
				}

				if (is_null($auction_data['highest_bid'])) {
					$link_new->query("UPDATE game_data_auctions SET highest_bidder = {$_SESSION['user_id']}, highest_bid = {$attr['bid_amount']} WHERE  id = {$attr['auction_id']} AND status_code = 1 LIMIT 1");
				} else {
					$link_new->query("UPDATE game_data_auctions SET highest_bidder = {$_SESSION['user_id']}, highest_bid = {$attr['bid_amount']} WHERE highest_bidder = {$auction_data['highest_bidder']} AND highest_bid = {$auction_data['highest_bid']} AND id = {$attr['auction_id']} AND status_code = 1 LIMIT 1");
				}

				if (mysqli_affected_rows($link_new) != 0) {
					if (!is_null($auction_data['highest_bid'])) {
						$link_new->query("UPDATE game_data_auction_bids SET status_code = 5 WHERE bid_amount = {$auction_data['highest_bid']} AND creator = {$auction_data['highest_bidder']} AND auction = {$auction_data['id']}");
						$sql = "INSERT INTO game_data_private_messages "
							. "(status_code, origin, target, date, message) "
							. "VALUES "
							. "(17, 52745, {$auction_data['highest_bidder']}, NOW(), 'Du er desvære blevet overbudt på en auktion på hesten med ID:{$auction_data['object_id']} , pengene {$auction_data['highest_bid']}wkr er returneret til din konto.')";
						$link_new->query($sql);
						accounting::add_entry(['amount' => $auction_data['highest_bid'], 'line_text' => "Overbudt på auktion", 'mode' => '+', 'user_id' => $auction_data['highest_bidder']]);
					}
					$link_new->query("INSERT INTO game_data_auction_bids (creator, auction, bid_amount, bid_date, status_code) VALUES ({$_SESSION['user_id']}, {$attr['auction_id']}, {$attr['bid_amount']}, '{$bid_date->format('Y-m-d H:i:s')}', 4)");
					accounting::add_entry(['amount' => $attr['bid_amount'], 'line_text' => "Bud på auktion"]);
					return ["Dit bud er registreret.", 'success'];
				}
			}
		} else {
			return ['Kritisk fejl: #5, prøv igen eller kontakt en admin evt. på admin@net-hesten.dk', 'error'];
		}
		if ($result) {
			while ($data = $result->fetch_assoc()) {
				$return_data = ['bid_amount' => $data['bid_amount'], 'creator' => $data['creator']];
			}
			return $return_data;
		} else {
			return false;
		}
	}

	public static function get_all($attr = [])
	{
		global $link_new;
		$return_data = [];
		$defaults = [];
		foreach ($defaults as $key => $value) {
			isset($attr[$key]) ?: $attr[$key] = $value;
		}
		foreach ($attr as $key => $value) {
			$attr[$key] = $link_new->real_escape_string($value);
		}
		//			game_data_auctions - id, creator, status_code, object_id, object_type, minimum_price, instant_price, creation_date, end_date
		//			game_data_auction_bids - creator, auction, bid_amount, bid_date, status_code
		//			game_data_status_codes - id, name, description = auction_live, auction_ended, auction_halted, bid_accepted, bid_refunded, bid_won
		$sql = ''
			. 'SELECT '
			. '* '
			. 'FROM game_data_auctions '
			. 'WHERE status_code = 1 '
			. (isset($attr['creator']) ? "AND creator = {$attr['creator']} " : '')
			. 'ORDER BY end_date ASC, id ASC '
			. (isset($attr['limit']) ? "LIMIT {$attr['limit']} " : '')
			. (isset($attr['offset']) ? "OFFSET {$attr['offset']} " : '');

		$result = $link_new->query($sql);
		$i = 0;
		while ($data = $result->fetch_assoc()) {
			foreach ($data as $key => $info) {
				$return_data[$i][$key] = $info;
			}
			++$i;
		}
		return $return_data;
	}

	public static function get_one($attr = [])
	{
		global $link_new;
		$return_data = [];
		$defaults = [];
		foreach ($defaults as $key => $value) {
			isset($attr[$key]) ?: $attr[$key] = $value;
		}
		foreach ($attr as $key => $value) {
			$attr[$key] = $link_new->real_escape_string($value);
		}
		$sql = ''
			. 'SELECT '
			. '* '
			. 'FROM game_data_auctions '
			. 'WHERE (status_code = 1 or status_code = 2) '
			. "AND id = {$attr['id']} "
			. "LIMIT 1";

		$result = $link_new->query($sql);

		$i = 0;
		while ($data = $result->fetch_assoc()) {
			foreach ($data as $key => $info) {
				$return_data[$i][$key] = $info;
			}
			++$i;
		}
		return $return_data;
	}

	public static function put_on_sale($attr = [])
	{
		global $link_new;
		$return_data = [];
		$default_date_holder = new DateTime('now');
		$five_days_in_future = $default_date_holder->add(new DateInterval('P5D'));
		if ($attr['sell_date'] == '' || $attr['sell_date'] == false) {
			$attr['sell_date'] == $five_days_in_future->format('Y-m-d');
		}
		$defaults = [];
		foreach ($defaults as $key => $value) {
			isset($attr[$key]) ?: $attr[$key] = $value;
		}
		foreach ($attr as $key => $value) {
			$attr[$key] = $link_new->real_escape_string($value);
		}
		if (isset($attr['horse_id'])) {
			/* rows creator, status_code, object_id, object_type, minimum_price, instant_price, creation_date, end_date */
			/* Verify ownership of horse */
			$horses = horses::get_all(['user_name' => $_SESSION['username']]);
			/* TODO: optimise this loop */
			$seller_user_name = $_SESSION['username'];

			foreach ($horses as $horse) {
				if ($horse['id'] == ($attr['horse_id'])) {
					/* Transfer horse to auctions user */
					$sql = "UPDATE {$GLOBALS['DB_NAME_OLD']}.Heste SET bruger = 'Auktionshuset' WHERE id = '{$horse['id']}' AND bruger = '{$seller_user_name}'";
					$result = $link_new->query($sql);
					/* Insert horse into auctions table */
					$sql = "INSERT INTO game_data_auctions (creator, status_code, object_id, object_type, minimum_price, instant_price, creation_date, end_date) "
						. "VALUES ('{$attr['seller_id']}', '1', '{$attr['horse_id']}', '1', '{$attr['minimum_bid']}','{$attr['buy_now_price']}', NOW(), '{$attr['sell_date']} 17:00:00' )";
					$result = $link_new->query($sql);
					/* TODO */
					/* Verify that horse is in auctions table or transfer back to user */
					/* Withdraw the posting charge from the creator */
					/* Output response to user */
					return ["Din hest er nu sat på auktion!", 'success'];
				}
			}
			return ["Du ejer ikke den hest du har forsøgt at sælge!", "error"];
		} else {
			return ['Kritisk fejl: #1', 'error'];
		}
	}
}
