<?php

/* Load base language to initialize all strings and have fallbacks */
require_once("{$basepath}/languages/da_DK/horse_objects.php");
require_once("{$basepath}/languages/da_DK/trade_terms.php");
require_once("{$basepath}/languages/da_DK/settings.php");

/* Load user language strings where they exists */

if (isset($_SESSION['settings']) && isset($_SESSION['settings']['user_language']) && !(in_array($_SESSION['settings']['user_language'], ['da_DK', '']))) {
    @include("{$basepath}/languages/{$_SESSION['settings']['user_language']}/horse_objects.php");
    @include("{$basepath}/languages/{$_SESSION['settings']['user_language']}/trade_terms.php");
    @include("{$basepath}/languages/{$_SESSION['settings']['user_language']}/settings.php");
}
