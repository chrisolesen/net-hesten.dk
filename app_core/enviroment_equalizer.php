<?php

if (defined('PROJECT_TIMEZONE')) {
    date_default_timezone_set(PROJECT_TIMEZONE);
    $time_zone_offset = ((new DateTime('now'))->getOffset() / 60 / 60) . ':00';
    if ($time_zone_offset == '0:00') {
        $time_zone_offset = '-' . $time_zone_offset;
    }
    $result = $link_new->query("SET time_zone = '{$time_zone_offset}'");
    $GLOBALS['pdo_new']->exec("SET time_zone = '{$time_zone_offset}'");
    $GLOBALS['pdo_old']->exec("SET time_zone = '{$time_zone_offset}'");
}
