<?php

class user
{
	/* SELECT user_id, TIMEDIFF(end, start) AS duration FROM netchw_db1.user_data_sessions order by TIMEDIFF(end, start) DESC; */

	public static function register_session($attr = [])
	{
		if (!isset($attr['user_id'])) {
			return false;
		}
		if (isset($_SESSION['impersonator_id'])) {
			return false;
		}
		global $link_new;

		$current_ip = $_SERVER['REMOTE_ADDR'];

		$active_session_id = ($link_new->query("SELECT id FROM user_data_sessions WHERE ip = '{$current_ip}' AND user_id = {$attr['user_id']} AND end > DATE_SUB(NOW(),INTERVAL 15 MINUTE) ORDER BY id DESC LIMIT 1")->fetch_object()->id) ?? false;
		if ($active_session_id) {
			$link_new->query("UPDATE user_data_sessions SET end = NOW() WHERE id = $active_session_id");
		} else {
			$link_new->query("INSERT INTO user_data_sessions (user_id, start, end, ip) VALUES ({$attr['user_id']}, NOW(), NOW(), '{$current_ip}')");
		}
		return true;
	}

	public static function update_rights($attr = [])
	{
		if (!isset($attr['user_id'])) {
			return false;
		} else {
			$user_id = (int) $attr['user_id'];
		}
		if (!isset($attr['privilege_type'])) {
			return false;
		} else {
			$privilege_id = (int) $attr['privilege_type'];
		}
		if (!isset($attr['action'])) {
			return false;
		}
		global $link_new;

		if ($attr['action'] == 'remove') {
			$sql = "UPDATE user_privileges SET end = NOW() WHERE user_id = {$user_id} AND privilege_id = $privilege_id";
		} else if ($attr['action'] == 'grant') {
			$sql = "INSERT INTO user_privileges (user_id, privilege_id, start, end) VALUES ({$user_id},'{$attr['privilege_type']}',NOW(),'0000-00-00 00:00:00') ON DUPLICATE KEY UPDATE start = NOW(), end = '0000-00-00 00:00:00'";
		}

		$result = $link_new->query($sql);
		if ($result) {
			return true;
		} else {
			return false;
		}
	}

	public static function register_timing($attr = [])
	{
		if (isset($_SESSION['impersonator_id'])) {
			return false;
		}
		global $link_new;
		global $link_new;
		$return_data = [];
		$defaults = ['key' => 'last_login'];
		foreach ($defaults as $key => $value) {
			isset($attr[$key]) ?: $attr[$key] = $value;
		}
		if (!(isset($attr['user_id']))) {
			return false;
		}
		$user_id = (int) $attr['user_id'];
		$sql = "INSERT INTO user_data_timing (parent_id, name, value) VALUES ({$user_id},'{$attr['key']}',NOW()) ON DUPLICATE KEY UPDATE value = NOW()";
		$result = $link_new->query($sql);
		if ($result) {
			return $result;
		} else {
			return false;
		}
	}

	public static function get_timing($attr = [])
	{
		global $link_new;
		$return_data = [];
		$defaults = [];
		foreach ($defaults as $key => $value) {
			isset($attr[$key]) ?: $attr[$key] = $value;
		}
		if (!($attr['name'] ?? false)) {
			return false;
		}


		$user_id = (((int) $_SESSION['user_id'] ?? ((int) $attr['user_id'] ?? false)));
		if ($user_id) {
			$sql = "SELECT `value` FROM user_data_timing WHERE `name` = '{$attr['name']}' AND `parent_id` = {$user_id}";
			$result = ($link_new->query($sql)->fetch_object()->value ?? false);
		}
		if ($user_id && $result) {
			return $result;
		} else {
			return '0000-00-00 00:00:00';
		}
	}
	public static function get_timings($attr = [])
	{
		global $link_new;
		global $GLOBALS;
		$return_data = [];
		$defaults = ['time_mode' => 'm', 'time_val' => '30', 'mode' => 'return'];
		foreach ($defaults as $key => $value) {
			isset($attr[$key]) ?: $attr[$key] = $value;
		}


		$mode = 'MINUTE';
		//		$target_time = new DateTime('now');
		if ($attr['time_mode'] == 'm') {
			$mode = 'MINUTE';
		}
		if ($attr['time_mode'] == 'd') {
			$mode = 'DAY';
		}
		if ($attr['time_mode'] == 'h') {
			$mode = 'HOUR';
		}

		if ($attr['mode'] == 'count') {
			return $link_new->query("SELECT count(parent_id) AS amount FROM user_data_timing WHERE name = '{$attr['key']}' AND value > DATE_SUB(NOW(),INTERVAL {$attr['time_val']} {$mode})")->fetch_object()->amount;
		}
		$user_id = (int) $attr['user_id'];
		$sql = "SELECT old.stutteri AS username, new.value AS time FROM user_data_timing AS new LEFT JOIN `{$GLOBALS['DB_NAME_OLD']}`.Brugere AS old ON old.id = new.parent_id WHERE new.name = '{$attr['key']}' AND new.value > DATE_SUB(NOW(),INTERVAL {$attr['time_val']} {$mode}) ORDER BY new.value DESC";
		$result = $link_new->query($sql);
		if ($result) {
			while ($data = $result->fetch_object()) {
				$return_data[] = (object) [
					'username' => $data->username,
					'last_online' => $data->time
				];
			}
			return $return_data;
		} else {
			return false;
		}
	}

