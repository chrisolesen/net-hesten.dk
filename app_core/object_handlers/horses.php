<?php

class horses
{

	public static function put_on_grass($attr = [])
	{
		global $link_new; /* {$GLOBALS['DB_NAME_NEW']}{$GLOBALS['DB_NAME_OLD']} */
		$return_data = [];
		$defaults = [];
		foreach ($defaults as $key => $value) {
			isset($attr[$key]) ?: $attr[$key] = $value;
		}
		foreach ($attr as $key => $value) {
			$attr[$key] = $link_new->real_escape_string($value);
		}
		if (isset($attr['horse_id'])) {
			$user_id = (int) $_SESSION['user_id'];
			$sql = "SELECT "
				. "id, bruger AS owner_name, navn AS name, race, kon AS gender, alder AS age, pris AS value, graesning, staevne, kaaring, status, original, unik, tegner AS artist, thumb, egenskab, ulempe, talent, changedate "
				. "FROM {$GLOBALS['DB_NAME_OLD']}.Heste "
				. "WHERE id = {$attr['horse_id']}";
			$result = $link_new->query($sql);
			while ($data = $result->fetch_object()) {
				if (strtolower($data->owner_name) == strtolower($_SESSION['username'])) {
					$sql = "UPDATE {$GLOBALS['DB_NAME_OLD']}.Heste SET graesning = 'ja', changedate = NOW() WHERE id = {$attr['horse_id']}";
					$result = $link_new->query($sql);
					return ["Hesten er nu sat på græs, husk at hente den ind, inden 14 timer.", 'success'];
				} else {
					return false;
				}
			}
		}
		return false;
	}

	public static function breed_horse($attr = [])
	{
		$username = $_SESSION['username'];

		global $link_new;
		$return_data = [];
		$defaults = [];
		foreach ($defaults as $key => $value) {
			isset($attr[$key]) ?: $attr[$key] = $value;
		}
		foreach ($attr as $key => $value) {
			$attr[$key] = $link_new->real_escape_string($value);
		}
		$target_horse_data = $link_new->query("SELECT * FROM {$GLOBALS['DB_NAME_OLD']}.Heste WHERE id = {$attr['target_horse_id']} LIMIT 1")->fetch_object();
		$horse_data = $link_new->query("SELECT * FROM {$GLOBALS['DB_NAME_OLD']}.Heste WHERE id = {$attr['horse_id']} LIMIT 1")->fetch_object();
		if (strtolower($horse_data->bruger) != strtolower($username)) {
			return ["Du ejer ikke den hest du forsøger at fole! {$horse_data->bruger} | {$username}", 'error'];
		}
		if (strtolower($target_horse_data->bruger) == strtolower($username)) {
			//			return["Du må ikke fole med dine egne hingste.", 'warning'];
		}
		if (strtolower($horse_data->graesning) == 'ja' || strtolower($horse_data->staevne) == 'ja') {
			return ["Du kan ikke fole en hest, når den befinder sig til et stævne eller på græsmarken.", 'warning'];
		}
		if (strtolower($horse_data->kon) != 'hoppe') {
			return ["Du kan ikke fole en hingst.", 'error'];
		}
		/* Tag wkr fra bruger */
		/* Giv wkr minus gebyr til hingst */
		/* Send besked til bruger */
		/* Send besked til hingst */
		/* Husk dyrelægen */
		/* Få status til at slå igennem på mit stutteri listen */
		$sql = "REPLACE INTO {$GLOBALS['DB_NAME_NEW']}.horse_metadata (horse_id, meta_key, meta_value, meta_date) VALUES ({$horse_data->id}, 'breeding', '{$target_horse_data->id}', NOW())";
		$link_new->query($sql);
		return ["Du har nu folet {$horse_data->navn} med {$target_horse_data->navn}. Du kan forvente et føl om ~40 dage.", 'success'];
	}

