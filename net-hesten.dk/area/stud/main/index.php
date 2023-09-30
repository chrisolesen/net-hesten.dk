<?php
/* REVIEW: SQL Queries */
/* Mit Stutteri */
$basepath = '../../../..';
$title = 'Stutteri';
require "{$basepath}/app_core/object_loader.php";
require "{$basepath}/global_modules/header.php";

function find_next_user_filename($username)
{
	global $basepath;
	if ($handle = opendir("{$basepath}/files.net-hesten.dk/users/")) {
		$found = false;
		$num_dirs = 0;
		while ($found != true) {
			++$num_dirs;
			$target_dir = str_replace(["/", "="], [""], base64_encode($num_dirs));
			if (!is_dir("{$basepath}/files.net-hesten.dk/users/{$target_dir}")) {
				mkdir("{$basepath}/files.net-hesten.dk/users/{$target_dir}");
			}
			if (is_dir("{$basepath}/files.net-hesten.dk/users/{$target_dir}")) {
				$num_files = 1;
				while ($num_files <= 250) {
					++$num_files;
					if (is_file("{$basepath}/files.net-hesten.dk/users/{$target_dir}/" . str_replace(["/", "="], [""], base64_encode($username)) . str_replace(["/", "="], [""], base64_encode($num_files)) . '.png')) {
						continue;
					} else if (is_file("{$basepath}/files.net-hesten.dk/users/{$target_dir}/" . str_replace(["/", "="], [""], base64_encode($username)) . str_replace(["/", "="], [""], base64_encode($num_files)) . '.jpg')) {
						continue;
					} else if (is_file("{$basepath}/files.net-hesten.dk/users/{$target_dir}/" . str_replace(["/", "="], [""], base64_encode($username)) . str_replace(["/", "="], [""], base64_encode($num_files)) . '.gif')) {
						continue;
					} else {
						return "{$basepath}/files.net-hesten.dk/users/{$target_dir}/" . str_replace(["/", "="], [""], base64_encode($username)) . str_replace(["/", "="], [""], base64_encode($num_files));
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
			$file_path = str_replace("{$basepath}/files.net-hesten.dk/users/", '', "{$target_file}.{$imageFileType}");
			$sql = "UPDATE `{$GLOBALS['DB_NAME_OLD']}`.Brugere SET thumb = '{$file_path}' WHERE id = {$_SESSION['user_id']}";
			$link_new->query($sql);
		} else {
			echo "Beklager, der skete er sket en uventet fejl, prøv igen lidt senere, eller kontakt stutteri TechHesten.";
		}
	}
}
if (isset($_POST['remove_user_thumbnail']) && empty($_POST['new_password']) && $_POST['your_name'] == $user_info->name) {
	$sql = "UPDATE `{$GLOBALS['DB_NAME_OLD']}`.Brugere SET thumb = '' WHERE id = {$_SESSION['user_id']}";
	$link_new->query($sql);
}
/* Change list-style */
if (isset($_POST['liststyle']) && $_POST['liststyle'] != $_SESSION['settings']['list_style']) {
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
if (isset($_POST['banner_size']) && $_SESSION['settings']['banner_size'] != $_POST['banner_size']) {
	if ($_POST['banner_size'] == 'hide') {
		$new_value = 'hide';
	} else {
		$new_value = 'standard';
	}
	$sql = "UPDATE user_data_varchar SET value = '{$new_value}', date = NOW() WHERE parent_id = {$_SESSION['user_id']} AND name = 'banner_size'";
	$link_new->query($sql);
	$_SESSION['settings']['banner_size'] = $new_value;
}
if (isset($_POST['display_width']) && $_SESSION['settings']['display_width'] != $_POST['display_width']) {
	if ($_POST['display_width'] == 'slim') {
		$new_value = 'slim';
	} else {
		$new_value = 'full';
	}
	$sql = "UPDATE user_data_varchar SET value = '{$new_value}', date = NOW() WHERE parent_id = {$_SESSION['user_id']} AND name = 'display_width'";
	$link_new->query($sql);
	$_SESSION['settings']['display_width'] = $new_value;
}
if (isset($_POST['left_menu_style']) && $_SESSION['settings']['left_menu_style'] != $_POST['left_menu_style']) {
	if ($_POST['left_menu_style'] == 'old_school') {
		$new_value = 'old_school';
	} else {
		$new_value = 'standard';
	}
	$sql = "UPDATE user_data_varchar SET value = '{$new_value}', date = NOW() WHERE parent_id = {$_SESSION['user_id']} AND name = 'left_menu_style'";
	$link_new->query($sql);
	$_SESSION['settings']['left_menu_style'] = $new_value;
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
if (isset($_POST['new_password']) && !empty($_POST['new_password'])) {
	$salt = uniqid('', true);
	$algo = '6';
	$rounds = '5042';
	$cryptSalt = '$' . $algo . '$rounds=' . $rounds . '$' . $salt;
	$password_hash = crypt(trim($_POST['new_password']), $cryptSalt);
	if ($password_hash) {
		$link_new->query("UPDATE `{$GLOBALS['DB_NAME_OLD']}`.Brugere SET `password` = '{$password_hash}' WHERE id = {$_SESSION['user_id']}");
	}
}
if (isset($_POST['your_name']) && $_POST['your_name'] !== $user_info->name) {


	$new_name = $link_new->real_escape_string($_POST['your_name']);
	$sql = "UPDATE `{$GLOBALS['DB_NAME_OLD']}`.Brugere SET navn = '{$new_name}' WHERE id = {$_SESSION['user_id']}";
	$link_new->query($sql);
}
if (isset($_POST['user_language']) && $_POST['user_language'] !== $_SESSION['settings']['user_language']) {
	$new_value = $link_new->real_escape_string($_POST['user_language']);
	$link_new->query("UPDATE user_data_varchar SET value = '{$new_value}', date = NOW() WHERE parent_id = {$_SESSION['user_id']} AND name = 'user_language'");
	$_SESSION['settings']['user_language'] = $new_value;
}

if (isset($_POST['banner_size'])) {
	header('Location: /area/stud/main/');
	exit();
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
$attr['custom_filter'] = horse_list_filters::get_filter_string(['zone' => "home"]);

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
		<span class="label"><?= $GLOBALS['language_strings']['AmountHorses']; ?></span>
		<?= number_dotter($link_new->query("SELECT count(id) AS amount FROM `{$GLOBALS['DB_NAME_OLD']}`.Heste WHERE bruger = '{$user_info->username}' AND status <> '{$dead}'")->fetch_object()->amount); ?> <span style="font-variant: small-caps;font-size: 0.7em;">(
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
		<a href="?logout" class="btn btn-danger"><?= $GLOBALS['language_strings']['Logout']; ?></a>
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
				$horse_amount = count($horse_array);
			}
			if (($horse_amount ?? 0) > 10) {
			?>
				<div data-section-type="info_square">
					<div class="page_selector">
						<span class="btn btn-white">Side:
							<?= $your_horses_page + 1; ?></span>&nbsp;<a class="btn btn-info" href="?your_horses_page=<?= $your_horses_page - 1; ?>">Forrige side</a>&nbsp;<a class="btn btn-info" href="?your_horses_page=<?= $your_horses_page + 1; ?>">Næste side</a>
					</div>
				</div>
			<?php
			}
			?>
		</div>
	</section>
</section>
<?php
/* Define modals - start */
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
						url: "//ajax.<?= HTTP_HOST; ?>/index.php",
						cache: false
					}).always(function(data) {
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
</style>
<?php
require_once("{$basepath}/global_modules/modals/user_settings_profile.php");
require_once("{$basepath}/global_modules/modals/user_settings_display.php");
require_once("{$basepath}/global_modules/modals/horse_put_in_stable.php");
require_once("{$basepath}/global_modules/modals/horse_put_on_grass.php");
require_once("{$basepath}/global_modules/modals/horse_breed_mare.php");
require_once("{$basepath}/global_modules/modals/horse_linage.php");
$modals[] = ob_get_contents();
ob_end_clean();
/* Define modals - end */
?>
<?php
require_once("{$basepath}/global_modules/footer.php");