	public static function request_personal_data($attr = [])
	{
		global $link_new;
		global $link_new;
		global $GLOBALS;/*{$GLOBALS['DB_NAME_NEW']}{$GLOBALS['DB_NAME_OLD']}*/
		foreach ($attr as $key => $value) {
			$attr[$key] = $link_new->real_escape_string($value);
		}
		$mail_message_data = '<br />';
		$mail_message_data .= 'Basis data:<br />';
		$result = $link_new->query("SELECT id, stutteri, `password`, navn, email, alder, kon, beskrivelse, thumb FROM `{$GLOBALS['DB_NAME_OLD']}`.Brugere WHERE id = {$_SESSION['user_id']}");
		while ($data = $result->fetch_object()) {
			$mail_message_data .= 'ID: ' . $data->id . '<br />';
			$mail_message_data .= 'Stutteri navn: ' . $data->stutteri . '<br />';
			$mail_message_data .= 'Password: ' . $data->password . '<br />';
			$mail_message_data .= 'Navn: ' . $data->navn . '<br />';
			$mail_message_data .= 'Email: ' . $data->email . '<br />';
			$mail_message_data .= 'Alder: ' . $data->alder . '<br />';
			$mail_message_data .= 'Køn: ' . $data->kon . '<br />';
			$mail_message_data .= 'Beskrivelse: ' . $data->beskrivelse . '<br />';
			$mail_message_data .= '<img src="//files.net-hesten.dk/users/' . $data->thumb . '" />' . '<br />';
		}
		$mail_message_data .= '<br /><br />';
		$mail_message_data .= 'Sessions:<br />';
		$result = $link_new->query("SELECT * FROM `{$GLOBALS['DB_NAME_NEW']}`.user_data_sessions WHERE user_id = {$_SESSION['user_id']}");
		while ($data = $result->fetch_object()) {
			$mail_message_data .= 'Fra: ' . $data->start . ' til ' . $data->end . ' via ' . $data->ip . '<br />';
			//			$mail_message_data .= var_export($data, true);
		}
		$mail_message_data .= '<br /><br />';
		$mail_message_data .= 'Privat Beskeder Fra:<br />';
		$result = $link_new->query("SELECT * FROM `{$GLOBALS['DB_NAME_NEW']}`.game_data_private_messages WHERE origin = {$_SESSION['user_id']}");
		while ($data = $result->fetch_object()) {
			$mail_message_data .= '<hr /><br />';
			$mail_message_data .= 'Modtager: ' . $data->target . ' | Dato: ';
			$mail_message_data .= $data->date . '<br />';
			$mail_message_data .= $data->message;
			$mail_message_data .= '<br /><hr /><br />';
			//			$mail_message_data .= var_export($data, true);
		}
		$mail_message_data .= '<br /><br />';
		$mail_message_data .= 'Privat Beskeder Til:<br />';
		$result = $link_new->query("SELECT * FROM `{$GLOBALS['DB_NAME_NEW']}`.game_data_private_messages WHERE target = {$_SESSION['user_id']} AND ORIGIN NOT IN (53432,52745)");
		while ($data = $result->fetch_object()) {
			$mail_message_data .= '<hr /><br />';
			$mail_message_data .= 'Afsender: ' . $data->origin . ' | Dato: ';
			$mail_message_data .= $data->date . '<br />';
			$mail_message_data .= $data->message;
			$mail_message_data .= '<br /><hr /><br />';
			//			$mail_message_data .= var_export($data, true);
		}
		$mail_message_data .= '<br /><br />';
		$mail_message_data .= 'Chat beskeder:<br />';
		$result = $link_new->query("SELECT * FROM `{$GLOBALS['DB_NAME_NEW']}`.game_data_chat_messages WHERE creator = {$_SESSION['user_id']}");
		while ($data = $result->fetch_object()) {
			$mail_message_data .= '<hr /><br />';
			$mail_message_data .= $data->creation_date . '<br />';
			$mail_message_data .= $data->value;
			$mail_message_data .= '<br /><hr /><br />';
			//			$mail_message_data .= var_export($data, true);
		}
		$mail_message_data .= '<br />';




		$mail_message = '<!DOCTYPE html>';
		$mail_message .= '<html><body style="background:#dfebd3;padding:20px;"><div style="background:rgba(146, 186, 106, 0.5);max-width:600px;margin:0 auto;padding:20px;border:1px solid white;">';
		$mail_message .= '<b>Hej ' . trim($_SESSION['username']) . '</b><br />';
		$mail_message .= '<br />';
		$mail_message .= 'Du har anmodet om at få tilsendt en kopi af dine bruger data.<br />';
		$mail_message .= '<br />';

		$mail_message .= $mail_message_data;

		$mail_message .= '<br />';
		$mail_message .= '<b>Med venlig hilsen</b><br />';
		$mail_message .= 'Net-Hesten - Teamet';
		$mail_message .= '</div></body></html>';

		pw_mailer(['to' => $_SESSION['email'], 'subject' => 'Dine bruger data fra net-hesten.dk', 'message' => $mail_message]);
		return true;
	}

