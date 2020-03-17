<?php

if (filter_input(INPUT_POST, 'action')) {
}
/* Horses */
if (filter_input(INPUT_POST, 'action') === 'put_horse_on_grass') {
	//	$script_feedback[] = 
	horses::put_on_grass([
		'horse_id' => filter_input(INPUT_POST, 'horse_id')
	]);
}
if (filter_input(INPUT_POST, 'action') === 'put_horse_in_stable') {
	$script_feedback[] = horses::put_horse_in_stable([
		'horse_id' => filter_input(INPUT_POST, 'horse_id')
	]);
}
if (filter_input(INPUT_POST, 'action') === 'breed_horse') {
	$script_feedback[] = horses::breed_horse([
		'horse_id' => filter_input(INPUT_POST, 'horse_id'),
		'target_horse_id' => filter_input(INPUT_POST, 'target_horse_id')
	]);
}
/* Chat */
if (filter_input(INPUT_POST, 'action') === 'post_chat_message') {
	$script_feedback[] = chat::post_message([
		'poster_id' => $_SESSION['user_id'],
		'message' => filter_input(INPUT_POST, 'message_text')
	]);
	$script_feedback = [];
}
if (filter_input(INPUT_POST, 'action') === 'post_alias_chat_message') {
	$script_feedback[] = alias_chat::post_message([
		'poster_id' => $_SESSION['user_id'],
		'alias' => $_SESSION['horse_rp_alias'],
		'message' => filter_input(INPUT_POST, 'message_text')
	]);
	$script_feedback = [];
}
if (filter_input(INPUT_POST, 'action') === 'post_private_message') {
	$script_feedback[] = private_messages::post_message([
		'poster_id' => $_SESSION['user_id'],
		'message' => filter_input(INPUT_POST, 'message_text'),
		'write_to' => filter_input(INPUT_POST, 'send_to'),
		'thread' => filter_input(INPUT_POST, 'thread')
	]);
	$script_feedback = [];
}
/* Chats - End */

if (filter_input(INPUT_POST, 'action') == strtoupper(md5(crypt('add_horse_template', $GLOBALS['project_upload_secret'])))) {
	$file_upload_allowed = true;
	$target_dir = "$basepath/files.net-hesten.dk/horses/templates";
	require_once "$basepath/app_core/object_handlers/file_uploads.php";
}
/* Auctions */
if (filter_input(INPUT_POST, 'action') === 'put_on_auction') {
	auctions::put_on_sale([
		'sell_date' => filter_input(INPUT_POST, 'sell_date'),
		'horse_id' => filter_input(INPUT_POST, 'horse_id'),
		'buy_now_price' => filter_input(INPUT_POST, 'buy_now_price'),
		'minimum_bid' => filter_input(INPUT_POST, 'minimum_bid'),
		'seller_id' => $_SESSION['user_id']
	]);
}
if (filter_input(INPUT_POST, 'action') == 'bid_on_auction') {
	auctions::place_bid([
		'auction_id' => filter_input(INPUT_POST, 'auction_id'),
		'bid_amount' => filter_input(INPUT_POST, 'bid_amount'),
		'mode' => (filter_input(INPUT_POST, 'buy_now') ? 'buy_now' : 'place_bid')
	]);
}

/* Privat trade */
if (filter_input(INPUT_POST, 'action') === 'offer_privat_trade') {
	private_trade::offer_privat_trade([
		'horse_id' => filter_input(INPUT_POST, 'horse_id'),
		'price' => filter_input(INPUT_POST, 'price'),
		'recipient' => filter_input(INPUT_POST, 'recipient'),
		'seller_id' => $_SESSION['user_id']
	]);
}
if (filter_input(INPUT_POST, 'action') == 'accept_privat_trade') {
	private_trade::accept_privat_trade([
		'trade_id' => filter_input(INPUT_POST, 'trade_id'),
		'buyer_id' => $_SESSION['user_id']
	]);
}
if (filter_input(INPUT_POST, 'action') == 'reject_privat_trade') {
	private_trade::reject_privat_trade([
		'trade_id' => filter_input(INPUT_POST, 'trade_id'),
		'rejector_id' => $_SESSION['user_id']
	]);
} 
if (filter_input(INPUT_POST, 'action') == 'request_private_trade') {
	private_trade::request_private_trade([
		'horse_id' => filter_input(INPUT_POST, 'horse_id'),
		'bid_amount' => filter_input(INPUT_POST, 'bid_amount'),
		'requester_id' => $_SESSION['user_id']
	]);
} 

