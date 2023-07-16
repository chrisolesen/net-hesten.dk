<?php
$basepath = '..';
require_once("{$basepath}/app_core/db_conf.php");

/*
require "{$basepath}/app_core/object_handlers/horse_trader.php";
$cached_races = [];
horse_trader::generate_count_cache();
*/


/* Update race-cache */
$races_for_file = $link_new->query("SELECT `id`, `name`, `max_height`, `min_height`, `description` FROM `{$GLOBALS['DB_NAME_NEW']}`.`horse_races`")->fetch_all();
$file_content = '<?php' . PHP_EOL;
$file_content .= '/* This file is auto generated by "/admin/hestetegner/admin_races.php" */' . PHP_EOL;
$file_content .= '$cached_races = [];' . PHP_EOL;
foreach ($races_for_file as $race) {
    $file_content .= '$cached_races["' . $race[0] . '"] = ["name" => "' . $race[1] . '","id" => ' . $race[0] . '];' . PHP_EOL;
}
file_put_contents("{$basepath}/files.net-hesten.dk/cache_data/list_of_races.php", $file_content);


/* Update artist */
$result = $link_new->query("SELECT DISTINCT `tegner` FROM `{$GLOBALS['DB_NAME_OLD']}`.`Heste` WHERE `status` <> 'død'");
$file_content = '<?php' . PHP_EOL;
$file_content .= '/* This file is auto generated by "/admin/from_cache/horse_artists.php" */' . PHP_EOL;
$file_content .= '$cached_artists = [];' . PHP_EOL;
while ($artist = $result->fetch_object()) {
    $file_content .= "\$cached_artists[] = (object) ['name' => '{$artist->tegner}'];" . PHP_EOL;
}
file_put_contents("{$basepath}/files.net-hesten.dk/cache_data/cached_artists.php", $file_content);

?>
<a href="/index.php">go back</a>