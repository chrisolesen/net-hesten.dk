<?php

if ($index_caller !== true) {
	exit();
}

$foel = 'føl';
$limits = filter_input(INPUT_GET, 'limits');
$only_foels = '';
if (strpos(' ' . $limits . ' ', 'only_foels')) {
	$real_limit = " AND status = '{$foel}' ";
	$only_foels = " AND status = '{$foel}' ";
} else {
	$only_foels = " AND status <> '{$foel}' ";
}

if (stripos(' ' . $limits . ' ', 'races')) {

	$real_limit = " AND race IN ('dfjbnifgnb'";
	$races = $link_new->query("SELECT name FROM `horse_races` WHERE `id` IN (" . str_replace('races:', '', $limits) . ")");
	while ($race = $races->fetch_object()) {
		$real_limit .= ",'" . mb_convert_encoding($race->name,'latin1','UTF-8') . "'";
	}
	$real_limit .= ")";
}
$user_id = filter_input(INPUT_GET, 'user_id');
$stutteri = $link_new->query("SELECT stutteri FROM `{$GLOBALS['DB_NAME_OLD']}`.`Brugere` WHERE `id` = {$user_id}")->fetch_object()->stutteri;

$dead = 'død';

$result = $link_new->query("SELECT id, navn, bruger, alder, thumb, egenskab, ulempe, talent, race "
		. "FROM `{$GLOBALS['DB_NAME_OLD']}`.`Heste` AS heste "
		. "LEFT JOIN `{$GLOBALS['DB_NAME_NEW']}`.game_data_competition_participants AS contests "
		. "ON contests.participant_id = heste.id AND contests.points IS NULL "
		. "LEFT JOIN `{$GLOBALS['DB_NAME_NEW']}`.horse_metadata AS breeding "
		. "ON breeding.horse_id = heste.id AND breeding.meta_key = 'breeding' "
		. "WHERE breeding.meta_value IS NULL AND competition_id IS NULL AND bruger = '{$stutteri}' AND staevne = '' AND STATUS <> '{$dead}' AND graesning = '' AND alder < 20  {$real_limit} {$only_foels} ORDER BY rand() LIMIT 4");
$return_data = '';
while ($data = $result->fetch_object()) {

	$return_data .= "<li style='cursor:pointer;padding:5px;clear:both;display:block;line-height:20px;' data-horse_id='{$data->id}' data-type='potential_breed_target'>"
			. " <img style = 'float:left;margin-right:10px;' src = '//files.net-hesten.dk/{$data->thumb}' height = '100px' />"
			. mb_convert_encoding(" <div>{$data->race}</div>  ", 'UTF-8', 'latin1')
			. mb_convert_encoding(" <div>{$data->navn}  ", 'UTF-8', 'latin1') . " ({$data->alder}år)</div>"
			. mb_convert_encoding(" <div>{$data->egenskab}</div>  ", 'UTF-8', 'latin1')
			. mb_convert_encoding(" <div>{$data->ulempe}</div>  ", 'UTF-8', 'latin1')
			. mb_convert_encoding(" <div>{$data->talent}-talent</div>  ", 'UTF-8', 'latin1')
			. " </li>";
}

if (!empty($return_data)) {
	$return_data = "<ul>{$return_data}</ul>";
	echo $return_data;
	exit();
} else {
	exit();
}