/* Horse Trader */
if (filter_input(INPUT_POST, 'action') == 'buy_horse_from_trader') {
	$script_feedback[] = horse_trader::buy([
		'horse_id' => filter_input(INPUT_POST, 'horse_id')
	]);
}
if (filter_input(INPUT_POST, 'action') == 'sell_horse_to_trader') {
	$script_feedback[] = horse_trader::sell([
		'horse_id' => filter_input(INPUT_POST, 'horse_id'),
		'seller_id' => $_SESSION['user_id']
	]);
}
/* Member functions */
if (filter_input(INPUT_POST, 'send_personal_data')) {
	$script_feedback[] = user::request_personal_data();
}
if (filter_input(INPUT_POST, 'action') == 'request_password') {
	if (empty(filter_input(INPUT_POST, 'request_email'))) {
		$script_feedback[] = user::request_password([
			'mail' => filter_input(INPUT_POST, 'request_quest'),
		]);
	}
}
if (filter_input(INPUT_POST, 'action') == 'request_membership') {
	if (empty(filter_input(INPUT_POST, 'request_email'))) {
		$script_feedback[] = user::request_membership([
			'user' => filter_input(INPUT_POST, 'request_username'),
			'name' => filter_input(INPUT_POST, 'request_name'),
			'mail' => filter_input(INPUT_POST, 'request_quest'),
			'pass' => filter_input(INPUT_POST, 'request_password'),
		]);
	}
}
if (filter_input(INPUT_GET, 'action') == 'verify_request_mail') {
	$script_feedback[] = user::update_membership_request([
		'mail' => filter_input(INPUT_GET, 'verify_mail'),
		'key' => filter_input(INPUT_GET, 'verify_key'),
		'action' => 'verify_request_mail',
	]);
}
if (filter_input(INPUT_GET, 'action') == 'admin_user_password_reset') {
	$script_feedback[] = user::admin_user_password_reset([
		'user_id' => filter_input(INPUT_GET, 'user_id')
	]);
	header('Location: /admin/management/user_management.php');
	die();
}
if (filter_input(INPUT_GET, 'action') == 'grant_right_horse_artist') {
	if (!in_array('global_admin', $_SESSION['rights'])) {
		ob_end_clean();
		header('Location: /');
		exit();
	}
	$script_feedback[] = user::update_rights([
		'user_id' => filter_input(INPUT_GET, 'user_id'),
		'privilege_type' => '5',
		'action' => 'grant'
	]);
	header("Location: /admin/management/user_management.php?page={$_GET['page']}");
	exit();
}
if (filter_input(INPUT_GET, 'action') == 'remove_right_horse_artist') {
	if (!in_array('global_admin', $_SESSION['rights'])) {
		ob_end_clean();
		header('Location: /');
		exit();
	}
	$script_feedback[] = user::update_rights([
		'user_id' => filter_input(INPUT_GET, 'user_id'),
		'privilege_type' => '5',
		'action' => 'remove'
	]);
	header("Location: /admin/management/user_management.php?page={$_GET['page']}");
	exit();
}


//if (filter_input(INPUT_POST, 'action') == 'post_chat_message') {
//    $script_feedback[] = chat::post_message([
//                'message_text' => filter_input(INPUT_POST, 'message_text')
//    ]);
//}

/* Artist center */
if (filter_input(INPUT_POST, 'action') == 'submit_drawing') {
	if (isset($_SESSION['user_id'])) {
		artist_center::submit_drawing([
			'user_id' => $_SESSION['user_id'],
			'file' => $_FILES['drawing_image'],
			'race' => filter_input(INPUT_POST, 'race'),
			'occasion' => filter_input(INPUT_POST, 'occasion'),
			'theme' => filter_input(INPUT_POST, 'theme'),
			'type' => filter_input(INPUT_POST, 'type')
		]);
	}
}

/* Competitions */
if (filter_input(INPUT_POST, 'action') == 'signup_horse') {
	if (isset($_SESSION['user_id'])) {
		competitions::signup_horse([
			'user_id' => $_SESSION['user_id'],
			'horse_id' => filter_input(INPUT_POST, 'target_horse_id'),
			'competition_id' => filter_input(INPUT_POST, 'event_id'),
		]);
	}
}

/* Filters */
if (filter_input(INPUT_POST, 'action') == 'filter_horses') {
	horse_list_filters::save_filter_settings();
}
