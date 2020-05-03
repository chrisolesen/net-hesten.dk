<?php

if (!isset($basepath)) {
	die();
}

if (!isset($time_now)) {
	date_default_timezone_set('Europe/Copenhagen');
	$current_date = new DateTime('now');
	$date_now = $current_date->format('Y-m-d');
	$time_now = $current_date->format('H:i:s');
}

$log_content = PHP_EOL . '# Generating horses to HorseTrader.';
file_put_contents("{$basepath}/app_core/cron_files/logs/cron_one_hour_{$date_now}", $log_content, FILE_APPEND);

require_once "{$basepath}/app_core/object_loader.php";
require_once "{$basepath}/app_core/cron_files/data_collections/generation_horse_names.php";

$Foelbox = 'Følkassen';
$foel = 'føl';

$today = date("d.m.y.G.i");

/* [Hour, minute, amount] */
$rounds = [[06, 30, 10], [12, 00, 10], [16, 00, 10], [19, 30, 10]];

$generation_age = 8;
$generated_horses = 0;
$target_horses = 1;

$tech_mail_message = '';
while ($generated_horses < $target_horses) {
	/*	$type_data = $link_new->query("SELECT tegner, thumb, race FROM `{$GLOBALS['DB_NAME_OLD']}`.Heste WHERE bruger != 'Hestehandleren*' AND bruger <> '{$Foelbox}' AND status <> '{$foel}' AND genfodes = 'ja' AND unik <> 'ja' ORDER BY RAND() LIMIT 1")->fetch_object();*/
	/*
			  19	type_unique
			  20	type_generation
			  21	type_rebirth
			  22	type_rebirth_generation
			  23	type_rebirth_unique
			  24	type_ordinary
			  25	type_foel
			  26	type_foel_rebirth
	*/
	/* TODO: Support multiple artists */
	$type_data = $link_new->query("SELECT `image`, `race`, `artists` FROM `{$GLOBALS['DB_NAME_NEW']}`.`horse_types` WHERE `status` IN (20,21,22) AND `artists` NOT LIKE '%,%' ORDER BY RAND() LIMIT 1")->fetch_object();
	$artist = (user::get_info(['user_id' => (int) $type_data->artists]))->username;
	$thumb = "/imgHorse/{$type_data->image}";
	$race = $type_data->race;

	$advantage = $link_new->query("SELECT `egenskab` FROM `{$GLOBALS['DB_NAME_OLD']}`.`horse_habits` WHERE `egenskab` <> '' ORDER BY RAND() LIMIT 1")->fetch_object()->egenskab;
	$disadvantage = $link_new->query("SELECT `ulempe` FROM `{$GLOBALS['DB_NAME_OLD']}`.`horse_habits` WHERE `ulempe` <> '' ORDER BY RAND() LIMIT 1")->fetch_object()->ulempe;
	$talent = $link_new->query("SELECT `talent` FROM `{$GLOBALS['DB_NAME_OLD']}`.`horse_habits` WHERE `talent` <> '' ORDER BY RAND() LIMIT 1")->fetch_object()->talent;

	$height_data = $link_new->query("SELECT `lower`, `upper` FROM `{$GLOBALS['DB_NAME_OLD']}`.`horse_height` WHERE `race` = '{$race}' LIMIT 1")->fetch_object();
	$height_lower = $height_data->lower;
	$height_upper = $height_data->upper;
	$height = mt_rand($height_lower, $height_upper);

	$gender = (mt_rand(1, 2) === 1 ? 'Hingst' : 'Hoppe');
	shuffle($boys_names);
	shuffle($girls_names);
	if ($gender === 'Hingst') {
		$name = $boys_names[0];
	} else {
		$name = $girls_names[0];
	}



	$statuschangedate = '00-00-00 00:00:00';
	$date_now_db_format = $current_date->format('Y-m-d H:i:s');

	$horse_age_to_irl_days = $generation_age * 40;
	$current_date = new DateTime('now');
	$birth_date = $current_date->sub(new DateInterval("P{$horse_age_to_irl_days}D"))->format('Y-m-d H:i:s');



	if ($artist && $thumb && $advantage && $disadvantage && $talent) {
		$sql = "INSERT INTO `{$GLOBALS['DB_NAME_OLD']}`.`Heste` 
		(
			`bruger`, `status`, `alder`, `pris`, `beskrivelse`, 
			`foersteplads`, `andenplads`, `tredieplads`, 
			`statuschangedate`, `date`, `changedate`, `status_skift`, `alder_skift`, 
			`navn`, `kon`, 
			`race`, `tegner`, `thumb`, `height`, `egenskab`, `ulempe`, `talent`, 
			`farid`, `morid`, `random_height`
		)
		VALUES 
		(
			'hestehandleren', 'Hest', {$generation_age}, 11000, '', 
			0, 0, 0, 
			'00-00-00 00:00:00', '{$birth_date}','{$birth_date}', NOW(), NOW(), 
			'{$name}', '{$gender}', 
			'{$race}', '{$artist}', '{$thumb}', {$height}, '{$advantage}', '{$disadvantage}', '{$talent}', 
			'', '', 'nej'
		)";
		$link_new->query($sql);

		$error = $link_new->error;
	}
	++$generated_horses;
}

$log_content = PHP_EOL . '#'
	. PHP_EOL . "# Generated {$generated_horses} horses.";
file_put_contents("{$basepath}/app_core/cron_files/logs/cron_one_hour_{$date_now}", $log_content, FILE_APPEND);
