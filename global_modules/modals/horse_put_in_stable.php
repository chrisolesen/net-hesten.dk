<div id="put_horse_in_stable" class="modal">
	<script>
		function put_horse_in_stable(caller) {
			jQuery('#put_horse_in_stable__horse_id').attr('value', jQuery(caller).parent().parent().attr('data-horse-id'));
			jQuery('#put_horse_in_stable__horse_id').val(jQuery(caller).parent().parent().attr('data-horse-id'));
			<?php
			if ($_SESSION['settings']['graes_confirmations'] == 'hide') {
			?>
				jQuery('#put_horse_in_stable').removeClass('active');
				jQuery("#put_horse_in_stable_form").ajaxSubmit(function() {
					jQuery(caller).parent().parent().remove();
				});
			<?php
			}
			?>
		}
	</script>
	<div class="shadow"></div>
	<div class="content">
		<h2>Sæt i stald</h2>
		<p style="font-size: 14px;line-height: 1.5;">
			Hesten vil hentet ind fra græs. Du tjener 2 wkr for hvert minut din hest har været på græs.
		</p>
		<form id="put_horse_in_stable_form" action="/area/stud/main/" method="post">
			<input type="hidden" name="action" value="put_horse_in_stable" />
			<input id="put_horse_in_stable__horse_id" type="hidden" name="horse_id" value="" />
			<input type="submit" class="btn btn-success" value="Sæt i stald" name="submit">
		</form>
	</div>
</div>
