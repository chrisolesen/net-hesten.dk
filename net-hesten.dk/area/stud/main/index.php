<?php
/* Mit Stutteri */
$basepath = '../../../..';
$title = 'Stutteri';
require "$basepath/app_core/object_loader.php";
require "$basepath/global_modules/header.php";

function find_next_user_filename($username)
{
	global $basepath;
	if ($handle = opendir("$basepath/files.<?= HTTP_HOST; ?>/users/")) {
		$found = false;
		$num_dirs = 0;
		while ($found != true) {
			++$num_dirs;
			$target_dir = str_replace(["/", "="], [""], base64_encode($num_dirs));
			if (!is_dir("$basepath/files.<?= HTTP_HOST; ?>/users/" . $target_dir)) {
				mkdir("$basepath/files.<?= HTTP_HOST; ?>/users/" . $target_dir);
			}
			if (is_dir("$basepath/files.<?= HTTP_HOST; ?>/users/" . $target_dir)) {
				$num_files = 1;
				while ($num_files <= 250) {
					++$num_files;
					if (is_file("$basepath/files.<?= HTTP_HOST; ?>/users/" . $target_dir . '/' . str_replace(["/", "="], [""], base64_encode($username)) . str_replace(["/", "="], [""], base64_encode($num_files)) . '.png')) {
						continue;
					} else if (is_file("$basepath/files.<?= HTTP_HOST; ?>/users/" . $target_dir . '/' . str_replace(["/", "="], [""], base64_encode($username)) . str_replace(["/", "="], [""], base64_encode($num_files)) . '.jpg')) {
						continue;
					} else if (is_file("$basepath/files.<?= HTTP_HOST; ?>/users/" . $target_dir . '/' . str_replace(["/", "="], [""], base64_encode($username)) . str_replace(["/", "="], [""], base64_encode($num_files)) . '.gif')) {
						continue;
					} else {
						return "$basepath/files.<?= HTTP_HOST; ?>/users/{$target_dir}/" . str_replace(["/", "="], [""], base64_encode($username)) . str_replace(["/", "="], [""], base64_encode($num_files));
					}
				}
			}
		}
	}
}

