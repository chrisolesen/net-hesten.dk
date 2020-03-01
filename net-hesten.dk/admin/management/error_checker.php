<?php
$basepath = '../../..';
$responsive = true;
require "$basepath/app_core/object_loader.php";
require "$basepath/global_modules/header.php";
?>
<?php
if (!in_array('global_admin', $_SESSION['rights'])) {
	ob_end_clean();
	header('Location: /');
}
$Foelbox = mb_convert_encoding('Følkassen', 'latin1', 'UTF-8');
$Foel = mb_convert_encoding('Føl', 'latin1', 'UTF-8');
$dead = mb_convert_encoding('død', 'latin1', 'UTF-8');
$choose_race = mb_convert_encoding('*Vælg race*', 'latin1', 'UTF-8');
$errors = [];
if (isset($_GET['fix']) && $_GET['fix'] == 'kaaring' && isset($_GET['id'])) {
	$sql = "UPDATE {$_GLOBALS['DB_NAME_OLD']}.Heste SET kaaring = '' WHERE kaaring = 'ja' AND status != '{$Foel}'";
	$link_old->query($sql);
}
?>
<section>
    <header>
        <h2 class="raised">Fejl søger</h2>
    </header>
	<?php
	$sql = "SELECT count(b.creator) AS count "
			. "FROM game_data_auctions a "
			. "LEFT JOIN game_data_auction_bids b "
			. "ON b.auction = a.id "
			. "WHERE b.status_code = 4 "
			. "AND a.status_code = 2";
	$result = $link_new->query($sql);
	$count = $result->fetch_object()->count;
	?>
    <h2>Auktion budsfejl  (<?= $count; ?>)</h2>
    <ul>
		<?php
		$sql = "SELECT a.id, a.object_id, a.end_date, a.instant_price, b.creator, b.auction, b.bid_amount, b.bid_date, b.status_code FROM game_data_auctions a LEFT JOIN game_data_auction_bids b ON b.auction = a.id WHERE b.status_code = 4 AND a.status_code = 2 ORDER BY a.id LIMIT 1";
		$result = $link_new->query($sql);
		while ($failed_auctions = $result->fetch_object()) {
			$singel_sql = "SELECT a.id, a.object_id, a.end_date, b.creator, b.auction, b.bid_amount, b.bid_date, b.status_code FROM game_data_auctions a LEFT JOIN game_data_auction_bids b ON b.auction = a.id WHERE a.id = {$failed_auctions->id} ORDER BY bid_date DESC";
			$related_bids_r = $link_new->query($singel_sql);
			if ((int) $related_bids_r->num_rows === 1) {
				$_GET['do_suggested_auction_fix'] = 'kralamut';
			}
			$suggested_sql = '';
			$refund_suggestions = [];
			$allRefunded = true;
			$allow_refund_for_one = false;
			$bid_one_creator = false;
			$i = 0;
			while ($related_bids = $related_bids_r->fetch_object()) {
				?>
				<li>
					<?php
					++$i;
					if ($i === 1) {
						$bid_one_creator = $related_bids->creator;
					}
					if ($i > 1 && $related_bids->status_code == 6) {
						$allow_refund_for_one = true;
					}
					if ($i > 1 && $related_bids->status_code != 5 && $related_bids->status_code != 6) {
						$allRefunded = false;
						/* code 5 = refunded */
						$refund_suggestions[$i] = $related_bids->creator;
					}
					if (isset($_GET['do_suggested_auction_refund']) && $_GET['do_suggested_auction_refund'] == $i) {
						$temp_user_name = $link_old->query("SELECT stutteri FROM Brugere WHERE id = {$related_bids->creator} LIMIT 1")->fetch_object()->stutteri;
						$temp_user_money = $link_old->query("SELECT penge FROM Brugere WHERE id = {$related_bids->creator} LIMIT 1")->fetch_object()->penge;

						$sql = "UPDATE Brugere SET penge = (penge + {$related_bids->bid_amount}) WHERE id = {$related_bids->creator}";
//                        echo "<br /> {$sql}";
						$link_old->query($sql);
						$sql = "INSERT INTO Konto (stutteri, tekst, transaktion, beloeb, saldo, date) VALUES ('{$temp_user_name}', 'Auktions bud [{$related_bids->bid_date}] refunderet.', 'auktion', '{$related_bids->bid_amount}', '" . ($temp_user_money + $related_bids->bid_amount) . "', NOW())";
//                        echo "<br /> {$sql}";
						$link_old->query($sql);
						$sql = "UPDATE game_data_auction_bids SET status_code = 5 WHERE bid_date = '{$related_bids->bid_date}' AND creator = {$related_bids->creator}";
//                        echo "<br /> {$sql}";
						$link_new->query($sql);
						
						$sql = "INSERT INTO game_data_private_messages "
								. "(status_code, origin, target, date, message) "
								. "VALUES "
								. "(17, 52745, {$related_bids->creator}, NOW(), 'Forsinket tilbagebetaling: Du er desvære blevet overbudt på en auktion, pengene er returneret til din konto.')";
						$link_new->query($sql);


						header('Location: /admin/management/error_checker.php');
						exit();
					}
					$suggested_sql === '' ? $suggested_sql = "update game_data_auction_bids SET status_code = 6 where creator = {$related_bids->creator} and bid_date = '{$related_bids->bid_date}' LIMIT 1" : '';
					$verifying_sql = "SELECT h.id as HesteID, h.bruger, b.stutteri, b.id AS BrugerID FROM {$_GLOBALS['DB_NAME_OLD']}.Heste h LEFT JOIN {$_GLOBALS['DB_NAME_OLD']}.Brugere b ON h.bruger = b.stutteri WHERE h.id = {$related_bids->object_id} LIMIT 1";
					/* Auto Verify */
//                    $link_old->query($verifying_sql)->fetch_object();

					if (isset($_GET['do_suggested_auction_fix']) && $_GET['do_suggested_auction_fix'] == 'kralamut') {
						$result = $link_new->query($suggested_sql);
						header('Location: /admin/management/error_checker.php');
						exit();
					}
					echo 'For auction ' . $related_bids->auction . ' with end date ' . $related_bids->end_date . ' on ID ' . $related_bids->object_id . ' user ' . $related_bids->creator . ' bid ' . $related_bids->bid_amount . ' on ' . $related_bids->bid_date . ' status_code is ' . $related_bids->status_code;
					?>
				</li>
				<?php
			}
			?>
			<br /><br />Verifying SQL result: <?php print_r($link_old->query($verifying_sql)->fetch_object()); ?> 
			<br />Suggesting SQL: "<?= $suggested_sql; ?>" <a style='position:absolute;top:5em;right:5em;' href="?do_suggested_auction_fix=kralamut">SET BID WON!</a>
			<?php
			foreach ($refund_suggestions as $key => $suggested_refund) {
				echo "<br />Suggesting refund for {$key}->{$suggested_refund} <a style='position:absolute;top:5em;right:5em;' href='?do_suggested_auction_refund={$key}'>grant refund to {{$key}->{$suggested_refund}}!</a>";
			}
			if ($allow_refund_for_one) {
				echo "<br />Suggesting refund for 1->{$bid_one_creator} <a style='position:absolute;top:5em;right:5em;' href='?do_suggested_auction_refund=1'>grant refund to {1->{$bid_one_creator}}!</a>";
			}
			?>
			<?php
		}
		?>
    </ul>
	<?php
	$result = $link_old->query("SELECT count(id) as count FROM {$_GLOBALS['DB_NAME_OLD']}.Heste where kaaring = 'ja' and status != '{$Foel}' Limit 1");
	$count = $result->fetch_object()->count;
	?>
    <h2>Heste kårings fejl (<?= $count; ?>)</h2>
    <ul>
		<?php
		$result = $link_old->query("SELECT id, kaaring, status, alder FROM {$_GLOBALS['DB_NAME_OLD']}.Heste where kaaring = 'ja' and status != '{$Foel}' order by ID asc LIMIT 25");
		while ($horse = $result->fetch_object()):
			if ($horse->alder >= 4) {
				?>
				<li><a href="?fix=kaaring&id=<?= $horse->id; ?>">Fix</a> Hest: <?= $horse->id; ?> har kaaring = "<?= $horse->kaaring; ?>" og status = "<?= $horse->status; ?>" og er "<?= $horse->alder; ?>" år</li>
				<?php
			}
		endwhile;
		?>
    </ul>
    <h2>Wkr fejl</h2>
    <ul>
		<?php
		$result = $link_old->query("SELECT penge, stutteri, alder FROM {$_GLOBALS['DB_NAME_OLD']}.Brugere WHERE penge < 0");
		while ($user = $result->fetch_object()):
			?>
			<li>Bruger: <?= $user->stutteri; ?> har <?= $user->penge; ?> wkr.</li>
			<?php
		endwhile;
		?>
    </ul>
    <h2>Heste thumb fejl</h2>
    <ul>
        <style>
            li {
                clear:both;
                display: block;
            }
        </style>
		<?php
		if (isset($_GET['put']) && isset($_GET['on'])) {
			$new_thumb = $link_old->query("SELECT id, thumb, tegner FROM {$_GLOBALS['DB_NAME_OLD']}.Heste WHERE id = '{$_GET['put']}' limit 1 ")->fetch_object();
			$sql = "UPDATE {$_GLOBALS['DB_NAME_OLD']}.Heste SET thumb = '{$new_thumb->thumb}', tegner = '{$new_thumb->tegner}' WHERE id = '{$_GET['on']}'";
			$link_old->query($sql);
		}
