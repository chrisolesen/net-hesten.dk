<?php

if ($index_caller !== true) {
	exit();
}


if (($user_id = (int) filter_input(INPUT_GET, 'user_id'))) {
	$response = ['time' => time()];
	$message_count = $link_new->query("SELECT count(`id`) AS `amount_messages` FROM `{$GLOBALS['DB_NAME_NEW']}`.`game_data_private_messages` WHERE `status_code` = 17 AND `target` = {$user_id} LIMIT 1")->fetch_object()->amount_messages;
	$last_main_chat_message = $link_new->query("SELECT `creation_date` FROM `{$GLOBALS['DB_NAME_NEW']}`.`praktisk_nethest_new`.`game_data_chat_messages` ORDER BY `creation_date` DESC LIMIT 1;")->fetch_object()->creation_date;
	$last_main_chat_read = $link_new->query("SELECT `value` FROM `{$GLOBALS['DB_NAME_NEW']}`.`praktisk_nethest_new`.`user_data_timing` WHERE `name` = 'last_online_chat' AND `parent_id` = {$user_id} LIMIT 1")->fetch_object()->value;
	
	if($last_main_chat_read && $last_main_chat_message > $last_main_chat_read){
		$response['main_chat'] = 'blink';
	} else if($last_main_chat_read && $last_main_chat_message <= $last_main_chat_read){
		$response['main_chat'] = 'no_blink';
	}
	
	if ($message_count && (int) $message_count > 0) {
		$response['private_messages'] = 'blink';
	} else {
		$response['private_messages'] = 'no_blink';
	}
	
	echo json_encode($response);
} else {
	echo 'no user';
	exit();
}