$user_info = user::get_info(['user_id' => $_SESSION['user_id']]);
if (isset($_FILES['fileToUpload']) && empty($_POST['new_password']) && $_POST['your_name'] == $user_info->name && !isset($_POST['remove_user_thumbnail'])) {
	$target_file = find_next_user_filename($_SESSION['username']);
	$uploadOk = 1;
	$imageFileType = pathinfo(basename($_FILES["fileToUpload"]["name"]), PATHINFO_EXTENSION);
	// Check if image file is a actual image or fake image
	if (isset($_POST["submit"])) {
		if ($_FILES["fileToUpload"]["tmp_name"] !== '') {
			$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
		} else {
			$check = false;
		}
		if ($check !== false) {
			//			echo "File is an image - " . $check["mime"] . ".";
			$uploadOk = 1;
		} else {
			//			echo "File is not an image.";
			$uploadOk = 0;
		}
	}
	// Check if file already exists
	if (file_exists($target_file . '.' . $imageFileType)) {
		echo "Sorry, file already exists.";
		$uploadOk = 0;
	}
	// Check file size
	if ($_FILES["fileToUpload"]["size"] > 250000) {
		echo "Beklager, din fil er for stor, den må maksimalt være 250kb.";
		$uploadOk = 0;
	}
	// Allow certain file formats
	if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
		echo "Beklager, det er kun JPG, JPEG, PNG & GIF filer som er tilladt. Den her fil er identificeret som {$check["mime"]}.";
		$uploadOk = 0;
	}
	// Check if $uploadOk is set to 0 by an error
	if ($uploadOk == 0) {
		/* echo "Sorry, your file was not uploaded."; */
		// if everything is ok, try to upload file
	} else {
		if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file . '.' . $imageFileType)) {
			//			echo "The file " . basename($_FILES["fileToUpload"]["name"]) . " has been uploaded. As {$target_file}.{$imageFileType}";
			$file_path = str_replace("$basepath/files.<?= HTTP_HOST; ?>/users/", '', "{$target_file}.{$imageFileType}");
			$sql = "UPDATE Brugere SET thumb = '{$file_path}' WHERE id = {$_SESSION['user_id']}";
			$link_new->query($sql);
		} else {
			echo "Beklager, der skete er sket en uventet fejl, prøv igen lidt senere, eller kontakt stutteri TechHesten.";
		}
	}
}
if (isset($_POST['remove_user_thumbnail']) && empty($_POST['new_password']) && $_POST['your_name'] == $user_info->name) {
	$sql = "UPDATE Brugere SET thumb = '' WHERE id = {$_SESSION['user_id']}";
	$link_new->query($sql);
}
/* Change list-style */
if (isset($_POST['liststyle'])) {
	if ($_POST['liststyle'] == 'compact') {
		$new_value = 'compact';
	} else {
		$new_value = 'standard';
	}
	$sql = "UPDATE user_data_varchar SET value = '{$new_value}', date = NOW() WHERE parent_id = {$_SESSION['user_id']} AND name = 'list_style'";
	$link_new->query($sql);
	$_SESSION['settings']['list_style'] = $new_value;
}
if (isset($_POST['accept_offers'])) {
	if ($_POST['accept_offers'] == 'accept') {
		$new_value = 'accept';
	} else {
		$new_value = 'reject';
	}
	$sql = "UPDATE user_data_varchar SET value = '{$new_value}', date = NOW() WHERE parent_id = {$_SESSION['user_id']} AND name = 'accept_offers'";
	$link_new->query($sql);
	$_SESSION['settings']['accept_offers'] = $new_value;
}
if (isset($_POST['banner_size'])) {
	if ($_POST['banner_size'] == 'hide') {
		$new_value = 'hide';
	} else {
		$new_value = 'standard';
	}
	$sql = "UPDATE user_data_varchar SET value = '{$new_value}', date = NOW() WHERE parent_id = {$_SESSION['user_id']} AND name = 'banner_size'";
	$link_new->query($sql);
	$_SESSION['settings']['banner_size'] = $new_value;
}
if (isset($_POST['display_width'])) {
	if ($_POST['display_width'] == 'slim') {
		$new_value = 'slim';
	} else {
		$new_value = 'full';
	}
	$sql = "UPDATE user_data_varchar SET value = '{$new_value}', date = NOW() WHERE parent_id = {$_SESSION['user_id']} AND name = 'display_width'";
	$link_new->query($sql);
	$_SESSION['settings']['display_width'] = $new_value;
}
if (isset($_POST['alter_user_settings'])) {
	if (isset($_POST['graes_confirmations'])) {
		$new_value = 'show';
	} else {
		$new_value = 'hide';
	}
	$sql = "UPDATE user_data_varchar SET value = '{$new_value}', date = NOW() WHERE parent_id = {$_SESSION['user_id']} AND name = 'graes_confirmations'";
	$link_new->query($sql);
	$_SESSION['settings']['graes_confirmations'] = $new_value;

	if (isset($_POST['horse_trader_buy_confirmations'])) {
		$new_value = 'show';
	} else {
		$new_value = 'hide';
	}
	$sql = "UPDATE user_data_varchar SET value = '{$new_value}', date = NOW() WHERE parent_id = {$_SESSION['user_id']} AND name = 'horse_trader_buy_confirmations'";
	$link_new->query($sql);
	$_SESSION['settings']['horse_trader_buy_confirmations'] = $new_value;


	if (isset($_POST['accept_offers'])) {
		$new_value = 'accept';
	} else {
		$new_value = 'reject';
	}
	$sql = "UPDATE user_data_varchar SET value = '{$new_value}', date = NOW() WHERE parent_id = {$_SESSION['user_id']} AND name = 'accept_offers'";
	$link_new->query($sql);
	$_SESSION['settings']['accept_offers'] = $new_value;
}
if (isset($_POST['banner_size'])) {
	header('Location: /area/stud/main/');
}
if (isset($_POST['new_password']) && !empty($_POST['new_password'])) {
	$salt = uniqid('', true);
	$algo = '6';
	$rounds = '5042';
	$cryptSalt = '$' . $algo . '$rounds=' . $rounds . '$' . $salt;
	$password_hash = crypt(trim($_POST['new_password']), $cryptSalt);
	if ($password_hash) {
		$link_new->query("UPDATE Brugere SET password = '{$password_hash}' WHERE id = {$_SESSION['user_id']}");
	}
}
if (isset($_POST['your_name']) && $_POST['your_name'] !== $user_info->name) {
	$new_name = $link_new->real_escape_string($_POST['your_name']);
	$link_new->query("UPDATE Brugere SET navn = '{$new_name}' WHERE id = {$_SESSION['user_id']}");
}
if (isset($_POST['user_language']) && $_POST['user_language'] !== $_SESSION['settings']['user_language']) {
	$new_value = $link_new->real_escape_string($_POST['user_language']);
	$link_new->query("UPDATE user_data_varchar SET value = '{$new_value}', date = NOW() WHERE parent_id = {$_SESSION['user_id']} AND name = 'user_language'");
	$_SESSION['settings']['user_language'] = $new_value;
}
$your_horses_page = max(0, (int) filter_input(INPUT_GET, 'your_horses_page'));
$horses_pr_page = 10;
?>
<?php $user_info = user::get_info(['user_id' => $_SESSION['user_id']]); ?>
<?php $dead = 'død'; ?>
<style>
	.tabs {
		margin-top: 1em;
	}

	[data-button-type="zone_activator"] {
		margin-bottom: 5px;
	}

	.foel {
		position: absolute;
		bottom: 45px;
		right: 12px;
	}

	.enter_graes {
		position: absolute;
		bottom: 12px;
		right: 12px;
	}

	.horse_square+.horse_square+.horse_square+.horse_square+.horse_square+.horse_square+.horse_square+.horse_square+.horse_square+.horse_square+.horse_square {
		display: none;
	}
