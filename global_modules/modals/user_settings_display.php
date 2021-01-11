<div id="user_settings_modal" class="modal">
	<div class="shadow"></div>
	<div class="content">
		<h2>Indstillinger</h2>
		<form action="" method="post">
			<input type="hidden" name="alter_user_settings" value="true" />
			<label class="fifty_p" for="liststyle">Liste visninger:</label>
			<select class="fifty_p" name="liststyle" id="liststyle">
				<option value="compact" <?= (($_SESSION['settings']['list_style'] ?? false) == 'compact' ? 'selected' : ''); ?>>Kompakt liste</option>
			</select>
			<label class="fifty_p" for="banner_size">Banner:</label>
			<select class="fifty_p" name="banner_size" id="banner_size">
				<option value="standard" <?= (($_SESSION['settings']['banner_size'] ?? false) == 'full_size' ? 'selected' : ''); ?>>Vis</option>
				<option value="hide" <?= (($_SESSION['settings']['banner_size'] ?? false) == 'hide' ? 'selected' : ''); ?>>Skjul</option>
			</select>
			<label class="fifty_p" for="display_width">Side størrelse:</label>
			<select class="fifty_p" name="display_width" id="display_width">
				<option value="standard" <?= (($_SESSION['settings']['display_width'] ?? false) == 'full' ? 'selected' : ''); ?>>Fuld bredde</option>
				<option value="slim" <?= (($_SESSION['settings']['display_width'] ?? false) == 'slim' ? 'selected' : ''); ?>>Smal visning</option>
			</select>
			<label class="fifty_p" for="left_menu_style">Venstre menu:</label>
			<select class="fifty_p" name="left_menu_style" id="left_menu_style">
				<option value="standard" <?= (($_SESSION['settings']['left_menu_style'] ?? false) == 'standard' ? 'selected' : ''); ?>>Stilistisk</option>
				<option value="old_school" <?= (($_SESSION['settings']['left_menu_style'] ?? false) == 'old_school' ? 'selected' : ''); ?>>Nostalgisk</option>
			</select>
			<label class="fifty_p" for="user_language">Vælg sprog:</label>
			<select class="fifty_p" name="user_language" id="user_language">
				<option value="da_DK" <?= (($_SESSION['settings']['user_language'] ?? false) == 'da_DK' ? 'selected' : ''); ?>>Dansk</option>
				<option value="en_US" <?= (($_SESSION['settings']['user_language'] ?? false) == 'en_US' ? 'selected' : ''); ?>>English</option>
			</select>
			<br />
			<h3 style="margin-bottom: 0.5em;">Notifikationer</h3>
			<div style="line-height: 20px;font-size:16px;">Vis Græsnings bekræftelser: <input style="height: 1em;" type="checkbox" name="graes_confirmations" <?= (($_SESSION['settings']['graes_confirmations'] ?? false)  == 'show' ? 'checked="checked"' : ''); ?> /></div>
			<div style="line-height: 20px;font-size:16px;">Vis bekræftelser i hestehandleren: <input style="height: 1em;" type="checkbox" name="horse_trader_buy_confirmations" <?= (($_SESSION['settings']['horse_trader_buy_confirmations'] ?? false) == 'show' ? 'checked="checked"' : ''); ?> /></div>
			<h3 style="margin-bottom: 0.5em;">Valg:</h3>
			<div style="line-height: 20px;font-size:16px;">Man må byde på alle mine heste: <input style="height: 1em;" type="checkbox" name="accept_offers" <?= (($_SESSION['settings']['accept_offers'] ?? false) == 'accept' ? 'checked="checked"' : ''); ?> /></div>
			<br />
			<input type="submit" class="btn btn-success" value="Gem" name="submit">
			<br />
			<br />
			<h3>Advancerede:</h3>
			<br />
			<input type="submit" class="btn btn-success" value="Tilsend kopi af persondata" name="send_personal_data">
			<?php if (is_array($_SESSION['rights']) && in_array('global_admin', $_SESSION['rights'])) { ?>
			<?php }
			?>
		</form>
	</div>
</div>
