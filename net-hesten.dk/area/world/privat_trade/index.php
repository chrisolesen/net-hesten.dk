<?php
/* REVIEW: SQL Queries */
$basepath = '../../../..';
$title = 'Privat handel';
require "{$basepath}/app_core/object_loader.php";
require "{$basepath}/global_modules/header.php";

user::register_timing(['user_id' => $_SESSION['user_id'], 'key' => 'checked_private_trade']); 

$horses_pr_page = (int) filter_input(INPUT_GET, 'horses_pr_page') ?: 45;

$your_horses_page = (int) filter_input(INPUT_GET, 'your_horses_page') ?: 0;
$your_horses_page = max($your_horses_page, 0);
$your_horses_page_offset = $your_horses_page * $horses_pr_page;

?>
<style>
	.tabs section {
		display: none;
	}

	.tabs section.visible {
		display: block;
	}

	.bids_line {
		position: relative;
		padding: 5px;
		margin: 2px 0;
	}

	.bids_line img {
		position: absolute;
		bottom: 0;
		left: 0;
		display: none;
	}

	.bids_line:hover img {
		display: block;
	}

	body .horse_object .compact_bottom_button {
		opacity: 1 !important;
		display: block !important;
	}

	body .horse_object form.compact_bottom_button input,
	body .horse_object form.compact_top_button input {
		margin-bottom: 0;
	}

	body .horse_object form.compact_bottom_button,
	body .horse_object form.compact_top_button {
		top: initial;
		background: none !important;
		padding: 0;
		margin: 0;
	}

	body .horse_object form.compact_top_button {
		bottom: 57px !important;
	}

	body .horse_object .compact_top_button {
		opacity: 1 !important;
		display: block !important;
	}