</style>
<?php
$horse_array = [];
$horse_tabs = [];
$attr = ['user_name' => $_SESSION['username'], 'mode' => 'your_stud'];
if ($filter_id = filter_input(INPUT_POST, 'id_filter')) {
	$attr = ['user_name' => $_SESSION['username'], 'id_filter' => $filter_id, 'mode' => 'your_stud'];
}
$attr['custom_filter'] .= horse_list_filters::get_filter_string(['zone' => "home"]);

$offset = $your_horses_page * $horses_pr_page;
$limit = ($your_horses_page * $horses_pr_page) + $horses_pr_page;
$attr['limit'] = 100;
$attr['offset'] = $offset;
$i = 0;
if (is_array(horses::get_all($attr))) {
	foreach (horses::get_all($attr) as $horse) {
		$horse_array[] = render_horse_object($horse, 'main_stud');
	}
}

$amount_active_selection = count($horse_array);

?>
<section>
	<!--<header><h1>Mit stutteri</h1></header>-->
	<div style="float:left;width: 650px;">
		<div class="image_block" style="height:130px;float:left;overflow: hidden;">
			<?php
			if ($user_info->thumb) {
				$stud_thumbnail = "//files." . HTTP_HOST . "/users/{$user_info->thumb}";
			} else {
				$stud_thumbnail = "//files." . HTTP_HOST . "/graphics/logo/default_logo.png";
			}
			?>
			<img style="float:left;margin-right: 2em;border-radius: 5px;max-width: 325px;max-height: 130px;" src="<?= $stud_thumbnail; ?>" />
		</div>
		<span class="label">Stutterinavn:</span>
		<?= $user_info->username; ?><br />
		<span class="label">Navn:</span>
		<?= $user_info->name; ?><br />
		<span class="label">Antal heste:</span>
		<?= number_dotter($link_new->query("SELECT count(id) AS amount FROM {$GLOBALS['DB_NAME_OLD']}.Heste WHERE bruger = '{$user_info->username}' AND status <> '{$dead}'")->fetch_object()->amount); ?> <span style="font-variant: small-caps;font-size: 0.7em;">(
			<?= number_dotter($amount_active_selection) . ($amount_active_selection >= 100 ? '+' : ''); ?>)</span><br />
		<span class="label">Penge:</span><span class="user_money_pool">
			<?= number_dotter($user_info->money); ?></span> <span class="wkr_symbol dev_test_effect">wkr</span><br />
		<br />
		<?php if (is_array($_SESSION['rights']) && in_array('tech_admin', $_SESSION['rights'])) { ?>
			<style>
				.user_money_pool {
					position: relative;
				}
			</style>
			<script>
				jQuery('.dev_test_effect').click(function() {
					jQuery('.user_money_pool').prepend('<div class="animation_countdown" style="position:absolute;top:-0.6em;right:-0.6em;font-size:0.5em;">1.000</div>');
				});
			</script>
		<?php }
		?>
		<a data-button-type="modal_activator" data-target="edit_user_modal" class="btn btn-success"><?= $GLOBALS['language_strings']['Edit']; ?></a>
		<a data-button-type="modal_activator" data-target="user_settings_modal" class="btn btn-info"><?= $GLOBALS['language_strings']['Settings']; ?></a>
		<a href="?logout" class="btn btn-danger">Logud</a>
	</div>
	<div style="float:left;width: 460px;">
		<!-- Old button array -->
	</div>
