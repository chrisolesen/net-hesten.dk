<?php

$basepath = '';

if (!isset($time_now)) {
	date_default_timezone_set('Europe/Copenhagen');
	$current_date = new DateTime('now');
	$date_now = $current_date->format('Y-m-d');
	$time_now = $current_date->format('H:i:s');
}

$log_content = PHP_EOL . '# Checking for foals, that are ready, to be born.';
file_put_contents("app_core/cron_files/logs/cron_one_hour_{$date_now}", $log_content, FILE_APPEND);

require_once "{$basepath}app_core/db_conf.php";

$Foelbox = 'Følkassen';
$foel = 'føl';
$Foel = 'Føl';

$ø = 'ø';

$today = date("d.m.y.G.i");
$loop = 0;

//--------find hingste som skal stilles tilbage til "hest"----------------------------------------


$sql = "SELECT id, statuschangedate FROM {$GLOBALS['DB_NAME_NEW']}.Heste WHERE status = 'Avl' AND kon = 'Hingst' ORDER BY statuschangedate";
$result = $link_new->query($sql);
$foel_amount = 0;
$grow_up_amount = 0;
$breeding_stallions = 0;
$stallions_changed_status = 0;
while ($data = $result->fetch_object()) {
	++$breeding_stallions;
	$sekunderx = time() - strtotime($data->statuschangedate);
	$dagex = $sekunderx / 86400;
	$dage_oprundetx = round($dagex);
	$tid_tilbagex = 20 - $dage_oprundetx;

	if ($tid_tilbagex < 0) {
		++$stallions_changed_status;
		$link_new->query("UPDATE Heste SET status='Hest', status_skift = '$today' WHERE id = '{$data->id}'");
	}
}

$log_content = PHP_EOL . "# Found {$breeding_stallions} breeding stallions."
		. PHP_EOL . "# Found {$stallions_changed_status} where ready to change status.";
file_put_contents("app_core/cron_files/logs/cron_one_hour_{$date_now}", $log_content, FILE_APPEND);



//--------find hopper som skal fole----------------------------------------

