<?php

$GLOBALS['hidden_system_users'] = [1, 2, 3];
$GLOBALS['hidden_system_users_sql'] = '1, 2, 3';

// You can grab a key from here https://www.random.org/passwords/?num=5&len=32&format=html&rnd=new
$GLOBALS['project_upload_secret'] = 'crypt-code';

define('PROJECT_TIMEZONE', 'Europe/Copenhagen');
define('DB_HOST', 'localhost');
define('DB_USER', '');
define('DB_PASS', '');
define('DB_PORT', '3306');
define('DB_NAME_OLD', '');
define('DB_NAME_NEW', '');
$GLOBALS['DB_NAME_OLD'] = DB_NAME_OLD;
$GLOBALS['DB_NAME_NEW'] = DB_NAME_NEW;

$link_new = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME_NEW, DB_PORT);
$link_new->set_charset('utf8');

$GLOBALS['pdo_new'] = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME_NEW . ';charset=utf8;', DB_USER, DB_PASS);

/* Only used for install */
$GLOBALS['pdo_old'] = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME_OLD . ';charset=utf8;', DB_USER, DB_PASS);
