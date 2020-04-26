<?php /* REVIEW: SQL Queries */ ?>
<!--<link href="https://fonts.googleapis.com/css?family=Sedgwick+Ave" rel="stylesheet">-->
<div id="fun_fact_book_modal">
	<div class="overlay"></div>
	<div id="mybook">
		<!--https://github.com/builtbywill/booklet-->
		<?php

		$month_array = ['', 'Jan', 'Feb', 'Mar', 'Apr', 'Maj', 'Jun', 'Jul', 'Aug', 'Sep', 'Okt', 'Nov', 'Dec'];
		$date_now = new DateTime('NOW');
		$high_month = (int) $date_now->format('m');
		$low_month = $high_month - 4;
		if ($low_month <= 0) {
		}

		?>

		<div style="height:100%;width:100%;background: url('//files.<?= HTTP_HOST; ?>/graphics/magazines/forside_fun_facts_4.png') no-repeat 50%;">
			<h1 style="position: absolute;top:1.5em;left:1.5em;color:white;text-shadow:0 1px rgba(0,0,0,0.75), 0 2px rgba(0,0,0,0.5);">NH Vrinsk</h1>
		</div>
		<div>

			<div class="page_inner">

				<h3>Spillere pr måned</h3>

				<?php

				$date_now = new DateTime('NOW');
				$next_month = new DateTime('NOW');
				$date_now->sub(new DateInterval("P4M"));
				$next_month->sub(new DateInterval("P3M"));
				$min = $date_now->format('Y') . '-' . $date_now->format('m') . '-01 00:00:00';
				$max = $next_month->format('Y-m') . '-01 00:00:00';
				$players = $link_new->query("SELECT count(DISTINCT user_id) AS amount FROM `{$GLOBALS['DB_NAME_NEW']}`.user_data_sessions WHERE start > '{$min}' AND start < '{$max}' AND TIMEDIFF(end, start) <> '00:00:00'")->fetch_object()->amount;
				$duration = round((1 / 60 / 60) * $link_new->query("SELECT SUM(TIME_TO_SEC(TIMEDIFF(end, start))) AS duration FROM `{$GLOBALS['DB_NAME_NEW']}`.user_data_sessions WHERE start > '{$min}' and start < '{$max}'")->fetch_object()->duration);

				echo "<span style='width:35px;display:inline-block;'>{$date_now->format('M')}:</span> $players spillere, med i alt $duration timer online.<br /><br />";


				$date_now->add(new DateInterval("P1M"));
				$next_month->add(new DateInterval("P1M"));
				$min = $date_now->format('Y-m') . '-01 00:00:00';
				$max = $next_month->format('Y-m') . '-01 00:00:00';
				$players = $link_new->query("SELECT count(DISTINCT user_id) AS amount FROM `{$GLOBALS['DB_NAME_NEW']}`.user_data_sessions WHERE start > '{$min}' AND start < '{$max}' AND TIMEDIFF(end, start) <> '00:00:00'")->fetch_object()->amount;
				$duration = round((1 / 60 / 60) * $link_new->query("SELECT SUM(TIME_TO_SEC(TIMEDIFF(end, start))) AS duration FROM `{$GLOBALS['DB_NAME_NEW']}`.user_data_sessions WHERE start > '{$min}' and start < '{$max}'")->fetch_object()->duration);

				echo "<span style='width:35px;display:inline-block;'>{$date_now->format('M')}:</span> $players spillere, med i alt $duration timer online.<br /><br />";


				$date_now->add(new DateInterval("P1M"));
				$next_month->add(new DateInterval("P1M"));
				$min = $date_now->format('Y-m') . '-01 00:00:00';
				$max = $next_month->format('Y-m') . '-01 00:00:00';
				$players = $link_new->query("SELECT count(DISTINCT user_id) AS amount FROM `{$GLOBALS['DB_NAME_NEW']}`.user_data_sessions WHERE start > '{$min}' AND start < '{$max}' AND TIMEDIFF(end, start) <> '00:00:00'")->fetch_object()->amount;
				$duration = round((1 / 60 / 60) * $link_new->query("SELECT SUM(TIME_TO_SEC(TIMEDIFF(end, start))) AS duration FROM `{$GLOBALS['DB_NAME_NEW']}`.user_data_sessions WHERE start > '{$min}' and start < '{$max}'")->fetch_object()->duration);

				echo "<span style='width:35px;display:inline-block;'>{$date_now->format('M')}:</span> $players spillere, med i alt $duration timer online.<br /><br />";


				$date_now->add(new DateInterval("P1M"));
				$next_month->add(new DateInterval("P1M"));
				$min = $date_now->format('Y-m') . '-01 00:00:00';
				$max = $next_month->format('Y-m') . '-01 00:00:00';
				$players = $link_new->query("SELECT count(DISTINCT user_id) AS amount FROM `{$GLOBALS['DB_NAME_NEW']}`.user_data_sessions WHERE start > '{$min}' AND start < '{$max}' AND TIMEDIFF(end, start) <> '00:00:00'")->fetch_object()->amount;
				$duration = round((1 / 60 / 60) * $link_new->query("SELECT SUM(TIME_TO_SEC(TIMEDIFF(end, start))) AS duration FROM `{$GLOBALS['DB_NAME_NEW']}`.user_data_sessions WHERE start > '{$min}' and start < '{$max}'")->fetch_object()->duration);

				echo "<span style='width:35px;display:inline-block;'>{$date_now->format('M')}:</span> $players spillere, med i alt $duration timer online.<br /><br />";


				$date_now->add(new DateInterval("P1M"));
				$next_month->add(new DateInterval("P1M"));
				$min = $date_now->format('Y-m') . '-01 00:00:00';
				$max = $next_month->format('Y-m') . '-01 00:00:00';
				$players = $link_new->query("SELECT count(DISTINCT user_id) AS amount FROM `{$GLOBALS['DB_NAME_NEW']}`.user_data_sessions WHERE start > '{$min}' AND start < '{$max}' AND TIMEDIFF(end, start) <> '00:00:00'")->fetch_object()->amount;
				$duration = round((1 / 60 / 60) * $link_new->query("SELECT SUM(TIME_TO_SEC(TIMEDIFF(end, start))) AS duration FROM `{$GLOBALS['DB_NAME_NEW']}`.user_data_sessions WHERE start > '{$min}' and start < '{$max}'")->fetch_object()->duration);

				echo "<span style='width:35px;display:inline-block;'>{$date_now->format('M')}:</span> $players spillere, med i alt $duration timer online.<br /><br />";

				?>

				<?php

				$sql = "SELECT "
					. "count(id) AS total_alive,"
					. "sum(CASE WHEN kon = 'hingst' THEN 1 ELSE 0 END) AS stalions, "
					. "sum(CASE WHEN kon = 'hoppe' THEN 1 ELSE 0 END) AS horsies, "
					. "sum(CASE WHEN kon = 'hingst' AND status = 'føl' THEN 1 ELSE 0 END) AS stallionfoels, "
					. "sum(CASE WHEN kon = 'hoppe' AND status = 'føl' THEN 1 ELSE 0 END) AS horsiefoels, "
					. "sum(CASE WHEN kon = 'hoppe' AND status = 'avl' THEN 1 ELSE 0 END) AS foaling "
					. "FROM `{$GLOBALS['DB_NAME_OLD']}`.Heste WHERE status != 'død' LIMIT 1";
				$fun_facts['horses'] = $link_new->query($sql)->fetch_object();

				?>
				<br />
				<h3>Heste</h3>
				Hingste: <?= number_dotter($fun_facts['horses']->stalions); ?><br /><br />
				Hingsteføl: <?= number_dotter($fun_facts['horses']->stallionfoels); ?><br /><br />
				Hopper: <?= number_dotter($fun_facts['horses']->horsies); ?><br /><br />
				Hoppeføl: <?= number_dotter($fun_facts['horses']->horsiefoels); ?><br /><br />
				Ifolede hopper: <?= number_dotter($fun_facts['horses']->foaling); ?><br /><br />
				Heste i alt: <?= number_dotter($fun_facts['horses']->total_alive); ?><br /><br />
			</div>
		</div>
		<div>
			<div class="page_inner">

				<h3>Auktionshusets mest populære racer</h3>
				<?php
				$date_now = new DateTime('Now');
				$month_now = (string) $date_now->format('m');
				$year_now = (int) $date_now->format('Y');
				if ((int) $month_now = 1) {
					$month_last = '12';
					$year_last = $year_now - 1;
				} else if ((int) $month_now < 10) {
					$month_last = '0' . ((int) $month_last - 1);
				} else {
					$month_last = (string) ((int) $month_last - 1);
				}
				$auction_data = $link_new->query("SELECT count(bids.bid_date) AS amount, heste.race AS race " .
					"FROM `{$GLOBALS['DB_NAME_NEW']}`.game_data_auction_bids AS bids " .
					"LEFT JOIN `{$GLOBALS['DB_NAME_NEW']}`.game_data_auctions AS ah on ah.id = bids.auction " .
					"LEFT JOIN `{$GLOBALS['DB_NAME_OLD']}`.Heste AS heste on heste.id = ah.object_id " .
					"LEFT JOIN `{$GLOBALS['DB_NAME_OLD']}`.Brugere AS brugere on brugere.id = bids.creator " .
					"WHERE bids.bid_date > '{$year_last}-{$month_last}-01 00:00:00' " .
					"AND bids.bid_date < '{$year_now}-{$month_now}-01 00:00:00' " .
					"GROUP BY heste.race " .
					"ORDER BY amount DESC " .
					"LIMIT 5");

				while ($horses = $auction_data->fetch_object()) {
					echo "<span style='width:150px;display:inline-block;'>{$horses->race}</span><span style='width:100px;display:inline-block;text-align:right;'>{$horses->amount} bud</span><br /><br />";
				}

				?>

				<br />
				<h3>WKR statistikker</h3>

				<?php
				$result = $link_new->query("SELECT * FROM `{$GLOBALS['DB_NAME_OLD']}`.Brugere");
				$i = 0;
				$rige = 0;
				$wealthy = 0;
				$super_rich = 0;
				$whatwhat = 0;
				$richest_wkr = 0;
				$richest = '';
				$nyhedsbrev = 0;
				$stats = [];
				$total_wkr = 0;
				while ($data = $result->fetch_assoc()) {
					if (in_array(strtolower($data['stutteri']), ['net-hesten', 'dennistest', 'b', 'auktionshuset', 'carsten', 'hestehandleren', 'techhesten'])) {
						continue;
					}
					$total_wkr = (int) $total_wkr + (int) $data['penge'];
					if ($data['penge'] > $richest_wkr) {
						$richest_wkr = $data['penge'];
						$richest = $data['stutteri'];
					}

					if ($data['penge'] >= 100000000) {
						++$whatwhat;
					} elseif ($data['penge'] >= 25000000) {
						++$super_rich;
					} elseif ($data['penge'] >= 10000000) {
						++$rige;
					} elseif ($data['penge'] >= 1000000) {
						++$wealthy;
					}
					++$i;
				}
				?>
				<?= $whatwhat; ?> har mere end 100.000.000 wkr<br /><br />
				<?= $super_rich; ?> har mere end 25.000.000 wkr<br /><br />
				<?= $rige; ?> har mere end 10.000.000 wkr<br /><br />
				<?= $wealthy; ?> har mere end 1.000.000 wkr<br /><br />
				<?= $richest; ?> med <?= number_dotter($richest_wkr); ?> wkr har flest<br /><br />
				Alle spillere, har tilsammen <?= number_dotter($total_wkr); ?> wkr<br /><br />

			</div>
		</div>
		<div style="height:100%;width:100%;background: url('//files.<?= HTTP_HOST; ?>/graphics/magazines/forside_fun_facts_4.png') no-repeat;">
		</div>
	</div>
