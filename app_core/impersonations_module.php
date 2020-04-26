<?php
if (in_array('global_admin', ($_SESSION['rights'] ?? [])) && filter_input(INPUT_GET, 'action') === 'impersonate') {
	/*if (in_array('global_admin', session_value(['name' => 'rights', 'mode' => 'array'])) && filter_input(INPUT_GET, 'action') === 'impersonate') {*/

	$_SESSION['impersonator_id'] = $_SESSION['user_id'];
	$_SESSION['impersonator_username'] = $_SESSION['username'];
	$_SESSION['impersonator_email'] = $_SESSION['email'];
	$impersonate_id = (int) filter_input(INPUT_GET, 'user_id');

	$result = $link_new->query("SELECT `email`, `id`, `stutteri`, `hestetegner`, `penge`, `navn` FROM `{$GLOBALS['DB_NAME_OLD']}`.`Brugere` WHERE `id` = {$impersonate_id}");

	$num_rows = $result->num_rows;
	while ($data = $result->fetch_assoc()) {
		$_SESSION['settings'] = [];

		/* data upgrade block - start */

		$user_list_style = $link_new->query("SELECT `value` FROM `user_data_varchar` WHERE `parent_id` = {$data['id']} AND `name` = 'list_style' LIMIT 1")->fetch_object()->value;
		$_SESSION['settings']['list_style'] = $user_list_style;
		if (!$user_list_style) {
			$link_new->query("INSERT INTO `user_data_varchar` (`parent_id`, `value`, `name`, `date`) VALUES ({$data['id']}, 'compact', 'list_style', NOW())");
			$_SESSION['settings']['list_style'] = 'compact';
		}

		$user_banner_size = $link_new->query("SELECT `value` FROM `user_data_varchar` WHERE `parent_id` = {$data['id']} AND `name` = 'banner_size' LIMIT 1")->fetch_object()->value;
		$_SESSION['settings']['banner_size'] = $user_banner_size;

		if (!$user_banner_size) {
			$link_new->query("INSERT INTO `user_data_varchar` (`parent_id`, `value`, `name`, `date`) VALUES ({$data['id']}, 'full_size', 'banner_size', NOW())");
			$_SESSION['settings']['banner_size'] = 'full_size';
		}

		$user_graes_confirmations = $link_new->query("SELECT `value` FROM `user_data_varchar` WHERE `parent_id` = {$data['id']} AND `name` = 'graes_confirmations' LIMIT 1")->fetch_object()->value;
		$_SESSION['settings']['graes_confirmations'] = $user_graes_confirmations;
		if (!$user_graes_confirmations) {
			$link_new->query("INSERT INTO `user_data_varchar` (`parent_id`, `value`, `name`, `date`) VALUES ({$data['id']}, 'show', 'graes_confirmations', NOW())");
			$_SESSION['settings']['graes_confirmations'] = 'show';
		}

		$horse_trader_buy_confirmations = $link_new->query("SELECT `value` FROM `user_data_varchar` WHERE `parent_id` = {$data['id']} AND `name` = 'horse_trader_buy_confirmations' LIMIT 1")->fetch_object()->value;
		$_SESSION['settings']['horse_trader_buy_confirmations'] = $horse_trader_buy_confirmations;
		if (!$horse_trader_buy_confirmations) {
			$link_new->query("INSERT INTO `user_data_varchar` (`parent_id`, `value`, `name`, `date`) VALUES ({$data['id']}, 'show', 'horse_trader_buy_confirmations', NOW())");
			$_SESSION['settings']['horse_trader_buy_confirmations'] = 'show';
		}

		/* data upgrade block - end */

		$_SESSION['logged_in'] = true;
		$_SESSION['username'] = $data['stutteri'];
		$_SESSION['user_id'] = $data['id'];
		$_SESSION['is_hestetegner'] = $data['hestetegner'];
		$_SESSION['email'] = $data['email'];
	}

	header('Location: /');
	exit();
}

if (filter_input(INPUT_GET, 'action') === 'impersonate_stop') {
	if (isset($_SESSION['impersonator_id']) && isset($_SESSION['impersonator_username']) && isset($_SESSION['impersonator_email'])) {
		$_SESSION['username'] = $_SESSION['impersonator_username'];
		$_SESSION['user_id'] = $_SESSION['impersonator_id'];
		$_SESSION['email'] = $_SESSION['impersonator_email'];
		unset($_SESSION['impersonator_id']);
		unset($_SESSION['impersonator_username']);
		unset($_SESSION['impersonator_email']);
	}

	header('Location: /');
	exit();
}
