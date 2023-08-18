<?php

$basepath = '../../..';
$responsive = true;
require "{$basepath}/app_core/object_loader.php";
require "{$basepath}/global_modules/header.php";

if (!in_array('global_admin', $_SESSION['rights']) && !in_array('hestetegner_admin', $_SESSION['rights'])) {
	ob_end_clean();
	header('Location: /');
	exit();
}
$Foelbox = 'Følkassen';
$Foel = 'Føl';
$selected_horse_id = filter_input(INPUT_GET, 'id');
?>
<a href="/admin/hestetegner/admin_types_version_two.php">Tilbage</a><br /><br />
<section id="change_generation_race">
	<header>
		<h1>Raceskift type <?= $selected_horse_id; ?></h1>
	</header>
	<style>
		/*a.btn {
			font-family:'Merienda One', cursive;
		}*/
	</style>

	<ul>
		<?php
		if (isset($_GET['do'])) {
			$thumb = filter_input(INPUT_GET, 'thumb');
			/*
			  19	type_unique
			  20	type_generation
			  21	type_rebirth
			  22	type_rebirth_generation
			  23	type_rebirth_unique
			  24	type_ordinary
			  25	type_foel
			  26	type_foel_rebirth
		 */
			//	&do=<?= (in_array($data->status, [21, 22, 23]) ? 'deaktivate' : 'activate')

		}
		$target_status = false;


		/*
		if (isset($_POST['save'])) {
			$latin_1_name = $_POST['artist'];
			$status = filter_input(INPUT_POST, 'status');
			$race = filter_input(INPUT_POST, 'race');
			$id = (int) filter_input(INPUT_POST, 'id');
			$artist = $link_new->query("SELECT id FROM `{$GLOBALS['DB_NAME_OLD']}`.`Brugere` WHERE `stutteri` = '{$latin_1_name}' LIMIT 1")->fetch_object()->id;
			if ($artist && in_array((int) $status, [22, 26, 19, 25, 24]) && $race && is_numeric($id)) {

				$sql = "UPDATE `{$GLOBALS['DB_NAME_NEW']}`.`horse_types` SET `artists` = '{$artist}', `status` = {$status}, `race` = '{$race}' WHERE `id` = {$id}";
				//				echo $sql;
				$link_new->query($sql);
			}
		}
		*/

		if (filter_input(INPUT_GET, 'do') === 'deaktivate') {
			if (filter_input(INPUT_GET, 'type') === 'foel') {
				$target_status = 25;
			}
			if (filter_input(INPUT_GET, 'type') === 'adult') {
				$previus_status = $link_new->query("SELECT `status` FROM `{$GLOBALS['DB_NAME_NEW']}`.`horse_types` WHERE `image` = '{$thumb}' LIMIT 1")->fetch_object()->status;
				if (in_array($previus_status, [19, 23])) {
					$target_status = 19;
				} else {
					$target_status = 24;
				}
			}
		}



		$sql = "SELECT * FROM `{$GLOBALS['DB_NAME_NEW']}`.`horse_types` WHERE `id` = {$selected_horse_id}";
		$result = $link_new->query($sql);
		$races = '';
		$latin_dead = 'død';
		while ($data = $result->fetch_object()) {
			$selected_race = $data->race;
			$genders = $data->allowed_gender;


			if (in_array($data->status, [25, 26])) {
				$age_type = 'foel';
			} else {
				$age_type = 'adult';
			}



			if (in_array($data->status, [19, 23])) {/* Unique */
				$amount = $link_new->query("SELECT count(`id`) AS `amount` 
				FROM `{$GLOBALS['DB_NAME_OLD']}`.`Heste` WHERE `thumb` LIKE '%{$data->image}%' AND `status` <> 'død'")->fetch_object()->amount;
			}
			$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https:" : "http:";
		?>
			<li>
				<form action="" method="POST">
					<input type="hidden" name="id" value="<?= $data->id; ?>" />
					<img style="float:left;" src="<?= $protocol; ?>//files.<?= filter_input(INPUT_SERVER, 'HTTP_HOST'); ?>/horses/imgs/<?= $data->image; ?>" />
					<div style="float:left;width:200px;">
						<label>Race</label><input type="text" list="horse_races" name="race" />
						<label>Tegner</label><input type="text" list="usernames" name="artist" />
						<label>status</label><select name="status">
							<option value="22">Hest genereres</option>
							<option value="26">Føl genereres</option>
							<option value="19">Unik</option>
							<option value="25">Føl</option>
							<option value="24">Normal Hest</option>
						</select>
						<input type="submit" class="save btn btn-success" name="save" value="Gem" />
					</div>
				</form>
			</li>


			<li title="Type ID: <?= $data->id; ?>" data-horse-thumb="<?= $data->image; ?>" class="<?= in_array($data->status, [21, 22, 23, 26]) ? 'rebirth' : ''; ?> <?php /*= in_array($data->status, [20, 22, 26]) ? 'generation' : ''; */ ?> <?= in_array($data->status, [19, 23]) ? 'unique' : ''; ?>">
				<img src="//files.<?= filter_input(INPUT_SERVER, 'HTTP_HOST'); ?>/imgHorse/<?= $data->image; ?>" />
				<?php if (!in_array($data->status, [19, 23]) || (in_array($data->status, [19, 23]) && $amount == 0)) { ?>
					<a class="generate_one btn btn-info" href="/admin/hestetegner/change_generation_status_version_two.php?race='<?= $selected_race; ?>'&do=generate_one&id=<?= $data->id; ?>&type=<?= $age_type; ?>">Lav én</a>
				<?php } ?>
				<a class="alter_gen btn <?= in_array($data->status, [21, 22, 23, 26]) ? 'btn-danger' : 'btn-success'; ?>" href="/admin/hestetegner/change_generation_status_version_two.php?race='<?= $selected_race; ?>'&do=<?= (in_array($data->status, [21, 22, 23, 26]) ? 'deaktivate' : 'activate'); ?>&thumb=<?= $data->image; ?>&type=<?= $age_type; ?>"><?= in_array($data->status, [21, 22, 23, 26]) ? 'Genfød ikke' : 'Genfød'; ?></a>
				<?php if (filter_input(INPUT_GET, 'type') !== 'foel') { ?>
					<a class="unique_ness btn <?= in_array($data->status, [20, 21, 22, 24]) ? 'btn-success' : 'btn-danger'; ?>" href="/admin/hestetegner/change_generation_status_version_two.php?race='<?= $selected_race; ?>'&do=<?= (in_array($data->status, [20, 21, 22, 24]) ? 'make_unique' : 'make_normal'); ?>&thumb=<?= $data->image; ?>&type=<?= $age_type; ?>"><?= in_array($data->status, [20, 21, 22, 24]) ? 'Unik' : 'Normal'; ?></a>
				<?php
				} ?>
				<!-- TO DO: finish -->
				<a class="race btn btn-info" style="pointer-events:none;">ID: <?= $data->id; ?></a>
				<a class="archive btn btn-danger" href="/admin/hestetegner/change_generation_status_version_two.php?race='<?= $selected_race; ?>'&do=archive&thumb=<?= $data->image; ?>&type=<?= $age_type; ?>">Arkiver</a>
				<a class="alter_type btn btn-info" href="/admin/hestetegner/change_generation_status_version_two.php?race='<?= $selected_race; ?>'&do=<?= (in_array($data->status, [25, 26]) ? 'make_adult' : 'make_foel'); ?>&thumb=<?= $data->image; ?>&type=<?= $age_type; ?>"><?= in_array($data->status, [25, 26]) ? 'Bliv Hest' : 'Bliv Føl'; ?></a>
				<div class="gender" style="overflow:hidden;height:32px;" onclick="slide_gender_toggle(this);">
					<?php if ($genders == 2) { ?>
						<i class="fa fa-mars fa-2x" style="color:#0f66b4; display:block;"></i></a>
						<a href="/admin/hestetegner/change_generation_status_version_two.php?race='<?= $selected_race; ?>'&do=make_venus&thumb=<?= $data->image; ?>&type=<?= $age_type; ?>"><i class="fa fa-venus fa-2x" style="color:#bd362f; display:block;"></i></a>
						<a href="/admin/hestetegner/change_generation_status_version_two.php?race='<?= $selected_race; ?>'&do=make_trans&thumb=<?= $data->image; ?>&type=<?= $age_type; ?>"><i class="fa fa-transgender fa-2x" style="color:#51a351; display:block;"></i></a>
					<?php } else if ($genders == 3) { ?>
						<i class="fa fa-venus fa-2x" style="color:#bd362f; display:block;"></i>
						<a href="/admin/hestetegner/change_generation_status_version_two.php?race='<?= $selected_race; ?>'&do=make_mars&thumb=<?= $data->image; ?>&type=<?= $age_type; ?>"><i class="fa fa-mars fa-2x" style="color:#0f66b4; display:block;"></i></a>
						<a href="/admin/hestetegner/change_generation_status_version_two.php?race='<?= $selected_race; ?>'&do=make_trans&thumb=<?= $data->image; ?>&type=<?= $age_type; ?>"><i class="fa fa-transgender fa-2x" style="color:#51a351; display:block;"></i></a>
					<?php } else if ($genders == 1) { ?>
						<i class="fa fa-transgender fa-2x" style="color:#51a351; display:block;"></i>
						<a href="/admin/hestetegner/change_generation_status_version_two.php?race='<?= $selected_race; ?>'&do=make_mars&thumb=<?= $data->image; ?>&type=<?= $age_type; ?>"><i class="fa fa-mars fa-2x" style="color:#0f66b4; display:block;"></i></a>
						<a href="/admin/hestetegner/change_generation_status_version_two.php?race='<?= $selected_race; ?>'&do=make_venus&thumb=<?= $data->image; ?>&type=<?= $age_type; ?>"><i class="fa fa-venus fa-2x" style="color:#bd362f; display:block;"></i></a>
					<?php } ?>
				</div>
			</li>
		<?php
		}
		?>
	</ul>
	<div id="switch_type_race" class="modal">
		<style>
			#switch_type_race .btn {
				margin: 2px 1px;
				width: 170px;
				font-family: Roboto;
				padding: 0 7px;
				font-size: 15px;
			}
		</style>
		<script>
			function switch_type_race(caller) {
				jQuery('#switch_type_race__thumb').attr('value', jQuery(caller).parent().attr('data-horse-thumb'));
				jQuery('#switch_type_race__thumb').val(jQuery(caller).parent().attr('data-horse-thumb'));
			}
		</script>
		<div class="shadow"></div>
		<div class="content" style="width:916px;">
			<h2>Skift race</h2>
			<form id="switch_type_race_form" action="" method="post">
				<input id="switch_type_race__thumb" type="hidden" name="thumb" value="" />
				<?php
				foreach (array_sorter($cached_races, 'name', true) as $race) {
				?><input class="btn btn-info" type="submit" value="<?= $race['name']; ?>" name="extra_race" />
				<?php }	?>
			</form>
		</div>
	</div>
	<script>
		function slide_gender_toggle(caller) {
			if (jQuery(caller).attr('state') == 'opened') {
				jQuery(caller).attr('state', 'closed');
				jQuery(caller).animate({
					height: "32px"
				}, 500);
			} else {
				jQuery(caller).attr('state', 'opened');
				jQuery(caller).animate({
					height: "96px"
				}, 500);
			}
		}
	</script>
</section>
<datalist id="usernames">
	<?php
	$result = $link_new->query("SELECT `stutteri` FROM `{$GLOBALS['DB_NAME_OLD']}`.`Brugere`");
	while ($data = $result->fetch_object()) {
	?>
		<option value="<?= $data->stutteri; ?>" /><?php
												}
													?>
</datalist>
<datalist id="horse_races">
	<?php
	$result = $link_new->query("SELECT `name` FROM `{$GLOBALS['DB_NAME_NEW']}`.`horse_races`");
	while ($data = $result->fetch_object()) {
	?>
		<option value="<?= $data->name; ?>" /><?php
											}
												?>
</datalist>
<?php
require "{$basepath}/global_modules/footer.php";
