<div id="breed_horse" class="modal">
	<script type="text/javascript">
		function breed_horse(caller) {
			jQuery('#breed_horse__horse_id').attr('value', jQuery(caller).parent().parent().attr('data-horse-id'));
			jQuery('#breed_horse__horse_id').val(jQuery(caller).parent().parent().attr('data-horse-id'));
			jQuery.get({
				url: "//ajax.<?= HTTP_HOST; ?>/index.php?request=suggest_breed_targets&horse_id=" + jQuery(caller).parent().parent().attr('data-horse-id'),
				cache: false
			}).then(function(data) {
				jQuery("#breed_targets_zone").html(data);
				jQuery("#breed_horse input[type='submit']").attr('disabled', 'disabled');
				jQuery('[data-type="potential_breed_target"]').each(function() {
					jQuery(this).click(function() {
						jQuery('#breed_horse__target_horse_id').attr('value', jQuery(this).attr('data-horse_id'));
						jQuery('#breed_horse__target_horse_id').val(jQuery(this).attr('data-horse_id'));
						jQuery('.marked_breed_target').removeClass('marked_breed_target');
						jQuery(this).addClass('marked_breed_target');
						jQuery(this).parent().parent().parent().find('input[type="submit"]').removeAttr('disabled');
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
		<h2>Vælg en hingst til din hoppe</h2>
		<div id="breed_targets_zone" style="font-size:16px;"></div>
		<form action="" method="post" style="position: relative;">
			<input type="hidden" name="action" value="breed_horse" />
			<input id="breed_horse__horse_id" type="hidden" name="horse_id" value="" />
			<input id="breed_horse__target_horse_id" type="hidden" name="target_horse_id" value="" />
			<p style="font-size:16px;line-height: 20px;margin-top:10px;">Der går 1 hesteår (~40 dage) før føllet kommet til verden. <br />Hoppen skal stå i foleboksen det meste af denne periode og kan derfor ikke deltage i stævner samtidig.</p>
			<p style="font-size:16px;line-height: 30px;">Det koster 7.500 wkr, at folet din hoppe.</p>
			<input style="position:absolute;bottom:0;right:0;" type="submit" disabled="disabled" class="btn btn-success" value="Start avl" name="submit">
		</form>
		<input id="find_id" type="text" placeholder="Find ID" value="" />
		<button class="btn btn-success fetch_id">Søg</button>
	</div>
	<script>
		jQuery('.fetch_id').click(function() {
			console.log('?');
			jQuery.get({
				url: "//ajax.<?= HTTP_HOST; ?>/index.php?request=suggest_breed_targets&find_id=" + jQuery("#find_id").val(),
				cache: false
			}).then(function(data) {
				jQuery("#breed_targets_zone").html(data);
				jQuery("#breed_horse input[type='submit']").attr('disabled', 'disabled');
				jQuery('[data-type="potential_breed_target"]').each(function() {
					jQuery(this).click(function() {
						jQuery('#breed_horse__target_horse_id').attr('value', jQuery(this).attr('data-horse_id'));
						jQuery('#breed_horse__target_horse_id').val(jQuery(this).attr('data-horse_id'));
						jQuery('.marked_breed_target').removeClass('marked_breed_target');
						jQuery(this).addClass('marked_breed_target');
						jQuery(this).parent().parent().parent().find('input[type="submit"]').removeAttr('disabled');
					});
				});
			});
		});
	</script>
</div>