$sql = "SELECT id, bruger, navn, race, partnerid, thumb, statuschangedate FROM {$GLOBALS['DB_NAME_NEW']}.Heste WHERE status = 'Avl' AND kon = 'Hoppe' order by statuschangedate";
$result = $link_new->query($sql);
$breeding_amount = 0;
$born_amount = 0;
$in_waiting = 0;
while ($output = $result->fetch_object()) {
	++$breeding_amount;
	$sekunder = time() - strtotime($output->statuschangedate);
	$dage = $sekunder / 86400;
	$dage_oprundet = round($dage);
	$tid_tilbage = 40 - $dage_oprundet;

	if ($tid_tilbage < 0) {
		if ($born_amount >= 200) {
			++$in_waiting;
			continue;
			/* Limit the birthings to 200 an hour */
		}
		++$born_amount;
		$nybruger = $output->bruger;
		$new_user_id = $link_new->query("SELECT id FROM {$GLOBALS['DB_NAME_OLD']}.Brugere WHERE stutteri = '{$nybruger}'")->fetch_object()->id;
		$nyrace = $output->race;
		$glnavn = $output->navn;
		$glthumb = $output->thumb;
		$idtest = $output->id;

		if (rand(1, 2) == 1) {
			$nykon = "Hingst";
		} else {
			$nykon = "Hoppe";
		}
		$nyid = $output->id;
		$nyhingstid = $output->partnerid;

		$farid = $nyhingstid;
		$morid = $nyid;

//-----test om heste skal have random højde i racens interval, ellers vælg fars og mors højde, afgør laveste og højeste værdi, generer en random højde mellem disse værdier---------------------------------

		if (rand(1, 10) == 1) {
			$lowest_height = $link_new->query("SELECT lower FROM horse_height WHERE race = '$output->race' LIMIT 1")->fetch_object()->lower;
			$highest_height = $link_new->query("SELECT upper FROM horse_height WHERE race = '$output->race' LIMIT 1")->fetch_object()->upper;
			$child_height = rand($lowest_height, $highest_height);
			$random_height = "ja";
		} else {
			$daddy_height = $link_new->query("SELECT height FROM {$GLOBALS['DB_NAME_NEW']}.Heste WHERE id = '$output->partnerid' LIMIT 1")->fetch_object();
			$mommy_height = $link_new->query("SELECT height FROM {$GLOBALS['DB_NAME_NEW']}.Heste WHERE id = '$output->id' LIMIT 1")->fetch_object();
			$child_height = rand(min($daddy_height->height, $mommy_height->height), max($daddy_height->height, $mommy_height->height));
			$random_height = "nej";
		}

//-------------------Vælg tilfældig egenskab, ulempe og talent, rand bruges for arvelighed, gider ikke forklare det, det burde være let at gennemskue------------------------------
		if (rand(1, 10) == 1) {
			if (rand(1, 2) == 1) {
				$egenskab = $link_new->query("SELECT Egenskab FROM {$GLOBALS['DB_NAME_NEW']}.Heste WHERE id = '$farid' LIMIT 1")->fetch_object()->Egenskab;
			} else {
				$egenskab = $link_new->query("SELECT Egenskab FROM {$GLOBALS['DB_NAME_NEW']}.Heste WHERE id = '$morid' LIMIT 1")->fetch_object()->Egenskab;
			}
		} else {
			$egenskab = $link_new->query("SELECT Egenskab FROM horse_habits WHERE Egenskab != '' ORDER BY RAND() LIMIT 1")->fetch_object()->Egenskab;
		}
		if ($egenskab == "") {
			$egenskab = $link_new->query("SELECT Egenskab FROM horse_habits WHERE Egenskab != '' ORDER BY RAND() LIMIT 1")->fetch_object()->Egenskab;
		}

		if (rand(1, 10) == 1) {
			if (rand(1, 2) == 1) {
				$ulempe = $link_new->query("SELECT Ulempe FROM {$GLOBALS['DB_NAME_NEW']}.Heste WHERE id = '$farid' LIMIT 1")->fetch_object()->Ulempe;
			} else {
				$ulempe = $link_new->query("SELECT Ulempe FROM {$GLOBALS['DB_NAME_NEW']}.Heste WHERE id = '$morid' LIMIT 1")->fetch_object()->Ulempe;
			}
		} else {
			$ulempe = $link_new->query("SELECT Ulempe FROM horse_habits WHERE Ulempe != '' ORDER BY RAND() LIMIT 1")->fetch_object()->Ulempe;
		}
		if ($ulempe == "") {
			$ulempe = $link_new->query("SELECT Ulempe FROM horse_habits WHERE Ulempe != '' ORDER BY RAND() LIMIT 1")->fetch_object()->Ulempe;
		}

		if (rand(1, 100) <= 50) {
			if (rand(1, 2) == 1) {
				$talent = $link_new->query("SELECT Talent FROM {$GLOBALS['DB_NAME_NEW']}.Heste WHERE id = '$farid' LIMIT 1")->fetch_object()->Talent;
			} else {
				$talent = $link_new->query("SELECT Talent FROM {$GLOBALS['DB_NAME_NEW']}.Heste WHERE id = '$morid' LIMIT 1")->fetch_object()->Talent;
			}
		} else {
			$talent = $link_new->query("SELECT Talent FROM horse_habits WHERE Talent != '' ORDER BY RAND() LIMIT 1")->fetch_object()->Talent;
		}
		if ($talent == "") {
			$talent = $link_new->query("SELECT Talent FROM horse_habits WHERE Talent != '' ORDER BY RAND() LIMIT 1")->fetch_object()->Talent;
		}




//------pluk en tilfældig thumb fra føllene i Følkassen-----------------------------------------

		$result_layer_three = $link_new->query("SELECT tegner, thumb FROM {$GLOBALS['DB_NAME_NEW']}.Heste WHERE bruger = '{$Foelbox}' AND race = '$nyrace' ORDER BY RAND() LIMIT 1");
		$rand_thumb = $result_layer_three->fetch_object();
		$nythumb = $rand_thumb->thumb;
		$foltegner = $rand_thumb->tegner;
//----------generer føllene og stil status tilbage til "Hest"----------------------------------------------
		$link_new->query("INSERT into Heste (bruger, navn, race, kon, alder, beskrivelse, pris, foersteplads, andenplads, tredieplads, status, farid, morid, tegner, thumb, date, changedate, status_skift, alder_skift, height, random_height, egenskab, ulempe, talent) VALUES ('$nybruger','Unavngivet','$nyrace','$nykon','0','','6000','0','0','0','{$foel}','$nyhingstid','$nyid','$foltegner','$nythumb',now(),now(),'$today','$today','$child_height','$random_height', '$egenskab', '$ulempe', '$talent')");
		$link_new->query("UPDATE Heste SET status='Hest', status_skift=now() WHERE id = '$nyid'");


//----------sender post til Postkassen"----------------------------------------------
		$dims = '"';
		$tegn = array("&", "$dims", "'");
		$substitut = array("og", "&quot;", "&#039;");

		$horse_name = str_replace($tegn, $substitut, $glnavn);
		$user_name = str_replace($tegn, $substitut, $nybruger);
		if ($horse_name) {

			$user_name = $user_name;
			$utf_8_message = "Tillykke {$user_name} :-) <br />{$horse_name} har f{$ø}dt et velskabt {$foel}.<br />";
			$sql_message_to_owner = "INSERT INTO game_data_private_messages (status_code, hide, origin, target, date, message) VALUES (17, 0, 53432, {$new_user_id}, NOW(), '{$utf_8_message}' )";
			$link_new->query($sql_message_to_owner);
		}
	}
}

$log_content = PHP_EOL . "# Found {$breeding_amount} breeding horsies."
		. PHP_EOL . "# {$born_amount} gave birth."
		. PHP_EOL . "# {$in_waiting} had to wait.";
file_put_contents("app_core/cron_files/logs/cron_one_hour_{$date_now}", $log_content, FILE_APPEND);