	public static function get_info($attr = [])
	{
		global $link_new;
		if (!isset($attr['user_id'])) {
			return false;
		}
		$return_data = [];
		$defaults = ['mode' => 'user_id'];
		foreach ($defaults as $key => $value) {
			isset($attr[$key]) ?: $attr[$key] = $value;
		}
		foreach ($attr as $key => $value) {
			$attr[$key] = $link_new->real_escape_string($value);
		}

		if ($attr['mode'] === 'username') {
			$search_for = $attr['user_id'];
			$sql = "SELECT stutteri AS username, thumb, penge AS money, id, navn AS name FROM `{$GLOBALS['DB_NAME_OLD']}`.Brugere WHERE stutteri = '$search_for' LIMIT 1";
		} else {
			$sql = "SELECT stutteri AS username, thumb, penge AS money, id, navn AS name FROM `{$GLOBALS['DB_NAME_OLD']}`.Brugere WHERE id = '{$attr['user_id']}' LIMIT 1";
		}
		$result = $link_new->query($sql)->fetch_object();
		if ($result) {
			return $result;
		} else {
			return false;
		}
	}

	public static function update_membership_request($attr = [])
	{
		global $link_new;

		$return_data = [];
		$defaults = [];
		foreach ($defaults as $key => $value) {
			isset($attr[$key]) ?: $attr[$key] = $value;
		}
		if ($attr['action'] == 'verify_request_mail') {
			$db_mail = $link_new->real_escape_string(trim($attr['mail']));
			$db_key = $link_new->real_escape_string(trim($attr['key']));
			$request = $link_new->query("SELECT verify_date, id, count(id) AS result_count FROM user_application WHERE email = '{$db_mail}' AND verify_request_key = '{$db_key}' LIMIT 1")->fetch_object();
			if ($request && $request->result_count > 0) {
				if ($request->verify_date == NULL) {
					$link_new->query("UPDATE user_application SET verify_date = NOW() WHERE email = '{$db_mail}' AND verify_request_key = '{$db_key}' LIMIT 1");
					$return_data[] = ["Din anmodning er nu verificeret, vi skal stadig godkende den manuelt.", 'success'];
					/* indsæt besked i ny pb system */
					$utf_8_message = "Der er en ny bruger ansøgning, der afventer godkendelse.";
					$sql = "INSERT INTO game_data_private_messages (status_code, hide, origin, target, date, message) VALUES (17, 0, 53681, 8, NOW(), '{$utf_8_message}' )";
					$link_new->query($sql);
				} else {
					$return_data[] = ["Din anmodning var allerede verificeret, du modtager en mail, når vi har gennemgået den.", 'success'];
				}
			} else {
				$return_data[] = ["Din anmodning kunne ikke findes i systemet.", 'warning'];
			}
		} else {
			$return_data = false;
		}
		return $return_data;
	}

