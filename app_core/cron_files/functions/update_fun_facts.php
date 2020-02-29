<?php

$basepath = '';

if (!isset($time_now)) {
	date_default_timezone_set('Europe/Copenhagen');
	$current_date = new DateTime('now');
	$date_now = $current_date->format('Y-m-d');
	$time_now = $current_date->format('H:i:s');
}

$log_content = PHP_EOL . '# Updating fun facts.';
file_put_contents("app_core/cron_files/logs/cron_{$cron_interval}_{$date_now}", $log_content, FILE_APPEND);

require_once "{$basepath}app_core/db_conf.php";

$Foelbox = mb_convert_encoding('Følkassen', 'latin1', 'UTF-8');
$foel = mb_convert_encoding('føl', 'latin1', 'UTF-8');
$Foel = mb_convert_encoding('Føl', 'latin1', 'UTF-8');

$today = date("d.m.y.G.i");


$log_content = PHP_EOL . '# Done updating fun facts.';
file_put_contents("app_core/cron_files/logs/cron_{$cron_interval}_{$date_now}", $log_content, FILE_APPEND);