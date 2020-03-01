<?php
class chat
{
	public static function register_online($attr = [])
	{
		if (isset($_SESSION['impersonator_id'])) {
			return false;
		}
		global $link_new;
		global $link_old;
		$return_data = [];
		$defaults = [];
		foreach ($defaults as $key => $value) {
			isset($attr[$key]) ? : $attr[$key] = $value;
		}
		if (!(isset($attr['user_id']))) {
			return false;
		}
		$user_id = (int)$attr['user_id'];
		$sql = "INSERT INTO user_data_timing (parent_id, name, value) VALUES ({$user_id},'last_online_chat',NOW()) ON DUPLICATE KEY UPDATE value = NOW()";
		$result = $link_new->query($sql);
		if ($result) {
			return $result;
		} else {
			return false;
		}
	}
	public static function get_online($attr = [])
	{
		global $link_new;
		global $link_old;
		global $_GLOBALS;
		$return_data = [];
		$defaults = ['time_mode' => 'm', 'time_val' => '30', 'mode' => 'return'];
		foreach ($defaults as $key => $value) {
			isset($attr[$key]) ? : $attr[$key] = $value;
		}
		$mode = 'MINUTE';
//		$target_time = new DateTime('now');
		if ($attr['time_mode'] == 'm') {
			$mode = 'MINUTE';
		}
		if ($attr['time_mode'] == 'd') {
			$mode = 'day';
		}
		if ($attr['time_mode'] == 'h') {
			$mode = 'HOUR';
		}
		if ($attr['mode'] == 'count') {
			return $link_new->query("SELECT count(parent_id) AS amount FROM user_data_timing WHERE name = 'last_online_chat' AND value > DATE_SUB(NOW(),INTERVAL {$attr['time_val']} {$mode})")->fetch_object()->amount;
		}
		$user_id = (int)$attr['user_id'];
		$sql = "SELECT old.id AS userid, old.stutteri AS username, new.value AS time FROM user_data_timing AS new LEFT JOIN {$_GLOBALS['DB_NAME_OLD']}.Brugere AS old ON old.id = new.parent_id WHERE new.name = 'last_online_chat' AND new.value > DATE_SUB(NOW(),INTERVAL {$attr['time_val']} {$mode}) ORDER BY new.value DESC";
		$result = $link_new->query($sql);
		if ($result) {
			while ($data = $result->fetch_object()) {
				$return_data[] = (object)[
					'user_id' => $data->userid,
					'username' => $data->username,
					'last_online' => $data->time
				];
			}
			return $return_data;
		} else {
			return false;
		}
	}
	public static function post_message($attr = [])
	{
		global $link_new;
		global $link_old;
		$return_data = [];
		$defaults = [];
		foreach ($defaults as $key => $value) {
			isset($attr[$key]) ? : $attr[$key] = $value;
		}
		if ($attr['message'] == '') {
			return false;
		}
		foreach ($attr as $key => $value) {
			$attr[$key] = $link_new->real_escape_string($value);
		}
		$sql = "INSERT INTO game_data_chat_messages "
			. "(creator, status_code, creation_date, value) "
			. "VALUES "
			. "({$attr['poster_id']}, 11, NOW(), '{$attr['message']}')";
		$result = $link_new->query($sql);
		if ($result) {
			$return_data[] = [true, 'Besked postet'];
			return $return_data;
		} else {
			return false;
		}
	}
	public static function get_messages($attr = [])
	{
		global $link_new;
		global $link_old;
		global $_GLOBALS;
		$return_data = [];
		$defaults = ['limit' => 10];
		foreach ($defaults as $key => $value) {
			isset($attr[$key]) ? : $attr[$key] = $value;
		}
		$limit = '';
		if (isset($attr['limit']) && isset($attr['page']) && $attr['page'] != false) {
			$offset = $attr['limit'] * ((int)$attr['page'] - 1);
			$limit = "LIMIT {$attr['limit']} OFFSET {$offset}";
		}
		$sql = "SELECT "
			. "old.id AS creator_id, "
			. "old.stutteri AS creator, "
			. "new.creation_date, "
			. "new.value "
			. "FROM "
			. "{$_GLOBALS['DB_NAME_NEW']}.game_data_chat_messages AS new "
			. "LEFT JOIN "
			. "{$_GLOBALS['DB_NAME_OLD']}.Brugere AS old "
			. "ON "
			. "old.id = new.creator "
			. "WHERE "
			. "new.status_code <> 13 "
			. ($limit == '' ? "AND new.creation_date > DATE_SUB(NOW(),INTERVAL 7 DAY) " : '')
			. "ORDER BY "
			. "new.creation_date DESC "
			. "{$limit}";
		$result = $link_new->query($sql);
		while ($data = $result->fetch_object()) {
			$return_data[] = ['creator_id' => $data->creator_id, 'creator' => $data->creator, 'date' => $data->creation_date, 'text' => $data->value];
		}
		if (!empty($return_data)) {
			return $return_data;
		} else {
			return false;
		}
	}
}
