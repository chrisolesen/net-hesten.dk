<?php

if (!isset($time_now)) {
	date_default_timezone_set('Europe/Copenhagen');
	$current_date = new DateTime('now');
	$date_now = $current_date->format('Y-m-d');
	$time_now = $current_date->format('H:i:s');
}
if (!isset($cron_interval)) {
	$cron_interval = 'one_day';
}
$date_target = DateTime::createFromFormat("Y-m-d H:i:s", $current_date->format("Y-m-d H:i:s"));
$date_target->sub(new DateInterval("P2DT12H"));
$mysqli_date_target = $date_target->format("Y-m-d H:i:s");
$mysqli_date_now = $current_date->format("Y-m-d H:i:s");

$log_content = PHP_EOL . "# Updating age of horses with {$mysqli_date_target} as target date.";
file_put_contents("{$basepath}app_core/cron_files/logs/cron_{$cron_interval}_{$date_now}", $log_content, FILE_APPEND);

require_once "{$basepath}app_core/db_conf.php";

$Foelbox = 'Følkassen';
$foel = 'føl';
$Foel = 'Føl';
$dead = 'død';
$Dead = 'Død';

$today = date("d.m.y.G.i");


/* Find all horses */
$total_horses = $link_new->query("SELECT count(*) AS `total` FROM `{$GLOBALS['DB_NAME_OLD']}`.`Heste` 
WHERE `bruger` <> 'Hestehandleren*' AND `bruger` <> '{$Foelbox}' AND `status` <> '{$dead}' AND `age_updated` < '{$mysqli_date_target}'")->fetch_object()->total;

$log_content = PHP_EOL . "# Found a total of {$total_horses} living target horses.";
file_put_contents("{$basepath}app_core/cron_files/logs/cron_{$cron_interval}_{$date_now}", $log_content, FILE_APPEND);

$limit_pr_run = 400;
$processed_horses = 0;
$skipped = 0; /* Age is correct */
$high = 0; /* Fatal, age i too high */
$high_more_than_one = 0;
$low = 0; /* Need aging */

$result = $link_new->query("SELECT * FROM `{$GLOBALS['DB_NAME_OLD']}`.`Heste` 
WHERE `bruger` <> 'Hestehandleren*' AND `bruger` <> '{$Foelbox}' AND `status` <> '{$Dead}' AND `status` <> '{$dead}' AND `age_updated` < '{$mysqli_date_target}' 
ORDER BY `id` ASC LIMIT {$limit_pr_run}");
if ($result) {
	while ($horse = $result->fetch_object()) {
		++$processed_horses;
		$horse_born = DateTime::createFromFormat("Y-m-d H:i:s", $horse->date);
		$date_interval = $current_date->diff($horse_born);
		$horse_target_age = (int) floor((($date_interval->days) / 40));
		if ($horse_target_age == $horse->alder) {
			++$skipped;
			/* Horse matches expected age, update touch date */
			$link_new->query("UPDATE `{$GLOBALS['DB_NAME_OLD']}`.`Heste` SET `age_updated` = '{$mysqli_date_now}' WHERE `id` = '{$horse->id}'");
			continue;
		}
		if ($horse_target_age > $horse->alder) {
			++$low;
			/* Update horse age */
			$link_new->query("UPDATE `{$GLOBALS['DB_NAME_OLD']}`.`Heste` SET `alder` = '{$horse_target_age}', `age_updated` = '{$mysqli_date_now}' WHERE `id` = '{$horse->id}'");
			continue;
		}
		if ($horse_target_age < $horse->alder) {
			++$high;
			/* Dont down age horses, so simply set age_updated */
			$link_new->query("UPDATE `{$GLOBALS['DB_NAME_OLD']}`.`Heste` SET `age_updated` = '{$mysqli_date_now}' WHERE `id` = '{$horse->id}'");
			continue;
		}
	}
}

$log_content = PHP_EOL . "# Processed {$processed_horses} horses."
	. PHP_EOL . "# Low: {$low}, High: {$high}, Skipped {$skipped}.";
file_put_contents("{$basepath}app_core/cron_files/logs/cron_{$cron_interval}_{$date_now}", $log_content, FILE_APPEND);
