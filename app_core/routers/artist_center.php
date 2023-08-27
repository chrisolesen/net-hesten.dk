<?php

if (filter_input(INPUT_GET, 'delete_artist_submission')) {

	$script_feedback[] = artist_center::delete_drawing([
		'submission_id' => filter_input(INPUT_GET, 'delete_artist_submission'),
	]);
	
}
