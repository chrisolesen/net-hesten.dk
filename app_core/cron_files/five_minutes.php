<?php

//exit();
chdir(dirname(__FILE__));
$basepath = '../../';

date_default_timezone_set('Europe/Copenhagen');
$current_date = new DateTime('now');
$date_now = $current_date->format('Y-m-d');
$time_now = $current_date->format('H:i:s');
$cron_interval = 'five_minutes';

include_once "{$basepath}app_core/cron_files/functions/get_older.php";

$log_content = PHP_EOL . PHP_EOL . '#######################################################'
	. PHP_EOL . '# Five minutes cron started at ' . $time_now
	. PHP_EOL . '# Checking for ended auctions. ';
file_put_contents("{$basepath}/app_core/cron_files/logs/cron_five_minutes_{$date_now}", $log_content, FILE_APPEND);

require_once "{$basepath}app_core/db_conf.php";
require_once "{$basepath}app_core/object_handlers/accounting.php";

/* Find no bid auctions */
$log_content = '';
$sql = ""
	. "SELECT "
	. "auctions.*, "
	. "bids.auction "
	. "FROM `{$GLOBALS['DB_NAME_NEW']}`.game_data_auctions AS auctions "
	. "LEFT JOIN `{$GLOBALS['DB_NAME_NEW']}`.game_data_auction_bids AS bids "
	. "ON bids.auction = auctions.id "
	. "WHERE end_date < '{$date_now} {$time_now}' "
	. "AND ISNULL(bids.auction) "
	. "AND auctions.status_code = 1 ";
$result = $link_new->query($sql);

$num_horses = 0;
$num_auctions = 0;
if ($result) {
	while ($data = $result->fetch_assoc()) {
		++$num_auctions;
		if ($data['object_type'] == 1) {
			++$num_horses;
			$sql = "UPDATE `{$_GLOBALS['DB_NAME_OLD']}`.Heste AS h LEFT JOIN `{$_GLOBALS['DB_NAME_OLD']}`.Brugere AS b ON b.id = '{$data['creator']}' SET h.bruger = b.stutteri WHERE h.id = {$data['object_id']} AND h.bruger = 'Auktionshuset'";
			$link_new->query($sql);
			$sql = "UPDATE game_data_auctions AS a SET a.status_code = 2 WHERE a.id = {$data['id']}";
			$link_new->query($sql);
		}
	}
}
$log_content .= PHP_EOL . "# {$num_auctions} Auctions had no bids.";
$log_content .= PHP_EOL . "# {$num_horses} Horses returned to owners.";
file_put_contents("{$basepath}/app_core/cron_files/logs/cron_five_minutes_{$date_now}", $log_content, FILE_APPEND);

$five_in_past = $current_date->sub(new DateInterval('PT5M'))->format('Y-m-d H:i:s');

$log_content = PHP_EOL . "# Looking for winning bids.";
$sql = ''
	. 'SELECT '
	. 'auctions.id AS auction_id, '
	. 'auctions.creator AS seller, '
	. 'auctions.object_id AS object_id, '
	. 'bids.creator AS winner, '
	. 'bids.bid_amount AS winning_amount, '
	. 'bids.bid_date AS bid_date '
	. "FROM `{$GLOBALS['DB_NAME_NEW']}`.game_data_auction_bids AS bids "
	. "LEFT JOIN `{$GLOBALS['DB_NAME_NEW']}`.game_data_auctions AS auctions "
	. 'ON bids.auction = auctions.id '
	. "WHERE end_date < '{$five_in_past}' "
	. 'AND bids.status_code = 4 '
	. 'AND auctions.status_code = 1 '
	. 'ORDER BY bids.auction ASC, bids.bid_amount DESC';
