<?php

class private_trade
{

	public static function offer_privat_trade($attr = [])
	{
		global $link_new;
		$return_data = [];
		$defaults = [];
		foreach ($defaults as $key => $value) {
			isset($attr[$key]) ?: $attr[$key] = $value;
		}

		$horse_id = (int) $attr['horse_id'];
		$price = (int) $attr['price'];
		$seller_id = (int) $attr['seller_id'];
		$recipient = $link_new->real_escape_string($attr['recipient']);
		$recipient_id = (int) $link_new->query("SELECT `id` FROM `{$GLOBALS['DB_NAME_OLD']}`.`Brugere` WHERE `stutteri` = '{$recipient}'")->fetch_object()->id;
		if (!is_numeric($recipient_id) || !$recipient_id) {
			return false;
		}
		$horse_owner = $link_new->query("SELECT `bruger` FROM `{$GLOBALS['DB_NAME_OLD']}`.`Heste` WHERE `id` = '{$attr['horse_id']}'")->fetch_object()->bruger;
		if (!$horse_owner) {
			return false;
		}
		$horse_owner_id = $link_new->query("SELECT `id` FROM `{$GLOBALS['DB_NAME_OLD']}`.`Brugere` WHERE `stutteri` = '{$horse_owner}'")->fetch_object()->id;
		if (!$horse_owner_id || !is_numeric($horse_owner_id)) {
			return false;
		}
		if (!($horse_owner_id == $seller_id)) {
			return false;
		}
		$link_new->query(
			"INSERT INTO `game_data_private_trade` (`seller`, `buyer`, `horse_id`, `price`, `creation_date`, `end_date`, `status_code`) 
			VALUES ({$seller_id}, {$recipient_id}, {$horse_id}, {$price}, NOW(), DATE_ADD(NOW(), INTERVAL 7 DAY), 38)"
		);
		$link_new->query("UPDATE `{$GLOBALS['DB_NAME_OLD']}`.`Heste` SET `bruger` = 'SystemPrivatHandel' WHERE `id` = {$horse_id}");
	}

	public static function request_private_trade($attr = [])
	{
		global $link_new;
		$defaults = [];
		foreach ($defaults as $key => $value) {
			isset($attr[$key]) ?: $attr[$key] = $value;
		}

		$bid_amount = (int) $attr['bid_amount'];
		$requester_id = (int) $attr['requester_id'];
		$horse_id = (int) $attr['horse_id'];
		if (!is_numeric($requester_id) || !$requester_id) {
			return false;
		}
		if ((user::get_info(['user_id' => $requester_id]))->money > $bid_amount) {
			$horse_owner = $link_new->query("SELECT `bruger` AS `user` FROM `{$GLOBALS['DB_NAME_OLD']}`.`Heste` WHERE `id` = '{$horse_id}'")->fetch_object()->user;
			if (!$horse_owner) {
				return false;
			}
			$horse_owner_id = $link_new->query("SELECT `id` FROM `{$GLOBALS['DB_NAME_OLD']}`.`Brugere` WHERE `stutteri` = '{$horse_owner}'")->fetch_object()->id;
			if (!$horse_owner_id || !is_numeric($horse_owner_id)) {
				return false;
			}

			accounting::add_entry(['amount' => $bid_amount, 'line_text' => "Anmodet om privat handel"]);


			$link_new->query(
				"INSERT INTO `game_data_private_trade` (`seller`, `buyer`, `horse_id`, `price`, `creation_date`, `end_date`, `status_code`) 
				VALUES ({$horse_owner_id}, {$requester_id}, {$horse_id}, {$bid_amount}, NOW(), DATE_ADD(NOW(), INTERVAL 7 DAY), 44)"
			);
		}
	}

	public static function accept_privat_trade($attr = [])
	{
		global $link_new;
		$return_data = [];
		$defaults = [];
		foreach ($defaults as $key => $value) {
			isset($attr[$key]) ?: $attr[$key] = $value;
		}

		$trade_id = (int) $attr['trade_id'];
		$acceptor_id = $_SESSION['user_id'];
		$trade_object = $link_new->query("SELECT * FROM `game_data_private_trade` WHERE `id` = $trade_id")->fetch_object();
		$buyer_id = $trade_object->buyer;
		$seller_id = $trade_object->seller;
		$horse_id = $trade_object->horse_id;
		$price = $trade_object->price;

		if (!in_array($trade_object->status_code, [38, 44])) {
			return false;
		}


		$recipient_object = $link_new->query("SELECT `stutteri`, `penge` FROM `{$GLOBALS['DB_NAME_OLD']}`.`Brugere` WHERE `id` = {$buyer_id}")->fetch_object();
		$recipient = $recipient_object->stutteri;
		$recipient_money = $recipient_object->penge;

		$owner_id = horses::get_owner(['horse_id' => $horse_id]);

		if ($owner_id == $seller_id) {
			/* The trade was a request and is already paid for */
			if ($seller_id !== $acceptor_id) {
				return ["Det kun sælgeren der kan accepterer en anmodning.", 'error'];
			}
		} else {
			/* The trade was an offer */
			if ($recipient_money < $price) {
				return ["Du har ikke råd til at accepterer tilbudet.", 'error'];
			}
			accounting::add_entry(['amount' => $price, 'line_text' => "Privat handel", 'user_id' => $buyer_id]);
			if ($buyer_id !== $acceptor_id) {
				return ["Det kun køberen der kan accepterer et tilbud.", 'error'];
			}
		}

		$link_new->query("UPDATE `game_data_private_trade` SET `status_code` = 2 WHERE `id` = $trade_id");
		$link_new->query("UPDATE `{$GLOBALS['DB_NAME_OLD']}`.`Heste` SET `bruger` = '$recipient' WHERE `id` = $horse_id");

		accounting::add_entry(['amount' => $price, 'line_text' => "Privat handel", 'mode' => '+', 'user_id' => $seller_id]);
	}

