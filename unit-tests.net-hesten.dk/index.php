<?php
chdir(dirname(__FILE__));
$basepath = realpath(__DIR__ . '/..');
require_once "{$basepath}/app_core/object_loader.php";

$php_time = (new DateTime('now'))->format('H:i:s');
$link_time = $link_new->query('SELECT time(NOW()) AS server_time')->fetch_object()->server_time;

/* PDO time */
$sth = $GLOBALS['pdo_new']->prepare('SELECT time(NOW()) AS server_time', array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
$sth->execute();
$pdo_time = $sth->fetch(PDO::FETCH_OBJ)->server_time;

echo "TimeZone: ".PROJECT_TIMEZONE."<br />";
echo "Server times: PHP = {$php_time} - DBLink = {$link_time} - PDO - {$pdo_time}";