$auction_array = $link_new->query($sql);
$num_auctions = 0;
if ($auction_array) {
	/* Only winning bids remain for expired auctions, and all bid have been prepaid, thus no validtions required */
	while ($data = $auction_array->fetch_assoc()) {
		++$num_auctions;
		if (!isset($user_id_name_array[$data['winner']])) {
			$sql = "SELECT stutteri, penge AS money FROM `{$_GLOBALS['DB_NAME_OLD']}`.Brugere WHERE id = {$data['winner']} LIMIT 1";
			$user_query_result = $link_new->query($sql);
			if ($user_query_result) {
				$user_query_data = $user_query_result->fetch_assoc();
				$user_id_name_array[$data['winner']] = [$user_query_data['stutteri'], $user_query_data['money']];
			} else {
				$log_content .= PHP_EOL . "# Failed to find user with ID {$data['winner']} - Skipping!";
				continue;
			}
		}
		if (!isset($user_id_name_array[$data['seller']])) {
			$sql = "SELECT stutteri, penge AS money FROM `{$_GLOBALS['DB_NAME_OLD']}`.Brugere WHERE id = {$data['seller']} LIMIT 1";
			$user_query_result = $link_new->query($sql);
			if ($user_query_result) {
				$user_query_data = $user_query_result->fetch_assoc();
				$user_id_name_array[$data['seller']] = [$user_query_data['stutteri'], $user_query_data['money']];
			} else {
				$log_content .= PHP_EOL . "# Failed to find user with ID {$data['seller']} - Skipping!";
				continue;
			}
		}
		$log_content .= PHP_EOL . "# Moving horse with ID {$data['object_id']} to user {$user_id_name_array[$data['winner']][0]} with ID {$data['winner']}.";
		$sql = "UPDATE `{$_GLOBALS['DB_NAME_OLD']}`.Heste SET bruger = '{$user_id_name_array[$data['winner']][0]}' WHERE id = {$data['object_id']} AND bruger = 'Auktionshuset'";
		$link_new->query($sql);

		$auction_fee = round(max(500, ($data['winning_amount'] * 0.005)), 0);
		$earnings = $data['winning_amount'] - $auction_fee;
		accounting::add_entry(['amount' => $earnings, 'line_text' => "Solgt en hest på auktion", 'mode' => '+', 'user_id' => $data['seller']]);


		//		$sql = "UPDATE Brugere SET penge = (penge + {$earnings}) WHERE id = {$data['seller']}";
		//		$link_new->query($sql);
		$sql = "UPDATE game_data_auctions SET status_code = 2 WHERE id = {$data['auction_id']}";
		$link_new->query($sql);
		$sql = "UPDATE game_data_auction_bids SET status_code = 6 WHERE auction = {$data['auction_id']} AND bid_date = '{$data['bid_date']}' AND creator = {$data['winner']}";
		$link_new->query($sql);
		/* Send besked til den ny ejer */
		$sql = "INSERT INTO game_data_private_messages "
			. "(status_code, origin, target, date, message) "
			. "VALUES "
			. "(17, 52745, {$data['winner']}, NOW(), 'Tillykke {$user_id_name_array[$data['winner']][0]}, du har vundet en auktion, med et bud på {$data['winning_amount']} wkr. Hesten med ID: {$data['object_id']}, er nu din.')";
		$result = $link_new->query($sql);
		/* Send besked til den gamle ejer */
		$sql = "INSERT INTO game_data_private_messages "
			. "(status_code, origin, target, date, message) "
			. "VALUES "
			. "(17, 52745, {$data['seller']}, NOW(), 'Tillykke {$user_id_name_array[$data['seller']][0]}, det er lykkeds at sælge din hest på auktion, for {$data['winning_amount']} wkr!')";
		$result = $link_new->query($sql);
		/* Opret indlæg i den gamle ejers 'konto' */

		$log_content .= " (done).";
	}
}
$log_content .= PHP_EOL . "# Found $num_auctions finished auctions.";

file_put_contents("{$basepath}/app_core/cron_files/logs/cron_five_minutes_{$date_now}", $log_content, FILE_APPEND);


$log_content = ''
	. PHP_EOL . '# Cron compleated its run. '
	. PHP_EOL . '#######################################################' . PHP_EOL;
file_put_contents("{$basepath}/app_core/cron_files/logs/cron_five_minutes_{$date_now}", $log_content, FILE_APPEND);
