<?php
require_once "$basepath/app_core/db_conf.php";
require_once "$basepath/app_core/user_validate.php";
if ((!$_SESSION['logged_in'] == true || !isset($basepath)) && filter_input(INPUT_SERVER, 'REQUEST_URI') !== '/') {
	header("Location: /");
}
ob_start();

user::register_session(['user_id' => $_SESSION['user_id']]);
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
		<!--<script type="text/javascript" src="http://files.net-hesten.dk/scripts/snowstorm.js"></script>-->
        <link href='https://fonts.googleapis.com/css?family=Work+Sans:400,600' rel='stylesheet' type='text/css'>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
        <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
        <link rel="stylesheet" href="/style/font-awesome.min.css" />
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
        <link rel="stylesheet" href="/admin/style/main.css" />
		<?php if (filter_input(INPUT_GET, 'iframe_mode')) { ?>
			<link rel="stylesheet" href="/style/iframe.css" />
		<?php } ?>
		<?php if (filter_input(INPUT_GET, 'test_mode')) { ?>
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<?php } ?>
    </head>
    <body>
        <section class="page_wrap">
			<?php if (!filter_input(INPUT_GET, 'iframe_mode')) { ?>
				<header>
					<h1 class="raised">Net-Hesten<?= $title ? " - $title" : ''; ?></h1>
					<h2 class="raised">Velkommen til "<?= $_SESSION['username']; ?>"</h2>
				</header>
				<br />
				<a href="/">Til forsiden</a> <a href="javascript:history.go(-1)">En tilbage</a>
				<br /><br />
			<?php } ?>