<?php
/* TODO: Horse skill should influence chances to win */
if (!defined('cron_by')) {
	die();
}

if (cron_by == 'admin-panel') {
	chdir(dirname(__FILE__));
	$basepath = realpath(__DIR__ . '/../../..');
}

if (!isset($basepath)) {
	die();
}

if (!isset($time_now)) {
	date_default_timezone_set('Europe/Copenhagen');
	$current_date = new DateTime('now');
	$date_now = $current_date->format('Y-m-d');
	$time_now = $current_date->format('H:i:s');
}

require_once "{$basepath}/app_core/object_loader.php";

if (cron_by == 'one_hour') {
	$log_content = PHP_EOL . '# Automating contests.';
	file_put_contents("{$basepath}/app_core/cron_files/logs/cron_one_hour_{$date_now}", $log_content, FILE_APPEND);
}

/* End active competitions - start */
$sql = "SELECT * FROM `{$GLOBALS['DB_NAME_NEW']}`.`game_data_competitions` WHERE `status_code` <> 31 ORDER BY `start_date` DESC, `id` DESC";
$competition_result = $link_new->query($sql);
while ($competition_data = $competition_result->fetch_object()) {
	if ($end_competition_id = $competition_data->id) {
		$competition = $link_new->query("SELECT status_code, name, end_date FROM `game_data_competitions` WHERE `id` = {$end_competition_id}")->fetch_object();
		if ($competition->status_code == 31) {
			//			echo 'Competion already ended';
		} else {
			//	31 = ended;
			$sql = "SELECT `horse`.`id` AS `hid`, `horse`.`navn` AS `hname`, `user`.`stutteri` AS `uname`, `user`.`id` AS `uid`, `user`.`penge` AS `money`, `horse`.`pris` AS `value` 
			FROM `{$GLOBALS['DB_NAME_NEW']}`.`game_data_competition_participants` AS `PData` 
			LEFT JOIN `{$GLOBALS['DB_NAME_OLD']}`.`Heste` AS `horse` ON `horse`.`id` = `PData`.`participant_id` 
			LEFT JOIN `{$GLOBALS['DB_NAME_OLD']}`.`Brugere` AS `user` ON `user`.`stutteri` = `horse`.`bruger` 
			WHERE `competition_id` = {$end_competition_id} 
			ORDER BY rand()";
			$result = $link_new->query($sql);
			$participants = [];
			$points = 0;
			if ($result) {
				if ($result->num_rows > 0) {
					while ($data = $result->fetch_object()) {
						++$points;
						if ($competition->name == 'Følkåring') {
							$point_array = [1, 2, 3, 4];
						} else {
							$point_array = [1, 2, 3];
						}
						if (in_array($points, $point_array)) {
							if ($competition->name == 'Følkåring') {
								if ($points == 1) {
									$medal = 'Helhedsindtryk';
									$price_money = 15000;
									$value_add = 7500;
								} else if ($points == 2) {
									$medal = 'Kropsbygning';
									$price_money = 10000;
									$value_add = 5000;
								} else if ($points == 3) {
									$medal = 'Temperament';
									$price_money = 10000;
									$value_add = 5000;
								} else if ($points == 4) {
									$medal = 'Gangart';
									$price_money = 10000;
									$value_add = 5000;
								}
							} else {
								if ($points == 1) {
									$medal = 'Guld';
									$price_money = 50000;
									$value_add = 20000;
								} else if ($points == 2) {
									$medal = 'Sølv';
									$price_money = 25000;
									$value_add = 10000;
								} else if ($points == 3) {
									$medal = 'Bronze';
									$price_money = 10000;
									$value_add = 5000;
								}
							}
							$utf_8_message = "<b>Tilykke {$data->uname}</b>,<br /><br /> Din hest {$data->hname} har vundet {$medal} i {$competition->name}. ({$competition->end_date})<br /><br />Du har fået {$price_money}wkr og din hest er steget med {$value_add} i værdi.<br /><br /><b>Med venlig hilsen</b><br />Konkurrencestyrelsen";
							$origin = 53844; /* Konkurrencestyrelsen */

							$link_new->query("UPDATE `{$GLOBALS['DB_NAME_OLD']}`.`Brugere` SET penge = (penge + {$price_money}) WHERE id = {$data->uid}");
							$link_new->query("UPDATE `{$GLOBALS['DB_NAME_OLD']}`.`Heste` SET pris = (pris + {$value_add}) WHERE id = {$data->hid}");
							$link_new->query("INSERT INTO game_data_private_messages (status_code, hide, origin, target, date, message) VALUES (17, 0, {$origin}, {$data->uid}, NOW(), '{$utf_8_message}' )");
							accounting::add_entry(['amount' => $price_money, 'line_text' => "Gevinst for {$competition->name}", "user_id" => $data->uid, "mode" => "+"]);
						} 
						$link_new->query("UPDATE `game_data_competition_participants` SET points = '{$points}' WHERE `competition_id` = {$end_competition_id} AND `participant_id` = {$data->hid}");
						$link_new->query("UPDATE `game_data_competitions` SET status_code = 31 WHERE `id` = {$end_competition_id}");
					}
				} else {
					/* No participants */
					$link_new->query("UPDATE `game_data_competitions` SET status_code = 31 WHERE `id` = {$end_competition_id}");
				}
			}
		}
	}
}
/* End active competitions - end */



