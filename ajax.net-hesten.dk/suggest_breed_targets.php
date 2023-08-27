<?php

if ($index_caller !== true) {
	exit();
}

if (($horse_id = filter_input(INPUT_GET, 'horse_id'))) {
	$horse_race = $link_new->query("SELECT `race` FROM `{$GLOBALS['DB_NAME_OLD']}`.`Heste` WHERE `id` = '{$horse_id}' LIMIT 1")->fetch_object()->race;
	$dead = 'død';
	$result = $link_new->query("SELECT `id`, `navn`, `bruger`, `alder`, `thumb`, `egenskab`, `ulempe`, `talent` FROM `{$GLOBALS['DB_NAME_OLD']}`.`Heste` WHERE `status` <> '{$dead}' AND `bruger` <> 'carsten' AND `bruger` <> 'auktionshuset' AND `kon` = 'Hingst' AND `race` = '{$horse_race}' AND `navn` <> 'Unavngivet' AND `status` = 'hest' AND `staevne` = '' AND `graesning` = '' AND `alder` < 20 ORDER BY rand() LIMIT 4");
	$return_data = '';
	while ($data = $result->fetch_object()) {

		$return_data .= "<li style='cursor:pointer;padding:5px;clear:both;display:block;line-height:20px;' data-horse_id='{$data->id}' data-type='potential_breed_target'>"
			. "<img style='float:left;margin-right:10px;' src='//files." . HTTP_HOST . "/{$data->thumb}' height='100px' />"
			. "<div>{$data->bruger}</div>"
			. "<div>{$data->navn}" . " ({$data->alder}år)</div>"
			. "<div>{$data->egenskab}</div>"
			. "<div>{$data->ulempe}</div>"
			. "<div>{$data->talent}-talent</div>"
			. "</li>";
	}

	if (!empty($return_data)) {
		$return_data = "<ul>{$return_data}</ul>";
		echo $return_data;
		exit();
	} else {
		exit();
	}
} else if ($find_id = filter_input(INPUT_GET, 'find_id')) {
	$result = $link_new->query("SELECT `id`, `navn`, `bruger`, `alder`, `thumb`, `egenskab`, `ulempe`, `talent` FROM `{$GLOBALS['DB_NAME_OLD']}`.`Heste` WHERE `id` = {$find_id}");
	$return_data = '';
	while ($data = $result->fetch_object()) {

		$return_data .= "<li style='cursor:pointer;padding:5px;clear:both;display:block;line-height:20px;' data-horse_id='{$data->id}' data-type='potential_breed_target'>"
			. "<img style='float:left;margin-right:10px;' src='//files." . HTTP_HOST . "/{$data->thumb}' height='100px' />"
			. "<div>{$data->bruger}</div>"
			. "<div>{$data->navn}" . " ({$data->alder}år)</div>"
			. "<div>{$data->egenskab}</div>"
			. "<div>{$data->ulempe}</div>"
			. "<div>{$data->talent}-talent</div>"
			. "</li>";
	}

	if (!empty($return_data)) {
		$return_data = "<ul>{$return_data}</ul>";
		echo $return_data;
		exit();
	} else {
		exit();
	}
}