	public static function put_horse_in_stable($attr = [])
	{
		global $link_new; /* {$GLOBALS['DB_NAME_NEW']}{$GLOBALS['DB_NAME_OLD']} */
		$return_data = [];
		$defaults = [];
		foreach ($defaults as $key => $value) {
			isset($attr[$key]) ?: $attr[$key] = $value;
		}
		foreach ($attr as $key => $value) {
			$attr[$key] = $link_new->real_escape_string($value);
		}
		if (isset($attr['horse_id'])) {
			$user_id = (int) $_SESSION['user_id'];
			$username = $_SESSION['username'];
			$dead = 'død';
			$graes_money = 'Græsningspenge';
			$user = $link_new->query("SELECT id, penge, stutteri FROM {$GLOBALS['DB_NAME_OLD']}.Brugere WHERE stutteri = '{$username}' LIMIT 1")->fetch_object();

			$sql = "SELECT "
				. "id, bruger AS owner_name, navn AS name, race, kon AS gender, alder AS age, pris AS value, graesning, staevne, kaaring, status, original, unik, tegner AS artist, thumb, egenskab, ulempe, talent, changedate "
				. "FROM {$GLOBALS['DB_NAME_OLD']}.Heste "
				. "WHERE id = {$attr['horse_id']}";
			$result = $link_new->query($sql);
			while ($horse = $result->fetch_object()) {
				$date_now = new DateTime('NOW');
				//				50 wkr time inden 14 timer ellers  500 wkr fra
				if (strtolower($horse->owner_name) == strtolower($username)) {
					if ($horse->graesning == 'ja') {
						$date_now = new DateTime('NOW');
						$date_then = new DateTime($horse->changedate);
						$durration = $date_now->diff($date_then);
						if ($durration->y > 0 || $durration->m > 0 || $durration->d > 0 || $durration->h > 13) {
							/* Punish */
							$sql = "UPDATE {$GLOBALS['DB_NAME_OLD']}.Heste SET graesning = '', changedate = NOW() WHERE id = {$attr['horse_id']}";
							$result = $link_new->query($sql);
							return ["Din hest har stået for længe på græs!", 'warning'];
						} else {
							/* Pay */
							$minutes = ($durration->h * 60) + ($durration->i);
							$payment = $minutes * 2;
							if ($payment > 0) {
								accounting::add_entry(['amount' => $payment, 'line_text' => "Græsning af hest", 'mode' => '+']);
							}
							$sql = "UPDATE {$GLOBALS['DB_NAME_OLD']}.Heste SET graesning = '', changedate = NOW() WHERE id = {$attr['horse_id']}";
							$result = $link_new->query($sql);
							return ["Du lige tjent {$payment} wkr på græsning, godt arbejde!", 'success'];
						}
						return false;
					}
				}
			}
		}
		return false;
	}

	public static function get_all($attr = [])
	{
		global $link_new;
		$return_data = [];
		$defaults = ['mode' => 'auction'];
		foreach ($defaults as $key => $value) {
			isset($attr[$key]) ?: $attr[$key] = $value;
		}
		foreach ($attr as $key => $value) {
			$attr[$key] = $link_new->real_escape_string($value);
		}
		if (isset($attr['user_name'])) {
			$username = $attr['user_name'];

			$sql = "SELECT "
				. "heste.foersteplads AS gold_medal, "
				. "heste.andenplads AS silver_medal, "
				. "heste.tredieplads AS bronze_medal, "
				. "heste.kaaringer AS junior_medal, "
				. "heste.id, "
				. "heste.bruger AS owner_name, "
				. "heste.navn AS name, "
				. "heste.race, "
				. "heste.kon AS gender, "
				. "heste.alder AS age, "
				. "heste.pris AS value, "
				. "heste.graesning, "
				. "heste.staevne, "
				. "heste.kaaring, "
				. "heste.status, "
				. "heste.original, "
				. "heste.unik, "
				. "heste.tegner AS artist, "
				. "heste.thumb, "
				. "heste.egenskab, "
				. "heste.ulempe, "
				. "heste.talent, "
				. "heste.changedate AS change_date_one, "
				. "heste.statuschangedate AS change_date_two, "
				. "heste.date AS change_date_three, "
				. "contests.competition_id, "
				. "contests.points, "
				. "breeding.meta_value AS breed_partner, "
				. "breeding.meta_date AS breed_date "
				. "FROM {$GLOBALS['DB_NAME_OLD']}.Heste AS heste "
				. "LEFT JOIN {$GLOBALS['DB_NAME_NEW']}.game_data_competition_participants AS contests "
				. "ON contests.participant_id = heste.id AND contests.points IS NULL "
				. "LEFT JOIN {$GLOBALS['DB_NAME_NEW']}.horse_metadata AS breeding "
				. "ON breeding.horse_id = heste.id AND breeding.meta_key = 'breeding' "
				. "WHERE status != 'død' "
				. (($attr['mode'] == 'search_all') ? "AND heste.talent != '' " : '')
				. (($attr['mode'] == 'search_all') ? "AND heste.egenskab != '' " : '')
				. (($attr['mode'] == 'search_all') ? "AND heste.ulempe != '' " : '')
				. (($attr['mode'] !== 'search_all') ? "AND bruger = '{$username}' " : "AND bruger NOT IN ('genfoedsel','carsten','hestehandleren*','følkassen') ")
				. (($attr['mode'] !== 'search_all') ? '' : "AND thumb <> '/imgHorse/..' AND pris <> ''")
				. (($attr['mode'] == 'auction') ? "AND staevne != 'ja' " : '')
				. (($attr['mode'] == 'auction') ? "AND kaaring != 'ja' " : '')
				. (($attr['mode'] == 'auction') ? "AND graesning != 'ja' " : '')
				//					. ($username === 'hestehandleren' ? "AND alder < 18 " : '')
				. (isset($attr['id_filter']) ? 'AND id = ' . $attr['id_filter'] . ' ' : '')
				//					. (($username === 'hestehandleren' && $attr['noorder'] == true) ? "AND date > '2017-06-27 12:00:00' " : '')
				. (($username == 'hestehandleren' && $attr['noorder'] == true) ? 'ORDER BY rand() ' : '')
				. (isset($attr['custom_filter']) ? "{$attr['custom_filter']} " : '')
				. ($username !== 'hestehandleren' ? "ORDER BY unik DESC, original DESC, status DESC, alder ASC " : '')
				. ((isset($attr['custom_filter']) && $username == 'hestehandleren') ? "ORDER BY date DESC " : '')
				. (isset($attr['limit']) ? "LIMIT {$attr['limit']} " : '')
				. (isset($attr['offset']) ? "OFFSET {$attr['offset']} " : '')
				. "";

			$result = $link_new->query($sql);
			$i = 0;
			if ($result) {
				while ($data = $result->fetch_assoc()) {
					foreach ($data as $key => $info) {
						$return_data[$i][$key] = $info;
					}
					++$i;
				}
			}
			return $return_data;
		}
		return false;
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
		if (isset($attr['ID'])) {
			$sql = "SELECT "
				. "id, bruger AS owner_name, navn AS name, race, kon AS gender, alder AS age, pris AS value, graesning, staevne, kaaring, status, original, unik, tegner AS artist, thumb, egenskab, ulempe, talent "
				. "FROM {$GLOBALS['DB_NAME_OLD']}.Heste "
				. "WHERE id = '{$attr['ID']}' ";
			$result = $link_new->query($sql);
			if ($data = $result->fetch_assoc()) {
				foreach ($data as $key => $info) {
					$return_data[$key] = $info;
				}
				return (object) $return_data;
			}
		}
		return false;
	}

