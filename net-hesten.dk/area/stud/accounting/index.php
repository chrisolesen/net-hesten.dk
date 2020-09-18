<?php
/* Mit Stutteri */
$basepath = '../../../..';
$title = 'Konto oversigt';
require "{$basepath}/app_core/object_loader.php";
require "{$basepath}/global_modules/header.php";

$user_info = user::get_info(['user_id' => $_SESSION['user_id']]);
?>
<style>
	.tabs {
		margin-top: 1em;
	}

	[data-button-type="zone_activator"] {
		margin-bottom: 5px;
	}
</style>
<section class="tabs">
	<section data-zone="horses">
		<div class="grid">
			<div data-section-type="info_square">
				<header>
					<h1 style="float:left;margin-right:1em;">Konto oversigten</h1>
					<span style="font-weight:bold;">Penge: <?= number_dotter(accounting::get_account_total()); ?><span class="wkr_symbol dev_test_effect">wkr</span></span>
				</header>
			</div>
		</div>
		<?php
		$account_entries = accounting::fetch_entries();
		?>
		<style>
			#account_list td+td+td {
				color: black !important;
				text-align: left;
				padding: 0 3em 0 1em;
				text-shadow: none !important;
			}

			#account_list .negative td+td {
				/*color:red;*/
				text-shadow: 0 0 1px red;
			}

			#account_list .positive td+td {
				color: green;
			}

			body #account_list td+td+td+td,
			#account_list td+td {
				padding: 0 1em 0 3em;
				text-align: right;
			}

			#account_list li+li {
				margin-top: 1em;
			}

			th {
				font-size: 1.25em;
			}

			th,
			td {
				padding: 0 3em 0 1em;
				color: black;
				text-align: left;
				height: 50px;
				line-height: 50px;
				box-sizing: border-box;
			}

			tbody td.date_line {
				padding: 0;
				height: 3em;
				line-height: 3em;
				font-weight: bold;
				font-size: 1.25em;
				text-align: center;
				/*background: whitesmoke;*/
				margin-bottom: 0;
				display: table-cell;
			}

			tbody td {
				background-color: rgba(146, 186, 106, 0.5);
				position: relative;
			}

			tbody td:before {
				content: "";
				position: absolute;
				z-index: 2;
				top: 5px;
				left: 5px;
				bottom: 5px;
				right: 5px;
				pointer-events: none !important;
				border: 1px #dfebd3 solid
			}

			tbody td:after {
				content: "";
				position: absolute;
				z-index: 2;
				top: 9px;
				left: 9px;
				bottom: 9px;
				right: 9px;
				pointer-events: none !important;
				border: 2px #dfebd3 solid;
			}

			table {
				border-spacing: 0.5em;
				border-collapse: separate;
			}
		</style>
		<table id="account_list">
			<thead>
				<tr>
					<th>Klokken</th>
					<th>Beløb</th>
					<th>Beskrivelse</th>
					<th>Saldo</th>
				</tr>
			</thead>
			<tbody>
				<?php $date = new DateTime('now'); ?>
				<?php $date_test = (new DateTime('now'))->format('Y-m-d'); ?>
				<?php foreach ($account_entries as $account_line) { ?>
					<?php if ((new DateTime($account_line->date))->format('Y-m-d') != $date_test) { ?>
						<?php
						$date = (new DateTime($account_line->date));
						$date_test = $date->format('Y-m-d');
						?>
						<tr>
							<td data-section-type="info_square" class="date_line" colspan="4"><?= $date->format('d/m - Y'); ?></td>
						</tr>
					<?php } ?>
					<tr class="<?= (($account_line->meta->operator == '+') ? 'positive' : 'negative'); ?>">
						<td><?= (new DateTime($account_line->date))->format('H:i'); ?></td>
						<td><?= number_dotter(str_replace('-', '', $account_line->amount)); ?><span class="wkr_symbol dev_test_effect">wkr</span></td>
						<td><?= $account_line->meta->line_text; ?></td>
						<td><?= number_dotter($account_line->meta->line_total); ?><span class="wkr_symbol dev_test_effect">wkr</span></td>
					</tr>
				<?php } ?>
			</tbody>
		</table>
	</section>
</section>
<script>
	jQuery(document).ready(function() {
		<?php if (!isset($_GET['tab'])) { ?>
			jQuery('[data-target="idle_horses"]').click();
		<?php } else {
		?>
			jQuery('[data-target="<?= $_GET['tab']; ?>"]').click();
		<?php }
		?>
	});
	jQuery('[data-button-type="zone_activator"]').each(function() {
		jQuery(this).click(function() {
			jQuery('.tabs > section').removeClass('visible');
			jQuery('.tabs > section[data-zone="' + jQuery(this).attr('data-target') + '"]').addClass('visible');
		});
	});
</script>
<?php
/* Define modal - start */
ob_start();
?>
<style>
	.fifty_p {
		/*display: inline-block;*/
		width: 50%;
		float: left;
		line-height: 25px;
		margin-bottom: 5px;
	}
