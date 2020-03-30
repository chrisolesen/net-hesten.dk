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
	<section>
		<div class="grid">
			<div data-section-type="info_square">
				<header>
					<h1>Stævner og kårringer</h1>
				</header>
			</div>
			<?php

			$date_now = new DateTime('NOW');
			$sql = "SELECT * FROM `game_data_competitions` WHERE `status_code` = 32";
			$result = $link_new->query($sql);
			$date_now = new DateTime('NOW', new DateTimeZone('Europe/Copenhagen'));
			$i = 0;

			while ($data = $result->fetch_object()) {

				if ($data->end_date < $date_now->format('Y-m-d H:i:s')) {
					continue;
				}
				if ($data->start_date > $date_now->format('Y-m-d H:i:s')) {
					continue;
				}
				++$i;
				$number_of_signups = $link_new->query("SELECT count(participant_id) AS signups FROM `game_data_competition_participants` WHERE `competition_id` = {$data->id}")->fetch_object()->signups;

				if ($data->allowed_races) {
					$allowed_races = '';
					$races = $link_new->query("SELECT name FROM `horse_races` WHERE `id` IN ({$data->allowed_races})");
					while ($race = $races->fetch_object()) {
						$allowed_races .= $race->name . '<br />';
					}
				} else {

					$allowed_races = 'Alle';
				}
			?>
				<div data-section-type="competition">
					<h2><?= $data->name; ?></h2>
					<div class="competition_description">
						<?php if ($data->name == 'Følkåring') { ?>
							<img src="/style/graphics/foelkaaring.png" />
						<?php } else { ?>
							<img src="/style/graphics/goldmedal.png" />
						<?php } ?>
						<?php if ($data->name == 'Følkåring') { ?>
							Helhedsindtryk:<br /> 15.000 wkr<br /><br />
							Øvrige:<br /> 10.000 wkr<br /><br />
						<?php } else { ?>
							1. præmien:<br /> 50.000 wkr<br /><br />
							2. præmien:<br /> 25.000 wkr<br /><br />
							3. præmien:<br /> 10.000 wkr<br /><br />
						<?php } ?>

					</div>
					<?php if ($data->name == 'Følkåring') { ?>
						<div style="text-align: initial;">
							<h3>Kåringer gives for:</h3>Helhedsindtryk, Kropsbygning, Temperament og Gangart<br />
						</div>
					<?php } ?>
					<?php if ($data->name != 'Følkåring') { ?>
						<h3>Tilladte racer</h3>
						<div><?= $allowed_races; ?></div>
					<?php } ?>
					<span class="signed_up">Tilmeldte <?= $number_of_signups; ?></span>
					<?php if ($data->name != 'Følkåring') { ?>
						<button data-button-type='modal_activator' data-target='signup_horse' date-event-id="<?= $data->id; ?>" data-limits="races:<?= $data->allowed_races; ?>" class="btn btn-success signup">Tilmeld</button>
					<?php } else { ?>
						<button data-button-type='modal_activator' data-target='signup_horse' date-event-id="<?= $data->id; ?>" data-limits="only_foels" class="btn btn-success signup">Tilmeld</button>
					<?php } ?>
				</div>
			<?php
			}
			?>
		</div>
	</section>
</section>
<div id="signup_horse" class="modal">
	<script>
		function signup_horse(caller) {
			jQuery('#signup_horse__event_id').attr('value', jQuery(caller).attr('date-event-id'));


			jQuery.get({
				url: "//ajax.<?= HTTP_HOST; ?>/index.php?user_id=<?= $_SESSION['user_id']; ?>&request=suggest_event_participants&limits=" + jQuery(caller).attr('data-limits'),
				cache: false
			}).then(function(data) {
				jQuery("#signup_targets_zone").html(data);
				jQuery("#signup_horse input[type='submit']").attr('disabled', 'disabled');
				jQuery('[data-type="potential_breed_target"]').each(function() {
					jQuery(this).click(function() {
						jQuery('#signup_horse__target_horse_id').attr('value', jQuery(this).attr('data-horse_id'));
						jQuery('#signup_horse__target_horse_id').val(jQuery(this).attr('data-horse_id'));
						jQuery('.marked_breed_target').removeClass('marked_breed_target');
						jQuery(this).addClass('marked_breed_target');
						jQuery(this).parent().parent().parent().find('input[type="submit"]').removeAttr('disabled');
						document.event_auto_submit_target.submit();
					});
				});
			});
		}
	</script>
	<style>
		.marked_breed_target {
			box-shadow: 0 0 2px 2px rgba(0, 0, 0, 0.3);
		}

		input[type='submit'][disabled='disabled'] {
			opacity: 0.5;
		}
	</style>
	<div class="shadow"></div>
	<div class="content">
		<h2>Vælg en hest til konkurrence</h2>
		<div id="signup_targets_zone" style="font-size:16px;"></div>
		<form id="event_auto_submit_target" name="event_auto_submit_target" action="" method="post" style="position: relative;">
			<input type="hidden" name="action" value="signup_horse" />
			<input id="signup_horse__event_id" type="hidden" name="event_id" value="" />
			<input id="signup_horse__target_horse_id" type="hidden" name="target_horse_id" value="" />
			<p style="font-size:16px;line-height: 20px;margin-top:10px;">En vinder bliver fundet i morgen ~18:00. Din hest kan ikke stilles på græs eller ifoles, imens den er tilmeldt stævne eller en kåring.</p>
			<p style="font-size:16px;line-height: 30px;">Det koster ikke noget at tilmelde en hest.</p>
			<input style="position:absolute;bottom:0;right:0;" type="submit" disabled="disabled" class="btn btn-success" value="Tilmeld" name="submit_">
		</form>
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
