<?php

chdir(dirname(__FILE__));
$basepath = '../../';

date_default_timezone_set('Europe/Copenhagen');
$current_date = new DateTime('now');
$date_now = $current_date->format('Y-m-d');
$time_now = $current_date->format('H:i:s');
$cron_interval = 'one_day';


$log_content = PHP_EOL . PHP_EOL . '#######################################################'
		. PHP_EOL . '# One day cron started at ' . $time_now;
file_put_contents("{$basepath}app_core/cron_files/logs/cron_{$cron_interval}_{$date_now}", $log_content, FILE_APPEND);

include_once "{$basepath}app_core/cron_files/functions/update_fun_facts.php";

$log_content = ''
		. PHP_EOL . '# Cron compleated its run. '
		. PHP_EOL . '#######################################################' . PHP_EOL;
file_put_contents("app_core/cron_files/logs/cron_{$cron_interval}_{$date_now}", $log_content, FILE_APPEND);
