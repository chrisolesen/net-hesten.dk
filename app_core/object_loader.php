<?php

/* ['Message', 'type']

 * Types: Success, Warning, Error

 * * */

$script_feedback = [];

define('HTTP_HOST', filter_input(INPUT_SERVER, 'HTTP_HOST'));
mb_internal_encoding('UTF-8');
require_once "$basepath/app_core/db_conf.php";

/* Load cache data */
include "$basepath/files.net-hesten.dk/cache_data/list_of_races.php"; /* $cached_races */
include "$basepath/files.net-hesten.dk/cache_data/latin_one_strings.php";
include "$basepath/files.net-hesten.dk/cache_data/cached_artists.php";
require "$basepath/app_core/functions/number_dotter.php";
require "$basepath/app_core/functions/array_sorter.php";
require "$basepath/app_core/functions/url_exists.php";
require "$basepath/app_core/functions/months_ago.php";
require "$basepath/app_core/functions/mailer.php";
/* Load order sensitive object handlers */
require "$basepath/app_core/object_handlers/private_messages.php";
require "$basepath/app_core/object_handlers/accounting.php";
/* Non critical object handlers */
require "$basepath/app_core/object_handlers/horses.php";
require "$basepath/app_core/object_handlers/auctions.php";
require "$basepath/app_core/object_handlers/horse_trader.php";
require "$basepath/app_core/object_handlers/alias_chat.php";
require "$basepath/app_core/object_handlers/chat.php";
require "$basepath/app_core/object_handlers/private_trade.php";
require "$basepath/app_core/object_handlers/user.php";
require "$basepath/app_core/object_handlers/artist_center.php";
require "$basepath/app_core/object_handlers/competitions.php";
require "$basepath/app_core/object_handlers/horse_list_filters.php";
require "$basepath/app_core/renderers/loader.php";

require_once "$basepath/app_core/user_validate.php";
require "$basepath/app_core/language_loader.php";

require "$basepath/app_core/impersonations_module.php";

require "$basepath/app_core/action_routing_logic.php";
