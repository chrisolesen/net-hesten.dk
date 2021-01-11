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
