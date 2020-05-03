<?php

chdir(dirname(__FILE__));
$basepath = '../../';

define('cron_by', 'one_hour');

date_default_timezone_set('Europe/Copenhagen');
$current_date = new DateTime('now');
$date_now = $current_date->format('Y-m-d');
$time_now = $current_date->format('H:i:s');

$log_content = PHP_EOL . PHP_EOL . '#######################################################'
	. PHP_EOL . '# One hour cron started at ' . $time_now;
file_put_contents("{$basepath}/app_core/cron_files/logs/cron_one_hour_{$date_now}", $log_content, FILE_APPEND);

include_once "{$basepath}/app_core/cron_files/functions/die.php";
include_once "{$basepath}/app_core/cron_files/functions/grow_up.php";
include_once "{$basepath}/app_core/cron_files/functions/give_birth.php";
if ($time_now > '18:00:00' && $time_now < '19:00:00') {
	include_once "{$basepath}/app_core/cron_files/functions/automate_contests.php";
}

$log_content = ''
	. PHP_EOL . '# Cron compleated its run. '
	. PHP_EOL . '#######################################################' . PHP_EOL;
file_put_contents("{$basepath}/app_core/cron_files/logs/cron_one_hour_{$date_now}", $log_content, FILE_APPEND);
