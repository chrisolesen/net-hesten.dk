<?php

if (!isset($basepath)) {
	die();
}

if (!isset($time_now)) {
	date_default_timezone_set('Europe/Copenhagen');
	$current_date = new DateTime('now');
	$date_now = $current_date->format('Y-m-d');
	$time_now = $current_date->format('H:i:s');
}

$log_content = PHP_EOL . '# Checking for foals, that are ready, to be born.';
file_put_contents("{$basepath}/app_core/cron_files/logs/cron_one_hour_{$date_now}", $log_content, FILE_APPEND);

require_once "{$basepath}/app_core/db_conf.php";

$breeds = $link_new->query("SELECT `meta`.`meta_value` AS `partner_id`, `meta`.`horse_id`, `horse`.`bruger`,`horse`.`race` 
FROM `{$GLOBALS['DB_NAME_NEW']}`.`horse_metadata` AS `meta` 
LEFT JOIN `{$GLOBALS['DB_NAME_OLD']}`.`Heste` AS `horse` ON `horse`.`id` = `meta`.`horse_id` 
WHERE `meta_key` = 'breeding' AND `meta_date` < DATE_SUB(NOW(),INTERVAL 40 DAY) 
LIMIT 150");

$foel_amount = 0;
$grow_up_amount = 0;
$breeding_stallions = 0;
$stallions_changed_status = 0;
$Foelbox = 'Følkassen';
$foel = 'føl';
$Foel = 'Føl';

$ø = 'ø';

$today = date("d.m.y.G.i");
$loop = 0;

while ($breed = $breeds->fetch_object()) {
	$horse = $breed->horse_id;
	$partner = $breed->partner_id;
	$user = $breed->bruger;

	if (rand(1, 2) == 1) {
		$nykon = "Hingst";
	} else {
		$nykon = "Hoppe";
	}

	$nyid = $horse;
	$nyhingstid = $partner;
	/* get stallion value */
	$stallion_value =  (int) ($link_new->query("SELECT `pris` FROM `{$GLOBALS['DB_NAME_OLD']}`.`Heste` WHERE `id` = {$breed->partner_id}")->fetch_object()->pris);

	$farid = $partner;
	$morid = $horse;
	//-----test om heste skal have random højde i racens interval, ellers vælg fars og mors højde, afgør laveste og højeste værdi, generer en random højde mellem disse værdier---------------------------------

	if (rand(1, 10) == 1) {
		$lowest_height = $link_new->query("SELECT `lower` FROM `{$GLOBALS['DB_NAME_OLD']}`.`horse_height` WHERE `race` = '{$breed->race}' LIMIT 1")->fetch_object()->lower;
		$highest_height = $link_new->query("SELECT `upper` FROM `{$GLOBALS['DB_NAME_OLD']}`.`horse_height` WHERE `race` = '{$breed->race}' LIMIT 1")->fetch_object()->upper;
		$child_height = rand($lowest_height, $highest_height);
		$random_height = "ja";
	} else {
		$daddy_height = $link_new->query("SELECT `height` FROM `{$GLOBALS['DB_NAME_OLD']}`.`Heste` WHERE `id` = '$partner' LIMIT 1")->fetch_object();
		$mommy_height = $link_new->query("SELECT `height` FROM `{$GLOBALS['DB_NAME_OLD']}`.`Heste` WHERE `id` = '$morid' LIMIT 1")->fetch_object();
		$child_height = rand(min($daddy_height->height, $mommy_height->height), max($daddy_height->height, $mommy_height->height));
		$random_height = "nej";
	}

	//-------------------Vælg tilfældig egenskab, ulempe og talent, rand bruges for arvelighed, gider ikke forklare det, det burde være let at gennemskue------------------------------
	if (rand(1, 10) == 1) {
		if (rand(1, 2) == 1) {
			$egenskab = $link_new->query("SELECT `Egenskab` FROM `{$GLOBALS['DB_NAME_OLD']}`.`Heste` WHERE `id` = '$farid' LIMIT 1")->fetch_object()->Egenskab;
		} else {
			$egenskab = $link_new->query("SELECT `Egenskab` FROM `{$GLOBALS['DB_NAME_OLD']}`.`Heste` WHERE `id` = '$morid' LIMIT 1")->fetch_object()->Egenskab;
		}
	} else {
		$egenskab = $link_new->query("SELECT `Egenskab` FROM `{$GLOBALS['DB_NAME_OLD']}`.`horse_habits` WHERE `Egenskab` <> '' ORDER BY RAND() LIMIT 1")->fetch_object()->Egenskab;
	}
	if ($egenskab == "") {
		$egenskab = $link_new->query("SELECT `Egenskab` FROM `{$GLOBALS['DB_NAME_OLD']}`.`horse_habits` WHERE `Egenskab` <> '' ORDER BY RAND() LIMIT 1")->fetch_object()->Egenskab;
	}

	if (rand(1, 10) == 1) {
		if (rand(1, 2) == 1) {
			$ulempe = $link_new->query("SELECT `Ulempe` FROM `{$GLOBALS['DB_NAME_OLD']}`.`Heste` WHERE `id` = '$farid' LIMIT 1")->fetch_object()->Ulempe;
		} else {
			$ulempe = $link_new->query("SELECT `Ulempe` FROM `{$GLOBALS['DB_NAME_OLD']}`.`Heste` WHERE `id` = '$morid' LIMIT 1")->fetch_object()->Ulempe;
		}
	} else {
		$ulempe = $link_new->query("SELECT `Ulempe` FROM `{$GLOBALS['DB_NAME_OLD']}`.`horse_habits` WHERE `Ulempe` <> '' ORDER BY RAND() LIMIT 1")->fetch_object()->Ulempe;
	}
	if ($ulempe == "") {
		$ulempe = $link_new->query("SELECT `Ulempe` FROM `{$GLOBALS['DB_NAME_OLD']}`.`horse_habits` WHERE `Ulempe` <> '' ORDER BY RAND() LIMIT 1")->fetch_object()->Ulempe;
	}

	if (rand(1, 100) <= 50) {
		if (rand(1, 2) == 1) {
			$talent = $link_new->query("SELECT `Talent` FROM `{$GLOBALS['DB_NAME_OLD']}`.`Heste` WHERE `id` = '$farid' LIMIT 1")->fetch_object()->Talent;
		} else {
			$talent = $link_new->query("SELECT `Talent` FROM `{$GLOBALS['DB_NAME_OLD']}`.`Heste` WHERE `id` = '$morid' LIMIT 1")->fetch_object()->Talent;
		}
	} else {
		$talent = $link_new->query("SELECT `Talent` FROM `{$GLOBALS['DB_NAME_OLD']}`.`horse_habits` WHERE `Talent` <> '' ORDER BY RAND() LIMIT 1")->fetch_object()->Talent;
	}
	if ($talent == "") {
		$talent = $link_new->query("SELECT `Talent` FROM `{$GLOBALS['DB_NAME_OLD']}`.`horse_habits` WHERE `Talent` <> '' ORDER BY RAND() LIMIT 1")->fetch_object()->Talent;
	}

	//------pluk en tilfældig thumb fra føllene i Følkassen-----------------------------------------

	$result_layer_three = $link_new->query("SELECT `tegner`,`thumb` FROM `{$GLOBALS['DB_NAME_OLD']}`.`Heste` WHERE `bruger` = '{$Foelbox}' AND `race` = '{$breed->race}' ORDER BY RAND() LIMIT 1");
	$rand_thumb = $result_layer_three->fetch_object();
	$nythumb = $rand_thumb->thumb;
	$foltegner = $rand_thumb->tegner;
	$nybruger = $user;

	$foel_value = 4500 + floor(($stallion_value * 0.1));
	//----------generer føllene og stil status tilbage til "Hest"----------------------------------------------
	$link_new->query("INSERT INTO `{$GLOBALS['DB_NAME_OLD']}`.`Heste` (`bruger`,`navn`,`race`, `kon`, `alder`, `beskrivelse`, `pris`, `foersteplads`, `andenplads`, `tredieplads`, 
	`status`, `farid`, `morid`, `tegner`, `thumb`, `date`, `changedate`, `status_skift`, `alder_skift`, `height`, `random_height`, `egenskab`, `ulempe`, `talent`) 
	VALUES ('$nybruger','Unavngivet','{$breed->race}','$nykon','0','','{$foel_value}','0','0','0','{$foel}','$nyhingstid','$nyid','$foltegner','$nythumb',now(),now(),'$today','$today','$child_height','$random_height', '$egenskab', '$ulempe', '$talent')");
	
	$link_new->query("INSERT INTO `{$GLOBALS['DB_NAME_NEW']}`.`horse_metadata` 
	(`horse_id`,`meta_key`,`meta_value`,`meta_date`) 
	VALUES ({$link_new->insert_id},'breeder','{$user}',NOW())
	");

	$link_new->query("DELETE FROM `{$GLOBALS['DB_NAME_NEW']}`.`horse_metadata` WHERE `horse_id` = '{$horse}' AND `meta_key` = 'breeding'");


	//----------sender post til Postkassen"----------------------------------------------
	$dims = '"';
	$tegn = array("&", "$dims", "'");
	$substitut = array("og", "&quot;", "&#039;");
}


$log_content = PHP_EOL . "# Breeding finished";
file_put_contents("{$basepath}/app_core/cron_files/logs/cron_one_hour_{$date_now}", $log_content, FILE_APPEND);
