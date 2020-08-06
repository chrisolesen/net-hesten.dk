<?php

if ($index_caller !== true) {
	exit();
}

$limits = filter_input(INPUT_GET, 'limits');
$only_foels = '';
if (strpos(' ' . $limits . ' ', 'only_foels')) {
	$real_limit = " AND `status` = 'føl' ";
	$only_foels = " AND `status` = 'føl' ";
} else {
	$only_foels = " AND `status` <> 'føl' ";
}

if (stripos(' ' . $limits . ' ', 'races')) {

	$real_limit = " AND `race` IN ('dfjbnifgnb'";
	$races = $link_new->query("SELECT `name` FROM `{$GLOBALS['DB_NAME_NEW']}`.`horse_races` WHERE `id` IN (" . str_replace('races:', '', $limits) . ")");
	while ($race = $races->fetch_object()) {
		$real_limit .= ",'" . $race->name . "'";
	}
	$real_limit .= ")";
}
$user_id = filter_input(INPUT_GET, 'user_id');
$stutteri = $link_new->query("SELECT `stutteri` FROM `{$GLOBALS['DB_NAME_OLD']}`.`Brugere` WHERE `id` = {$user_id}")->fetch_object()->stutteri;

$result = $link_new->query(
	"SELECT `id`, `navn`, `bruger`, `alder`, `thumb`, `egenskab`, `ulempe`, `talent`, `race` 
	FROM `{$GLOBALS['DB_NAME_OLD']}`.`Heste` AS `heste` 
	LEFT JOIN `{$GLOBALS['DB_NAME_NEW']}`.`game_data_competition_participants` AS `contests` 
	ON `contests`.`participant_id` = `heste`.`id` AND `contests`.`points` IS NULL 
	LEFT JOIN `{$GLOBALS['DB_NAME_NEW']}`.`horse_metadata` AS `breeding` 
	ON `breeding`.`horse_id` = `heste`.`id` AND `breeding`.`meta_key` = 'breeding' 
	WHERE `breeding`.`meta_value` IS NULL AND `contests`.`competition_id` IS NULL AND `bruger` = '{$stutteri}' AND `staevne` = '' AND `status` <> 'død' AND `graesning` = '' AND `alder` < 20  {$real_limit} {$only_foels} ORDER BY rand() LIMIT 4"
);
$return_data = '';
while ($data = $result->fetch_object()) {

	$return_data .= "<li style='cursor:pointer;padding:5px;clear:both;display:block;line-height:20px;' data-horse_id='{$data->id}' data-type='potential_breed_target'>"
		. " <img style = 'float:left;margin-right:10px;' src = '//files." . HTTP_HOST . "/{$data->thumb}' height = '100px' />"
		. " <div>{$data->race}</div>  "
		. " <div>{$data->navn}  ({$data->alder}år)</div>"
		. " <div>{$data->egenskab}</div>  "
		. " <div>{$data->ulempe}</div>  "
		. " <div>{$data->talent}-talent</div>  "
		. " </li>";
}

if (!empty($return_data)) {
	$return_data = "<ul>{$return_data}</ul>";
	echo $return_data;
	exit();
} else {
	exit();
}
