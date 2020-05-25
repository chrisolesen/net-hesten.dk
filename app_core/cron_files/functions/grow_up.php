<?php

if (!isset($time_now)) {
	date_default_timezone_set('Europe/Copenhagen');
	$current_date = new DateTime('now');
	$date_now = $current_date->format('Y-m-d');
	$time_now = $current_date->format('H:i:s');
}

$log_content = PHP_EOL . '# Checking for foals, that are ready, to grow up.';
file_put_contents("{$basepath}app_core/cron_files/logs/cron_one_hour_{$date_now}", $log_content, FILE_APPEND);

require_once "{$basepath}app_core/db_conf.php";

$Foelbox = 'Følkassen';
$foel = 'føl';
$Foel = 'Føl';

$today = date("d.m.y.G.i");
$loop = 0;

//----------------------------------------------------------------------------------------------------
//-----Gør Føl TIL HESTE Når DE BLIVER OVER 4 åR OG SæT ALDER TIL 4 åR--DETTE SKER EFTER 140 DAGE-----
//----------------------------------------------------------------------------------------------------
//-----Find føl som er over 4 år-------------------------------------------------------------------

$sql = "SELECT `id` FROM `{$GLOBALS['DB_NAME_OLD']}`.`Heste` WHERE `bruger` <> 'Følkassen' AND `bruger` <> 'hestehandleren*' AND `status` = 'føl' AND `alder` >= 4";
$result = $link_new->query($sql);
$foel_amount = 0;
$grow_up_amount = 0;
while ($data = $result->fetch_assoc()) {
	$foel_id = $data['id'];
	++$foel_amount;

	$result_layer_two = $link_new->query("SELECT `id`, `bruger`, `alder`, `navn`, `race`, `pris`, `thumb`, `date` FROM `{$GLOBALS['DB_NAME_OLD']}`.`Heste` WHERE `id` = '$foel_id' LIMIT 1");
	$poalder = $result_layer_two->fetch_object();
	if ($poalder) {
		$sekunder = time() - strtotime($poalder->date);
		$dage = $sekunder / 86400;
		$mdr = $dage / 40;
		$mdr_oprundet = round($mdr);

		if ($mdr_oprundet < 4) {
		} elseif ($mdr_oprundet >= 4) {
			++$grow_up_amount;
			++$loop;
			$nyid = $poalder->id;
			$nyrace = $poalder->race;
			$pris = $poalder->pris;
			$nypris = $poalder->pris + 5000;
			$nynavn = $poalder->navn;
			$nybruger = $poalder->bruger;
			$glthumb = $poalder->thumb;
			$nyalder = 4;
			$tilskrevet = $nyalder - $poalder->alder;

			//------pluk en tilfældig thumb fra hestene i databasen-----------------------------------------
			$thumb_data = $link_new->query("SELECT `tegner`, `thumb` 
			FROM `{$GLOBALS['DB_NAME_OLD']}`.`Heste` 
			WHERE `bruger` <> 'Hestehandleren*' AND `bruger` <> '{$Foelbox}' AND `race` = '$nyrace' AND `status` <> 'føl' AND `genfodes` = 'ja' AND `unik` <> 'ja' 
			ORDER BY RAND() LIMIT 1 ");
			$rand_heste_thumb = $thumb_data->fetch_object();
			if ($rand_heste_thumb) {
				//-----------SæT VARIABLER---------------------------------------
				$tegner = $rand_heste_thumb->tegner;
				$nythumb = $rand_heste_thumb->thumb;
				//-----------update thumb, penge, status og slet det gamle føl-billede-------------------------
				$link_new->query("UPDATE `{$GLOBALS['DB_NAME_OLD']}`.`Heste` 
				SET `pris`='{$nypris}', `status`='Hest', `alder`='$nyalder', `date`=NOW(), `status_skift`='$today', `alder_skift`='$today', `tegner`='$tegner', `thumb`='$nythumb' 
				WHERE id = '$nyid'");

				$dims = '"';
				$tegn = array("&", "$dims", "'");
				$substitut = array("og", "&quot;", "&#039;");

				$horse_name = str_replace($tegn, $substitut, $nynavn);
				$user_name = str_replace($tegn, $substitut, $nybruger);
				if ($horse_name) {
					//	$link_new->query("INSERT into Postsystem (emne, besked, sender, modtager, mappe, date) VALUES ('Dit {$foel} $horse_name er blevet voksen','Tillykke $user_name :-)) <br>$horse_name er blevet 4 &aring;r, og er nu en voksen hest<br>Derfor er den steget 5000 wkr i v&aelig;rdi.<br><img src=/imgHorse/$nythumb>','admin@net-hesten','$user_name','Indbakke',now() )");
				}
			}
		}
	}
}
$log_content = PHP_EOL . '#'
	. PHP_EOL . "# Found {$foel_amount} foels in total."
	. PHP_EOL . "# Found {$grow_up_amount} that were ready to grow up.";
file_put_contents("{$basepath}app_core/cron_files/logs/cron_one_hour_{$date_now}", $log_content, FILE_APPEND);
