<?php
$basepath = '../../../..';
$title = 'Donationer';
require "$basepath/app_core/object_loader.php";
require "$basepath/global_modules/header.php";
?>
<section class="tabs">
	<div class="grid">
		<div data-section-type="info_square">
			<header><h1>Lotto - Hurtig Chancen</h1></header>
		</div>
		<?php
		$date_now = (new DateTime('NOW'))->format('Y-m-d');
		$last_chance = $link_new->query("SELECT value FROM user_data_timing WHERE parent_id = {$_SESSION['user_id']} AND name = 'chance_last_taken'");
		$can_do_chance_now = false;
		if ($last_chance) {
			$last_chance = $last_chance->fetch_object();
			$last_chance_date = (new DateTime($last_chance->value))->format('Y-m-d');
			if ($date_now > $last_chance_date) {
				$can_do_chance_now = true;
			}
		} else {
			$can_do_chance_now = true;
		}
		if ($can_do_chance_now) {
			$chance_event = $link_new->query("SELECT * FROM {$_GLOBALS['DB_NAME_OLD']}.Chancen ORDER BY rand() LIMIT 1")->fetch_object();
			$link_new->query("INSERT INTO user_data_timing (parent_id, name, value) "
					. "VALUES ({$_SESSION['user_id']}, 'chance_last_taken', NOW()) "
					. "ON DUPLICATE KEY UPDATE value = NOW()");
			if (stripos($chance_event->penge, '-')) {
				$money_amount = str_replace('-', '', $chance_event->penge);
				accounting::add_entry(['amount' => $money_amount, 'line_text' => "Tog chancen"]);
			} else {
				accounting::add_entry(['amount' => $chance_event->penge, 'line_text' => "Tog chancen", 'mode' => '+']);
			}
			echo '<p>';
			echo $chance_event->chancetekst . ' ' . number_dotter($chance_event->penge) . ' <span class="wkr_symbol">wkr</span>';
			echo '</p>';
		} else {
			?>
			<p>Du har allerede taget chancen idag - kom tilbage i morgen.</p>
			<?php
		}
		?>
	</div>
</section>
<?php
require_once ("{$basepath}/global_modules/footer.php");
