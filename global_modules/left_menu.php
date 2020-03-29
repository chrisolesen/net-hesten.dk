<section id="left_menu">
	<?php if ($_SESSION['logged_in'] == true) { ?>
		<a data-custom-title="Indbakke" href="/area/stud/messages"><img src="//files.<?= HTTP_HOST; ?>/graphics/inbox.png"></a>
		<a data-custom-title="Heste Handleren" href="/area/world/horsetrader"><img src="//files.<?= HTTP_HOST; ?>/graphics/trader.png"></a>

		<a data-custom-title="Uofficielt Forum" href="http://nethesten.boards.net/" target="_BLANK"><img height="55" width="55" src="//files.<?= HTTP_HOST; ?>/graphics/forum.png"></a>

		<a data-custom-title="Discord chat" href="https://discord.gg/cspYYPf" target="_BLANK"><img height="55" width="55" src="//files.<?= HTTP_HOST; ?>/graphics/discord.png"></a>
		<a data-custom-title="Auktionshus" href="/area/world/auction/"><img src="//files.<?= HTTP_HOST; ?>/graphics/auctions.png"></a>
		<a data-custom-title="Tag chancen" href="/area/world/lotto/quick_chance.php"><img src="//files.<?= HTTP_HOST; ?>/graphics/chance.png"></a>
		<a data-custom-title="Konkurrencer" href="/area/world/competition"><img src="//files.<?= HTTP_HOST; ?>/graphics/competition.png"></a>
		<a data-custom-title="Søg på heste" href="/area/world/search/"><img src="//files.<?= HTTP_HOST; ?>/graphics/list.png"></a>
		<a data-custom-title="Alle stutterier" href="/area/world/visit/"><img src="//files.<?= HTTP_HOST; ?>/graphics/list.png"></a>
		<a data-custom-title="Hestetegner" href="/area/artist_center"><img src="//files.<?= HTTP_HOST; ?>/graphics/artist.png"></a>
		<a data-custom-title="Privat handel" href="/area/world/privat_trade/"><img src="//files.<?= HTTP_HOST; ?>/graphics/private_trade.png"></a>
		<a data-custom-title="Konto oversigt" href="/area/stud/accounting"><img src="//files.<?= HTTP_HOST; ?>/graphics/money.png"></a>
		<a data-custom-title="Støt net-hesten" href="/area/world/support_nethesten/"><img src="//files.<?= HTTP_HOST; ?>/graphics/buy_wkr.png"></a>
		<?php if (in_array('global_admin', $_SESSION['rights'])) { ?>
			<a><img src="//files.<?= HTTP_HOST; ?>/graphics/blank.png"></a>
		<?php } ?>
		<?php if (in_array('tech_admin', $_SESSION['rights'])) { ?>
			<a><img src="//files.<?= HTTP_HOST; ?>/graphics/blank.png"></a>
			<?php
			$competition_data = $link_new->query("SELECT id FROM {$GLOBALS['DB_NAME_NEW']}.game_data_simple_competition WHERE startdate < NOW() ORDER BY startdate DESC LIMIT 1")->fetch_object();
			$partaking = false;
			$partaking = $link_new->query("SELECT * FROM {$GLOBALS['DB_NAME_NEW']}.game_data_simple_competition_participants WHERE competition_id = {$competition_data->id} AND participant_id = {$_SESSION['user_id']}");
			?>
			<a <?php if (!$partaking->fetch_object()) {
					echo 'message_status=""';
				} ?> data-custom-title="Lodtrækning" href="/area/world/simple_competition/"><img src="//files.<?= HTTP_HOST; ?>/graphics/simple_competition.png"></a>
			<a data-custom-title="Vær din hest"><img src="//files.<?= HTTP_HOST; ?>/graphics/be_your_horse.png"></a>
			<a><img src="//files.<?= HTTP_HOST; ?>/graphics/house.png"></a>
			<a><img src="//files.<?= HTTP_HOST; ?>/graphics/lotto.png"></a>
		<?php } ?>

		<?php if (in_array('global_admin', $_SESSION['rights']) || in_array('admin_panel_access', $_SESSION['rights']) || in_array('site_helper', $_SESSION['rights'])) {	?>
			<a data-custom-title="Admin" href="/admin/"><img src="//files.<?= HTTP_HOST; ?>/graphics/admin.png"></a>
		<?php } ?>
	<?php } ?>
</section>