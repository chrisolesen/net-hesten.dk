<?php

$basepath = '../../..';
$responsive = true;
require "{$basepath}/app_core/object_loader.php";
require "{$basepath}/global_modules/header.php";

if (!in_array('global_admin', $_SESSION['rights']) && !in_array('hestetegner_admin', $_SESSION['rights']) && !in_array('admin_template_helper', $_SESSION['rights'])) {
	ob_end_clean();
	header('Location: /');
	exit();
}
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

function find_next_type_filename()
{
	global $basepath;
	if ($handle = opendir("{$basepath}/files.net-hesten.dk/horses/imgs/")) {
		$found = false;
		$num_dirs = 0;
		while ($found != true) {
			++$num_dirs;
			$target_dir = str_replace(["/", "="], [""], base64_encode($num_dirs));
			if (!is_dir("{$basepath}/files.net-hesten.dk/horses/imgs/" . $target_dir)) {
				return "$target_dir does not exist";
				//						mkdir("{$basepath}/files.net-hesten.dk/horses/imgs/" . $target_dir);
			}
			if (is_dir("{$basepath}/files.net-hesten.dk/horses/imgs/" . $target_dir)) {
				$num_files = 1;
				while ($num_files <= 250) {
					++$num_files;
					if (is_file("{$basepath}/files.net-hesten.dk/horses/imgs/" . $target_dir . '/' . str_replace(["/", "="], [""], base64_encode($num_files)) . '.png')) {
						continue;
					} else if (is_file("{$basepath}/files.net-hesten.dk/horses/imgs/" . $target_dir . '/' . str_replace(["/", "="], [""], base64_encode($num_files)) . '.jpg')) {
						continue;
					} else if (is_file("{$basepath}/files.net-hesten.dk/horses/imgs/" . $target_dir . '/' . str_replace(["/", "="], [""], base64_encode($num_files)) . '.gif')) {
						continue;
					} else {
						return "{$basepath}/files.net-hesten.dk/horses/imgs/{$target_dir}/" . str_replace(["/", "="], [""], base64_encode($num_files));
					}
				}
			}
		}
	}
}

if (isset($_FILES['fileToUpload'])) {
	$target_file = find_next_type_filename();
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

	if ($_FILES["fileToUpload"]["size"] > 450000) {
		echo "Beklager, din fil er for stor, den må maksimalt være 450kb.";
		$uploadOk = 0;
	}
	// Allow certain file formats
	if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
		echo "Beklager, det er kun JPG, JPEG, PNG & GIF filer som er tilladt. Den her fil er identificeret som {$check["mime"]}.";
		$uploadOk = 0;
	}
	// Check if $uploadOk is set to 0 by an error
	if ($uploadOk == 0) {
		echo "Sorry, your file was not uploaded.";
		// if everything is ok, try to upload file
	} else {
		if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file . '.' . $imageFileType)) {
			//			echo "The file " . basename($_FILES["fileToUpload"]["name"]) . " has been uploaded. As {$target_file}.{$imageFileType}";
			$file_path = str_replace("{$basepath}/files.net-hesten.dk/horses/imgs/", '', "{$target_file}.{$imageFileType}");
			$sql = "INSERT INTO `horse_types` (`image`, `date`) VALUES ('{$file_path}', NOW())";
			$link_new->query($sql);
			echo mysqli_error($link_new);
		} else {
			echo "Beklager, der skete er sket en uventet fejl, prøv igen lidt senere, eller kontakt stutteri TechHesten.";
		}
	}
}
?>
<a class="btn btn-info" href="/admin/hestetegner/">Tilbage</a>
<h2>Tilføj Heste Type</h2>
<form action="" method="post" enctype="multipart/form-data">
	<input type="file" name="fileToUpload" id="fileToUpload">
	<input type="submit" value="Upload Hestens Billede" name="submit">
</form>
<br />
<?php
$sql = "SELECT count(`id`) AS `amount` FROM `{$GLOBALS['DB_NAME_NEW']}`.`horse_types` WHERE (`status` IS NULL OR `race` IS NUll) AND `id` > 7000";
$number_of_new = $link_new->query($sql)->fetch_object()->amount;
?>
<a href='/admin/hestetegner/finish_type_addition.php'>Færdigør tilføjelser (<?= $number_of_new; ?>)</a>
<br /><br />

<section style="padding-bottom: 35px;">
	<header>
		<h2 class="raised">Race administration</h2>
	</header>
	<style>
		ul {
			border: 1px solid black;
			padding: 10px;
			display: table;
		}

		ul li {
			display: table-row;
		}

		ul li span {
			display: table-cell;
			padding: 2px 10px;
			line-height: 1.2;
			border-bottom: 1px dashed black;
		}

		ul li span+span {
			border-left: 1px dotted black;
		}

		.wkr {
			text-align: right;
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
		}
	</style>
	<div class="col">
		<h2>Føl</h2>
		<ul>
			<?php
			//			$sql = "SELECT count(id) AS amount, race FROM `{$GLOBALS['DB_NAME_NEW']}`.horse_types WHERE status IN (25, 26) GROUP BY race ORDER BY amount ASC";
			$sql = "SELECT `races`.`name`, `template`.`amount` 
			FROM `{$GLOBALS['DB_NAME_NEW']}`.`horse_races` AS `races` 
			LEFT JOIN (SELECT count(`id`) AS `amount`, `race` 
			FROM `{$GLOBALS['DB_NAME_NEW']}`.`horse_types` WHERE `status` IN (25,26) AND `archived` = 0 
			GROUP BY `race`) AS `template` 
			ON `template`.`race` = `races`.`name` 
			ORDER BY `template`.`race`";
			$result = $link_new->query($sql);
			while ($data = $result->fetch_object()) {
				echo "<li><a href='/admin/hestetegner/change_generation_status_version_two.php?race=\"{$data->name}\"&type=foel'>{$data->name} => {$data->amount}</a></li>";
			}
			?>
		</ul>
	</div>
	<div class="col">
		<h2>Heste</h2>
		<ul>
			<?php
			$sql = "SELECT `races`.`name`, `template`.`amount` 
			FROM `{$GLOBALS['DB_NAME_NEW']}`.`horse_races` AS `races` 
			LEFT JOIN (SELECT count(`id`) AS `amount`, `race` 
			FROM `{$GLOBALS['DB_NAME_NEW']}`.`horse_types` WHERE `status` IN (19,20,21,22,23,24) AND `archived` = 0 
			GROUP BY `race`) AS `template` 
			ON `template`.`race` = `races`.`name` 
			ORDER BY `template`.`race`";

			$result = $link_new->query($sql);
			while ($data = $result->fetch_object()) {
				echo "<li><a href='/admin/hestetegner/change_generation_status_version_two.php?race=\"{$data->name}\"&type=adult'>{$data->name} => {$data->amount}</a></li>";
			}
			?>
		</ul>
	</div>
</section>
<?php
require "{$basepath}/global_modules/footer.php";
