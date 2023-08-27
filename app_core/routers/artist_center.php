<?php

if (filter_input(INPUT_GET, 'reject_artist_submission')) {

	$script_feedback[] = artist_center::reject_drawing([
		'submission_id' => filter_input(INPUT_GET, 'reject_artist_submission'),
	]);
	
}
