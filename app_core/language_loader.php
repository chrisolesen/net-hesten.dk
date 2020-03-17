<?php 

/* Load base language to initialize all strings and have fallbacks */
require_once("{$basepath}/languages/da_DK/horse_objects.php");
require_once("{$basepath}/languages/da_DK/settings.php");

/* Load user language strings where they exists */
if($_SESSION['settings']['user_language'] != 'da_DK'){
    include ("{$basepath}/languages/{$_SESSION['settings']['user_language']}/horse_objects.php");
    include ("{$basepath}/languages/{$_SESSION['settings']['user_language']}/settings.php");
}