	public static function request_membership($attr = [])
	{
		global $link_new;
		global $link_new;
		global $GLOBALS;

		$return_data = [];
		$defaults = [];
		foreach ($defaults as $key => $value) {
			isset($attr[$key]) ?: $attr[$key] = $value;
		}

		$block_signup = false;
		$db_user = $link_new->real_escape_string($attr['user']);
		$db_email = $link_new->real_escape_string($attr['mail']);
		if ($link_new->query("SELECT count(id) AS count_result FROM `{$GLOBALS['DB_NAME_OLD']}`.Brugere WHERE email = '{$db_email}' LIMIT 1")->fetch_object()->count_result > 0) {
			$return_data[] = ["Den valgte email '{$attr['mail']}', findes allerede.", 'warning'];
			$block_signup = true;
		}
		if ($link_new->query("SELECT count(id) AS count_result FROM `{$GLOBALS['DB_NAME_OLD']}`.Brugere WHERE stutteri = '{$db_user}' LIMIT 1")->fetch_object()->count_result > 0) {
			$return_data[] = ["Det valgte stutterinavn '{$attr['user']}', findes allerede.", 'warning'];
			$block_signup = true;
		}

		if (strpos($attr['mail'], "'") || strpos($attr['mail'], '"') || strpos($attr['mail'], ' ')) {
			$text_error = '<style>span.error_holder_text span { color:red;text-decoration:underline;}</style>'
				. '<span class="error_holder_text">' . str_replace(
					["'", '"', ' '],
					["<span>'</span>", "<span>\"</span>", '<span> </span>'],
					$attr['mail']
				) . '</span>';
			$return_data[] = ["Din mail, indeholder blokerede tegn, markeret med rød:<br />{$text_error}<br /><br />", 'warning'];
			$block_signup = true;
		}
		if (strpos($attr['name'], "'") || strpos($attr['name'], '"')) {
			$text_error = '<style>span.error_holder_text span { color:red;text-decoration:underline;}</style>'
				. '<span class="error_holder_text">' . str_replace(
					["'", '"'],
					["<span>'</span>", "<span>\"</span>"],
					$attr['name']
				) . '</span>';
			$return_data[] = ["Dit navn, indeholder blokerede tegn, markeret med rød:<br />{$text_error}<br /><br />", 'warning'];
			$block_signup = true;
		}
		if (strpos($attr['user'], "'") || strpos($attr['user'], '"') || strpos($attr['user'], ' ')) {
			$text_error = '<style>span.error_holder_text span { color:red;text-decoration:underline;}</style>'
				. '<span class="error_holder_text">' . str_replace(
					["'", '"', ' '],
					["<span>'</span>", "<span>\"</span>", '<span> </span>'],
					$attr['user']
				) . '</span>';
			$return_data[] = ["Der er fejl i dit stutteri navn, markeret med rød:<br />{$text_error}<br /><br />", 'warning'];
			$block_signup = true;
		}

		if (!$block_signup) {
			$db_name = $link_new->real_escape_string(trim($attr['name']));
			$db_mail = $link_new->real_escape_string(trim($attr['mail']));
			$db_user = $link_new->real_escape_string(trim($attr['user']));
			$request_key = uniqid('', true);


			$salt = uniqid('', true);
			$algo = '6';
			$rounds = '5042';
			$cryptSalt = '$' . $algo . '$rounds=' . $rounds . '$' . $salt;

			$password_hash = crypt(trim($attr['pass']), $cryptSalt);
			if ($password_hash) {
				/* Indsæt brugeren i anmodnings tabellen */
				$link_new->query("INSERT INTO user_application (username, email, password, message, date, verify_request_key, name) VALUES ('{$db_user}', '{$db_mail}', '{$password_hash}', 'Bruger oprettelse.', NOW(), '{$request_key}', '{$db_name}')");
			}

			$mail_message = '<!DOCTYPE html>';
			$mail_message .= '<html><body style="background:#dfebd3;padding:20px;"><div style="background:rgba(146, 186, 106, 0.5);max-width:600px;margin:0 auto;padding:20px;border:1px solid white;">';
			$mail_message .= '<b>Hej ' . trim($attr['name']) . '</b><br />';
			$mail_message .= '<br />';
			$mail_message .= 'Du har anmodet om at blive oprettet som "' . trim($attr['user']) . '" på net-hesten.dk<br />';
			$mail_message .= '<br />';
			$mail_message .= 'Før du kan få et stutteri, skal du bekræfte, at du ejer den valgte mail konto.<br />';
			$mail_message .= "Det gør du ved hjælp af dette <a href='https://" . HTTP_HOST . "/?action=verify_request_mail&verify_mail={$db_mail}&verify_key={$request_key}'>link</a><br />";
			$mail_message .= '<br />';
			$mail_message .= "<b>Vigtigt:</b> Når du har bekræftet mailen, skal vi stadig manuelt godkende dit stutteri, vi gør altid dette hurtigst muligt, ofte næsten med det samme, og normalvis inden for 24 time.<br />";
			$mail_message .= '<br />';
			$mail_message .= '<b>Med venlig hilsen</b><br />';
			$mail_message .= 'Net-Hesten - Teamet';
			$mail_message .= '</div></body></html>';

			pw_mailer(['to' => trim($attr['mail']), 'subject' => 'Bekræft din anmodning til Net-Hesten', 'message' => $mail_message]);

			$return_data[] = ["Din anmodning, er oprettet i vores system, husk at bekræfte, via den mail du snart modtager.", 'success'];
		}
		if (count($return_data) > 0) {
			return $return_data;
		} else {
			return false;
		}
	}