$date_tomorrow = new DateTime('now');
$date_tomorrow->add(new DateInterval('P1D'));
$end_date = $date_tomorrow->format('Y-m-d') . ' 17:55:00';

$sql = "SELECT count(`id`) AS `count` FROM `{$GLOBALS['DB_NAME_NEW']}`.`game_data_competitions` WHERE `status_code` <> 31 AND `name` = 'Følkåring'";
if (($link_new->query($sql)->fetch_object()->count ?? 0) < 2) {
	$link_new->query("INSERT INTO `game_data_competitions` 
		(`status_code`, `start_date`, `end_date`, `allowed_races`, `allowed_types`,`name`,`description`) 
		VALUES 
		(32, NOW(), '{$end_date}','','2','Følkåring','')
		");
}



$name = 'Western';
$sql = "SELECT count(`id`) AS `count` FROM `{$GLOBALS['DB_NAME_NEW']}`.`game_data_competitions` WHERE `status_code` <> 31 AND `name` = '{$name}'";
if (!($link_new->query($sql)->fetch_object()->count ?? false)) {
	$allowed_races = '';
	$sql = "SELECT `id` FROM `{$GLOBALS['DB_NAME_NEW']}`.`horse_races` WHERE `id` < 103 ORDER BY RAND() LIMIT 2";
	$result = $link_new->query($sql);
	while ($data = $result->fetch_object()) {
		$allowed_races .= "{$data->id},";
	}
	$allowed_types = '';
	$allowed_races = substr($allowed_races, 0, (strlen($allowed_races) - 1));
	$link_new->query("INSERT INTO `game_data_competitions` 
		(`status_code`, `start_date`, `end_date`, `allowed_races`, `allowed_types`,`name`,`description`) 
		VALUES 
		(32, NOW(), '{$end_date}','{$allowed_races}','{$allowed_types}','{$name}','')
		");
}


$name = 'Dressur';
$sql = "SELECT count(`id`) AS `count` FROM `{$GLOBALS['DB_NAME_NEW']}`.`game_data_competitions` WHERE `status_code` <> 31 AND `name` = '{$name}'";
if (!($link_new->query($sql)->fetch_object()->count ?? false)) {
	$allowed_races = '';
	$sql = "SELECT `id` FROM `{$GLOBALS['DB_NAME_NEW']}`.`horse_races` WHERE `id` < 103 ORDER BY RAND() LIMIT 2";
	$result = $link_new->query($sql);
	while ($data = $result->fetch_object()) {
		$allowed_races .= "{$data->id},";
	}
	$allowed_types = '';
	$allowed_races = substr($allowed_races, 0, (strlen($allowed_races) - 1));
	$link_new->query("INSERT INTO `game_data_competitions` 
(`status_code`, `start_date`, `end_date`, `allowed_races`, `allowed_types`,`name`,`description`) 
VALUES 
(32, NOW(), '{$end_date}','{$allowed_races}','{$allowed_types}','{$name}','')
");
}


$name = 'Spring';
$sql = "SELECT count(`id`) AS `count` FROM `{$GLOBALS['DB_NAME_NEW']}`.`game_data_competitions` WHERE `status_code` <> 31 AND `name` = '{$name}'";
if (!($link_new->query($sql)->fetch_object()->count ?? false)) {
	$allowed_races = '';
	$sql = "SELECT `id` FROM `{$GLOBALS['DB_NAME_NEW']}`.`horse_races` WHERE `id` < 103 ORDER BY RAND() LIMIT 2";
	$result = $link_new->query($sql);
	while ($data = $result->fetch_object()) {
		$allowed_races .= "{$data->id},";
	}
	$allowed_types = '';
	$allowed_races = substr($allowed_races, 0, (strlen($allowed_races) - 1));
	$link_new->query("INSERT INTO `game_data_competitions` 
(`status_code`, `start_date`, `end_date`, `allowed_races`, `allowed_types`,`name`,`description`) 
VALUES 
(32, NOW(), '{$end_date}','{$allowed_races}','{$allowed_types}','{$name}','')
");
}

$sql = "SELECT count(`id`) AS `count` FROM `{$GLOBALS['DB_NAME_NEW']}`.`game_data_competitions` WHERE `status_code` <> 31 AND `name` = 'Følkåring'";
if (($link_new->query($sql)->fetch_object()->count ?? 0) < 2) {
	$link_new->query("INSERT INTO `game_data_competitions` 
		(`status_code`, `start_date`, `end_date`, `allowed_races`, `allowed_types`,`name`,`description`) 
		VALUES 
		(32, NOW(), '{$end_date}','','2','Følkåring','')
		");
}
