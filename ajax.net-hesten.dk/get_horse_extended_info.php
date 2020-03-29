<?php

if ($index_caller !== true) {
	exit();
}
header('content-type: application/json; charset=utf-8');

if (($horse_id = (int) filter_input(INPUT_GET, 'horse_id'))) {
	$response = array();
	$response['status'] = false;
	$response['time'] = time();
	if (is_numeric($horse_id)) {

		$result = $link_new->query("SELECT horse.id, navn AS name, alder AS age, kon AS gender, race, tegner AS artist, pris AS value, bruger AS owner_name, "
				. "talent, ulempe, egenskab, unik, original, "
				. "("
				. "SELECT count(participant_id) AS gold_medal FROM `{$GLOBALS['DB_NAME_NEW']}`.game_data_competition_participants participant "
				. "LEFT JOIN `{$GLOBALS['DB_NAME_NEW']}`.game_data_competitions competition ON competition.id = participant.competition_id "
				. "WHERE participant_id = horse.id AND participant.points = 1 AND competition.allowed_types IN (null, '') "
				. ") AS gold_medal, "
				. "("
				. "SELECT count(participant_id) AS gold_medal FROM `{$GLOBALS['DB_NAME_NEW']}`.game_data_competition_participants participant "
				. "LEFT JOIN `{$GLOBALS['DB_NAME_NEW']}`.game_data_competitions competition ON competition.id = participant.competition_id "
				. "WHERE participant_id = horse.id AND participant.points = 2 AND competition.allowed_types IN (null, '') "
				. ") AS silver_medal, "
				. "("
				. "SELECT count(participant_id) AS gold_medal FROM `{$GLOBALS['DB_NAME_NEW']}`.game_data_competition_participants participant "
				. "LEFT JOIN `{$GLOBALS['DB_NAME_NEW']}`.game_data_competitions competition ON competition.id = participant.competition_id "
				. "WHERE participant_id = horse.id AND participant.points = 3 AND competition.allowed_types IN (null, '') "
				. ") AS bronze_medal, "
				. "("
				. "SELECT count(participant_id) AS gold_medal FROM `{$GLOBALS['DB_NAME_NEW']}`.game_data_competition_participants participant "
				. "LEFT JOIN `{$GLOBALS['DB_NAME_NEW']}`.game_data_competitions competition ON competition.id = participant.competition_id "
				. "WHERE participant_id = horse.id AND participant.points < 6 AND competition.allowed_types IN (2) "
				. ") AS junior_medal "
				. "FROM `{$GLOBALS['DB_NAME_OLD']}`.Heste horse "
				. "WHERE horse.id = {$horse_id}");
		if (!$result) {
			$response['horse_id'] = $horse_id;
			echo $_GET['callback'] . '(' . json_encode([$response]) . ')';
			exit();
		}
		while ($data = $result->fetch_object()) {
			$response['status'] = true;
			$response['horse_data'] = $data;

			echo $_GET['callback'] . '(' . json_encode($response) . ')';
			exit();
		}
	}
	exit();
}