</style>
<section class="tabs">
	<nav>
		<ul style="padding: 1em 0;">
			<li class="btn btn-info" data-target="your-horses">Dine Heste</li>
			<li class="btn btn-info" data-target="your-trades">Handler</li>
		</ul>
	</nav>
	<section data-zone="your-horses">
		<div class="grid">
			<div data-section-type="info_square">
				<header>
					<h1>Dine heste</h1>
				</header>
				<div class="page_selector">
					<span class="btn">Side: <?= $your_horses_page + 1; ?></span>&nbsp;<a class="btn btn-info" href="?your_horses_page=<?= $your_horses_page - 1; ?>&tab=your-horses">Forrige side</a>&nbsp;<a class="btn btn-info" href="?your_horses_page=<?= $your_horses_page + 1; ?>&tab=your-horses">Næste side</a>
				</div>
				<a class="btn btn-info" style="line-height: 30px;" data-button-type='modal_activator' data-target='filter_your_horses'>Filtre</a>
			</div>
			<?php
			$attr = ['user_name' => $_SESSION['username'], 'offset' => $your_horses_page_offset, 'limit' => $horses_pr_page];
			if ($filter_id = filter_input(INPUT_POST, 'id_filter')) {
				$attr = ['user_name' => $_SESSION['username'], 'id_filter' => $filter_id];
			}
			$attr['custom_filter'] = horse_list_filters::get_filter_string(['zone' => "private_trade_sell"]);
			if (!empty(horses::get_all($attr))) {
				foreach (horses::get_all($attr) as $horse) {
					$gender = ((string) strtolower($horse['gender']) === 'hoppe') ? 'female' : '';
					$gender = ((string) strtolower($horse['gender']) === 'hingst') ? 'male' : $gender;
					$gender = ((string) $gender === '') ? 'error' : $gender;
			?>
					<div class="horse_square horse_object <?= $gender; ?>">
						<div class="info">
							<span class="name"><?= $horse['name']; ?></span>
							<i class='gender <?= "icon-{$gender}-1"; ?>'></i>
							<div class='horse_vcard'>
								<i class='icon-vcard'></i>
								<div class='extended_info'>
									<span class='type_age'><?= ($horse['unik'] == 'ja' ? '<span class="unique">Unik</span>' : ($horse['original'] == 'ja' ? '<span class="original">Original</span>' : '')); ?> <?= $horse['race']; ?>: <?= $horse['age']; ?> år</span>
									<span class='horse_id'>ID: <?= $horse['id']; ?></span><br /><br />
									<span class='ability'>Egenskab: <?= $horse['egenskab']; ?></span><br />
									<span class='disability'>Ulempe: <?= $horse['ulempe']; ?></span><br />
									<span class='talent'>Talent: <?= $horse['talent']; ?></span><br /><br />
									<span class='artist'>Tegner: <?= $horse['artist']; ?></span>
									<span class='value hide_on_standard'>Værdi: <?= number_dotter($horse['value']); ?> <span class="wkr_symbol">wkr</span></span>
								</div>
							</div>
							<span class='value hide_on_compact''>Værdi: <?= number_dotter($horse['value']); ?> <span class="wkr_symbol">wkr</span></span>
						<button class=' open_sell_window btn btn-success compact_bottom_button'>Sælg</button>
								<form action="" method="post" class="action_popup buy_horse" style="height: 190px;z-index:3;">
									<input type="hidden" name="action" value="offer_privat_trade" />
									<input type="hidden" name="horse_id" value="<?= $horse['id']; ?>" />
									<input type='text' class='raised' required='required' name='price' placeholder="Pris" />
									<input type="text" class='raised' name='recipient' placeholder="Modtager" list="active_usernames" required="required" />
									<input type='submit' class='btn btn-success' name='trade' value='Tilbyd' />
									<button class='close_sell_window btn btn-danger'>Luk</button>
								</form>
						</div>
						<img src='//files.<?= HTTP_HOST; ?>/<?= $horse['thumb']; ?>' />
					</div>
			<?php

				}
			}
			?>
		</div>
	</section>
	<section data-zone="your-trades">
		<div data-section-type="info_square">
			<header>
				<h1>Handler</h1>
			</header>
			<div class="page_selector">
				<span class="btn">Side: <?= $your_horses_page + 1; ?></span>&nbsp;<a class="btn btn-info" href="?your_horses_page=<?= $your_horses_page - 1; ?>&tab=your-trades">Forrige side</a>&nbsp;<a class="btn btn-info" href="?your_horses_page=<?= $your_horses_page + 1; ?>&tab=your-trades">Næste side</a>
			</div>
			<a class="btn btn-info" style="line-height: 30px;" data-button-type='modal_activator' data-target='filter_your_horses'>Filtre</a>
		</div>
		<div>
			<ul>
				<?php 
				$attr = ['user_name' => $_SESSION['username'], 'user_id' => $_SESSION['user_id']];
				if (!empty(private_trade::list_trade_offers($attr))) {
					foreach (private_trade::list_trade_offers($attr) as $trade) {
						$trade_id = $trade['id'];
						$buyer = $link_new->query("SELECT stutteri FROM `{$GLOBALS['DB_NAME_OLD']}`.`Brugere` WHERE id = {$trade['buyer']}")->fetch_object()->stutteri;
						$horse = $trade['horse_data'];
						$gender = ((string) strtolower($horse['gender']) === 'hoppe') ? 'female' : '';
						$gender = ((string) strtolower($horse['gender']) === 'hingst') ? 'male' : $gender;
						$gender = ((string) $gender === '') ? 'error' : $gender;
				?>
						<div class="horse_square horse_object <?= $gender; ?>" style="z-index:3;">
							<div class="info">
								<span class="name"><?= $horse['name']; ?></span>
								<i class='gender <?= "icon-{$gender}-1"; ?>'></i>
								<div class='horse_vcard'>
									<i class='icon-vcard'></i>
									<div class='extended_info'>
										<span class='type_age'><?= ($horse['unik'] == 'ja' ? '<span class="unique">Unik</span>' : ($horse['original'] == 'ja' ? '<span class="original">Original</span>' : '')); ?> <?= $horse['race']; ?>: <?= $horse['age']; ?> år</span>
										<span class='horse_id'>ID: <?= $horse['id']; ?></span><br /><br />
										<span class='ability'>Køber: <?= $buyer; ?></span><br />
										<span class='talent'>Pris: <?= number_dotter($trade['price']); ?> <span class="wkr_symbol">wkr</span></span><br /><br />
										<span class='artist'>Tegner: <?= $horse['artist']; ?></span>
										<span class='value hide_on_standard'>Værdi: <?= number_dotter($horse['value']); ?> <span class="wkr_symbol">wkr</span></span>
									</div>
								</div>
								<span class='value hide_on_compact'>Værdi: <?= number_dotter($horse['value']); ?> <span class="wkr_symbol">wkr</span></span>
								<?php if ($trade['buyer'] == $_SESSION['user_id'] && $trade['status_code'] == 44) { ?>
									<form action="" method="post" class="compact_top_button">
										<input type="hidden" name="action" value="reject_privat_trade" />
										<input type="hidden" name="trade_id" value="<?= $trade_id; ?>" />
										<input type='submit' class='btn btn-danger' name='reject' value='Annuller Anmodning' />
									</form>
								<?php } ?>
								<?php if ($trade['buyer'] == $_SESSION['user_id'] && $trade['status_code'] == 38) { ?>
									<form action="" method="post" class="compact_top_button">
										<input type="hidden" name="action" value="reject_privat_trade" />
										<input type="hidden" name="trade_id" value="<?= $trade_id; ?>" />
										<input type='submit' class='btn btn-danger' name='reject' value='Afvis Tilbud' />
									</form>
									<form action="" method="post" class="compact_bottom_button">
										<input type="hidden" name="action" value="accept_privat_trade" />
										<input type="hidden" name="trade_id" value="<?= $trade_id; ?>" />
										<input type='submit' class='btn btn-success' name='accept' value='Accepter Tilbud' />
									</form>
								<?php } ?>
								<?php if ($trade['seller'] == $_SESSION['user_id'] && $trade['status_code'] == 44) { ?>
									<form action="" method="post" class="compact_top_button">
										<input type="hidden" name="action" value="reject_privat_trade" />
										<input type="hidden" name="trade_id" value="<?= $trade_id; ?>" />
										<input type='submit' class='btn btn-danger' name='reject' value='Afvis Anmodning' />
									</form>
									<form action="" method="post" class="compact_bottom_button">
										<input type="hidden" name="action" value="accept_privat_trade" />
										<input type="hidden" name="trade_id" value="<?= $trade_id; ?>" />
										<input type='submit' class='btn btn-success' name='accept' value='Accepter Anmodning' />
									</form>
								<?php } ?>
								<?php if ($trade['seller'] == $_SESSION['user_id'] && $trade['status_code'] == 38) { ?>
									<form action="" method="post" class="compact_top_button">
										<input type="hidden" name="action" value="reject_privat_trade" />
										<input type="hidden" name="trade_id" value="<?= $trade_id; ?>" />
										<input type='submit' class='btn btn-danger' name='reject' value='Annuller Tilbud' />
									</form>
								<?php } ?>
							</div>
							<img src='//files.<?= HTTP_HOST; ?>/<?= $horse['thumb']; ?>' />
						</div>
				<?php
					}
				}
				?>
			</ul>
		</div>
	</section>
