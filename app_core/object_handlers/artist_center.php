<?php

function ac_find_next_artist_submission_filename($mode = 'default')
{
	global $basepath;
	$dir_path = "$basepath/files.net-hesten.dk/horses/artist_submissions/";
	if ($mode == 'yield_path') {
		return $dir_path;
	}
	if (!is_dir($dir_path)) {
		return false;
	}
	if ($handle = opendir("$basepath/files.net-hesten.dk/horses/artist_submissions/")) {
		$found = false;
		$num_dirs = 0;
		while ($found != true) {
			//			if (++$i >= $fallback_max_i) {
			//				return false;
			//			}
			++$num_dirs;
			$target_dir = str_replace(["/", "="], [""], base64_encode($num_dirs));
			if (!is_dir($dir_path . $target_dir)) {
				mkdir($dir_path . $target_dir);
			}
			if (is_dir($dir_path . $target_dir)) {
				$num_files = 1;
				while ($num_files <= 250) {
					++$num_files;
					if (is_file($dir_path . $target_dir . '/' . str_replace(["/", "="], [""], base64_encode($num_files)) . '.png')) {
						continue;
					} else if (is_file($dir_path . $target_dir . '/' . str_replace(["/", "="], [""], base64_encode($num_files)) . '.jpg')) {
						continue;
					} else if (is_file($dir_path . $target_dir . '/' . str_replace(["/", "="], [""], base64_encode($num_files)) . '.gif')) {
						continue;
					} else {
						return "$dir_path{$target_dir}/" . str_replace(["/", "="], [""], base64_encode($num_files));
					}
				}
			}
		}
	}
}

class artist_center
{

	public static function submit_drawing($attr = [])
	{
		global $link_new;
		$return_data = [];
		$defaults = [];
		foreach ($defaults as $key => $value) {
			isset($attr[$key]) ?: $attr[$key] = $value;
		}
		if (!isset($attr['file'])) {
			die();
		}
		$dir_path = ac_find_next_artist_submission_filename('yield_path');

		$target_file = ac_find_next_artist_submission_filename();
		if ($target_file) {

			$uploadOk = 1;
			$imageFileType = pathinfo(basename($attr['file']["name"]), PATHINFO_EXTENSION);
			// Check if image file is a actual image or fake image
			$check = getimagesize($attr['file']["tmp_name"]);
			if ($check !== false) {
				/* don't expose error messages */
				$uploadOk = 1;
			} else {
				/* don't expose error messages */
				$uploadOk = 0;
			}
			if (file_exists($target_file . '.' . $imageFileType)) {
				/* don't expose error messages */
				$uploadOk = 0;
			}
			if ($attr['file']["size"] > 250000) {
				/* don't expose error messages */
				$uploadOk = 0;
			}
			if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" && $imageFileType != "jfif") {
				/* don't expose error messages */
				$uploadOk = 0;
			}
			if ($uploadOk == 0) {
				/* don't expose error messages */
			} else {
				if (move_uploaded_file($attr['file']["tmp_name"], $target_file . '.' . $imageFileType)) {
					$file_path = str_replace($dir_path, '', "{$target_file}.{$imageFileType}");
					$sql = "INSERT INTO `artist_center_submissions` (`image`,`status`,`type`,`theme`,`occasion`,`race`,`artist`,`date`) VALUES ('{$file_path}',27,'{$attr['type']}','{$attr['theme']}','{$attr['occasion']}','{$attr['race']}','{$attr['user_id']}',NOW())";
					$link_new->query($sql);
				} else {
					/* don't expose error messages */
				}
			}
			return true;
		} else {
			return ['Critical error in finding next valid file', 'error'];
		}
	}

	public static function fetch_drawings($attr = [])
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
		if (!isset($attr['user_id']) && $attr['mode'] != 'approve') {
			exit();
		}

		if (isset($attr['user_id'])) {
			$sql = "SELECT * FROM `artist_center_submissions` WHERE `artist` = {$attr['user_id']} " . (($attr['status'] ?? false) ? "AND `status` = " . ((int) $attr['status']) : '');
		} else {
			if ($attr['status']) {
				$sql = "SELECT * FROM `artist_center_submissions` WHERE `status` = " . ((int) $attr['status']);
			} else {
				$sql = "SELECT * FROM `artist_center_submissions` WHERE `status` = 27";
			}
		}
		$result = $link_new->query($sql);
		while ($data = $result->fetch_object()) {
			$return_data[] = ["admin_comment" =>  $data->admin_comment, "image" => $data->image, "type" => $data->type, "theme" => $data->theme, "occasion" => $data->occasion, "race" => $data->race, "artist" => $data->artist, "date" => $data->date];
		}
		return $return_data;
	}

	public static function yield_waiting($attr = [])
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
		if (!isset($attr['user_id']) && $attr['mode'] != 'approve') {
			exit();
		}

		$sql = "SELECT count(id) AS waiting_submissions FROM `artist_center_submissions` WHERE `status` = 27 AND artist = {$attr['user_id']}";
		$result = ($link_new->query($sql)->fetch_object()->waiting_submissions ?? 0);
		return $result;
	}
	public static function yield_approved($attr = [])
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
		if (!isset($attr['user_id']) && $attr['mode'] != 'approve') {
			exit();
		}

		$sql = "SELECT count(id) AS waiting_submissions FROM `artist_center_submissions` WHERE `status` = 28 AND artist = {$attr['user_id']}";
		$result = ($link_new->query($sql)->fetch_object()->waiting_submissions ?? 0);
		return $result;
	}
	public static function yield_rejected($attr = [])
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
		if (!isset($attr['user_id']) && $attr['mode'] != 'approve') {
			exit();
		}

		$sql = "SELECT count(id) AS waiting_submissions FROM `artist_center_submissions` WHERE `status` = 29 AND artist = {$attr['user_id']}";
		$result = ($link_new->query($sql)->fetch_object()->waiting_submissions ?? 0);
		return $result;
	}
	public static function yield_points($attr = [])
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
		if (!isset($attr['user_id']) && $attr['mode'] != 'approve') {
			exit();
		}

		$sql = "SELECT `value` AS artist_points FROM `user_data_numeric` WHERE `name` = 'artist_points' AND parent_id = {$attr['user_id']}";
		$result = ($link_new->query($sql)->fetch_object()->artist_points ?? 0);
		return $result;
	}
}