	public static function request_password($attr = [])
	{
		global $link_new;
		global $GLOBALS;

		$return_data = [];
		$defaults = [];
		foreach ($defaults as $key => $value) {
			isset($attr[$key]) ?: $attr[$key] = $value;
		}
		$new_password = substr(md5(rand()), 0, 7);
		$attr['mail'] = $link_new->real_escape_string($attr['mail']);
		$attr['user_id'] = $link_new->query("SELECT id FROM `{$GLOBALS['DB_NAME_OLD']}`.Brugere WHERE email = '{$attr['mail']}' LIMIT 1")->fetch_object()->id;
		if ($attr['user_id']) {
			$user_name = $link_new->query("SELECT stutteri AS username FROM `{$GLOBALS['DB_NAME_OLD']}`.Brugere WHERE id = {$attr['user_id']} LIMIT 1")->fetch_object()->username;
			$user_mail = $link_new->query("SELECT email AS mail FROM `{$GLOBALS['DB_NAME_OLD']}`.Brugere WHERE id = {$attr['user_id']} LIMIT 1")->fetch_object()->mail;

			$salt = uniqid('', true);
			$algo = '6';
			$rounds = '5042';
			$cryptSalt = '$' . $algo . '$rounds=' . $rounds . '$' . $salt;

			$password_hash = crypt(trim($new_password), $cryptSalt);
			if ($password_hash) {
				$link_new->query("UPDATE Brugere SET password = '{$password_hash}' WHERE id = {$attr['user_id']}");
			}

			$mail_message = '<!DOCTYPE html>';
			$mail_message .= '<html><body style="background:#dfebd3;padding:20px;"><div style="background:rgba(146, 186, 106, 0.5);max-width:600px;margin:0 auto;padding:20px;border:1px solid white;">';
			$mail_message .= "<b>Hej {$user_name}</b><br />";
			$mail_message .= '<br />';
			$mail_message .= 'Nogen (forhåbentlig dig), har anmodet om en ny kode, på net-hesten.dk<br />';
			$mail_message .= '<br />';
			$mail_message .= "Koden er: \"{$new_password}\" - uden anførselstegn<br />";
			$mail_message .= '<br />';
			$mail_message .= 'Du skal logge ind med ovenstående kode i fremtiden.<br />';
			$mail_message .= '<br />';
			$mail_message .= "<b>Vigtigt:</b> Har du ikke anmodet om en ny kode?<br />";
			$mail_message .= 'Denne mail er kun sendt direkte til din mail, så ingen anden har fået udleveret dit kodeord, du kan blot logge ind med ovenstående, og ændre koden til det du ønsker.<br />';
			$mail_message .= 'Du må dog stadig gerne henvende dig til os, hvis du oplever chikane eller andre problemer med denne funktion.<br />';
			$mail_message .= '<br />';
			$mail_message .= '<b>Med venlig hilsen</b><br />';
			$mail_message .= 'Net-Hesten - Teamet';
			$mail_message .= '</div></body></html>';

			pw_mailer(['to' => $user_mail, 'subject' => 'Nyt password til Net-Hesten', 'message' => $mail_message]);
		} else {
			sleep(2);
		}

		$return_data[] = ["Hvis en bruger findes på den mail, har du nu modtaget en mail med yderligere info.", 'success'];

		if (count($return_data) > 0) {
			return $return_data;
		} else {
			return false;
		}
	}