	public static function reject_privat_trade($attr = [])
	{
		global $link_new;
		$return_data = [];
		$defaults = [];
		foreach ($defaults as $key => $value) {
			isset($attr[$key]) ?: $attr[$key] = $value;
		}

		$trade_id = (int) $attr['trade_id'];
		$rejector_id = $_SESSION['user_id'];
		$trade_object = $link_new->query("SELECT * FROM `game_data_private_trade` WHERE `id` = $trade_id")->fetch_object();

		if (!in_array($trade_object->status_code, [38, 44])) {
			return false;
		}
		$buyer_id = $trade_object->buyer;
		$seller_id = $trade_object->seller;
		$horse_id = $trade_object->horse_id;
		$price = $trade_object->price;

		if ($seller_id == $rejector_id || $buyer_id == $rejector_id) {
		} else {
			return false;
		}

		$owner_id = horses::get_owner(['horse_id' => $horse_id]);

		if ($owner_id == $seller_id) {
			/* The trade was a request - not an offer - repay the requestor */
			accounting::add_entry(['amount' => $price, 'line_text' => "Privat handel", 'mode' => '+', 'user_id' => $buyer_id]);
		} else {
			/* The trade was an offer - return the horse to it's owner */
			$recipient = $link_new->query("SELECT `stutteri` AS `username` FROM `{$GLOBALS['DB_NAME_OLD']}`.`Brugere` WHERE `id` = {$seller_id}")->fetch_object()->username;
			$link_new->query("UPDATE `{$GLOBALS['DB_NAME_OLD']}`.`Heste` SET `bruger` = '{$recipient}' WHERE `id` = {$horse_id}");
		}

		$link_new->query("UPDATE `game_data_private_trade` SET `status_code` = 3 WHERE `id` = $trade_id");
	}

	public static function list_trade_offers($attr = [])
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
		if (($attr['mode'] ?? false) == 'latest_offer') {
			$user_id = ($attr['user_id'] ?? ($_SESSION['user_id'] ?? false));
			if ($user_id) {
				$sql = "SELECT `creation_date` FROM `game_data_private_trade` 
				WHERE (`seller` = {$user_id} AND `status_code` = 44) OR (`buyer` = {$user_id} AND `status_code` = 38)
				ORDER BY `creation_date` DESC LIMIT 1";
				$latest = ($link_new->query($sql)->fetch_object() ?? false);
				if ($latest) {
					return $latest->creation_date;
				}
			}
			return '0000-00-00 00:00:00';
		}
		if (isset($attr['user_id'])) {
			$offers = $link_new->query("SELECT * FROM `game_data_private_trade` WHERE (`seller` = {$attr['user_id']} OR `buyer` = {$attr['user_id']}) AND `status_code` IN (44,38) ");
			if ($offers) {
				$i = 0;
				while ($data = $offers->fetch_assoc()) {
					foreach ($data as $key => $info) {
						if ($key == 'horse_id') {
							$horse_datas = $link_new->query(
								"SELECT `heste`.`id`, `heste`.`navn` AS `name`, `heste`.`race`, `heste`.`kon` AS `gender`, `heste`.`alder` AS `age`, 
								`heste`.`pris` AS `value`, `heste`.`original`, `heste`.`unik`, `heste`.`tegner` AS `artist`, `heste`.`thumb`, 
								`heste`.`egenskab`, `heste`.`ulempe`, `heste`.`talent` 
								FROM `{$GLOBALS['DB_NAME_OLD']}`.`Heste` AS `heste` WHERE `id` = $info"
							);
							$show_horse_data = [];
							while ($horse_data = $horse_datas->fetch_assoc()) {
								foreach ($horse_data as $key => $info) {
									$show_horse_data[$key] = $info;
								}
							}
							$return_data[$i][$key] = $info;
							$return_data[$i]['horse_data'] = $show_horse_data;
						} else {
							$return_data[$i][$key] = $info;
						}
					}
					++$i;
				}
				return $return_data;
			}
		}
		return false;
	}
}