//		if (isset($_GET['accept']) && isset($_GET['for'])) {
//			$sql = "UPDATE {$_GLOBALS['DB_NAME_OLD']}.Heste SET thumb = '{$_GET['accept']}' WHERE id = '{$_GET['for']}'";
//			//			echo $sql;
//			$link_old->query($sql);
//		}
		if (isset($_GET['no_rebirth'])) {
			$sql = "UPDATE {$_GLOBALS['DB_NAME_OLD']}.Heste SET genereres = '', genfodes = '' WHERE id = '{$_GET['no_rebirth']}'";
			if ($_GET['foel'] == 'true') {
				$kon = 'Hoppe';
				if (rand(1, 3) >= 2) {
					$kon = 'Hingst';
				}
				$sql = "UPDATE {$_GLOBALS['DB_NAME_OLD']}.Heste SET genereres = '', genfodes = '', bruger = 'techhesten', kon = '{$kon}' WHERE id = '{$_GET['no_rebirth']}'";
			}
			$link_old->query($sql);
		}
		$amount = ($link_old->query("SELECT count(id) AS number FROM {$_GLOBALS['DB_NAME_OLD']}.Heste WHERE (thumb = '/imgHorse/..' OR thumb = '') AND race <> '' and race <> '{$choose_race}' and status <> '{$Foel}' and unik = 'ja'")->fetch_object()->number);
		echo "Unikke: {$amount} <br />";
		?>
		<h2>Unikke med thumb fejl, pr race.</h2>
		<?php
		$relevant_races = $link_old->query("SELECT DISTINCT race FROM {$_GLOBALS['DB_NAME_OLD']}.Heste WHERE (thumb = '/imgHorse/..' OR thumb = '') AND race <> '' and race <> '{$choose_race}' and status <> '{$Foel}' and unik = 'ja' AND date > '2015-01-01 00:00:00'");
//		var_dump($relevant_races);
		while ($single_race = $relevant_races->fetch_object()->race) {
			echo '<br />' . mb_convert_encoding($single_race, 'UTF-8', 'latin1') . ': (' . ($link_old->query("SELECT count(id) AS amount FROM {$_GLOBALS['DB_NAME_OLD']}.Heste WHERE (thumb = '/imgHorse/..' OR thumb = '') AND race = '{$single_race}' and status <> '{$Foel}' and unik = 'ja' AND date > '2015-01-01 00:00:00'")->fetch_object()->amount) . ')';
		}
		?>
    </ul>    
</section>
<?php
require "$basepath/global_modules/footer.php";
