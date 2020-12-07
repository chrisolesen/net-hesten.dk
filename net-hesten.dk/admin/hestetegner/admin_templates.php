<?php

$basepath = '../../..';
require "{$basepath}/app_core/object_loader.php";
require "{$basepath}/global_modules/header.php";
?>
<?php
if (!in_array('global_admin', $_SESSION['rights']) && !in_array('hestetegner_admin', $_SESSION['rights']) && !in_array('admin_template_helper', $_SESSION['rights'])) {
	ob_end_clean();
	header('Location: /');
	exit();
}

function find_next_filename()
{
	global $basepath;
	if ($handle = opendir("{$basepath}/files.net-hesten.dk/templates/")) {
		$found = false;
		$num_dirs = 0;
		while ($found != true) {
			++$num_dirs;
			$target_dir = str_replace(["/", "="], [""], base64_encode($num_dirs));
			if (!is_dir("{$basepath}/files.net-hesten.dk/templates/" . $target_dir)) {
				mkdir("{$basepath}/files.net-hesten.dk/templates/" . $target_dir);
			}
			if (is_dir("{$basepath}/files.net-hesten.dk/templates/" . $target_dir)) {
				$num_files = 1;
				while ($num_files <= 250) {
					++$num_files;
					if (is_file("{$basepath}/files.net-hesten.dk/templates/" . $target_dir . '/' . str_replace(["/", "="], [""], base64_encode($num_files)) . '.png')) {
						continue;
					} else if (is_file("{$basepath}/files.net-hesten.dk/templates/" . $target_dir . '/' . str_replace(["/", "="], [""], base64_encode($num_files)) . '.jpg')) {
						continue;
					} else if (is_file("{$basepath}/files.net-hesten.dk/templates/" . $target_dir . '/' . str_replace(["/", "="], [""], base64_encode($num_files)) . '.gif')) {
						continue;
					} else {
						return "{$basepath}/files.net-hesten.dk/templates/{$target_dir}/" . str_replace(["/", "="], [""], base64_encode($num_files));
					}
				}
			}
		}
	}
}

if (isset($_FILES['fileToUpload'])) {
	$target_file = find_next_filename();
	$uploadOk = 1;
	$imageFileType = pathinfo(basename($_FILES["fileToUpload"]["name"]), PATHINFO_EXTENSION);
	// Check if image file is a actual image or fake image
	if (isset($_POST["submit"])) {
		$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
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
	if ($_FILES["fileToUpload"]["size"] > 500000) {
		echo "Sorry, your file is too large.";
		$uploadOk = 0;
	}
	// Allow certain file formats
	if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
		echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed. This file was {$check["mime"]}.";
		$uploadOk = 0;
	}
	// Check if $uploadOk is set to 0 by an error
	if ($uploadOk == 0) {
		echo "Sorry, your file was not uploaded.";
		// if everything is ok, try to upload file
	} else {
		if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file . '.' . $imageFileType)) {
			//			echo "The file " . basename($_FILES["fileToUpload"]["name"]) . " has been uploaded. As {$target_file}.{$imageFileType}";
			$file_path = str_replace("{$basepath}/files.net-hesten.dk/templates/", '', "{$target_file}.{$imageFileType}");
			$sql = "INSERT INTO `horse_templates` (`image`, `status`, `date`) VALUES ('$file_path', 1, NOW())";
			$link_new->query($sql);
		} else {
			echo "Sorry, there was an error uploading your file.";
		}
	}
}

if (filter_input(INPUT_POST, 'submit_edit')) {
	$races = json_encode($_POST['suggested_race']);
	$note_1 = filter_input(INPUT_POST, 'note');
	$pony = filter_input(INPUT_POST, 'pony');
	$pixel = filter_input(INPUT_POST, 'pixel');
	$foel = filter_input(INPUT_POST, 'foel');
	$status = filter_input(INPUT_POST, 'status');
	$fetlock = filter_input(INPUT_POST, 'fetlock');
	$stance = filter_input(INPUT_POST, 'stance');
	$sql = "UPDATE `horse_templates` SET `special_note` = '{$note_1}', `pony` = {$pony}, `pixel` = {$pixel}, `foel` = {$foel}, 
	`status` = {$status}, `fetlock` = {$fetlock}, `stance` = '{$stance}', `suggested_races` = '{$races}' 
	WHERE `ID` = '{$_POST['id']}' LIMIT 1";
	$link_new->query($sql);
}

