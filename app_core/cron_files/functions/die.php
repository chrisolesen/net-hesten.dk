<?php

if (!isset($time_now)) {
	date_default_timezone_set('Europe/Copenhagen');
	$current_date = new DateTime('now');
	$date_now = $current_date->format('Y-m-d');
	$time_now = $current_date->format('H:i:s');
}

$log_content = PHP_EOL . '# Checking for horses, that are ready, for the afterlife.';
file_put_contents("{$basepath}/app_core/cron_files/logs/cron_one_hour_{$date_now}", $log_content, FILE_APPEND);
require_once "{$basepath}/app_core/object_loader.php";

$Foelbox = 'Følkassen';
$foel = 'føl';
$Foel = 'Føl';
$dead = 'død';
$DEAD = 'DØD';
$Doctor = 'Dyrelægen';
$Dear = 'Kære';
$the_value = 'værdien';
$competitions = 'stævner';
$Foel_competitions = 'Følkåringer';
$foel_wins = 'kåringer';
$first_place = 'førsteplads';
$gets = 'får';
$got = 'fået';
$sadly = 'desværre';
$Unicorns = 'Enhjørninge';
$Unicorn = 'Enhjørning';
$special = 'særlig';
$of = 'på';
$hope = 'håber';
$claim_pay = 'værdiudbetaling';
$years = 'år';

$today = date("d.m.y.G.i");
$loop = 0;
/* Limit 200 = stabil */

/*
$sql = "SELECT `id`, `alder`, `bruger`, `navn`, `foersteplads`, `andenplads`, `tredieplads`, `kaaringer`, `pris`, `race`, `original`, `unik` 
FROM `{$GLOBALS['DB_NAME_OLD']}`.`Heste` 
LEFT JOIN `{$GLOBALS['DB_NAME_OLD']}`.`Brugere` AS `user` ON `user`.`stutteri` = `horse`.`bruger` 
WHERE `alder` > 20 AND `bruger` <> '{$Foelbox}' AND `bruger` <> 'hestehandleren*' AND `bruger` <> 'genfoedsel' AND `status` = 'hest' 
LIMIT 10000";
*/
$sql = "SELECT 
`user`.`id` AS `uid`, 
`horse`.`id`,`horse`.`alder`,`horse`.`bruger`, `horse`.`navn`, `horse`.`foersteplads`, `horse`.`andenplads`, `horse`.`tredieplads`, `horse`.`kaaringer`, `horse`.`pris`, `horse`.`race`, `horse`.`original`, `horse`.`unik` 
FROM `{$GLOBALS['DB_NAME_OLD']}`.`Heste` AS `horse`
LEFT JOIN `{$GLOBALS['DB_NAME_OLD']}`.`Brugere` AS `user` ON `user`.`stutteri` = `horse`.`bruger` 
WHERE `horse`.`alder` > 20 AND `horse`.`bruger` <> '{$Foelbox}' AND `horse`.`bruger` <> 'hestehandleren*' AND `horse`.`bruger` <> 'genfoedsel' AND `horse`.`status` = 'hest' 
LIMIT 10000;";


$result = $link_new->query($sql);
$viable_horses = 0;
$killed_amount = 0;
if ($result) {

	while ($horse = $result->fetch_object()) {
		++$viable_horses;

		if (mt_rand(0, 27) <= $horse->alder) {

			if ($horse->bruger == 'hestehandleren' || $horse->bruger == 'Hestehandleren' || $horse->bruger == NULL) {
				$horse->bruger = 'techhesten';
			}
			$horse_name = $horse->navn;
			$horse_user_name = $horse->bruger;


			if (strpos("'", $horse->bruger)) {
				file_put_contents("{$basepath}/app_core/cron_files/logs/cron_one_hour_{$date_now}", PHP_EOL . "#User {$horse->bruger} is invalid", FILE_APPEND);
				continue;
			}
			++$killed_amount;

			$tegn = array("&", '"', "'");
			$substitut = array("og", "&quot;", "&#039;");

			$claim = 0;
			$unique = false;
			if (strtolower($horse->unik) === 'ja') {
				$unique = true;
				$claim = $horse->pris * 1.1;
			} else {
				$claim = round(($horse->pris * 0.8), 0);
			}
			$claim += ($kids * 1000);

			$claim_for_text = number_dotter($claim);
			$message = "Kære {$horse_user_name}. <br /><br/>";
			$message .= "Dyrelægen kunne desværre ikke stille noget op, og {$horse_name} er død, {$horse->alder} år gammel.<br /><br />";

			if ($unique) {
				$message .= "Da {$horse_name} var en unik hest, har du fået udbetalt 110% af værdien på  og en bonus, der tilsammen giver {$claim_for_text} wkr.<br />";
			} else {
				$message .= "Du har fået udbetalt 80% af værdien på {$horse_name} og en bonus, der tilsammen giver {$claim_for_text} wkr.<br />";
			}
			$message .= "Det er beregnet ud fra, hvor mange stævner {$horse_name} vandt, hvor mange kåringer {$horse_name} vandt, "
				. "og hvor mange føl {$horse_name} fik. <br /><br />";
			$message .= "{$horse_name} fik {$kids} føl, der giver en bonus på 1.000 wkr pr. føl. <br />";

			$message .= "Vi håber, du har haft mange gode stunder med {$horse_name}.";
			$message = str_replace($tegn, $substitut, $message);

			/* Kill Horse */
			$link_new->query("UPDATE `{$GLOBALS['DB_NAME_OLD']}`.`Heste` SET `status` = '{$dead}', `death_date` = '{$date_now}' WHERE `id` = {$horse->id}");
			/* Inform user */
			$utf_8_message = $message;
			$link_new->query("INSERT INTO `{$GLOBALS['DB_NAME_NEW']}`.`game_data_private_messages` (`status_code`, `hide`, `origin`, `target`, `date`, `message`) VALUES (17, 0, 53432, {$horse->uid}, NOW(), '{$utf_8_message}' )");
			/* Sæt giv penge til brugeren */
			accounting::add_entry(['amount' => $claim, 'line_text' => "Erstatning for {$horse_name} [{$horse->id}]", "user_id" => $horse->uid, "mode" => "+"]);
		}
	}
}
$log_content = PHP_EOL . '#'
	. PHP_EOL . "# Found {$viable_horses} viable horses. ({$killed_amount} were killed.)";
file_put_contents("{$basepath}/app_core/cron_files/logs/cron_one_hour_{$date_now}", $log_content, FILE_APPEND);
