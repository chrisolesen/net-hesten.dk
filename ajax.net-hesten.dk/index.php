<?php
session_start();

$index_caller = true;
require_once '../app_core/db_conf.php';

if (filter_input(INPUT_GET,'request') === 'feed_live_content') {
	require './feed_live_content.php';
} else if (filter_input(INPUT_GET,'request') === 'suggest_breed_targets') {
	require './suggest_breed_targets.php';
} else if (filter_input(INPUT_GET,'request') === 'suggest_event_participants') {
	require './suggest_event_participants.php';
} else if (filter_input(INPUT_GET,'request') === 'fetch_stud_horse') {
//	require './fetch_stud_horse.php';
} else if (filter_input(INPUT_GET,'request') === 'save_horse_name') {
	require './save_horse_name.php';
} else if (filter_input(INPUT_GET,'request') === 'fecth_linage') {
	require './fetch_linage.php';
} else if (filter_input(INPUT_GET,'request') === 'fecth_extended_info') {
	require './get_horse_extended_info.php';
}
exit();
