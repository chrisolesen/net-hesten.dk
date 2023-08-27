<?php

if ($index_caller !== true) {
	exit();
}


if (($horse_id = filter_input(INPUT_GET, 'horse_id'))) {

	$sql = "SELECT `morid` AS `mother`, `farid` AS `father` 
	FROM `{$GLOBALS['DB_NAME_OLD']}`.`Heste` 
	WHERE `id` = {$horse_id} 
	LIMIT 1";
	$parents = $link_new->query($sql);
	if ($parents) {
		$parents = $parents->fetch_object();
	} else {
		$parents = (object) [];
		$parents->father = 0;
		$parents->mother = 0;
	}

	$sql = "SELECT `id`, `navn`, `alder`, `thumb`, `kon`, `status`, `unik`, `farid`, `morid` 
	FROM `net-hesten-old`.`Heste` 
	WHERE 
	`farid` = {$horse_id} OR `morid` = {$horse_id} -- Children 
	OR `id` = {$horse_id} -- SELF  
	OR `id` IN (0,{$parents->father},{$parents->mother}) -- Parents 
	OR `farid` = {$parents->father} OR `morid` = {$parents->mother} -- Siblings
	";

	$result = $link_new->query($sql);

	$return_data = [];

	while ($data = $result->fetch_object()) {
		$horse_array = [
			'id' => $data->id,
			'name' => $data->navn,
			'age' => $data->alder,
			'image' => $data->thumb,
			'gender' => (strtolower($data->kon) == 'hoppe' ? 'female' : 'male'),
			'status' => $data->status,
			'unique' => $data->unik
		];

		if ($data->id === $parents->father) {
			$return_data['parents']['father'] = $horse_array;
		} else if ($data->id === $parents->mother) {
			$return_data['parents']['mother'] = $horse_array;
		} else if ($data->id === $horse_id) {
			$return_data['self'] = $horse_array;
		} else {
			$return_data['children'][] = $horse_array;
		}
	}
	if (!empty($return_data)) {
		echo json_encode($return_data, JSON_FORCE_OBJECT);
		exit();
	} else {
		exit();
	}
}
