<section id="left_menu">
	<?php if ($_SESSION['logged_in'] == true) { ?>
		<a data-custom-title="Indbakke" href="/area/stud/messages"><img src="https://files.net-hesten.dk/graphics/inbox.png"></a>
		<a data-custom-title="Heste Handleren" href="/area/world/horsetrader"><img src="https://files.net-hesten.dk/graphics/trader.png"></a>
		<a data-custom-title="uofficielt Forum" href="http://nethesten.boards.net/" target="_Blank"><img src="https://files.net-hesten.dk/graphics/forum.png"></a>
		<a data-custom-title="Auktionshus" href="/area/world/auction/"><img src="https://files.net-hesten.dk/graphics/auctions.png"></a>
		<a data-custom-title="Tag chancen" href="/area/world/lotto/quick_chance.php"><img src="https://files.net-hesten.dk/graphics/chance.png"></a>
		<a data-custom-title="Konkurrencer" href="/area/world/competition"><img src="https://files.net-hesten.dk/graphics/competition.png"></a>
		<?php
		$competition_data = $link_new->query("SELECT id FROM {$_GLOBALS['DB_NAME_NEW']}.game_data_simple_competition WHERE startdate < NOW() ORDER BY startdate DESC LIMIT 1")->fetch_object();
		$partaking = false;
		$partaking = $link_new->query("SELECT * FROM {$_GLOBALS['DB_NAME_NEW']}.game_data_simple_competition_participants WHERE competition_id = {$competition_data->id} AND participant_id = {$_SESSION['user_id']}");
		?>
		<a <?php if (!$partaking->fetch_object()) {
				echo 'message_status=""';
			} ?> data-custom-title="Lodtrækning" href="/area/world/simple_competition/"><img src="https://files.net-hesten.dk/graphics/simple_competition.png"></a>
		<a data-custom-title="Hestetegner" href="/area/artist_center"><img src="https://files.net-hesten.dk/graphics/artist.png"></a>
		<a data-custom-title="Søg på heste" href="/area/world/search/"><img src="https://files.net-hesten.dk/graphics/list.png"></a>
		<a data-custom-title="Alle stutterier" href="/area/world/visit/"><img src="https://files.net-hesten.dk/graphics/list.png"></a>
		<a data-custom-title="Privat handel" href="/area/world/privat_trade/"><img src="https://files.net-hesten.dk/graphics/private_trade.png"></a>
		<a data-custom-title="Konto oversigt" href="/area/stud/accounting"><img src="https://files.net-hesten.dk/graphics/money.png"></a>
		<a data-custom-title="Udviklings noter" <?php if ($title != 'dev_notes' && $_SESSION['settings']['last_read_dev_notes'] < (date("Y-m-d H:i:s", filemtime("$basepath/net-hesten.dk/area/world/dev_notes/index.php")))) { ?> message_status="blink" <?php } ?> href="/area/world/dev_notes/"><img src="https://files.net-hesten.dk/graphics/blank.png"><i class="fal fa-file-alt fa-lg" style="position:absolute;top:50%;left:50%;transform:translateY(-50%) translateX(-50%);"></i></a>
		<a data-custom-title="Støt net-hesten" href="/area/world/support_nethesten/"><img src="https://files.net-hesten.dk/graphics/buy_wkr.png"></a>
		<?php if (in_array('hestetegner', $_SESSION['rights']) || in_array('global_admin', $_SESSION['rights'])) { ?>
			<a data-custom-title="Chat for tegnere" href="https://discord.gg/cspYYPf" target="_BLANK"><img height="55" width="55" src="https://files.net-hesten.dk/graphics/discord.png"></a>
		<?php } ?>

		<?php if (in_array('global_admin', $_SESSION['rights'])) { ?>
			<a><img src="https://files.net-hesten.dk/graphics/blank.png"></a>
		<?php } ?>

		<?php if (in_array('tech_admin', $_SESSION['rights'])) { ?>

			<a><img src="https://files.net-hesten.dk/graphics/blank.png"></a>
			<a data-custom-title="Vær din hest"><img src="https://files.net-hesten.dk/graphics/be_your_horse.png"></a>
			<a><img src="https://files.net-hesten.dk/graphics/house.png"></a>
			<a><img src="https://files.net-hesten.dk/graphics/lotto.png"></a>

		<?php } ?>

		<?php if (in_array('global_admin', $_SESSION['rights']) || in_array('admin_panel_access', $_SESSION['rights'])) { ?>
			<a data-custom-title="Admin" href="/admin/"><img src="https://files.net-hesten.dk/graphics/admin.png"></a>
		<?php } ?>

	<?php } ?>
</section>