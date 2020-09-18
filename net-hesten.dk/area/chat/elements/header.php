<?php
require_once "{$basepath}/app_core/db_conf.php";
require_once "{$basepath}/app_core/user_validate.php";
if ((!$_SESSION['logged_in'] == true || !isset($basepath)) && filter_input(INPUT_SERVER, 'REQUEST_URI') !== '/') {
	?>
	<h2>Login for at se chatten.</h2>
	<script>
		setTimeout(function () {
			window.location.replace("/area/chat/global/");
			;
		}, 15000);
	</script>
	<?php
	exit();
}
ob_start();
$admin_colors = ['tækhesten', 'net-hesten'];
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <link href='https://fonts.googleapis.com/css?family=Work+Sans:400,600' rel='stylesheet' type='text/css'>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
        <link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
        <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
        <link rel="stylesheet" href="/admin/style/main.css" />
		<style>
			.username {
				font-weight: bold;
			}
			.username.admin:before {
				content:"";
				padding:1px;
				height: 9px;
				border-left:2px blue solid;
				display: inline-block;
			}
		</style>
    </head>
    <body>