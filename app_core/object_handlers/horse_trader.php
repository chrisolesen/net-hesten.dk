<?php

class horse_trader
{

	public static function buy($attr = [])
	{
		global $link_new;
		global $link_new;
		$return_data = [];
		$defaults = [];
		foreach ($defaults as $key => $value) {
			isset($attr[$key]) ?: $attr[$key] = $value;
		}
		foreach ($attr as $key => $value) {
			$attr[$key] = $link_new->real_escape_string($value);
		}

		$bid_date = new DateTime('now');
		$sql = "SELECT stutteri, penge FROM `{$GLOBALS['DB_NAME_OLD']}`.Brugere WHERE id = {$_SESSION['user_id']} LIMIT 1";
		$result = $link_new->query($sql);
		if ($result) {
			$data = $result->fetch_assoc();
			$temp_user_data = ['money' => $data['penge'], 'username' => $data['stutteri']];
		} else {
			return ['Kritisk fejl: Dit stutteri kunne ikke findes, prøv igen eller kontakt en admin evt. på admin@net-hesten.dk', 'error'];
		}


		$accepted_last_buy_date = new DateTime('NOW');
		$accepted_last_buy_date->sub(new DateInterval("P0DT15M"));
		$sql = "SELECT value FROM user_data_timing WHERE parent_id = {$_SESSION['user_id']} AND name = 'last_horse_trader_buy' LIMIT 1";
		$result = $link_new->query($sql);
		if ($result) {

			if ($data = $result->fetch_object()->value) {
				if ($data < $accepted_last_buy_date->format('Y-m-d H:i:s')) {
					/* all good */
				} else {
					return ["Du må højst købe en hest hver 15. minut", 'warning'];
				}
			} else {
				/* User never bought a horse from the trader before - all good */
			}

			if (!$temp_horse_data = horses::get_one(['ID' => $attr['horse_id']])) {
				return ["Kritisk fejl: Hesten med det angivne ID {{$attr['horse_id']}} findes ikke, prøv igen eller kontakt en admin evt. på admin@net-hesten.dk", 'error'];
			}
			if ($temp_horse_data->owner_name !== 'hestehandleren') {
				return ['Hesten du har forsøgt at købe, tilhører desværre ikke længere hestehandleren.', 'warning'];
			}

			if ($temp_user_data['money'] >= $temp_horse_data->value) {
				$sql = "UPDATE `{$GLOBALS['DB_NAME_OLD']}`.Heste SET bruger = '{$temp_user_data['username']}' WHERE id = {$attr['horse_id']} AND bruger = 'hestehandleren'";
				$link_new->query($sql);
				accounting::add_entry(['amount' => $temp_horse_data->value, 'line_text' => "HH: Købt hest: [{$attr['horse_id']}]"]);

				$sql = "INSERT INTO user_data_timing (parent_id, name, value) VALUES ({$_SESSION['user_id']},'last_horse_trader_buy',NOW()) ON DUPLICATE KEY UPDATE value = NOW()";
				$link_new->query($sql);

				return ["Du lige købt denne hest for {$temp_horse_data->value} wkr, tillykke!", 'success'];
			} else {
				return ["Du har ikke penge nok til at købe denne hest, du har {$temp_user_data['money']}, men du skal bruge {$temp_horse_data->value}", 'warning'];
			}
		}
		return false;
	}

	public static function sell($attr = [])
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

		$bid_date = new DateTime('now');
		$sql = "SELECT stutteri, penge FROM `{$GLOBALS['DB_NAME_OLD']}`.Brugere WHERE id = {$attr['seller_id']} LIMIT 1";
		$result = $link_new->query($sql);
		if ($result) {
			$data = $result->fetch_assoc();
			$temp_user_data = ['money' => $data['penge'], 'username' => $data['stutteri']];
		} else {
			return ['Kritisk fejl: Dit stutteri kunne ikke findes, prøv igen eller kontakt en admin evt. på admin@net-hesten.dk', 'error'];
		}

		if (!$temp_horse_data = horses::get_one(['ID' => $attr['horse_id']])) {
			return ["Kritisk fejl: Hesten med det angivne ID {{$attr['horse_id']}} findes ikke, prøv igen eller kontakt en admin evt. på admin@net-hesten.dk", 'error'];
		}
		if (strtolower($temp_horse_data->owner_name) !== strtolower("{$temp_user_data['username']}")) {
			return ['Hesten du har forsøgt at sælge, tilhører desværre ikke dig.', 'warning'];
		}

		$sell_income = ($temp_horse_data->value * 0.9);

		accounting::add_entry(['amount' => $sell_income, 'line_text' => "HH: Solgt hest: [{$attr['horse_id']}]", 'mode' => '+']);

		$sql = "UPDATE `{$GLOBALS['DB_NAME_OLD']}`.Heste SET bruger = 'hestehandleren' WHERE id = {$attr['horse_id']} AND bruger = '{$temp_user_data['username']}'";
		$link_new->query($sql);


		return ["Du lige solgt din hest for {$sell_income} wkr, tillykke!", 'success'];

		return false;
	}
}