</section>
<div id="filter_your_horses" class="modal">
	<script>
		function filter_your_horses(caller) {}
	</script>
	<style>
	</style>
	<div class="shadow"></div>
	<div class="content">
		<?= horse_list_filters::render_filter_settings(['zone' => 'private_trade_sell']); ?>
	</div>
</div>
<script type="text/javascript">
	<?php if (!empty(private_trade::list_trade_offers($attr))) {	?>
		jQuery(document).ready(function() {
			jQuery('[data-zone="your-trades"]').addClass('visible');
		});
	<?php	} else {	?>
		jQuery(document).ready(function() {
			jQuery('[data-zone="your-horses"]').addClass('visible');
		});
	<?php	}	?>
	<?php if (filter_input(INPUT_GET, 'tab') === 'your-horses') { ?>
		jQuery(document).ready(function() {
			jQuery('[data-target="your-horses"]').click();
		});
	<?php	}	?>

	jQuery('.horse_square .close_sell_window').each(function() {
		jQuery(this).click(function(e) {
			e.preventDefault();
			jQuery(this).parent().parent().parent().removeClass('visible');
			jQuery(this).parent().parent().find('form').removeClass('visible');
		});
	});
	jQuery('.horse_square .open_sell_window').each(function() {
		jQuery(this).click(function() {
			jQuery(this).parent().parent().addClass('visible');
			jQuery(this).parent().find('form').addClass('visible').mouseleave(function() {
				//				jQuery(this).removeClass('visible');
			});
		});
	});
	jQuery('.tabs nav li').each(function() {
		jQuery(this).click(function() {
			jQuery('.tabs > section').removeClass('visible');
			jQuery('.tabs > section[data-zone="' + jQuery(this).attr('data-target') + '"]').addClass('visible');
		});
	});

	// iOS Hover Event Class Fix
	if ((navigator.userAgent.match(/iPhone/i)) || (navigator.userAgent.match(/iPod/i)) || (navigator.userAgent.match(/iPad/i))) {
		$(".horse_square").click(function() {
			// Update '.change-this-class' to the class of your menu
			// Leave this empty, that's the magic sauce
		});
	}
	if ((navigator.userAgent.match(/iPhone/i)) || (navigator.userAgent.match(/iPod/i)) || (navigator.userAgent.match(/iPad/i))) {
		$(".icon-vcard").click(function() {
			// Update '.change-this-class' to the class of your menu
			// Leave this empty, that's the magic sauce
		});
	}
</script>
<?php
require_once("{$basepath}/global_modules/footer.php");
