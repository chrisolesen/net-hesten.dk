<?php

$basepath = realpath(__DIR__ . '/..');
require_once "{$basepath}/app_core/db_conf.php";
require_once "{$basepath}/app_core/object_loader.php";
require_once "{$basepath}/app_core/user_validate.php";

if (is_numeric(($_SESSION['user_id'] ?? false))) {
	user::register_timing(['user_id' => $_SESSION['user_id'], 'key' => 'last_active']);
	user::register_session(['user_id' => $_SESSION['user_id']]);
}

$admin = false;
$public_page = true;
require_once("{$basepath}/global_modules/header.php"); ?>

<link href="//files.<?= HTTP_HOST; ?>/scripts/booklet/jquery.booklet.latest.css" type="text/css" rel="stylesheet" media="screen, projection, tv">
<script src="//files.<?= HTTP_HOST; ?>/scripts/booklet/jquery.booklet.latest.min.js"></script>
<section>

	<?php
	if (($_SESSION['logged_in'] ?? false) == true) {
	?>
		<img id="booklet_opener" height="300" src="//files.<?= HTTP_HOST; ?>/graphics/magazines/forside_fun_facts_4.png" />
		<form action="" method="post">
			<input class="btn btn-danger" type="submit" name="logout" value="Log ud" />
		</form>
</section>
<?php require "{$basepath}/global_modules/fun_facts_book_modal.php"; ?>
<?php

	} else {

?>

	<h2>Login</h2>
	<form action="" method="post">
		<input type="text" name="username" placeholder="Brugernavn / Mail" />
		<input type="password" name="password" placeholder="Kodeord" />
		<br />
		<br />
		<input style="float:left;margin-right:1em;" type="submit" name="login" value="Login" class="btn btn-success" />
		<a style="float:left;margin-right:1em;" data-button-type="modal_activator" data-target="forgot_password" class="btn btn-danger">Glemt password</a>
		<a data-button-type="modal_activator" data-target="request_membership" class="btn btn-info">Anmod om medlemsskab</a>
	</form>
<?php
		require_once("{$basepath}/global_modules/modals/request_membership.php");
		require_once("{$basepath}/global_modules/modals/forgot_password.php");
	}
	require_once("{$basepath}/global_modules/footer.php");
