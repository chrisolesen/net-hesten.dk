<?php

if (!isset($basepath)) {
	$basepath = '';
}

if (!isset($time_now)) {
	date_default_timezone_set('Europe/Copenhagen');
	$current_date = new DateTime('now');
	$date_now = $current_date->format('Y-m-d');
	$time_now = $current_date->format('H:i:s');
}

$log_content = PHP_EOL . '# Generating horses to HorseTrader.';
file_put_contents("app_core/cron_files/logs/cron_one_hour_{$date_now}", $log_content, FILE_APPEND);

require_once "{$basepath}app_core/db_conf.php";
require_once "{$basepath}app_core/cron_files/data_collections/generation_horse_names.php";

$Foelbox = mb_convert_encoding('Følkassen', 'latin1', 'UTF-8');
$foel = mb_convert_encoding('føl', 'latin1', 'UTF-8');

$today = date("d.m.y.G.i");

/* [Hour, minute, amount] */
$rounds = [[06, 30, 10], [12, 00, 10], [16, 00, 10], [19, 30, 10]];

$generation_age = 8;
$generated_horses = 0;
$target_horses = 1;

$tech_mail_message = '';
while ($generated_horses <= $target_horses) {

	$thumb_data = $link_old->query("SELECT tegner, thumb, race FROM {$_GLOBALS['DB_NAME_OLD']}.Heste WHERE bruger != 'Hestehandleren*' AND bruger <> '{$Foelbox}' AND status <> '{$foel}' AND genfodes = 'ja' AND unik <> 'ja' ORDER BY RAND() LIMIT 1")->fetch_object();
	$artist = $thumb_data->tegner;
	$thumb = $thumb_data->thumb;
	$race = $thumb_data->race;

	$advantage = $link_old->query("SELECT egenskab FROM {$_GLOBALS['DB_NAME_OLD']}.horse_habits WHERE egenskab <> '' ORDER BY RAND() LIMIT 1")->fetch_object()->egenskab;
	$disadvantage = $link_old->query("SELECT ulempe FROM {$_GLOBALS['DB_NAME_OLD']}.horse_habits WHERE ulempe <> '' ORDER BY RAND() LIMIT 1")->fetch_object()->ulempe;
	$talent = $link_old->query("SELECT talent FROM {$_GLOBALS['DB_NAME_OLD']}.horse_habits WHERE talent <> '' ORDER BY RAND() LIMIT 1")->fetch_object()->talent;

	$height_data = $link_old->query("SELECT lower, upper FROM {$_GLOBALS['DB_NAME_OLD']}.horse_height WHERE race = '{$race}' LIMIT 1")->fetch_object();
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
	$target_date = $current_date->sub(new DateInterval("P{$horse_age_to_irl_days}D"))->format('Y-m-d H:i:s');



	if ($artist && $thumb && $advantage && $disadvantage && $talent) {
		$sql = "INSERT INTO {$_GLOBALS['DB_NAME_OLD']}.Heste " . PHP_EOL
				. '(' . PHP_EOL
				. 'bruger, status, alder, pris, beskrivelse, ' . PHP_EOL
				. 'foersteplads, andenplads, tredieplads, ' . PHP_EOL
				. 'statuschangedate, date, changedate, status_skift, alder_skift, ' . PHP_EOL
				. 'navn, kon, ' . PHP_EOL
				. 'race, tegner, thumb, height, egenskab, ulempe, talent, ' . PHP_EOL
				. 'farid, morid, random_height' . PHP_EOL
				. ')' . PHP_EOL
				. ' VALUES ' . PHP_EOL
				. '(' . PHP_EOL
				. "'hestehandleren', 'Hest', $generation_age, 11000, '', " . PHP_EOL
				. '0, 0, 0, ' . PHP_EOL
				. "'00-00-00 00:00:00', '{$target_date}','{$target_date}', NOW(), NOW(), " . PHP_EOL
				. "'{$name}', '{$gender}', " . PHP_EOL
				. "'{$race}', '{$artist}', '{$thumb}', {$height}, '{$advantage}', '{$disadvantage}', '{$talent}', " . PHP_EOL
				. "'', '', 'nej'" . PHP_EOL
				. ")";
		$link_old->query($sql);

		$error = $link_old->error;

		
	}
	++$generated_horses;
}

$log_content = PHP_EOL . '#'
		. PHP_EOL . "# Generated {$generated_horses} horses.";
file_put_contents("app_core/cron_files/logs/cron_one_hour_{$date_now}", $log_content, FILE_APPEND);