</section>
<section class="tabs">
	<section data-zone="horses">
		<div class="grid">
			<div data-section-type="info_square">
				<header>
					<h1>Dine Heste</h1>
				</header>
				<div class="page_selector">
					<span class="btn btn-white">Side:
						<?= $your_horses_page + 1; ?></span>&nbsp;<a class="btn btn-info" href="?your_horses_page=<?= $your_horses_page - 1; ?>">Forrige side</a>&nbsp;<a class="btn btn-info" href="?your_horses_page=<?= $your_horses_page + 1; ?>">Næste side</a>
					<a class="btn btn-info" style="line-height: 30px;" data-button-type='modal_activator' data-target='filter_horses'>Filtre</a>
				</div>
			</div>
			<?php
			if (is_array($horse_array)) {
				foreach ($horse_array as $horse) {
					echo $horse . PHP_EOL;
				}
			}
			?>
		</div>
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
		width: 50%;
		float: left;
		line-height: 25px;
		margin-bottom: 5px;
	}
</style>
<div id="filter_horses" class="modal">
	<script>
		function filter_horses(caller) {}
	</script>
	<style>
	</style>
	<div class="shadow"></div>
	<div class="content">
		<?= horse_list_filters::render_filter_settings(['zone' => "home"]); ?>
	</div>
</div>
<div id="edit_user_modal" class="modal">
	<div class="shadow"></div>
	<div class="content">
		<h2>Rediger stutteri</h2>
		<form action="" method="post" enctype="multipart/form-data">
			<label class="fifty_p" for="fileToUpload">Stutteri billede:</label>
			<input class="fifty_p" type="file" name="fileToUpload" id="fileToUpload">
			<label class="fifty_p" for="your_name">Dit navn:</label>
			<input class="fifty_p" type="text" name="your_name" value="<?= $user_info->name; ?>" id="your_name">
			<label class="fifty_p" for="new_password">Skift Password:</label>
			<input class="fifty_p" type="text" name="new_password" id="new_password">
			<input type="submit" class="btn btn-success" value="Gem" name="submit">
			<input type="submit" class="btn btn-danger" value="Fjern billede" name="remove_user_thumbnail">
		</form>
	</div>
