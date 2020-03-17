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
		$recipient_id = (int) $link_new->query("SELECT id FROM `Brugere` WHERE stutteri = '{$recipient}'")->fetch_object()->id;
		if (!is_numeric($recipient_id) || !$recipient_id) {
			return false;
		}
		$horse_owner = "{$link_new->query("SELECT bruger FROM `Heste` WHERE id = '{$attr['horse_id']}'")->fetch_object()->bruger}";
		if (!$horse_owner) {
			return false;
		}
		$horse_owner_id = $link_new->query("SELECT id FROM Brugere WHERE stutteri = '$horse_owner'")->fetch_object()->id;
		if (!$horse_owner_id || !is_numeric($horse_owner_id)) {
			return false;
		}
		if (!($horse_owner_id == $seller_id)) {
			return false;
		}
		$link_new->query(
			"INSERT INTO game_data_private_trade (seller, buyer, horse_id, price, creation_date, end_date, status_code) " .
				"VALUES " .
				"($seller_id, $recipient_id, $horse_id, $price, NOW(), DATE_ADD(NOW(), INTERVAL 7 DAY), 1)"
		);
		$link_new->query("UPDATE Heste SET bruger = 'SystemPrivatHandel' WHERE id = $horse_id");
	}

	public static function request_private_trade($attr = [])
	{
		global $link_new;
		$return_data = [];
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
		$horse_owner = "{$link_new->query("SELECT bruger FROM `Heste` WHERE id = '{$horse_id}'")->fetch_object()->bruger}";
		if (!$horse_owner) {
			return false;
		}
		$horse_owner_id = $link_new->query("SELECT id FROM Brugere WHERE stutteri = '{$horse_owner}'")->fetch_object()->id;
		if (!$horse_owner_id || !is_numeric($horse_owner_id)) {
			return false;
		}
		/* TODO: Withdraw money from requester up front */
		$link_new->query(
			"INSERT INTO game_data_private_trade (seller, buyer, horse_id, price, creation_date, end_date, status_code) " .
				"VALUES " .
				"($horse_owner_id, $requester_id, $horse_id, $bid_amount, NOW(), DATE_ADD(NOW(), INTERVAL 7 DAY), 44)"
		);
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
		$acceptor_id = $attr['buyer_id'];
		$trade_object = $link_new->query("SELECT * FROM game_data_private_trade WHERE id = $trade_id")->fetch_object();
		$buyer_id = $trade_object->buyer;
		$seller_id = $trade_object->seller;
		$horse_id = $trade_object->horse_id;
		$price = $trade_object->price;

		if ($buyer_id !== $acceptor_id) {
			return false;
		}

		$recipient_object = $link_new->query("SELECT stutteri, penge FROM `Brugere` WHERE id = {$buyer_id}")->fetch_object();
		$recipient = $recipient_object->stutteri;
		$recipient_money = $recipient_object->penge;

		if ($recipient_money < $price) {
			return false;
		}

		$link_new->query("UPDATE game_data_private_trade SET `status_code` = 2 WHERE id = $trade_id");
		$link_new->query("UPDATE Heste SET bruger = '$recipient' WHERE id = $horse_id");
		$link_new->query("UPDATE `Brugere` SET penge = penge + $price WHERE id = $seller_id");
		$link_new->query("UPDATE `Brugere` SET penge = penge - $price WHERE id = $buyer_id");
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
		$rejector_id = $attr['rejector_id'];
		$trade_object = $link_new->query("SELECT * FROM game_data_private_trade WHERE id = $trade_id")->fetch_object();
		$buyer_id = $trade_object->buyer;
		$seller_id = $trade_object->seller;
		$horse_id = $trade_object->horse_id;
		$price = $trade_object->price;

		if ($seller_id == $rejector_id || $buyer_id == $rejector_id) {
		} else {
			return false;
		}

		$recipient = $link_new->query("SELECT stutteri FROM `Brugere` WHERE id = {$seller_id}")->fetch_object()->stutteri;

		$link_new->query("UPDATE game_data_private_trade SET `status_code` = 3 WHERE id = $trade_id");
		$link_new->query("UPDATE Heste SET bruger = '$recipient' WHERE id = $horse_id");
	}

	public static function list_trade_offerings($attr = [])
	{
		global $link_new;
		global $GLOBALS;
		$return_data = [];
		$defaults = [];

		foreach ($defaults as $key => $value) {
			isset($attr[$key]) ?: $attr[$key] = $value;
		}
		foreach ($attr as $key => $value) {
			$attr[$key] = $link_new->real_escape_string($value);
		}
		if (isset($attr['user_name'])) {
			$offers = $link_new->query("SELECT * FROM game_data_private_trade WHERE `seller` = {$attr['user_id']} AND `status_code` = 1 ");
			if ($offers) {
				$i = 0;
				while ($data = $offers->fetch_assoc()) {
					foreach ($data as $key => $info) {
						if ($key == 'horse_id') {
							$horse_datas = $link_new->query(
								"SELECT " .
									"heste.id, "
									. "heste.navn AS name, "
									. "heste.race, "
									. "heste.kon AS gender, "
									. "heste.alder AS age, "
									. "heste.pris AS value, "
									. "heste.original, "
									. "heste.unik, "
									. "heste.tegner AS artist, "
									. "heste.thumb, "
									. "heste.egenskab, "
									. "heste.ulempe, "
									. "heste.talent "
									. "FROM {$GLOBALS['DB_NAME_OLD']}.Heste AS heste WHERE id = $info"
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

	public static function list_trade_offers($attr = [])
	{
		global $link_new;
		global $GLOBALS;
		$return_data = [];
		$defaults = [];

		foreach ($defaults as $key => $value) {
			isset($attr[$key]) ?: $attr[$key] = $value;
		}
		foreach ($attr as $key => $value) {
			$attr[$key] = $link_new->real_escape_string($value);
		}
		if (isset($attr['user_name'])) {
			$offers = $link_new->query("SELECT * FROM game_data_private_trade WHERE `buyer` = {$attr['user_id']} AND `status_code` = 1 ");
			if ($offers) {
				$i = 0;
				while ($data = $offers->fetch_assoc()) {
					foreach ($data as $key => $info) {
						if ($key == 'horse_id') {
							$horse_datas = $link_new->query(
								"SELECT " .
									"heste.id, "
									. "heste.navn AS name, "
									. "heste.race, "
									. "heste.kon AS gender, "
									. "heste.alder AS age, "
									. "heste.pris AS value, "
									. "heste.original, "
									. "heste.unik, "
									. "heste.tegner AS artist, "
									. "heste.thumb, "
									. "heste.egenskab, "
									. "heste.ulempe, "
									. "heste.talent "
									. "FROM {$GLOBALS['DB_NAME_OLD']}.Heste AS heste WHERE id = $info"
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