</div>
<style>
	#mybook,
	#mybook * {
		max-width: none !important;
	}

	#mybook {
		position: absolute;
		z-index: 2;
		top: 50%;
		left: 50%;
		transform: translateX(-50%) translateY(-50%);
		box-sizing: content-box !important;
	}

	#booklet_opener {
		position: absolute;
		right: 1rem;
		top: 1rem;
		transition: all 0.2s linear;
		opacity: 0.8;
		border: black 1px solid;
	}

	#booklet_opener:hover {
		opacity: 1;
	}

	#fun_fact_book_modal {
		transition: all 0.2s linear;
		display: block;
		position: fixed;
		top: 0;
		bottom: 0;
		right: 0;
		left: 0;
		transition: all 0.2s linear;
		opacity: 0;
		pointer-events: none;
	}

	#fun_fact_book_modal .overlay {
		position: absolute;
		z-index: 1;
		top: 0;
		left: 0;
		right: 0;
		bottom: 0;
		background: rgba(50, 50, 50, 0.8);
	}

	#fun_fact_book_modal.visible {
		opacity: 1;
		pointer-events: initial;
	}

	.page_inner h3 {
		font-weight: bold;
		font-size: 1.2em;
	}

	.page_inner h3 {
		line-height: 20px;
		margin-bottom: 10px;
	}

	.page_inner {
		-webkit-box-sizing: border-box;
		-moz-box-sizing: border-box;
		box-sizing: border-box;
		padding: 20px;
		font-size: 14px;
	}

	.booklet .b-page-cover {
		background: rgb(146, 186, 106);

	}

	.booklet .b-counter {
		background: rgb(146, 186, 106);
		color: white;
		line-height: 25px;
		height: 25px;
		padding: 0;
		font-size: 1em;
	}
</style>
<script>
	$(function() {
		//single book
		$('#mybook').booklet({
			closed: true,
			covers: true,
			width: 780,
			height: 520,
			pagePadding: 0,
			manual: false,
			overlays: true,
			hovers: false
		});

		$("#fun_fact_book_modal .overlay").click(function() {
			$("#fun_fact_book_modal").removeClass('visible');
		});

		$("#booklet_opener").click(function() {
			$("#fun_fact_book_modal").addClass('visible');
		});

	});
</script>