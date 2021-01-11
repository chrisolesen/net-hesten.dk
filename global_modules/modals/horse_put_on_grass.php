<div id="put_horse_on_grass" class="modal">
	<script type="text/javascript">
		function put_horse_on_grass(caller) {
			jQuery('#put_horse_on_grass__horse_id').attr('value', jQuery(caller).parent().parent().attr('data-horse-id'));
			jQuery('#put_horse_on_grass__horse_id').val(jQuery(caller).parent().parent().attr('data-horse-id'));
			<?php
			if ($_SESSION['settings']['graes_confirmations'] == 'hide') {
			?>
				jQuery('#put_horse_on_grass').removeClass('active');
				jQuery("#put_horse_on_grass_form").ajaxSubmit(function() {
					jQuery(caller).parent().parent().remove();
				});
			<?php
			}
			?>
		}
	</script>
	<div class="shadow"></div>
	<div class="content">
		<h2>Sæt på græs</h2>
		<p style="font-size: 14px;line-height: 1.5;">
			Hesten vil blive sat på græs. Du tjener 2 wkr for hvert minut din hest er på græs. Husk dog at sætte den tilbage i stalden inden 14 timer ellers bliver der trukket 500 wkr fra din konto og du mister optjeningen.
		</p>
		<form id="put_horse_on_grass_form" action="/area/stud/main/" method="post">
			<input type="hidden" name="action" value="put_horse_on_grass" />
			<input id="put_horse_on_grass__horse_id" type="hidden" name="horse_id" value="" />
			<input type="submit" class="btn btn-success" value="Sæt på græs" name="submit">
		</form>
	</div>
</div>
