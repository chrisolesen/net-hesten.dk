<?php

if (defined('PROJECT_TIMEZONE')) {
    date_default_timezone_set(PROJECT_TIMEZONE);
    $time_zone_offset = ((new DateTime('now'))->getOffset() / 60 / 60);
    if ($time_zone_offset >= 0) {
        $time_zone_offset = '+' . $time_zone_offset;
    }

    $link_new->query("SET time_zone = '{$time_zone_offset}:00'");
    $GLOBALS['pdo_new']->exec("SET time_zone = '{$time_zone_offset}:00'");
    $GLOBALS['pdo_old']->exec("SET time_zone = '{$time_zone_offset}:00'");
}