/* List templates */
?>
<script src="https://<?= filter_input(INPUT_SERVER, 'HTTP_HOST'); ?>/scripts/list.min.js" type="text/javascript"></script>
<section>
	<header>
		<h2 class="raised">Skabeloner</h2>
	</header>
	<form action="/admin/hestetegner/admin_templates.php" method="post" enctype="multipart/form-data">
		<?php
		if (isset($_GET['edit_template'])) {

			$sql = "SELECT * FROM `horse_templates` WHERE `ID` = '{$_GET['edit_template']}' LIMIT 1";
			$result = $link_new->query($sql);
			while ($data = $result->fetch_object()) {
				$active_races = json_decode($data->suggested_races);
		?>
				<div>
					<img style="max-height: 150px;float:left;margin-bottom: 125px;margin-right:20px;" src="https://<?= filter_input(INPUT_SERVER, 'HTTP_HOST'); ?>/templates/<?= $data->image; ?>" />
					<div style="height: 275px;float:left;margin-right:20px;">
						<label for="suggested_race">Racer:</label>
						<select style="height:275px;" multiple id="suggested_race" name="suggested_race[]">
							<?php
							$sql = "SELECT id, name FROM `{$GLOBALS['DB_NAME_NEW']}`.horse_races ORDER BY name";
							$race_names = $link_new->query($sql);
							while ($race = $race_names->fetch_object()) {
							?>
								<option value="<?= $race->id; ?>" <?= in_array($race->id, $active_races) ? 'selected' : ''; ?>><?= $race->name; ?></option>
							<?php
							}
							?>
						</select>
					</div>
					<label for="stance">Stilling:</label>
					<select id="stance" name="stance">
						<option value="standing" <?= $data->stance == 'standing' ? 'selected' : ''; ?>>Stående</option>
						<option value="rearing" <?= $data->stance == 'rearing' ? 'selected' : ''; ?>>Stejlende</option>
						<option value="trot" <?= $data->stance == 'trot' ? 'selected' : ''; ?>>Trav</option>
						<option value="steps" <?= $data->stance == 'steps' ? 'selected' : ''; ?>>Skridt</option>
						<option value="gallop" <?= $data->stance == 'gallop' ? 'selected' : ''; ?>>Gallop</option>
						<option value="lieing" <?= $data->stance == 'lieing' ? 'selected' : ''; ?>>liggende</option>
						<option value="other" <?= $data->stance == 'other' ? 'selected' : ''; ?>>Andet</option>
						<option value="jump" <?= $data->stance == 'jump' ? 'selected' : ''; ?>>Spring</option>
					</select><br />
					<label for="pixel">Pnoy:</label>
					<select id="pony" name="pony" value="<?= $data->pony; ?>">
						<option value="1" <?= ($data->pony == 1) ? 'selected' : ''; ?>>Ja</option>
						<option value="0" <?= ($data->pony == 0) ? 'selected' : ''; ?>>Nej</option>
					</select><br />
					<label for="pixel">Pixel:</label>
					<select id="pixel" name="pixel" value="<?= $data->pixel; ?>">
						<option value="1" <?= ($data->pixel == 1) ? 'selected' : ''; ?>>Ja</option>
						<option value="0" <?= ($data->pixel == 0) ? 'selected' : ''; ?>>Nej</option>
					</select><br />
					<label for="foel">Føl:</label>
					<select id="foel" name="foel" value="<?= $data->foel; ?>">
						<option value="1" <?= ($data->foel == 1) ? 'selected' : ''; ?>>Ja</option>
						<option value="0" <?= ($data->foel == 0) ? 'selected' : ''; ?>>Nej</option>
					</select><br />
					<label for="fetlock">Hovskæg:</label>
					<select id="fetlock" name="fetlock" value="<?= $data->fetlock; ?>">
						<option value="1" <?= ($data->fetlock == 1) ? 'selected' : ''; ?>>Ja</option>
						<option value="0" <?= ($data->fetlock == 0) ? 'selected' : ''; ?>>Nej</option>
					</select><br />
					<label for="status">Status:</label>
					<select id="status" name="status" value="<?= $data->status; ?>">
						<option value="1" <?= ($data->status == 1) ? 'selected' : ''; ?>>Aktiv</option>
						<option value="0" <?= ($data->status == 0) ? 'selected' : ''; ?>>Inaktiv</option>
					</select><br />
					<label for="note">Note:</label><input id="note" name="note" value="<?= $data->special_note; ?>" /><br />
				</div>
				<input type="hidden" name="id" value="<?= filter_input(INPUT_GET, 'edit_template'); ?>" />
				<input type="submit" value="Ret Skabelon" name="submit_edit">
				<input type="submit" value="Fortryd" name="regreat">
			<?php
			}
		} else {
			?>
			<input type="file" name="fileToUpload" id="fileToUpload">
			<input type="submit" value="Upload Skabelon" name="submit">
		<?php
		}
		?>

	</form>
	<style>
		label {
			width: 100px;
			display: inline-block;
		}

		input,
		select,
		textArea {
			width: 200px;
			margin-right: 10px;
			margin-bottom: 5px;
		}

		ul {
			/*margin: 2em;*/
			width: 100%;
			display: block;
			text-align: justify;
			padding: 1em;
		}

		li {
			position: relative;
			display: inline-block;
			margin: 1em;
			width: calc(((1116px - 2em) / 5) - 2em);
			height: calc(((1116px - 2em) / 5) - 2em);
		}

		li div {
			display: none;
			position: absolute;
			top: 0;
			left: 0;
			bottom: 0;
			right: 0;
			background: rgba(0, 0, 0, 0.2);
		}

		li i {
			display: none;
		}

		li:hover div {
			display: block;
		}

		li img {
			position: absolute;
			top: 50%;
			left: 50%;
			transform: translateX(-50%) translateY(-50%);
		}
	</style>
	<?php if (!isset($_GET['edit_template'])) { ?>
		<div id="template_list">
			<input class="search" placeholder="Search" />
			<button class="sort" data-sort="name">
				Sort by name
			</button>
			<ul class="list">
				<?php
				$sql = "SELECT * FROM horse_templates ORDER BY id DESC";
				$result = $link_new->query($sql);
				while ($data = $result->fetch_object()) {
				?><li>
						<i class="suggested_race"><?= $data->suggested_races; ?></i>
						<img style="max-height: 150px;" src="//files.<?= filter_input(INPUT_SERVER, 'HTTP_HOST'); ?>/templates/<?= $data->image; ?>" />
						<div>
							<a href="//files.<?= filter_input(INPUT_SERVER, 'HTTP_HOST'); ?>/templates/<?= $data->image; ?>">Download</a>
							<a href="?edit_template=<?= $data->id; ?>">Edit</a>
						</div>
					</li><?php
						}
							?>
			</ul>
		</div>
	<?php } ?>
</section>
<script type="text/javascript">
	jQuery(document).ready(function() {
		var options = {
			valueNames: ["suggested_race"]
		};
		template_list = new List("template_list", options);
	});
</script>
<?php
require "{$basepath}/global_modules/footer.php";
