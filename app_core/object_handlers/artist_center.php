<?php

function ac_find_next_artist_submission_filename($mode = 'default')
{
	global $basepath;
	$dir_path = "{$basepath}/files.net-hesten.dk/horses/artist_submissions/";
	if ($mode == 'yield_path') {
		return $dir_path;
	}
	if (!is_dir($dir_path)) {
		return false;
	}
	if ($handle = opendir("{$basepath}/files.net-hesten.dk/horses/artist_submissions/")) {
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
	public static function find_next_type_filename($attr = [])
	{
		global $basepath;
		if ($handle = opendir("{$basepath}/files.net-hesten.dk/horses/imgs/")) {
			$found = false;
			$num_dirs = 0;
			while ($found != true) {
				++$num_dirs;
				$target_dir = str_replace(["/", "="], [""], base64_encode($num_dirs));
				if (!is_dir("{$basepath}/files.net-hesten.dk/horses/imgs/" . $target_dir)) {
					mkdir("{$basepath}/files.net-hesten.dk/horses/imgs/" . $target_dir);
				}
				if (is_dir("{$basepath}/files.net-hesten.dk/horses/imgs/" . $target_dir)) {
					$num_files = 1;
					while ($num_files <= 250) {
						++$num_files;
						if (is_file("{$basepath}/files.net-hesten.dk/horses/imgs/" . $target_dir . '/' . str_replace(["/", "="], [""], base64_encode($num_files)) . '.png')) {
							continue;
						} else if (is_file("{$basepath}/files.net-hesten.dk/horses/imgs/" . $target_dir . '/' . str_replace(["/", "="], [""], base64_encode($num_files)) . '.jpg')) {
							continue;
						} else if (is_file("{$basepath}/files.net-hesten.dk/horses/imgs/" . $target_dir . '/' . str_replace(["/", "="], [""], base64_encode($num_files)) . '.gif')) {
							continue;
						} else if (is_file("{$basepath}/files.net-hesten.dk/horses/imgs/" . $target_dir . '/' . str_replace(["/", "="], [""], base64_encode($num_files)) . '.jfif')) {
							continue;
						} else {
							return ['path' => "{$basepath}/files.net-hesten.dk/horses/imgs/", 'filename' => "{$target_dir}/" . str_replace(["/", "="], [""], base64_encode($num_files))];
						}
					}
				}
			}
		}
	}

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

		// 805087
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
			$sql = "SELECT acs.*,hr.name AS race_name FROM `artist_center_submissions` acs
			LEFT JOIN horse_races hr ON hr.id = acs.race 
			WHERE acs.`artist` = {$attr['user_id']} " . (($attr['status'] ?? false) ? "AND acs.`status` = " . ((int) $attr['status']) : '');
		} else {
			if (($attr['status'] ?? false)) {
				$sql = "SELECT * FROM `artist_center_submissions` WHERE `status` = " . ((int) $attr['status']);
			} else {
				$sql = "SELECT * FROM `artist_center_submissions` WHERE `status` = 27";
			}
		}
		$result = $link_new->query($sql);
		while ($data = $result->fetch_object()) {
			$return_data[] = ["id" => $data->id, "admin_comment" =>  $data->admin_comment, "image" => $data->image, "type" => $data->type, "theme" => $data->theme, "occasion" => $data->occasion, "race" => $data->race, "race_name" => $data->race_name, "artist" => $data->artist, "date" => $data->date];
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

		$sql = "SELECT count(id) AS `waiting_submissions` FROM `artist_center_submissions` WHERE `status` = 27 AND `artist` = {$attr['user_id']}";
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

		$sql = "SELECT count(`id`) AS `waiting_submissions` FROM `artist_center_submissions` WHERE `status` = 28 AND `artist` = {$attr['user_id']}";
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

		$sql = "SELECT count(`id`) AS `waiting_submissions` FROM `artist_center_submissions` WHERE `status` = 29 AND `artist` = {$attr['user_id']}";
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

		$sql = "SELECT `value` AS `artist_points` FROM `user_data_numeric` WHERE `name` = 'artist_points' AND `parent_id` = {$attr['user_id']}";
		$result = ($link_new->query($sql)->fetch_object() ?? false);
		if ($result) {
			return $result->artist_points;
		} else {
			$sql = $link_new->query("INSERT INTO `user_data_numeric` (`value`,`name`,`parent_id`,`date`) VALUES (0,'artist_points',{$attr['user_id']},NOW())");
			return 0;
		}
	}

	public static function grant_points($attr = [])
	{
		global $link_new;
		$points = (self::yield_points(['user_id' => $attr['user_id']])) + $attr['points'];
		$sql = "UPDATE `user_data_numeric` SET `value` = {$points} WHERE `name` = 'artist_points' AND `parent_id` = {$attr['user_id']}";
		$link_new->query($sql);
		return true;
	}

	public static function approve_drawing($attr = [])
	{
		global $link_new;
		global $basepath;
		$return_data = [];
		$defaults = [];
		foreach ($defaults as $key => $value) {
			isset($attr[$key]) ?: $attr[$key] = $value;
		}
		foreach ($attr as $key => $value) {
			$attr[$key] = $link_new->real_escape_string($value);
		}



		if (isset($attr['submission_id']) && is_numeric($attr['submission_id'])) {

			$sql = "SELECT * FROM `artist_center_submissions` WHERE `status` = 27";
			$submission = ($link_new->query("SELECT * FROM `artist_center_submissions` WHERE `id` = {$attr['submission_id']} AND `status` = 27 ")->fetch_object() ?? false);
			if ($submission) {
				/* Message user */
				private_messages::post_message(['message' => 'Din tegning er blevet godkendt.', 'write_to' => $submission->artist, 'poster_id' => $_SESSION['user_id']]);
				/* Billedet skal markeres */
				$link_new->query("UPDATE `artist_center_submissions` SET `status` = 28 WHERE `id` = {$submission->id} AND `status` = 27 ");
				/* Billedet skal aktiveres i typer */
				$target_file = (object) self::find_next_type_filename();
				$full_origin_file = "{$basepath}/files.net-hesten.dk/horses/artist_submissions/{$submission->image}";
				$target_file_type = substr($submission->image, (strripos(($submission->image), '.')));
				$full_target_file = $target_file->path . $target_file->filename . $target_file_type;
				if (!file_exists($full_target_file)) {
					copy($full_origin_file, $full_target_file);
				}

				$sql = "INSERT INTO `horse_types` (`image`, `date`,`artists`) VALUES ('{$target_file->filename}{$target_file_type}', NOW(),'{$submission->artist}')";
				$link_new->query($sql);
				//				$link_new->query("SELECT * FROM `artist_center_submissions` WHERE `id` = {$attr['submission_id']} AND `status` = 27 ");

				self::grant_points(['user_id' => $submission->artist, 'points' => 1]);
			}
		}
		return $return_data;
	}

	public static function reject_drawing($attr = [])
	{
		global $link_new;
		global $basepath;
		$return_data = [];
		$defaults = [];
		foreach ($defaults as $key => $value) {
			isset($attr[$key]) ?: $attr[$key] = $value;
		}
		foreach ($attr as $key => $value) {
			$attr[$key] = $link_new->real_escape_string($value);
		}



		if (isset($attr['submission_id']) && is_numeric($attr['submission_id'])) {
			$sql = "SELECT * FROM `artist_center_submissions` WHERE `id` = {$attr['submission_id']}";
			$submission = ($link_new->query($sql)->fetch_object() ?? false);
			if ($submission) {

				if ($submission->artist == $_SESSION['user_id'] || (in_array('global_admin', $_SESSION['rights']) || in_array('hestetegner_admin', $_SESSION['rights']))) {
					/* Message user */
					//private_messages::post_message(['message' => 'Din tegning er afvist.', 'write_to' => $submission->artist, 'poster_id' => $_SESSION['user_id']]);
					/* Billedet skal markeres */
					$link_new->query("UPDATE `artist_center_submissions` SET `status` = 29 WHERE `id` = {$submission->id} AND `status` = 27 ");
					/* Billedet skal aktiveres i typer */
					return ["Du har selv afvist din tegning.", 'success'];
				} else {
					return ["Du har ikke tegnet den tegning du har anmodet om at slette.", 'error'];
				}
			} else {
				return ["Den hest du har anmodet om at slette findes ikke.", 'error'];
			}
		}
		return ["Du har ikke sendt et ID med.", 'error'];
	}
}
