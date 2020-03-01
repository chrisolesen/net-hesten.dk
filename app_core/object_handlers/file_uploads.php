<?php
/*
if ($file_upload_allowed) {

	$target_file = "$target_dir/" . base64_encode(basename($_FILES["image"]["name"]));
	$uploadOk = 1;
	$imageFileType = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
// Check if image file is a actual image or fake image
	if (isset($_POST["submit"])) {
		$check = getimagesize($_FILES["image"]["tmp_name"]);
		if ($check !== false) {
			$script_feedback[] = ["File is an image: {$imageFileType} ({$check["mime"]})", 'note'];
			$uploadOk = 1;
		} else {
			$script_feedback[] = ["File is not an image.", 'warning'];
			$uploadOk = 0;
		}
	}
// Check if file already exists
	if (file_exists($target_file)) {
		$script_feedback[] = ["Sorry, file already exists.", 'warning'];
		$uploadOk = 0;
	}
// Check file size
	if ($_FILES["image"]["size"] > 500000) {
		$script_feedback[] = ["Sorry, your file is too large.", 'warning'];
		$uploadOk = 0;
	}
// Allow certain file formats
	if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
		$script_feedback[] = ["Sorry, only JPG, JPEG, PNG & GIF files are allowed.", 'warning'];
		$uploadOk = 0;
	}
// Check if $uploadOk is set to 0 by an error
	if ($uploadOk == 0) {
		$script_feedback[] = ["Sorry, your file was not uploaded.", 'error'];
// if everything is ok, try to upload file
	} else {
		if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
			$script_feedback[] = ["The file " . basename($_FILES["image"]["name"]) . " has been uploaded.", 'success'];
		} else {
			$script_feedback[] = ["Sorry, there was an error uploading your file.", 'error'];
		}
	}
} else {
	$script_feedback[] = ["You called a script incorrectly", 'error'];
}
*/