	public static function admin_user_password_reset($attr = [])
	{
		global $link_new;
		global $link_new;
		global $GLOBALS;

		$return_data = [];
		$defaults = [];
		foreach ($defaults as $key => $value) {
			isset($attr[$key]) ?: $attr[$key] = $value;
		}

		$new_password = substr(md5(rand()), 0, 7);
		$user_name = $link_new->query("SELECT stutteri AS username FROM `{$GLOBALS['DB_NAME_OLD']}`.Brugere WHERE id = {$attr['user_id']} LIMIT 1")->fetch_object()->username;
		$user_mail = $link_new->query("SELECT email AS mail FROM `{$GLOBALS['DB_NAME_OLD']}`.Brugere WHERE id = {$attr['user_id']} LIMIT 1")->fetch_object()->mail;

		$salt = uniqid('', true);
		$algo = '6';
		$rounds = '5042';
		$cryptSalt = '$' . $algo . '$rounds=' . $rounds . '$' . $salt;

		$password_hash = crypt(trim($new_password), $cryptSalt);
		if ($password_hash) {
			/* Indsæt brugeren i anmodnings tabellen */
			$link_new->query("UPDATE Brugere SET password = '{$password_hash}' WHERE id = {$attr['user_id']}");
		}

		$mail_message = '<!DOCTYPE html>';
		$mail_message .= '<html><body style="background:#dfebd3;padding:20px;"><div style="background:rgba(146, 186, 106, 0.5);max-width:600px;margin:0 auto;padding:20px;border:1px solid white;">';
		$mail_message .= "<b>Hej {$user_name}</b><br />";
		$mail_message .= '<br />';
		$mail_message .= 'En administrator, har genereret en ny kode til dig, på net-hesten.dk<br />';
		$mail_message .= '<br />';
		$mail_message .= "Koden er: \"{$new_password}\" - uden anførselstegn<br />";
		$mail_message .= '<br />';
		$mail_message .= 'Du skal logge ind med ovenstående kode i fremtiden.<br />';
		$mail_message .= '<br />';
		$mail_message .= "<b>Vigtigt:</b> Har du ikke anmodet om en ny kode?<br />";
		$mail_message .= 'Denne mail er kun sendt direkte til din mail, så ingen anden har fået udleveret dit kodeord, du kan blot logge ind med ovenstående, og ændre koden til det du ønsker.<br />';
		$mail_message .= 'Der er dog, en god chance for, at der er en anden, der har haft en vis grad af held med, at udgive sig for at være dig, så svar gerne på denne mail, så vi kan få blokeret den der har anmodet falskt.<br />';
		$mail_message .= '<br />';
		$mail_message .= '<b>Med venlig hilsen</b><br />';
		$mail_message .= 'Net-Hesten - Teamet';
		$mail_message .= '</div></body></html>';

		pw_mailer(['to' => $user_mail, 'subject' => 'Nyt password til Net-Hesten', 'message' => $mail_message]);

		$return_data[] = ["Brugerens password er blevet nulstillet, og han/hun har modtaget en mail med information.", 'success'];

		if (count($return_data) > 0) {
			return $return_data;
		} else {
			return false;
		}
	}
}
