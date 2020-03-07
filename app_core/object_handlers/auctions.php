<?php

class auctions {

	public static function list_bids($attr = []) {
		global $link_new;
		global $link_old;
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

	public static function get_highest_bid($attr = ['auction_id']) {
		global $link_new;
		global $link_old;
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
				. 'bid_amount, '
				. 'creator '
				. 'FROM game_data_auction_bids '
				. "WHERE (status_code = 4 OR status_code = 6) AND auction = {$attr['auction_id']} "
				. "ORDER BY bid_amount DESC LIMIT 1";

		$result = $link_new->query($sql);
		if ($result) {
			while ($data = $result->fetch_assoc()) {
				$return_data = ['bid_amount' => $data['bid_amount'], 'creator' => $data['creator']];
			}
			return $return_data;
		} else {
			return false;
		}
	}

	public static function place_bid($attr = []) {
		global $link_new;
		global $link_old;
		$return_data = [];
		$defaults = [];
		$attr['bid_amount'] = str_replace('.', '', $attr['bid_amount']);
		foreach ($defaults as $key => $value) {
			isset($attr[$key]) ?: $attr[$key] = $value;
		}
        foreach ($attr as $key => $value) {
            $attr[$key] = $link_new->real_escape_string($value);
        }

		if ($attr['mode'] == 'buy_now') {
			$bid_date = new DateTime('now');
			$sql = "SELECT stutteri, penge FROM Brugere WHERE id = {$_SESSION['user_id']} LIMIT 1";
			$result = $link_new->query($sql);
			if ($result) {
				$data = $result->fetch_assoc();
				$temp_user_data = ['money' => $data['penge'], 'username' => $data['stutteri']];
			} else {
				return ['Kritisk fejl: #2, prøv igen eller kontakt en admin evt. på admin@net-hesten.dk', 'error'];
			}
			$sql = "SELECT id, object_id, creator, instant_price, status_code, end_date FROM game_data_auctions WHERE id = {$attr['auction_id']} AND status_code = 1 LIMIT 1";
			$result = $link_new->query($sql);
			if ($result) {
				$data = $result->fetch_assoc();
				$temp_auctions_data = ['object_id' => $data['object_id'], 'creator' => $data['creator'], 'instant_price' => $data['instant_price']];
				if ($data['status_code'] != 1 || $data['end_date'] < $bid_date->format('Y-m-d H:i:s')) {
					return ['Denne auktion er desværre slut.', 'error'];
				}
			} else {
				return ['Kritisk fejl: #3, prøv igen eller kontakt en admin evt. på admin@net-hesten.dk', 'error'];
			}
			if ($temp_user_data['money'] >= $temp_auctions_data['instant_price']) {
				$sql = "UPDATE game_data_auctions SET status_code = 2 WHERE id = {$attr['auction_id']}";
				$link_new->query($sql);
				$sql = "INSERT INTO game_data_auction_bids (creator, auction, bid_amount, bid_date, status_code) "
						. "Values ({$_SESSION['user_id']}, {$attr['auction_id']}, {$temp_auctions_data['instant_price']}, NOW(), 6)";
				$link_new->query($sql);

				$sql = "UPDATE Heste SET bruger = '{$temp_user_data['username']}' WHERE id = {$temp_auctions_data['object_id']} AND bruger = 'Auktionshuset'";
				$link_new->query($sql);
				
				/* TODO - update message */
				accounting::add_entry(['amount' => $temp_auctions_data['instant_price'], 'line_text' => "Købt hest på auktion"]);
				$auction_fee = round(max(500, ($temp_auctions_data['instant_price'] * 0.005)), 0);
				$earnings = $temp_auctions_data['instant_price'] - $auction_fee;
				/* TODO - update message */
				accounting::add_entry(['amount' => $earnings, 'line_text' => "Solgt en hest på auktion", 'mode' => '+', 'user_id' => $temp_auctions_data['creator']]);

				
				/* Send besked til den ny ejer */
				/*$sql = "INSERT INTO Postsystem (emne, besked, sender, modtager, mappe, date) VALUES ('Du har vundet en auktion', '" . (mb_convert_encoding("Tillykke {$temp_user_data['username']}, du har købt en hest til køb nu pris.", 'latin1', 'UTF-8')) . "', 'Auktionshuset', '{$temp_user_data['username']}', 'Indbakke', NOW() )";
				$link_new->query($sql);
*/
				/* indsæt besked i ny pb system */
				$utf_8_username = mb_convert_encoding($temp_user_data['username'], 'UTF-8', 'latin1');
				$utf_8_message = $link_new->real_escape_string("Tillykke {$utf_8_username}, du har købt en hest til køb nu pris.");
				$sql = "INSERT INTO game_data_private_messages (status_code, hide, origin, target, date, message) VALUES (17, 0, 52745, {$_SESSION['user_id']}, NOW(), '{$utf_8_message}' )";
				$link_new->query($sql);


				$sql = "SELECT stutteri, penge FROM Brugere WHERE id = {$temp_auctions_data['creator']} LIMIT 1";
				$result = $link_new->query($sql);
				if ($result) {
					$data = $result->fetch_assoc();
					$temp_seller_data = ['money' => $data['penge'], 'username' => $data['stutteri']];
				}
				/* Send besked til den gamle ejer */
				/*$sql = "INSERT INTO Postsystem (emne, besked, sender, modtager, mappe, date) VALUES ('Din auktion er slut', '" . (mb_convert_encoding("Tillykke {$temp_seller_data['username']}, det er lykkeds at sælge din hest på auktion til køb nu pris!", 'latin1', 'UTF-8')) . "', 'Auktionshuset', '{$temp_seller_data['username']}', 'Indbakke', NOW() )";
				$link_new->query($sql);*/
				/* indsæt besked i ny pb system */
//				$utf_8_username = mb_convert_encoding($temp_user_data['username'], 'UTF-8', 'latin1');
//				$utf_8_message = $link_new->real_escape_string("Tillykke {$utf_8_username}, du har købt en hest til køb nu pris.");
//				$sql = "INSERT INTO game_data_private_messages (status_code, hide, origin, target, date, message) VALUES (17, 0, 52745, {$_SESSION['user_id']}, NOW(), '{$utf_8_message}' )";
//				$link_new->query($sql);

								
				/* Refunder til højeste bud */
				$sql = "SELECT creator, bid_amount, bid_date FROM game_data_auction_bids WHERE auction = {$attr['auction_id']} AND status_code = 4 ORDER BY bid_amount DESC LIMIT 1";
				$result = $link_new->query($sql);
				if ($result) {
					$data = $result->fetch_assoc();
					/* Sæt bud beløbet retur */
					/* TODO - update message */
					accounting::add_entry(['amount' => $data['bid_amount'], 'line_text' => "Refundering af auktionsbud", 'mode' => '+', 'user_id' => $data['creator']]);
					/* Mark bid as refunded */
					$sql = "UPDATE game_data_auction_bids SET status_code = 5 WHERE bid_date = '{$data['bid_date']}' AND creator = {$data['creator']}";
					$link_new->query($sql);
					/* Find hest id */
					
					$sql = "SELECT object_id AS horse_id FROM game_data_auctions WHERE id = {$attr['auction_id']} LIMIT 1";
					$horse_id = $link_new->query($sql)->fetch_object()->horse_id;
					
					/* Send besked til budgiver om refunderet bud */
					$sql = "INSERT INTO game_data_private_messages "
							. "(status_code, origin, target, date, message) "
							. "VALUES "
							. "(17, 52745, {$data['creator']}, NOW(), 'Hesten med ID:{$horse_id} på auktion, er desværre blevet købt med køb nu, pengene {$data['bid_amount']}wkr er returneret til din konto.')";
					$link_new->query($sql);
				}

				return ["Du lige vundet denne auktion for {$temp_auctions_data['instant_price']} wkr, tillykke!", 'success'];
			} else {
				return ["Du har ikke penge nok til at købe denne auktion, du har {$temp_user_data['money']}, men du skal bruge {$temp_auctions_data['instant_price']}", 'warning'];
			}
		}
		if ($attr['mode'] == 'place_bid') {
			$bid_date = new DateTime('now');
			$sql = "SELECT stutteri, penge FROM Brugere WHERE id = {$_SESSION['user_id']} LIMIT 1";
			$result = $link_new->query($sql);
			if ($result) {
				$data = $result->fetch_assoc();
				$temp_user_data = ['money' => $data['penge'], 'username' => $data['stutteri']];
				if (!($temp_user_data['money'] >= $attr['bid_amount'])) {
					return ["Du har ikke penge nok til at lave det bud, du har {$temp_user_data['money']}, men du ville byde {$attr['bid_amount']}.", 'warning'];
				}
			} else {
				return ['Kritisk fejl: #4, prøv igen eller kontakt en admin evt. på admin@net-hesten.dk', 'error'];
			}
			$sql = "SELECT id, object_id, creator, instant_price, minimum_price, status_code, end_date FROM game_data_auctions WHERE id = {$attr['auction_id']} AND status_code = 1 LIMIT 1";
			$result = $link_new->query($sql);
			if ($result) {
				$data = $result->fetch_assoc();
				$temp_auctions_data = ['object_id' => $data['object_id'], 'creator' => $data['creator'], 'instant_price' => $data['instant_price'], 'minimum_price' => $data['minimum_price']];
				if ($data['status_code'] != 1 || $data['end_date'] < $bid_date->format('Y-m-d H:i:s')) {
					return ["Denne auktion er desværre slut.", 'error'];
				}
				if (!($attr['bid_amount'] >= $temp_auctions_data['minimum_price'])) {
					return ["Dit bud er for lavt, du har budt {$attr['bid_amount']}, men det mindste bud på denne auktion er {$temp_auctions_data['minimum_price']}.", 'warning'];
				}
			} else {
				return ['Kritisk fejl: #5, prøv igen eller kontakt en admin evt. på admin@net-hesten.dk', 'error'];
			}
			/* Bid is higher than minimum price and user can afford it */
			/* Check for higher bids */
			$can_place_bid = false;
			$sql = "SELECT creator, bid_amount, bid_date FROM game_data_auction_bids WHERE auction = {$attr['auction_id']} AND status_code != 5 ORDER BY bid_amount DESC LIMIT 1";
			$result = $link_new->query($sql);
			if ($result) {
				$data = $result->fetch_assoc();
				$temp_bid_data = ['creator' => $data['creator'], 'bid_amount' => $data['bid_amount'], 'bid_date' => $data['bid_date']];
				$needed_bid = max(($temp_bid_data['bid_amount'] * 1.01), ($temp_bid_data['bid_amount'] + 2500));
				if (!($attr['bid_amount'] >= $needed_bid)) {
					return ["Dit bud er for lavt, du har budt {$attr['bid_amount']}, men en anden har budt {$temp_bid_data['bid_amount']}, og du skal derfor minimum byde {$needed_bid}.", 'warning'];
				} else {
					/* Bid is high enough */
					$can_place_bid = true;
				}
			} else {
				/* No bids exist */
				$can_place_bid = true;
			}
			$placed_highest_bid = false;
			if ($can_place_bid) {
				$sql = "INSERT INTO game_data_auction_bids (creator, auction, bid_amount, bid_date, status_code) VALUES ({$_SESSION['user_id']}, {$attr['auction_id']}, {$attr['bid_amount']}, '{$bid_date->format('Y-m-d H:i:s')}', 10)";
				$result = $link_new->query($sql);
				/* Check that your bid is infact the highest bid "Timing errors" */
				$sql = "SELECT creator, bid_amount, bid_date FROM game_data_auction_bids WHERE auction = {$attr['auction_id']} AND status_code != 5 ORDER BY bid_amount DESC LIMIT 1";
				$result = $link_new->query($sql);
				if ($result) {
					$data = $result->fetch_assoc();
					$bid_verify_data = ['creator' => $data['creator'], 'bid_amount' => $data['bid_amount'], 'bid_date' => $data['bid_date']];
					if ($bid_verify_data['creator'] !== $_SESSION['user_id']) {
						/* Set bid as refunded and return message 'timing related' */
						$sql = "UPDATE game_data_auction_bids SET status_code = 5 WHERE bid_date = '{$bid_date->format('Y-m-d H:i:s')}' AND creator = {$_SESSION['user_id']}";
						$result = $link_new->query($sql);
						return ['Der er desværre kommet et højere bud imens vi var ved at registrerer dit bud.'];
					} else {
						$placed_highest_bid = true;
					}
				} else {
					/* No bids exist (Yours should) */
					return ['Kritisk fejl: #6, prøv igen eller kontakt en admin evt. på admin@net-hesten.dk', 'error'];
				}
			}
			if ($placed_highest_bid) {
				$data_new = $data;
				
				$sql = "SELECT creator, bid_amount, bid_date FROM game_data_auction_bids WHERE auction = {$attr['auction_id']} AND status_code = 4 ORDER BY bid_amount DESC LIMIT 1";
				$result_old_bid = $link_new->query($sql);
				if ($result_old_bid) {
					$data = $result_old_bid->fetch_assoc();
					/* Sæt bud beløbet retur */
					/* TODO - update message */
					accounting::add_entry(['amount' => $data['bid_amount'], 'line_text' => "Overbudt på auktion", 'mode' => '+', 'user_id' => $data['creator']]);
					/* Mark bid as refunded */
					$sql = "UPDATE game_data_auction_bids SET status_code = 5 WHERE bid_date = '{$data['bid_date']}' AND creator = {$data['creator']}";
					$link_new->query($sql);
					/* Find horse id */
					
					$sql = "SELECT object_id AS horse_id FROM game_data_auctions WHERE id = {$attr['auction_id']} LIMIT 1";
					$horse_id = $link_new->query($sql)->fetch_object()->horse_id;
					
					/* Send besked til budgiver om refunderet bud */
					$sql = "INSERT INTO game_data_private_messages "
							. "(status_code, origin, target, date, message) "
							. "VALUES "
							. "(17, 52745, {$data['creator']}, NOW(), 'Du er desvære blevet overbudt på en auktion på hesten med ID:{$horse_id} , pengene {$data['bid_amount']}wkr er returneret til din konto.')";
					$link_new->query($sql);
					
				}

				/* Take the money */
				/* TODO - update message */
				accounting::add_entry(['amount' => $data['bid_amount'], 'line_text' => "Bud på auktion"]);

				/* Mark bid as accepted */
				$sql = "UPDATE game_data_auction_bids SET status_code = 4 WHERE bid_date = '{$bid_date->format('Y-m-d H:i:s')}' AND creator = {$_SESSION['user_id']}";
				$result = $link_new->query($sql);

				return ["Dit bud er registreret.", 'success'];
			}
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

	public static function get_all($attr = []) {
		global $link_new;
		global $link_old;
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

	public static function get_one($attr = []) {
		global $link_new;
		global $link_old;
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

	public static function put_on_sale($attr = []) {
		global $link_new;
		global $link_old;
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
			$seller_user_name = mb_convert_encoding($_SESSION['username'], 'latin1', 'UTF-8');

			foreach ($horses as $horse) {
				if ($horse['id'] == ($attr['horse_id'])) {
					/* Transfer horse to auctions user */
					$sql = "UPDATE Heste SET bruger = 'Auktionshuset' WHERE id = '{$horse['id']}' AND bruger = '{$seller_user_name}'";
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
