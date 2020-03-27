<?php
class private_messages
{
	public static function get_new_messages_count($attr = [])
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
		if (!isset($attr['user_id'])) {
			return false;
		}
		$sql = "SELECT "
			. "count(id) AS amount "
			. "FROM "
			. "{$GLOBALS['DB_NAME_NEW']}.game_data_private_messages "
			. "WHERE "
			. "target = {$attr['user_id']} "
			. "AND "
			. "status_code <> 18 "
			. (isset($attr['thread']) ? "AND thread = {$attr['thread']} " : '')
			. (isset($attr['origin']) ? "AND origin = {$attr['origin']} " : '');
		if ($link_new->query($sql)) {
			$return_data = $link_new->query($sql)->fetch_object()->amount;
		}
		/* status 17 = send */
		/* status 18 = read */
		if (!empty($return_data)) {
			return $return_data;
		} else {
			return (int) 0;
		}
	}
	public static function get_threads($attr = [])
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
		if (!isset($attr['user_id'])) {
			return false;
		}
		$sql = "SELECT "
			. "date, "
			. "message, "
			. "status_code, "
			. "target AS target_id, "
			. "origin AS origin_id,"
			. "hide "
			. "FROM "
			. "{$GLOBALS['DB_NAME_NEW']}.game_data_private_messages "
			. "WHERE "
			. "hide <> 3 "
			. "AND "
			. "( origin = {$attr['user_id']} OR target = {$attr['user_id']} )"
			. "ORDER BY "
			. "date DESC "
			. (isset($attr['limit']) ? "LIMIT {$attr['limit']} " : '')
			. "";
		$result = $link_new->query($sql);
		/* status 17 = send */
		/* status 18 = read */
		while ($data = $result->fetch_object()) {
			if ($data->hide == 1 && $data->origin_id == $attr['user_id']) {
				continue;
			}
			if ($data->hide == 2 && $data->target_id == $attr['user_id']) {
				continue;
			}
			if ($data->origin_id == $attr['user_id']) {
				$return_data[$data->target_id] += 0;
			} elseif ($data->target_id == $attr['user_id'] && $data->status_code === '17') {
				$return_data[$data->origin_id] += 1;
			} elseif ($data->target_id == $attr['user_id']) {
				$return_data[$data->origin_id] += 0;
			}
		}
		if (!empty($return_data)) {
			return $return_data;
		} else {
			return false;
		}
	}
	public static function post_message($attr = [])
	{
		global $link_new;
		$return_data = [];
		$defaults = ['thread' => 1];
		foreach ($defaults as $key => $value) {
			isset($attr[$key]) ?: $attr[$key] = $value;
		}
		if ($attr['message'] == '' || $attr['write_to'] == '' || $attr['poster_id'] == '') {
			return false;
		}
		foreach ($attr as $key => $value) {
			$attr[$key] = $link_new->real_escape_string($value);
		}
		$sql = "INSERT INTO game_data_private_messages "
			. "(status_code, origin, target, thread, date, message) "
			. "VALUES "
			. "(17, {$attr['poster_id']}, {$attr['write_to']}, {$attr['thread']}, NOW(), '{$attr['message']}')";
		$result = $link_new->query($sql);
		if ($result) {
			return true;
		} else {
			return false;
		}
	}
	public static function hide_message($attr = [])
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
		/* 1 = hidden to origin, 2 = hidden to target, 3 = hidden to both */
		$sql = "UPDATE "
			. "{$GLOBALS['DB_NAME_NEW']}.game_data_private_messages "
			. "SET "
			. "hide = 1 "
			. "WHERE "
			. "hide = 0 AND origin = {$attr['user_id']} AND id = {$attr['msg_id']} "
			. "";
		$result = $link_new->query($sql);
		$sql = "UPDATE "
			. "{$GLOBALS['DB_NAME_NEW']}.game_data_private_messages "
			. "SET "
			. "hide = 2 "
			. "WHERE "
			. "hide = 0 AND target = {$attr['user_id']} AND id = {$attr['msg_id']} "
			. "";
		$result = $link_new->query($sql);
		$sql = "UPDATE "
			. "{$GLOBALS['DB_NAME_NEW']}.game_data_private_messages "
			. "SET "
			. "hide = 3 "
			. "WHERE "
			. "hide = 2 AND origin = {$attr['user_id']} AND id = {$attr['msg_id']} "
			. "";
		$result = $link_new->query($sql);
		$sql = "UPDATE "
			. "{$GLOBALS['DB_NAME_NEW']}.game_data_private_messages "
			. "SET "
			. "hide = 3 "
			. "WHERE "
			. "hide = 1 AND target = {$attr['user_id']} AND id = {$attr['msg_id']} "
			. "";
		$result = $link_new->query($sql);
		return true;
	}
	public static function mark_as_read($attr = [])
	{
		if (isset($_SESSION['impersonator_id'])) {
			return false;
		}
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
		$sql = "UPDATE "
			. "{$GLOBALS['DB_NAME_NEW']}.game_data_private_messages "
			. "SET "
			. "status_code = 18 "
			. "WHERE "
			. "target = {$attr['user_id']} AND origin = {$attr['other_user']} AND thread = {$attr['thread']} "
			. "";
		$result = $link_new->query($sql);
		return true;
	}
	public static function get_messages($attr = [])
	{
		global $link_new;
		global $GLOBALS;
		$return_data = [];
		$defaults = ['limit' => 10];
		foreach ($defaults as $key => $value) {
			isset($attr[$key]) ?: $attr[$key] = $value;
		}
		foreach ($attr as $key => $value) {
			$attr[$key] = $link_new->real_escape_string($value);
		}
		$limit = '';
		if (isset($attr['limit']) && isset($attr['page']) && $attr['page'] != false) {
			$offset = $attr['limit'] * ((int) $attr['page'] - 1);
			$limit = "LIMIT {$attr['limit']} OFFSET {$offset}";
		}
		$sql = "SELECT "
			. "id, "
			. "date, "
			. "message, "
			. "status_code, "
			. "target, "
			. "origin,"
			. "hide "
			. "FROM "
			. "{$GLOBALS['DB_NAME_NEW']}.game_data_private_messages "
			. "WHERE "
			. "hide <> 3 "
			. "AND "
			. "( ( origin = {$attr['user_id']} AND target = {$attr['other_user']} ) OR ( target = {$attr['user_id']} AND origin = {$attr['other_user']} ) ) "
			. "AND "
			. "thread = {$attr['thread']} "
			. "ORDER BY "
			. "date DESC "
			. "{$limit}";
		$result = $link_new->query($sql);
		/* status 17 = send */
		/* status 18 = read */
		if ($result) {
			while ($data = $result->fetch_object()) {
				if ($data->hide == 1 && $data->origin == $attr['user_id']) {
					continue;
				}
				if ($data->hide == 2 && $data->target == $attr['user_id']) {
					continue;
				}
				$return_data[] = $data;
			}
		}
		if (!empty($return_data)) {
			return $return_data;
		} else {
			return false;
		}
	}
}