</div>
<div id="user_settings_modal" class="modal">
	<div class="shadow"></div>
	<div class="content">
		<h2>Indstillinger</h2>
		<form action="" method="post">
			<input type="hidden" name="alter_user_settings" value="true" />
			<label class="fifty_p" for="liststyle">Liste visninger:</label>
			<select class="fifty_p" name="liststyle" id="liststyle">
				<!--<option value="standard" <?= ($_SESSION['settings']['list_style'] == 'standard' ? 'selected' : ''); ?>>Normal</option>-->
				<option value="compact" <?= ($_SESSION['settings']['list_style'] == 'compact' ? 'selected' : ''); ?>>Kompakt liste</option>
			</select>
			<label class="fifty_p" for="banner_size">Banner:</label>
			<select class="fifty_p" name="banner_size" id="banner_size">
				<option value="standard" <?= ($_SESSION['settings']['banner_size'] == 'full_size' ? 'selected' : ''); ?>>Vis</option>
				<option value="hide" <?= ($_SESSION['settings']['banner_size'] == 'hide' ? 'selected' : ''); ?>>Skjul</option>
			</select>
			<label class="fifty_p" for="display_width">Side størrelse:</label>
			<select class="fifty_p" name="display_width" id="display_width">
				<option value="standard" <?= ($_SESSION['settings']['display_width'] == 'full' ? 'selected' : ''); ?>>Fuld bredde</option>
				<option value="slim" <?= ($_SESSION['settings']['display_width'] == 'slim' ? 'selected' : ''); ?>>Smal visning</option>
			</select>
			<label class="fifty_p" for="user_language">Vælg sprog:</label>
			<select class="fifty_p" name="user_language" id="user_language">
				<option value="da_DK" <?= ($_SESSION['settings']['user_language'] == 'da_DK' ? 'selected' : ''); ?>>Dansk</option>
				<option value="en_US" <?= ($_SESSION['settings']['user_language'] == 'en_US' ? 'selected' : ''); ?>>English</option>
			</select>
			<br />
			<h3 style="margin-bottom: 0.5em;">Notifikationer</h3>
			<div style="line-height: 20px;font-size:16px;">Vis Græsnings bekræftelser: <input style="height: 1em;" type="checkbox" name="graes_confirmations" <?= ($_SESSION['settings']['graes_confirmations'] == 'show' ? 'checked="checked"' : ''); ?> /></div>
			<div style="line-height: 20px;font-size:16px;">Vis bekræftelser i hestehandleren: <input style="height: 1em;" type="checkbox" name="horse_trader_buy_confirmations" <?= ($_SESSION['settings']['horse_trader_buy_confirmations'] == 'show' ? 'checked="checked"' : ''); ?> /></div>
			<h3 style="margin-bottom: 0.5em;">Valg:</h3>
			<div style="line-height: 20px;font-size:16px;">Man må byde på alle mine heste: <input style="height: 1em;" type="checkbox" name="accept_offers" <?= ($_SESSION['settings']['accept_offers'] == 'accept' ? 'checked="checked"' : ''); ?> /></div>
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
<div id="put_horse_on_grass" class="modal">
	<script type="text/javascript">
		function put_horse_on_grass(caller) {
			console.log(jQuery(caller).parent().parent().attr('data-horse-id'));
			jQuery('#put_horse_on_grass__horse_id').attr('value', jQuery(caller).parent().parent().attr('data-horse-id'));
			jQuery('#put_horse_on_grass__horse_id').val(jQuery(caller).parent().parent().attr('data-horse-id'));
			<?php
			if ($_SESSION['settings']['graes_confirmations'] == 'hide') {
			?>
				jQuery('#put_horse_on_grass').removeClass('active');
				jQuery("#put_horse_on_grass_form").ajaxSubmit(function() {
					jQuery(caller).parent().parent().remove();
					//					window.location.href = 'https://dev.<?= HTTP_HOST; ?>/area/stud/main/?tab=idle_horses';
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
<div id="breed_horse" class="modal">
	<script type="text/javascript">
		function breed_horse(caller) {
			console.log(jQuery(caller).parent().parent().attr('data-horse-id'));
			jQuery('#breed_horse__horse_id').attr('value', jQuery(caller).parent().parent().attr('data-horse-id'));
			jQuery('#breed_horse__horse_id').val(jQuery(caller).parent().parent().attr('data-horse-id'));
			jQuery.get({
				url: "https://ajax.<?= HTTP_HOST; ?>/index.php?request=suggest_breed_targets&horse_id=" + jQuery(caller).parent().parent().attr('data-horse-id'),
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
	</div>
</div>
<div id="put_horse_in_stable" class="modal">
	<script>
		function put_horse_in_stable(caller) {
			console.log(jQuery(caller).parent().parent().attr('data-horse-id'));
			jQuery('#put_horse_in_stable__horse_id').attr('value', jQuery(caller).parent().parent().attr('data-horse-id'));
			jQuery('#put_horse_in_stable__horse_id').val(jQuery(caller).parent().parent().attr('data-horse-id'));
			<?php
			if ($_SESSION['settings']['graes_confirmations'] == 'hide') {
			?>
				jQuery('#put_horse_in_stable').removeClass('active');
				jQuery("#put_horse_in_stable_form").ajaxSubmit(function() {
					jQuery(caller).parent().parent().remove();
					//					window.location.href = 'https://dev.<?= HTTP_HOST; ?>/area/stud/main/?tab=horses_on_grass';
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

<script type="text/javascript">
	jQuery(".horse_object .info .name").click(function() {
		if (jQuery(this).attr('data-object-edit-state') == 'open') {} else {
			if (jQuery(this).attr('data-object-edit-state') != 'animating') {
				old_name = jQuery(this).html();
				jQuery(this).prepend('<i class="fa fa-check"></i><input type="text" class="horse_rename_input" value="' + old_name + '" />');
				jQuery(this).attr('data-object-edit-state', 'open');
				jQuery(this).find('i.fa-check').click(function() {
					horse_id = jQuery(this).parent().parent().parent().attr('data-horse-id');
					new_name = jQuery(this).parent().find('input.horse_rename_input').val();
					//                        dataType: 'text',
					jQuery.getJSON({
						dataType: 'jsonp',
						data: {
							'new_name': new_name,
							'request': 'save_horse_name',
							'horse_id': horse_id
						},
						crossDomain: true,
						url: "https://ajax.<?= HTTP_HOST; ?>/index.php",
						cache: false
					}).always(function(data) {
						//                        console.log(data.status);
						if (data.status !== true) {
							alert('Der skete en fejl ved omdøbning, vent lidt tid, hvis fejlen bliver ved, så skriv hestens ID til Tækhesten');
						}
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

	@media all and (max-height:700px) {
		#breed_horse.modal .content {
			height: calc(100vh - 10px) !important;
			top: 5px !important;
			bottom: 5px !important;
			overflow: auto !important;
		}
	}
</style>
<?php ?>
<?php
require_once("{$basepath}/global_modules/modals/horse_linage.php");
$modals[] = ob_get_contents();
ob_end_clean();
/* Define modal - end */
?>
<?php
require_once("{$basepath}/global_modules/footer.php");
