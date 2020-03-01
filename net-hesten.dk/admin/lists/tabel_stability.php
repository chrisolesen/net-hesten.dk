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
?>
<section>
	<style>
		ul {
			border: 1px solid black;
			padding: 10px;
			display: table;
		}

		ul li {
			display: table-row;
		}

		ul li span {
			display: table-cell;
			padding: 2px 10px;
			line-height: 1.2;
			border-bottom: 1px dashed black;
		}

		ul li span+span {
			border-left: 1px dotted black;
		}

		.wkr {
			text-align: right;
		}

		.heading span {
			border-bottom: 3px double black;
			text-align: center;
		}

		.monospace {
			font-family: monospace;
		}

		.center_text {
			text-align: center;
		}
	</style>
	<header>
		<h1>Tabel Overview</h1>
	</header>
	<ul style="display:inline-block;overflow:hidden;">
		<li class="heading">
			<span>Tabel Name</span>
			<span>Table Rows</span>
			<span>Table Cols</span>
		</li>
		<?php
		$total_rows = 0;
		$table_count = 0;
		$total_data_points = 0;
		$tables = [
			'Article', 'ArticleLayouts', 'ArticleRating', 'Brugere',
			'Chancen', 'death_run',
			'Heste', 'Hesteracer', 'horse_habits', 'horse_height',
			'name_change', 'PaypalPayment', 'SolgtUdstyr',
			'Udstyr'
		];
		foreach ($tables as $name) {
			++$table_count;
			$sql = "SELECT COUNT(*) AS amount FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = '{$_GLOBALS['DB_NAME_OLD']}' AND table_name = '{$name}'";
			$cols = $link_old->query($sql)->fetch_object()->amount;
			$sql = "SELECT count(*) AS amount FROM `{$name}` LIMIT 1";
			$amount = $link_old->query($sql)->fetch_object()->amount;
			$total_rows += $amount;
			$total_data_points += ($amount * $cols);
		?>
			<li>
				<span><?= $name; ?></span>
				<span style="text-align: right;"><?= number_dotter($amount); ?></span>
				<span style="text-align: right;"><?= number_dotter($cols); ?></span>
			</li>
		<?php
		}
		?>
		<li>
			<span>Totalt</span>
			<span style="text-align: right;"><?= number_dotter($total_rows); ?></span>
			<span style="text-align: right;"><?= number_dotter($total_data_points); ?></span>
		</li>
	</ul>
	<ul style="display:inline-block;overflow:hidden;">
		<li class="heading">
			<span>Tabel Name</span>
			<span>Table Rows</span>
			<span>Table Cols</span>
		</li>
		<?php
		$total_rows = 0;
		$table_count = 0;
		$total_data_points = 0;
		$tables = [
			'artist_center_submissions',
			'game_data_auctions',
			'game_data_auction_bids',
			'game_data_chat_messages',
			'game_data_competitions',
			'game_data_competition_participants',
			'game_data_simple_competition',
			'game_data_simple_competition_participants',
			'game_data_private_messages',
			'game_data_private_trade',
			'horse_metadata',
			'horse_races',
			'horse_templates',
			'horse_types',
			'users',
			'user_application',
			'user_data_json',
			'user_data_numeric',
			'user_data_sessions',
			'user_data_text',
			'user_data_timing',
			'user_data_varchar'
		];
		foreach ($tables as $name) {
			++$table_count;
			$sql = "SELECT COUNT(*) AS amount FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = '{$_GLOBALS['DB_NAME_NEW']}' AND table_name = '{$name}'";
			$cols = $link_new->query($sql)->fetch_object()->amount;
			$sql = "SELECT count(*) AS amount FROM `{$name}` LIMIT 1";
			$amount = $link_new->query($sql)->fetch_object()->amount;
			$total_rows += $amount;
			$total_data_points += ($amount * $cols);
		?>
			<li>
				<span><?= $name; ?></span>
				<span style="text-align: right;"><?= number_dotter($amount); ?></span>
				<span style="text-align: right;"><?= number_dotter($cols); ?></span>
			</li>
		<?php
		}
		?>
		<li>
			<span>Totalt</span>
			<span style="text-align: right;"><?= number_dotter($total_rows); ?></span>
			<span style="text-align: right;"><?= number_dotter($total_data_points); ?></span>
		</li>
	</ul>
	<br />
	<br />
</section>
<?php
require "$basepath/global_modules/footer.php";