	public static function get_owner($attr = [])
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
		if (isset($attr['ID'])) {
			$result = ($link_new->query("SELECT bruger AS username FROM {$GLOBALS['DB_NAME_OLD']}.Heste WHERE id = '{$attr['ID']}' LIMIT 1"));
			if ($data = $result->fetch_object()) {
				return (user::get_info(['mode' => 'username', 'user_id' => $data->username]))->id;
			}
		}
		return false;
	}

	/* Temporary function */
	/* /portal/bridge.php recreation */

	public static function bridge_get($horse_id)
	{
		global $link_new;
		$horse_id = mysqli_real_escape_string($link_new, $horse_id);
		//	 , graesning, staevne, kaaring, 
		$sql = ''
			. "SELECT "
			. "thumb, "
			. "navn AS name, "
			. "race, "
			. "kon AS gender, "
			. "pris AS value, "
			. "original, "
			. "unik, "
			. "alder AS age, "
			. "tegner AS artist, "
			. "egenskab, "
			. "ulempe, "
			. "talent,"
			. "status "
			. "FROM {$GLOBALS['DB_NAME_OLD']}.Heste WHERE id = {$horse_id} LIMIT 1";
		$result = $link_new->query($sql);
		if ($result) {
			while ($data = $result->fetch_assoc()) {
				foreach ($data as $key => $value) {
					$data[$key] = $value;
				}
				$return_data = [
					'id' => $horse_id,
					'thumb' => $data['thumb'],
					'name' => $data['name'],
					'race' => $data['race'],
					'gender' => $data['gender'],
					'value' => $data['value'],
					'original' => $data['original'],
					'unik' => $data['unik'],
					'age' => $data['age'],
					'artist' => $data['artist'],
					'egenskab' => $data['egenskab'],
					'ulempe' => $data['ulempe'],
					'talent' => $data['talent'],
					'status' => $data['status']
				];
				return json_encode($return_data);
			}
		}
	}

	public static function change_owner($attr = [])
	{
		global $link_new;
		$defaults = ['old_owner' => $_SESSION['user_id']];
		foreach ($defaults as $key => $value) {
			isset($attr[$key]) ?: $attr[$key] = $value;
		}
		foreach ($attr as $key => $value) {
			$attr[$key] = $link_new->real_escape_string($value);
		}


		if (!isset($attr['new_owner'])) {
			return ['Kritisk fejl: #hco_1, prøv igen eller kontakt en admin evt. på admin@net-hesten.dk', 'error'];
		}
		if (!(isset($attr['horse_id'])) || !(is_numeric($attr['horse_id']))) {
			return ['Kritisk fejl: #hco_2, prøv igen eller kontakt en admin evt. på admin@net-hesten.dk', 'error'];
		}
		if (is_numeric($attr['old_owner'])) {
			$attr['old_owner'] = (user::get_info(['user_id' => $attr['old_owner']]))->username;
		}
		if (is_numeric($attr['new_owner'])) {
			$attr['new_owner'] = (user::get_info(['user_id' => $attr['new_owner']]))->username;
		}

		$link_new->query("UPDATE {$GLOBALS['DB_NAME_OLD']}.Heste SET bruger = '{$attr['new_owner']}' WHERE bruger = '{$attr['old_owner']}' AND id = {$attr['horse_id']} LIMIT 1");
	}
}
