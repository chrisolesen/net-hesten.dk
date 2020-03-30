<?php
$basepath = '../../../..';
$title = 'Hestehandleren';
require "$basepath/app_core/object_loader.php";
require "$basepath/global_modules/header.php";
?>
<section class="tabs">
	<nav>
		<ul>
		</ul>
	</nav>
	<section data-zone="all-horses">
		<div class="grid">
			<div data-section-type="info_square">
				<header>
					<h1>Hestehandleren - sælg dine heste</h1>
				</header>
				<a class="btn btn-info" href="/area/world/horsetrader/" style="line-height: 30px;">Køb</a>
				<a class="btn btn-info" style="line-height: 30px;" data-button-type='modal_activator' data-target='filter_horses'>Filtre</a>
			</div>
			<style>
				.nonconfirmation_form input {
					margin: 0 !important;
				}

				.nonconfirmation_form {
					background: transparent !important;
					padding: 0 !important;
					top: initial !important;
					border: initial !important;
					box-shadow: initial !important;
				}
			</style>
			<?php
			$custom_filter = '';
			$filtered = false;
			$custom_filter .= horse_list_filters::get_filter_string(['zone' => 'horse_trader_sell']);
			if ($custom_filter !== '') {
				$filtered = true;
			}

			$custom_filter .= " AND graesning != 'ja' AND staevne <> 'ja' AND status <> 'avl' AND status <> 'føl' AND kaaring <> 'ja' AND competition_id IS NULL ";


			if ($filtered) {
				$horses = horses::get_all(['user_name' => "{$_SESSION['username']}", 'limit' => '36', 'custom_filter' => $custom_filter]);
			} else {
				$horses = horses::get_all(['user_name' => "{$_SESSION['username']}", 'limit' => '12', 'noorder' => true]);
			}
			foreach ($horses as $horse) {
				/*
				if ($horse['competition_id'] || $horse['status'] == 'avl' || $horse['breed_date']) {
					continue;
				}


				$horse = (object) $horse;
				$gender = ((string) $horse->gender === 'Hoppe') ? 'female' : 'male';
			?>
				<div class="horse_square horse_object <?= $gender; ?>">
					<div class="info">
						<span class="name">
							<?= ($horse->unik == 'ja' ? '<span class="unique">Unik</span>' : ($horse->original == 'ja' ? '<span class="original">Original</span>' : '')); ?> <?= $horse->race; ?>, <?= $horse->age; ?> år:&nbsp;
							<?= $horse->name; ?>
						</span>
						<i class='gender <?= "icon-{$gender}-1"; ?>'></i>
						<div class='horse_vcard'>
							<i class='icon-vcard'></i>
							<div class='extended_info'>
								<span class='horse_id'>ID: <?= $horse->id; ?></span><br /><br />
								<span class='ability'>Egenskab: <?= $horse->egenskab; ?></span><br />
								<span class='disability'>Ulempe: <?= $horse->ulempe; ?></span><br />
								<span class='talent'>Talent: <?= $horse->talent; ?></span><br /><br />
								<span class='artist'>Tegner: <?= $horse->artist; ?></span>
								<span class='value hide_on_standard' style="transform: translateY(-18px);">Værdi: <?= number_dotter($horse->value); ?> <span class="wkr_symbol">wkr</span></span>
								<span class='value hide_on_standard'>Sælg for: <?= number_dotter($horse->value * 0.9); ?> <span class="wkr_symbol">wkr</span></span>
							</div>
						</div>
						<span class='value hide_on_compact'>Værdi: <?= number_dotter($horse->value); ?> wkr</span>
						<?php if ($_SESSION['settings']['horse_trader_buy_confirmations'] == 'show') { ?>
							<button class='open_sell_window btn btn-success compact_bottom_button'>Sælg</button>
							<form action="/area/world/horsetrader/sell.php" method="post" class="action_popup buy_horse">
								<?php /* code to place bid *//* ?>
								<input type="hidden" name="action" value="sell_horse_to_trader" />
								<input type="hidden" name="horse_id" value="<?= $horse->id; ?>" />
								<?php if (filter_input(INPUT_POST, 'race') && !empty(filter_input(INPUT_POST, 'race'))) { ?>
									<input type="hidden" name="race" value="<?= filter_input(INPUT_POST, 'race'); ?>" />
								<?php } ?>
								<?php if (filter_input(INPUT_POST, 'gender') && !empty(filter_input(INPUT_POST, 'gender'))) { ?>
									<input type="hidden" name="gender" value="<?= filter_input(INPUT_POST, 'gender'); ?>" />
								<?php } ?>
								<div>ID: <?= $horse->id; ?><br /><br /></div>
								<div>Du for: <?= number_dotter(($horse->value * 0.9)); ?> <span class="wkr_symbol">wkr</span><br /><br /></div>
								<input type='submit' class='btn btn-success' name='sell_now' value="Sælg nu" />
								<button class='close_sell_window btn btn-danger'>Luk</button>
							</form>
						<?php } else { ?>
							<form action="/area/world/horsetrader/sell.php" method="post" class="nonconfirmation_form visible open_sell_window btn btn-success compact_bottom_button">
								<?php if (filter_input(INPUT_POST, 'race') && !empty(filter_input(INPUT_POST, 'race'))) { ?>
									<input type="hidden" name="race" value="<?= filter_input(INPUT_POST, 'race'); ?>" />
								<?php } ?>
								<?php if (filter_input(INPUT_POST, 'gender') && !empty(filter_input(INPUT_POST, 'gender'))) { ?>
									<input type="hidden" name="gender" value="<?= filter_input(INPUT_POST, 'gender'); ?>" />
								<?php } ?>
								<input type="hidden" name="action" value="sell_horse_to_trader" />
								<input type="hidden" name="horse_id" value="<?= $horse->id; ?>" />
								<input type='submit' class='btn btn-success' name='sell_now' value="Sælg nu" />
							</form>
						<?php } ?>
					</div>
					<img src='//files.<?= HTTP_HOST; ?>/<?= $horse->thumb; ?>' />
					<img style='display: none;' class='zoom_img' src='//files.<?= HTTP_HOST; ?>/<?= $horse->thumb; ?>' />
				</div>
			<?php*/
				echo render_horse_object($horse, 'horse_trader_sell');
			}
			?>
		</div>
	</section>
</section>
<div id="filter_horses" class="modal">
	<script>
		function filter_horses(caller) {}
	</script>
	<style>
	</style>
	<div class="shadow"></div>
	<div class="content">
		<?= horse_list_filters::render_filter_settings(['zone' => 'horse_trader_sell']); ?>
	</div>
</div>
<script type="text/javascript">
	jQuery('[data-section-type="info_square"] select').change(function() {
		jQuery(this).parent().parent().submit();
	});

	jQuery('.horse_square .close_sell_window').each(function() {
		jQuery(this).click(function(e) {
			e.preventDefault();
			jQuery(this).parent().parent().parent().removeClass('visible');
			jQuery(this).parent().parent().find('form').removeClass('visible');
		});
	});
	jQuery('.horse_square .open_sell_window').each(function() {
		jQuery(this).click(function() {
			jQuery('.action_popup').removeClass('visible');
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
require_once "{$basepath}/global_modules/footer.php";
