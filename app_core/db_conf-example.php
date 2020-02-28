<?php

$_GLOBALS['hidden_system_users'] = [1, 2, 3];
$_GLOBALS['hidden_system_users_sql'] = '1, 2, 3';

$_GLOBALS['project_upload_secret'] = 'crypt-code';

date_default_timezone_set('Europe/Copenhagen');
define('DB_HOST', 'localhost');
define('DB_USER', '');
define('DB_PASS', '');
define('DB_PORT', '3306');
define('DB_NAME_OLD', '');
define('DB_NAME_NEW', '');
$_GLOBALS['DB_NAME_OLD'] = DB_NAME_OLD;
$_GLOBALS['DB_NAME_NEW'] = DB_NAME_NEW;

$link = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME_OLD, DB_PORT);
if (!$link) {
	die('<!--Could not connect: ' . mysqli_error($link) . '-->');
}
$link_old = $link;
$link_old->set_charset('latin1');
$link_old->query("SET time_zone = 'Europe/Copenhagen'");
$link_new = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME_NEW, DB_PORT);
$link_new->set_charset('utf8');
$link_new->query("SET time_zone = 'Europe/Copenhagen'");
