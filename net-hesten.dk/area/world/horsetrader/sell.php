<?php
$basepath = '../../../..';
$title = 'Hestehandleren';
require "{$basepath}/app_core/object_loader.php";
require "{$basepath}/global_modules/header.php";
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
