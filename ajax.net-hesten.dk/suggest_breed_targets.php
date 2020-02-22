<?php

if ($index_caller !== true) {
	exit();
}

if (($horse_id = filter_input(INPUT_GET, 'horse_id'))) {
	$horse_race = $link_old->query("SELECT race FROM Heste WHERE id = '{$horse_id}' LIMIT 1")->fetch_object()->race;
	$dead = mb_convert_encoding('død', 'latin1', 'utf-8');
	$result = $link_old->query("SELECT id, navn, bruger, alder, thumb, egenskab, ulempe, talent FROM Heste WHERE status <> '{$dead}' AND bruger <> 'carsten' AND bruger <> 'auktionshuset' AND kon = 'Hingst' AND race = '{$horse_race}' AND navn <> 'Unavngivet' AND status = 'hest' AND staevne = '' AND graesning = '' AND alder < 20 ORDER BY rand() LIMIT 4");
	$return_data = '';
	while ($data = $result->fetch_object()) {

		$return_data .= "<li style='cursor:pointer;padding:5px;clear:both;display:block;line-height:20px;' data-horse_id='{$data->id}' data-type='potential_breed_target'>"
				. "<img style='float:left;margin-right:10px;' src='https://files.net-hesten.dk/{$data->thumb}' height='100px' />"
				. mb_convert_encoding("<div>{$data->bruger}</div>", 'UTF-8', 'latin1')
				. mb_convert_encoding("<div>{$data->navn}", 'UTF-8', 'latin1') . " ({$data->alder}år)</div>"
				. mb_convert_encoding("<div>{$data->egenskab}</div>", 'UTF-8', 'latin1')
				. mb_convert_encoding("<div>{$data->ulempe}</div>", 'UTF-8', 'latin1')
				. mb_convert_encoding("<div>{$data->talent}-talent</div>", 'UTF-8', 'latin1')
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