</style>
<div id="horze_extended_info" class="modal">
	<script>
		function horze_extended_info(caller) {
			horse_data = JSON.parse(jQuery(caller).parent().attr('data-extended-info'));
			jQuery('#horze_extended_info .name').html(horse_data.name);
			jQuery('#horze_extended_info .id').html(horse_data.id);
			jQuery('#horze_extended_info .age').html(horse_data.age);
			jQuery('#horze_extended_info .gender').html(horse_data.gender);
			jQuery('#horze_extended_info .race').html(horse_data.race);
			jQuery('#horze_extended_info .artist').html(horse_data.artist);
			jQuery('#horze_extended_info .value').html(horse_data.value);
			jQuery('#horze_extended_info .owner_name').html(horse_data.owner_name);
			jQuery('#horze_extended_info .talent').html(horse_data.talent);
			jQuery('#horze_extended_info .ulempe').html(horse_data.ulempe);
			jQuery('#horze_extended_info .egenskab').html(horse_data.egenskab);
			jQuery('#horze_extended_info .type').html(horse_data.type);
			jQuery('#horze_extended_info .gold_medal').html(horse_data.gold_medal);
			jQuery('#horze_extended_info .silver_medal').html(horse_data.silver_medal);
			jQuery('#horze_extended_info .bronze_medal').html(horse_data.bronze_medal);
			jQuery('#horze_extended_info .junior_medal').html(horse_data.junior_medal);
		}
	</script>
	<style>
		#horze_extended_info div {
			line-height: 25px;
		}

		#horze_extended_info span {
			font-family: 'Merienda One', cursive;
		}
	</style>
	<div class="shadow"></div>
	<div class="content">
		<div style="position:absolute;top:6px;right:10px;" onclick="jQuery(this).parent().parent().removeClass('active');"><i class="fa fa-times fa-2x nh-error-color"></i></div>
		<h2>Mere om: <span class="name"></span> <span class="age"></span> år</h2>
		<div>
			<span class="label">ID:</span> <span class="id"></span>
		</div>
		<div>
			<span class="label">Køn:</span> <span class="gender"></span>
		</div>
		<div>
			<span class="label">Race:</span> <span class="race"></span>
		</div>
		<div>
			<span class="label">Tegner:</span> <span class="artist"></span>
		</div>
		<div>
			<span class="label">Værdi:</span> <span class="value"></span>
		</div>
		<div>
			<span class="label">Ejer:</span> <span class="owner_name"></span>
		</div>
		<div>
			<span class="label">Talent:</span> <span class="talent"></span>
		</div>
		<div>
			<span class="label">Ulempe:</span> <span class="ulempe"></span>
		</div>
		<div>
			<span class="label">Egenskab:</span> <span class="egenskab"></span>
		</div>
		<div>
			<span class="label">Type:</span> <span class="type"></span>
		</div>
		<div>
			<span class="label">Guld:</span> <span class="gold_medal"></span>
		</div>
		<div>
			<span class="label">Sølv:</span> <span class="silver_medal"></span>
		</div>
		<div>
			<span class="label">Bronze:</span> <span class="bronze_medal"></span>
		</div>
		<div>
			<span class="label">Føl kåringer:</span> <span class="junior_medal"></span>
		</div>
		<div>
			<span class="label">Udstyr:</span> <span class="">Kommer snart!</span>
		</div>
		<div>
			<span class="label">Stamtavle:</span> <span class="">Kommer snart!</span>
		</div>
		<div>
			<span class="label">Opdrætter:</span> <span class="">Kommer snart!</span>
		</div>
	</div>
</div>
<?php ?>
<script>
	jQuery(".horse_object .info .name").click(function() {
		if (jQuery(this).attr('data-object-edit-state') == 'open') {} else {
			if (jQuery(this).attr('data-object-edit-state') != 'animating') {
				old_name = jQuery(this).html();
				jQuery(this).prepend('<i class="fa fa-check"></i><input type="text" class="horse_rename_input" value="' + old_name + '" />');
				jQuery(this).attr('data-object-edit-state', 'open');
				jQuery(this).find('i.fa-check').click(function() {
					horse_id = jQuery(this).parent().parent().parent().attr('data-horse-id');
					new_name = jQuery(this).parent().find('input.horse_rename_input').val();
					jQuery.get({
						dataType: 'jsonp',
						data: {
							'new_name': new_name,
							'request': 'save_horse_name',
							'horse_id': horse_id
						},
						crossDomain: true,
						url: "//ajax.<?= HTTP_HOST; ?>/index.php",
						cache: false
					});
					jQuery(this).parent().attr('data-object-edit-state', 'animating');
					jQuery(this).parent().html(new_name);
				});
			} else {
				jQuery(this).attr('data-object-edit-state', 'close');
			}
		}
	});
</script>
<style>
	.horse_object .info .name {
		z-index: 5;
		cursor: pointer;
	}

	.horse_object .info .name input.horse_rename_input {
		position: absolute;
		top: 3px;
		width: 200px;
		max-width: none;
	}
</style>
<?php ?>
<?php
$modals[] = ob_get_contents();
ob_end_clean();
/* Define modal - end */
?>
<?php
require_once("{$basepath}/global_modules/footer.php");
