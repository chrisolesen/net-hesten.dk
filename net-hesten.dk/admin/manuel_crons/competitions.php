<?php
$basepath = '../../..';
require "$basepath/app_core/object_loader.php";
require "$basepath/global_modules/header.php";

if (!in_array('global_admin', $_SESSION['rights'])) {
	ob_end_clean();
	header('Location: /');
}
$date_tomorrow = new DateTime('now');
$date_tomorrow->add(new DateInterval('P1D'));
if (isset($_GET['make'])) {

	$end_date = $date_tomorrow->format('Y-m-d') . ' 17:55:00';
	if ($_GET['make'] == 'foel') {
		$name = 'Følkåring';
		$allowed_types = '2';
		$allowed_races = '';
	} else {
		$allowed_races = '';
		$sql = "SELECT id FROM `{$GLOBALS['DB_NAME_NEW']}`.horse_races WHERE ID < 103 ORDER BY RAND() LIMIT 2";
		$result = $link_new->query($sql);
		while ($data = $result->fetch_object()) {
			$allowed_races .= "{$data->id},";
		}
		$allowed_types = '';
		$allowed_races = substr($allowed_races, 0, (strlen($allowed_races) - 1));
	}
	if ($_GET['make'] == 'western') {

		$name = 'Western';
	}
	if ($_GET['make'] == 'dress') {

		$name = 'Dressur';
	}
	if ($_GET['make'] == 'jump') {
		$name = 'Spring';
	}

	$link_new->query('INSERT INTO game_data_competitions '
		. '(status_code, start_date, end_date, allowed_races, allowed_types,name,description) '
		. 'VALUES '
		. "(32, NOW(), '{$end_date}','{$allowed_races}','{$allowed_types}','{$name}','') ");
	echo $link_new->error;
	header('Location: /admin/manuel_crons/competitions.php');
	exit();
}

$date_now = new DateTime('NOW');
if ($end_competition_id = filter_input(INPUT_GET, 'end_competition')) {
	$competition = $link_new->query("SELECT status_code, name, end_date FROM `game_data_competitions` WHERE `id` = {$end_competition_id}")->fetch_object();
	if ($competition->status_code == 31) {
		echo 'Competion already ended';
	} else {

		//	31 = ended;
		$sql = "SELECT horse.id AS hid, horse.navn AS hname, user.stutteri AS uname, user.id AS uid, user.penge AS money, horse.pris AS value FROM `{$GLOBALS['DB_NAME_NEW']}`.`game_data_competition_participants` AS PData "
			. "LEFT JOIN `{$GLOBALS['DB_NAME_OLD']}`.`Heste` AS horse ON horse.id = PData.participant_id "
			. "LEFT JOIN `{$GLOBALS['DB_NAME_OLD']}`.`Brugere` AS user ON user.stutteri = horse.bruger "
			. "WHERE `competition_id` = {$end_competition_id} "
			. "ORDER BY rand()";
		$result = $link_new->query($sql);
		$participants = [];
		$points = 0;
		while ($data = $result->fetch_object()) {
			++$points;
			if ($competition->name == 'Følkåring') {
				$point_array = [1, 2, 3, 4];
			} else {
				$point_array = [1, 2, 3];
			}
			if (in_array($points, $point_array)) {
				if ($competition->name == 'Følkåring') {
					if ($points == 1) {
						$medal = 'Helhedsindtryk';
						$price_money = 15000;
						$value_add = 7500;
					} else if ($points == 2) {
						$medal = 'Kropsbygning';
						$price_money = 10000;
						$value_add = 5000;
					} else if ($points == 3) {
						$medal = 'Temperament';
						$price_money = 10000;
						$value_add = 5000;
					} else if ($points == 4) {
						$medal = 'Gangart';
						$price_money = 10000;
						$value_add = 5000;
					}
				} else {

					if ($points == 1) {
						$medal = 'Guld';
						$price_money = 50000;
						$value_add = 20000;
					} else if ($points == 2) {
						$medal = 'Sølv';
						$price_money = 25000;
						$value_add = 10000;
					} else if ($points == 3) {
						$medal = 'Bronze';
						$price_money = 10000;
						$value_add = 5000;
					}
				}
				$utf_8_message = "<b>Tilykke {$data->uname}</b>,<br /><br /> Din hest {$data->hname} har vundet {$medal} i {$competition->name}. ({$competition->end_date})<br /><br />Du har fået {$price_money}wkr og din hest er steget med {$value_add} i værdi.<br /><br /><b>Med venlig hilsen</b><br />Konkurrencestyrelsen";
				$origin = 53844; /* Konkurrencestyrelsen */

				$link_new->query("UPDATE `{$GLOBALS['DB_NAME_OLD']}`.`Brugere` SET penge = (penge + {$price_money}) WHERE id = {$data->uid}");
				$link_new->query("UPDATE `{$GLOBALS['DB_NAME_OLD']}`.`Heste` SET pris = (pris + {$value_add}) WHERE id = {$data->hid}");
				$link_new->query("INSERT INTO game_data_private_messages (status_code, hide, origin, target, date, message) VALUES (17, 0, {$origin}, {$data->uid}, NOW(), '{$utf_8_message}' )");
			}
			$link_new->query("UPDATE `game_data_competition_participants` SET points = '{$points}' WHERE `competition_id` = {$end_competition_id} AND `participant_id` = {$data->hid}");
			$link_new->query("UPDATE `game_data_competitions` SET status_code = 31 WHERE `id` = {$end_competition_id}");
		}
	}
}
?>

<style>
	td {
		padding: 0.25em 0.5em;
	}
</style>
<section>
	<h1>ManCrons - Stævner</h1><br />
	<h2>Nye Stævner</h2><br />
	<a href="?make=western">Lav Western</a><br />
	<a href="?make=jump">Lav Spring</a><br />
	<a href="?make=dress">Lav Dressur</a><br />
	<a href="?make=foel">Lav Følkåring</a><br /><br />
	<?php ?>

	<h2>Aktive Stævner</h2>
	<table>
		<thead>
			<tr>
				<td>ID</td>
				<td>Navn</td>
				<td>Tilladte Racer</td>
				<td>Start</td>
				<td>Slut</td>
				<td></td>
			</tr>
		</thead>
		<tbody>

			<?php
			//	30 competition_ongoing
			//	31 competition_ended
			//	32 competition_in_que
			$sql = "SELECT * FROM `{$GLOBALS['DB_NAME_NEW']}`.game_data_competitions WHERE status_code <> 31 ORDER BY start_date DESC, id DESC";
			$result = $link_new->query($sql);
			while ($data = $result->fetch_object()) {
			?>
				<tr>
					<td><?= $data->id; ?></td>
					<td><?= $data->name; ?></td>
					<td><?= $data->allowed_races; ?></td>
					<td><?= $data->start_date; ?></td>
					<td><?= $data->end_date; ?></td>
					<td><a href="?end_competition=<?= $data->id; ?>">Afslut</a></td>
					<?php /*
					  <?= $data->allowed_types; ?>
					  <?= $data->allowed_min_age; ?>
					  <?= $data->allowed_max_age; ?>
					  <?= $data->description; ?>
					  <?= $data->prices; ?>
					  <?= $data->participant_structure; ?>
					 * 
					 */ ?>
				<tr>
				<?php
			}
				?>

		</tbody>
	</table>
	<?php ?>
</section>

<?php
require "$basepath/global_modules/footer.php";
