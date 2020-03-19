<?php

$GLOBALS['hidden_system_users'] = [1, 2, 3];
$GLOBALS['hidden_system_users_sql'] = '1, 2, 3';

$GLOBALS['project_upload_secret'] = 'crypt-code';

date_default_timezone_set('Europe/Copenhagen');
define('DB_HOST', 'localhost');
define('DB_USER', '');
define('DB_PASS', '');
define('DB_PORT', '3306');
define('DB_NAME_OLD', '');
define('DB_NAME_NEW', '');
$mysql_host = DB_HOST;
$mysql_user = DB_USER;
$mysql_password = DB_PASS;
$mysql_database_old = DB_NAME_OLD;
$mysql_database_new = DB_NAME_NEW;
$GLOBALS['DB_NAME_OLD'] = DB_NAME_OLD;
$GLOBALS['DB_NAME_NEW'] = DB_NAME_NEW;

$link_new = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME_NEW, DB_PORT);
$link_new->set_charset('utf8');
$link_new->query("SET time_zone = 'Europe/Copenhagen'");

$GLOBALS['pdo_new'] = new PDO("mysql:host={$mysql_host};dbname={$mysql_database_new};charset=utf8;", $mysql_user, $mysql_password);
