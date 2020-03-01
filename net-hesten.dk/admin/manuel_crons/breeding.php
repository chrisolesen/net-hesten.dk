<?php
$basepath = '../../..';
require "$basepath/app_core/object_loader.php";
require "$basepath/global_modules/header.php";
?>
<?php
if (!in_array('global_admin', $_SESSION['rights'])) {
	ob_end_clean();
	header('Location: /');
}
?>
<style>
	td {
		padding:0.25em 0.5em;
	}
</style>

<section>
	<h1>ManCrons - Avl</h1><br />
	<?php
	if (isset($_GET['do']) && $_GET['do'] == 'foel_cron') {
		$breeds = $link_new->query("SELECT meta.meta_value AS partner_id, meta.horse_id, horse.bruger, horse.race FROM `{$_GLOBALS['DB_NAME_NEW']}`.`horse_metadata` AS meta "
				. "LEFT JOIN `{$_GLOBALS['DB_NAME_OLD']}`.`Heste` AS horse ON horse.id = meta.horse_id "
				. "WHERE meta_key = 'breeding' AND meta_date < DATE_SUB(NOW(),INTERVAL 40 DAY) "
				. "LIMIT 150");

		$foel_amount = 0;
		$grow_up_amount = 0;
		$breeding_stallions = 0;
		$stallions_changed_status = 0;
		$Foelbox = mb_convert_encoding('Følkassen', 'latin1', 'UTF-8');
		$foel = mb_convert_encoding('føl', 'latin1', 'UTF-8');
		$Foel = mb_convert_encoding('Føl', 'latin1', 'UTF-8');

		$ø = mb_convert_encoding('ø', 'latin1', 'UTF-8');

		$today = date("d.m.y.G.i");
		$loop = 0;

		$breeding_amount = 0;
		$born_amount = 0;
		$in_waiting = 0;
		while ($breed = $breeds->fetch_object()) {
			$latin_race = mb_convert_encoding($breed->race, 'latin1', 'UTF-8');
			$horse = $breed->horse_id;
			$partner = $breed->partner_id;
			$user = $breed->bruger;
			$nyrace = mb_convert_encoding($breed->race, 'latin1', 'UTF-8');

			if (rand(1, 2) == 1) {
				$nykon = "Hingst";
			} else {
				$nykon = "Hoppe";
			}

			$nyid = $horse;
			$nyhingstid = $partner;

			$farid = $partner;
			$morid = $horse;
//			continue;
//-----test om heste skal have random højde i racens interval, ellers vælg fars og mors højde, afgør laveste og højeste værdi, generer en random højde mellem disse værdier---------------------------------

			if (rand(1, 10) == 1) {
				$lowest_height = $link_old->query("SELECT lower FROM horse_height WHERE race = '$latin_race' LIMIT 1")->fetch_object()->lower;
				$highest_height = $link_old->query("SELECT upper FROM horse_height WHERE race = '$latin_race' LIMIT 1")->fetch_object()->upper;
				$child_height = rand($lowest_height, $highest_height);
				$random_height = "ja";
			} else {
				$daddy_height = $link_old->query("SELECT height FROM Heste WHERE id = '$partner' LIMIT 1")->fetch_object();
				$mommy_height = $link_old->query("SELECT height FROM Heste WHERE id = '$morid' LIMIT 1")->fetch_object();
				$child_height = rand(min($daddy_height->height, $mommy_height->height), max($daddy_height->height, $mommy_height->height));
				$random_height = "nej";
			}

//-------------------Vælg tilfældig egenskab, ulempe og talent, rand bruges for arvelighed, gider ikke forklare det, det burde være let at gennemskue------------------------------
			if (rand(1, 10) == 1) {
				if (rand(1, 2) == 1) {
					$egenskab = $link_old->query("SELECT Egenskab FROM Heste WHERE id = '$farid' LIMIT 1")->fetch_object()->Egenskab;
				} else {
					$egenskab = $link_old->query("SELECT Egenskab FROM Heste WHERE id = '$morid' LIMIT 1")->fetch_object()->Egenskab;
				}
			} else {
				$egenskab = $link_old->query("SELECT Egenskab FROM horse_habits WHERE Egenskab != '' ORDER BY RAND() LIMIT 1")->fetch_object()->Egenskab;
			}
			if ($egenskab == "") {
				$egenskab = $link_old->query("SELECT Egenskab FROM horse_habits WHERE Egenskab != '' ORDER BY RAND() LIMIT 1")->fetch_object()->Egenskab;
			}

			if (rand(1, 10) == 1) {
				if (rand(1, 2) == 1) {
					$ulempe = $link_old->query("SELECT Ulempe FROM Heste WHERE id = '$farid' LIMIT 1")->fetch_object()->Ulempe;
				} else {
					$ulempe = $link_old->query("SELECT Ulempe FROM Heste WHERE id = '$morid' LIMIT 1")->fetch_object()->Ulempe;
				}
			} else {
				$ulempe = $link_old->query("SELECT Ulempe FROM horse_habits WHERE Ulempe != '' ORDER BY RAND() LIMIT 1")->fetch_object()->Ulempe;
			}
			if ($ulempe == "") {
				$ulempe = $link_old->query("SELECT Ulempe FROM horse_habits WHERE Ulempe != '' ORDER BY RAND() LIMIT 1")->fetch_object()->Ulempe;
			}

			if (rand(1, 100) <= 50) {
				if (rand(1, 2) == 1) {
					$talent = $link_old->query("SELECT Talent FROM Heste WHERE id = '$farid' LIMIT 1")->fetch_object()->Talent;
				} else {
					$talent = $link_old->query("SELECT Talent FROM Heste WHERE id = '$morid' LIMIT 1")->fetch_object()->Talent;
				}
			} else {
				$talent = $link_old->query("SELECT Talent FROM horse_habits WHERE Talent != '' ORDER BY RAND() LIMIT 1")->fetch_object()->Talent;
			}
			if ($talent == "") {
				$talent = $link_old->query("SELECT Talent FROM horse_habits WHERE Talent != '' ORDER BY RAND() LIMIT 1")->fetch_object()->Talent;
			}




//------pluk en tilfældig thumb fra føllene i Følkassen-----------------------------------------

			$result_layer_three = $link_old->query("SELECT tegner, thumb FROM Heste WHERE bruger = '{$Foelbox}' AND race = '$latin_race' ORDER BY RAND() LIMIT 1");
			$rand_thumb = $result_layer_three->fetch_object();
			$nythumb = $rand_thumb->thumb;
			$foltegner = $rand_thumb->tegner;
			$nybruger = mb_convert_encoding($user, 'latin1', 'UTF-8');
//----------generer føllene og stil status tilbage til "Hest"----------------------------------------------
			$link_old->query("INSERT into Heste (bruger, navn, race, kon, alder, beskrivelse, pris, foersteplads, andenplads, tredieplads, status, farid, morid, tegner, thumb, date, changedate, status_skift, alder_skift, height, random_height, egenskab, ulempe, talent) VALUES ('$nybruger','Unavngivet','$nyrace','$nykon','0','','6000','0','0','0','{$foel}','$nyhingstid','$nyid','$foltegner','$nythumb',now(),now(),'$today','$today','$child_height','$random_height', '$egenskab', '$ulempe', '$talent')");
			$link_new->query("DELETE FROM `{$_GLOBALS['DB_NAME_NEW']}`.`horse_metadata` WHERE horse_id = '$horse' AND meta_key = 'breeding'");


//----------sender post til Postkassen"----------------------------------------------
			$dims = '"';
			$tegn = array("&", "$dims", "'");
			$substitut = array("og", "&quot;", "&#039;");

			/*$horsename = str_replace($tegn, $substitut, $glnavn);
			$username = str_replace($tegn, $substitut, $nybruger);
			if ($horsename) {
//				$link_old->query("INSERT into Postsystem (emne, besked, sender, modtager, mappe, date) VALUES ('$horsename har f{$ø}dt et {$foel}','Tillykke $username :-)) <br>$prut1 har f{$ø}dt et velskabt {$foel}.<br><img src=\"/imgHorse/$glthumb\" style=\'FILTER: FlipH;\'><img src=/imgHorse/$nythumb>','admin@Net-hesten','$username','Avl',now() )");
			}*/
		}
	}

	$ready_to_breed = $link_new->query("SELECT COUNT(horse_id) AS amount FROM `horse_metadata` WHERE meta_key = 'breeding' AND meta_date < DATE_SUB(NOW(),INTERVAL 40 DAY)")->fetch_object()->amount;
	?>
	<h2>Klar til at fole ( <?= $ready_to_breed; ?> )</h2><br />
	<a href="?do=foel_cron">Kør foling</a>
</section>

<?php
require "$basepath/global_modules/footer.php";