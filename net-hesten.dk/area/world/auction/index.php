<?php
$basepath = '../../../..';
$title = 'Auktioner';
require "$basepath/app_core/object_loader.php";
require "$basepath/global_modules/header.php";

$your_auctions = [];
$all_auctions = [];
$horses_pr_page = (int) filter_input(INPUT_GET, 'horses_pr_page') ?: 45;

$your_horses_page = (int) filter_input(INPUT_GET, 'your_horses_page') ?: 0;
$your_horses_page = max($your_horses_page, 0);
$your_horses_page_offset = $your_horses_page * $horses_pr_page;

$other_auctions_page = (int) filter_input(INPUT_GET, 'other_auctions_page') ?: 0;
$other_auctions_page = max($other_auctions_page, 0);
$other_auctions_page_offset = $other_auctions_page * $horses_pr_page;


ob_start();
//$auctions_buy_filter_data = horse_list_filters::get_filter_string(['zone' => "auctions_buy"]);
foreach (auctions::get_all(['offset' => $other_auctions_page_offset, 'limit' => $horses_pr_page]) as $auction) {
	ob_clean();
	$remote_data = json_decode(horses::bridge_get($auction['object_id']));
?>
	<?php
	$gender = ((string) $remote_data->gender === 'Hoppe') ? 'female' : 'male';
	?>
	<div class="horse_square horse_object <?= $gender; ?>" data-horse-id="<?= $remote_data->id; ?>">
		<div class="info">
			<span class="name">
				<?= $remote_data->name; ?>
			</span>
			<i class='gender <?= "icon-{$gender}-1"; ?>'></i>
			<div class='horse_vcard'>
				<i class='icon-vcard'></i>
				<div class='extended_info'>
					<span class='type_age'><?= ($remote_data->unik == 'ja' ? '<span class="unique">Unik</span>' : ($remote_data->original == 'ja' ? '<span class="original">Original</span>' : '')); ?> <?= $remote_data->race; ?>: <?= $remote_data->age; ?> år</span>
					<span class='horse_id'>ID: <?= $remote_data->id; ?></span><br /><br />
					<span class='ability'>Sælger: <?= user::get_info(['user_id' => $auction['creator']])->username; ?></span><br />
					<!--<span class='ability'>Egenskab: <?= $remote_data->egenskab; ?></span><br />-->
					<?php if ($auction['instant_price'] >= $remote_data->value) { ?>
						<span class='disability'>Køb nu: <?= number_dotter($auction['instant_price']); ?></span>
					<?php } ?>
					<?php
					$max_bid = auctions::get_highest_bid(['auction_id' => $auction['id']]);
					if ((int) $max_bid['bid_amount'] > 0) {
					?>
						<span class='talent'>Højeste bud: <?= number_dotter($max_bid['bid_amount']); ?><span class="wkr_symbol">wkr</span></span>
					<?php
					} else {
					?>
						<span class='talent' style="opacity:0.7;">Mindste bud: <?= number_dotter(max($auction['minimum_price'], $remote_data->value, ($max_bid['bid_amount'] + 2500), ($max_bid['bid_amount'] * 1.01))); ?><span class="wkr_symbol">wkr</span></span>
					<?php
					}
					?>
					<span class='artist'>Tegner: <?= $remote_data->artist; ?></span>
					<span class='value hide_on_standard'>Værdi: <?= number_dotter($remote_data->value); ?> <span class="wkr_symbol">wkr</span></span>
				</div>
			</div>
			<span class='value hide_on_compact'>Værdi: <?= number_dotter($remote_data->value); ?> <span class="wkr_symbol">wkr</span></span>
			<?php
			$temp_compare_date = new DateTime('now');
			$temp_date_buffer = DateTime::createFromFormat('Y-m-d H:i:s', $auction['end_date']);
			if ($temp_date_buffer < $temp_compare_date) {
				$auction['end_date'] = 'Slut';
			} else if (date_diff($temp_date_buffer, $temp_compare_date, true)->format('%d') == 0) {
				$auction['end_date'] = date_diff($temp_date_buffer, $temp_compare_date, true)->format('%H Timer %i Minutter');
			} else {
				$auction['end_date'] = date_diff($temp_date_buffer, $temp_compare_date, true)->format('%d Dage %H Timer %i Minutter');
			}
			?>
			<span class='btn btn-info compact_top_button' style="left:initial;right:0;position: absolute;pointer-events:none;"><?= $auction['end_date']; ?></span>
			<?php if ($auction['creator'] != $_SESSION['user_id']) { ?>
				<button class='open_sell_window btn btn-success compact_bottom_button'>Byd</button>
			<?php } ?>
			<form action="/area/world/auction/?tab=other-auctions" class="action_popup buy_horse" style="height: 170px;height: 200px;" method="post">
				<?php /* code to place bid */ ?>
				<input type="hidden" name="action" value="bid_on_auction" />
				<input type="hidden" name="auction_id" value="<?= $auction['id']; ?>" />
				<div>Mindste bud:<br /><?= number_dotter(max($auction['minimum_price'], $remote_data->value, ($max_bid['bid_amount'] + 2500), ($max_bid['bid_amount'] * 1.01))); ?> <span class="wkr_symbol">wkr</span><br /><br /></div>
				<input type='text' class='raised' name='bid_amount' placeholder="Angiv dit bud" />
				<input type='submit' class='btn btn-success' name='place_bid' value='Byd' onclick="return confirm('Er du sikker på at du vil byde ' + jQuery(this).parent().find('[name=\'bid_amount\']').val() + ' wkr for denne hest?');" />
				<?php if ($auction['instant_price'] >= $remote_data->value) { ?>
					<input type='submit' class='btn btn-info' name='buy_now' onclick="return confirm('Er du sikker på at du vil \'købe nu\' for <?= $auction['instant_price']; ?> wkr?')" value="Køb nu" />
				<?php } ?>
				<button class='close_sell_window btn btn-danger'>Luk</button>
			</form>
		</div>
		<img src='//<?= filter_input(INPUT_SERVER,'HTTP_HOST');?>/<?= $remote_data->thumb; ?>' data-button-type='modal_activator' data-target='horze_extended_info' />
		<img style='display: none;' class='zoom_img' src='//<?= filter_input(INPUT_SERVER,'HTTP_HOST');?>/<?= $remote_data->thumb; ?>' />
	</div>
<?php
	$current_auction = ob_get_contents();
	$all_auctions[] = $current_auction;
}
ob_end_clean();
?>
<style>
	.tabs section {
		display: none;
	}

	.tabs section.visible {
		display: block;
	}
