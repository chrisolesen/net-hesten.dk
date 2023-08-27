<?php

if (in_array('global_admin', $_SESSION['rights'] ?? []) || in_array('hestetegner_admin', $_SESSION['rights'] ?? [])) {
	if (filter_input(INPUT_GET, 'action') == 'reject_artist_submission') {
		$script_feedback[] = artist_center::reject_drawing([
			'submission_id' => filter_input(INPUT_GET, 'submission_id'),
		]);
	}
}
