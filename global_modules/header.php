<?php
require_once "{$basepath}/app_core/db_conf.php";
require_once "{$basepath}/app_core/user_validate.php";

if (((($_SESSION['logged_in'] ?? false) !== true) || !isset($basepath) || !isset($_SESSION['user_id']) || $_SESSION['user_id'] != ((int) $_SESSION['user_id'])) && filter_input(INPUT_SERVER, 'REQUEST_URI') !== '/' && !$public_page) {
	header("Location: /");
}
ob_start();
if (($_SESSION['logged_in'] ?? false) == true) {
	user::register_session(['user_id' => $_SESSION['user_id']]);
}
?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8" />
	<title id="page_title">Net-Hesten</title>
	<link rel="stylesheet" href="//files.<?= HTTP_HOST; ?>/style/main.css?v=<?= time(); ?>" />
	<?php if (($_SESSION['settings']['list_style'] ?? false) == 'compact') { ?>
		<link rel="stylesheet" href="//files.<?= HTTP_HOST; ?>/style/horselists_compact_style.css?v=<?= time(); ?>" />
	<?php }
	?>
	<?php if (isset($_GET['test_view'])) { ?>
		<meta name="viewport" content="width=1240, initial-scale=1.0">
	<?php } else {
	?>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<?php }
	?>
	<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Merienda+One" rel="stylesheet">
	<link rel="stylesheet" href="/style/font-awesome.min.css" />
	<link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css" />
	<script src="//ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.2.2/jquery.form.min.js" integrity="sha384-FzT3vTVGXqf7wRfy8k4BiyzvbNfeYjK+frTVqZeNDFl8woCbF0CYG6g2fMEFFo/i" crossorigin="anonymous"></script>
	<script src="//files.<?= HTTP_HOST; ?>/scripts/jquery.easing.1.3.js"></script>
	<script src="/scripts/jquery.nicescroll.min.js"></script>
	<!-- FA License only valid for net-hesten.dk and dev-net-hesten.dk -->
	<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.13.0/css/all.css" integrity="sha384-IIED/eyOkM6ihtOiQsX2zizxFBphgnv1zbe1bKA+njdFzkr6cDNy16jfIKWu4FNH" crossorigin="anonymous" />
	<script>
		console.log($(window).width());
		if (($(window).width()) > 960) {
			$(document).ready(function() {
				$("#page-content").niceScroll({
					cursorwidth: 12,
					cursoropacitymin: 0.4,
					cursorcolor: '#51a351',
					cursorborder: 'none',
					cursorborderradius: 4,
					autohidemode: 'leave'
				}); // free your immagination
			});
		}
	</script>
	<style>
		.nicescroll-rails-vr .nicescroll-cursors {
			background-image: linear-gradient(45deg, rgba(255, 255, 255, 0.2) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.2) 50%, rgba(255, 255, 255, 0.2) 75%, transparent 75%, transparent);
			background-size: 20px 20px;
		}

		/* temp fix */
		.btn {
			font-family: 'Merienda One', cursive !important;
		}
	</style>
</head>

<body>
	<section id="chat-frame">
		<iframe src="/area/chat/global/index.php?v=<?= time(); ?>"></iframe>
	</section>
	<script type="text/javascript">
		jQuery("#chat_fullsize_toggle").click(function() {
			jQuery(this).parent().parent().toggleClass('large');
		});
	</script>
	<section id="top_banner" style="position: relative;">
		<img src="//files.<?= HTTP_HOST; ?>/graphics/top_banner/two.png" style="position: absolute;top:50%;left:50%;transform:translateY(-50%) translateX(-50%);max-width: none;" height="200" width="1920" />
	</section>
	<section id="top_menu">
		<a href="/"><img src="//files.<?= HTTP_HOST; ?>/graphics/logo/logo_new.png" height="115" style="position:relative;top:5px;left:2em;height: 60px;" /></a>
		<nav>
			<a class="btn btn-menu" href="/area/stud/main/">Mit stutteri</a>
			<a <?= (isset($_GET['blink_test']) ? 'data-animation="blink"' : ''); ?> id="chat-frame-activator" class="btn btn-info" href="javascript:void(0);" onclick="jQuery('#chat-frame').toggleClass('visible');">(<?= chat::get_online(['mode' => 'count', 'time_mode' => 'h', 'time_val' => '1']); ?>) Chat</a>
		</nav>
	</section>
	<section id="page-wrap">
		<?php require_once("{$basepath}/global_modules/left_menu.php"); ?>
		<section id="page-content" class="page_wrap">