</style>
<section class="tabs">
	<nav>
		<ul style="padding: 1em 0;">
			<li class="btn btn-info" data-target="your-horses">Dine Heste</li>
			<li class="btn btn-info" data-target="your-bids">Dine Bud</li>
			<!--<li class="btn btn-info" data-target="your-auctions">Dine Auktioner</li>-->
			<li class="btn btn-info" data-target="other-auctions">Auktionshuset</li>
			<!--<li data-target=""><a href="http://m.net-hesten.dk/area/world/auction/?test_style=true">Prøv en anden visning</a></li>-->
			<a class="btn btn-info" href="/area/world/auction/">Opdater lister</a>
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
			$attr['custom_filter'] .= horse_list_filters::get_filter_string(['zone' => "auctions_sell"]);
			if (is_array(horses::get_all($attr))) {
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
								<form action="" method="post" class="action_popup buy_horse" style="height: 190px">
									<input type="hidden" name="action" value="put_on_auction" />
									<input type="hidden" name="horse_id" value="<?= $horse['id']; ?>" />
									<input type='text' class='raised' required='required' name='minimum_bid' placeholder="Minimum bud" />
									<input type='text' class='raised' name='buy_now_price' placeholder="ex. køb nu pris" />
									<input type="text" class='raised' name='sell_date' required="" placeholder="Vælg dato hesten sælges" id="auction_datepicker_<?= $horse['id']; ?>" />
									<input type='submit' class='btn btn-success' name='auction' value='Sælg' />
									<button class='close_sell_window btn btn-danger'>Luk</button>
									<script>
										jQuery("#auction_datepicker_<?= $horse['id']; ?>").datepicker({
											minDate: "+1D",
											maxDate: "+10D",
											dateFormat: 'yy-mm-dd'
										});
									</script>
								</form>
						</div>
						<img src='//<?= filter_input(INPUT_SERVER,'HTTP_HOST');?>/<?= $horse['thumb']; ?>' />
					</div>
			<?php
				}
			}
			?>
		</div>
	</section>
	<section data-zone="your-bids">
		<header>
			<h1 class="raised">Dine bud</h1>
		</header>
		<div>
			<style>
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
			</style>
			<ul>
				<?php
				$active_auction = 0;
				$status_traslation = ['10' => 'Placeret', '4' => 'accepteret', '5' => 'refunderet', '6' => 'vundet', '1' => 'igang', '2' => 'slut', '3' => 'slut'];
				if (is_array(auctions::list_bids())) {
					foreach (auctions::list_bids() as $bid) {
						if ($active_auction != $bid['auction']) {
							$active_auction = $bid['auction'];
						} else {
							continue;
						}
						if (in_array($bid['status_code'], [6, 5]) && in_array($bid['auction_status_code'], [2, 3])) {
							continue;
						}
						$remote_data = json_decode(horses::bridge_get($bid['horse_id']));
						$max_bid = auctions::get_highest_bid(['auction_id' => $bid['auction']]);
						$auction_data_one = auctions::get_one(['id' => $bid['auction']]);
						$your_bids_date_now = new DateTime('NOW');
						$auction_ended = false;
						if ($your_bids_date_now->format('Y-m-d H:i:s') > $auction_data_one[0]['end_date']) {
							$auction_ended = true;
						}
				?>
						<div class="horse_square <?= $gender; ?>">
							<div class="info">
								<span>ID: <?= $remote_data->id; ?></span>
								<span class='highest_bid'>Højeste bud: <?= number_dotter($max_bid['bid_amount']); ?></span>
								<span class='buy_now_price'>Dit bud:<?= ($bid['status_code'] != 5 ? number_dotter($bid['bid_amount']) . ' <span class="wkr_symbol">wkr</span>' : $status_traslation[(string) $bid['status_code']]); ?></span>
								<!--<span class='value'>Værdi: <?= number_dotter($remote_data->value); ?> <span class="wkr_symbol">wkr</span></span>-->
								<span class='value'>Slut dato: <?= (new DateTime($auction_data_one[0]['end_date']))->format('d/m/Y'); ?> </span>
							</div>
							<img src='//<?= filter_input(INPUT_SERVER,'HTTP_HOST');?>/<?= $remote_data->thumb; ?>' />
						</div>
				<?php
					}
				}
				?>
			</ul>
		</div>
	</section>
	<section data-zone="your-auctions">
		<div class="grid">
			<div data-section-type="info_square">
				<header>
					<h1>Dine auktioner</h1>
				</header>
				<div class="page_selector">
					<span class="btn">Side: <?= $other_auctions_page + 1; ?></span>&nbsp;<a class="btn btn-info" href="?other_auctions_page=<?= $other_auctions_page - 1; ?>&tab=other-auctions">Forrige side</a>&nbsp;<a class="btn btn-info" href="?other_auctions_page=<?= $other_auctions_page + 1; ?>&tab=other-auctions">Næste side</a>
				</div>
			</div>
			<?php
			foreach ($your_auctions as $auction) {
				echo $auction;
			}
			?>
		</div>
	</section>
	<section data-zone="other-auctions" class="visible">
		<div class="grid">
			<div data-section-type="info_square">
				<header>
					<h1>Auktionshuset</h1>
				</header>
				<div class="page_selector">
					<span class="btn">Side: <?= $other_auctions_page + 1; ?></span>&nbsp;<a class="btn btn-info" href="?other_auctions_page=<?= $other_auctions_page - 1; ?>&tab=other-auctions">Forrige side</a>&nbsp;<a class="btn btn-info" href="?other_auctions_page=<?= $other_auctions_page + 1; ?>&tab=other-auctions">Næste side</a>
				</div>
			</div>
			<?php
			foreach ($all_auctions as $auction) {
				echo $auction;
			}
			?>
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
		<?= horse_list_filters::render_filter_settings(['zone' => 'auctions_sell']); ?>
	</div>
</div>
<script type="text/javascript">
	<?php if (filter_input(INPUT_GET, 'tab') === 'other-auctions') { ?>
		jQuery(document).ready(function() {
			jQuery('[data-target="other-auctions"]').click();
		});
	<?php } ?>
	<?php if (filter_input(INPUT_GET, 'tab') === 'your-horses') { ?>
		jQuery(document).ready(function() {
			jQuery('[data-target="your-horses"]').click();
		});
	<?php } ?>

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
