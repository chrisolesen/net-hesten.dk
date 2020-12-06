<?php

ini_set('session.cookie_domain', HTTP_HOST);
session_start(['cookie_lifetime' => 172800, 'cookie_domain' => HTTP_HOST]);
if (isset($_POST['password']) && isset($_POST['username'])) {

	$username = mb_strtolower($_POST['username']);
	$password = $_POST['password'];
	$username = filter_var($username, FILTER_SANITIZE_STRING);
	$username = mysqli_real_escape_string($link_new, $username);

	$result = $link_new->query(
		"SELECT `email`, `id`, `stutteri`, `password`, `hestetegner`, `penge`, `navn` 
		FROM `{$GLOBALS['DB_NAME_OLD']}`.`Brugere` 
		WHERE '{$username}' IN (`stutteri`,`email`) 
		LIMIT 1"
	);

	if ($result) {
		while ($data = $result->fetch_assoc()) {
			if (crypt(trim($password), $data['password']) === $data['password']) {
				$user_found = true;
				$_SESSION['settings'] = [];

				/* data upgrade block - start */

				$user_list_style = ($link_new->query("SELECT `value` FROM `user_data_varchar` WHERE `parent_id` = {$data['id']} AND `name` = 'list_style' LIMIT 1")->fetch_object()->value ?? false);
				$_SESSION['settings']['list_style'] = $user_list_style;

				if (!$user_list_style) {
					$link_new->query("INSERT INTO `user_data_varchar` (`parent_id`, `value`, `name`, `date`) VALUES ({$data['id']}, 'compact', 'list_style', NOW())");
					$_SESSION['settings']['list_style'] = 'compact';
				}


				$user_display_width = ($link_new->query("SELECT `value` FROM `user_data_varchar` WHERE `parent_id` = {$data['id']} AND `name` = 'display_width' LIMIT 1")->fetch_object()->value ?? false);
				$_SESSION['settings']['display_width'] = $user_display_width;

				if (!$user_display_width) {
					$link_new->query("INSERT INTO `user_data_varchar` (`parent_id`, `value`, `name`, `date`) VALUES ({$data['id']}, 'full', 'display_width', NOW())");
					$_SESSION['settings']['display_width'] = 'full';
				}


				$user_display_width = ($link_new->query("SELECT `value` FROM `user_data_varchar` WHERE `parent_id` = {$data['id']} AND `name` = 'left_menu_style' LIMIT 1")->fetch_object()->value ?? false);
				$_SESSION['settings']['left_menu_style'] = $user_display_width;

				if (!$user_display_width) {
					$link_new->query("INSERT INTO `user_data_varchar` (`parent_id`, `value`, `name`, `date`) VALUES ({$data['id']}, 'standard', 'left_menu_style', NOW())");
					$_SESSION['settings']['left_menu_style'] = 'standard';
				}


				$user_last_read_dev_notes = ($link_new->query("SELECT `value` FROM `user_data_timing` WHERE `parent_id` = {$data['id']} AND `name` = 'last_read_dev_notes' LIMIT 1")->fetch_object()->value ?? false);
				$_SESSION['settings']['last_read_dev_notes'] = $user_last_read_dev_notes;

				if (!$user_last_read_dev_notes) {
					$link_new->query("INSERT INTO `user_data_timing` (`parent_id`, `value`, `name`) VALUES ({$data['id']}, '0000-00-00 00:00:00', 'last_read_dev_notes')");
					$_SESSION['settings']['last_read_dev_notes'] = '0000-00-00 00:00:00';
				}

				$user_accept_offers = ($link_new->query("SELECT `value` FROM `user_data_varchar` WHERE `parent_id` = {$data['id']} AND `name` = 'accept_offers' LIMIT 1")->fetch_object()->value ?? false);
				$_SESSION['settings']['accept_offers'] = $user_accept_offers;

				if (!$user_accept_offers) {
					$link_new->query("INSERT INTO `user_data_varchar` (`parent_id`, `value`, `name`) VALUES ({$data['id']}, 'reject', 'accept_offers')");
					$_SESSION['settings']['accept_offers'] = 'reject';
				}


				$user_banner_size = ($link_new->query("SELECT `value` FROM `user_data_varchar` WHERE `parent_id` = {$data['id']} AND `name` = 'banner_size' LIMIT 1")->fetch_object()->value ?? false);
				$_SESSION['settings']['banner_size'] = $user_banner_size;

				if (!$user_banner_size) {
					$link_new->query("INSERT INTO `user_data_varchar` (`parent_id`, `value`, `name`, `date`) VALUES ({$data['id']}, 'full_size', 'banner_size', NOW())");
					$_SESSION['settings']['banner_size'] = 'full_size';
				}

				$user_graes_confirmations = ($link_new->query("SELECT `value` FROM `user_data_varchar` WHERE `parent_id` = {$data['id']} AND `name` = 'graes_confirmations' LIMIT 1")->fetch_object()->value ?? false);
				$_SESSION['settings']['graes_confirmations'] = $user_graes_confirmations;

				if (!$user_graes_confirmations) {
					$link_new->query("INSERT INTO `user_data_varchar` (`parent_id`, `value`, `name`, `date`) VALUES ({$data['id']}, 'show', 'graes_confirmations', NOW())");
					$_SESSION['settings']['graes_confirmations'] = 'show';
				}

				$horse_trader_buy_confirmations = ($link_new->query("SELECT `value` FROM `user_data_varchar` WHERE `parent_id` = {$data['id']} AND `name` = 'horse_trader_buy_confirmations' LIMIT 1")->fetch_object()->value ?? false);
				$_SESSION['settings']['horse_trader_buy_confirmations'] = $horse_trader_buy_confirmations;
				if (!$horse_trader_buy_confirmations) {
					$link_new->query("INSERT INTO `user_data_varchar` (`parent_id`, `value`, `name`, `date`) VALUES ({$data['id']}, 'show', 'horse_trader_buy_confirmations', NOW())");
					$_SESSION['settings']['horse_trader_buy_confirmations'] = 'show';
				}

				$user_language = ($link_new->query("SELECT `value` FROM `user_data_varchar` WHERE `parent_id` = {$data['id']} AND `name` = 'user_language' LIMIT 1")->fetch_object()->value ?? false);
				$_SESSION['settings']['user_language'] = $user_language;
				if (!$user_language) {
					$link_new->query("INSERT INTO `user_data_varchar` (`parent_id`, `value`, `name`, `date`) VALUES ({$data['id']}, 'da_DK', 'user_language', NOW())");
					$_SESSION['settings']['user_language'] = 'da_DK';
				}

				/* data upgrade block - end */

				$_SESSION['logged_in'] = true;
				$_SESSION['username'] = $data['stutteri'];
				$_SESSION['user_id'] = $data['id'];
				$_SESSION['is_hestetegner'] = $data['hestetegner'];
				$_SESSION['email'] = $data['email'];

				$_SESSION['valid_ip'] = $_SERVER['REMOTE_ADDR'];

				user::register_timing(['user_id' => $_SESSION['user_id'], 'key' => 'last_login']);
				horse_list_filters::save_filter_settings(['reset_all_filters' => true]);
			}
		}
		if (!($user_found ?? false)) {
			echo "Ukendt bruger og password kombination: Du tastede \"$username\" som dit stutteri-navn. Prøv venligst igen. Hvis der er en fejl og du mener du burde have adgang så skriv til admin@net-hesten.dk.";
		}
	}
}

if ((isset($_SESSION['valid_ip']) && $_SESSION['valid_ip'] != $_SERVER['REMOTE_ADDR'])) {
	session_destroy();
	header('Location: /');
}

if (isset($_POST['logout']) || isset($_GET['logout'])) {
	session_destroy();
	header('Location: /');
}



if (isset($_SESSION['user_id']) && (isset($_SESSION['valid_ip']) && $_SESSION['valid_ip'] == filter_input(INPUT_SERVER, 'REMOTE_ADDR'))) {

	/* Select users privileges */
	$sql = "SELECT `privilege_name` as `rights`, `end` 
		FROM `privilege_types` 
		LEFT JOIN `user_privileges` ON `user_privileges`.`privilege_id` = `privilege_types`.`privilege_id` 
		WHERE `user_privileges`.`user_id` = {$_SESSION['user_id']}";

	$result_lvl2 = $link_new->query($sql);
	$_SESSION['rights'] = [];

	while ($data_lvl2 = $result_lvl2->fetch_assoc()) {
		if ($data_lvl2['end'] == '0000-00-00 00:00:00') {
			$_SESSION['rights'][] = $data_lvl2['rights'];
		}
	}
}
