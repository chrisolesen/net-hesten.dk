<?php

$basepath = '../../..';
$responsive = true;
require "{$basepath}/app_core/object_loader.php";
require "{$basepath}/global_modules/header.php";
?>
<?php
if (!in_array('global_admin', $_SESSION['rights'])) {
	ob_end_clean();
	header('Location: /');
	exit();
}
$Foelbox = 'Følkassen';
$Foel = 'Føl';
$selected_race = substr($_GET['race'], 1, -1);
?>
<a href="/admin/hestetegner/admin_types_version_two.php">Tilbage</a><br /><br />
<section>
	<header>
		<h1>Tilføjelses admin</h1>
	</header>
	<style>
		ul {
			border: 1px solid black;
			padding: 10px;
			display: block;
		}

		ul li {
			display: inline-block;
		}

		.heading span {
			border-bottom: 3px double black;
			text-align: center;
		}

		.monospace {
			font-family: monospace;
		}

		.center_text {
			text-align: center;
		}

		.col {
			float: left;
			width: 50%;
		}

		li {
			line-height: 1.3;
			height: 200px;
			width: 414px;
			border: 2px transparent solid;
			display: inline-block;
			position: relative;
			overflow: hidden;
			margin: 5px;
			padding: 5px;
		}

		img {
			max-height: 100%;
			max-width: 200px;
			position: relative;
		}
	</style>

	<ul>
		<?php
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

		$sql = "SELECT * FROM `{$GLOBALS['DB_NAME_NEW']}`.`horse_types` WHERE (`status` IS NULL OR `race` IS NUll) AND `id` > 7000";
		$result = $link_new->query($sql);
		$races = '';
		while ($data = $result->fetch_object()) {
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
		?>
			<li>
				<form action="" method="POST">
					<input type="hidden" name="id" value="<?= $data->id; ?>" />
					<img style="float:left;" src="https:<?= filter_input(INPUT_SERVER, 'HTTP_HOST'); ?>/imgHorse/<?= $data->image; ?>" />
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
		<?php
		}
		?>
	</ul>
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
</section>
<?php
require "{$basepath}/global_modules/footer.php";
