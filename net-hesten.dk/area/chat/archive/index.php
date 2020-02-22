<?php
$basepath = '../../../..';
$title = 'Chat Archive';
require "$basepath/app_core/object_loader.php";
require "$basepath/net-hesten.dk/area/chat/elements/header.php";
?>
<?php
if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
	chat::register_online(['user_id' => $_SESSION['user_id']]);
	user::register_session(['user_id' => $_SESSION['user_id']]);
}
if (filter_input(INPUT_GET, 'chat_page')) {
	$page = filter_input(INPUT_GET, 'chat_page');
} else {
	$page = 1;
}
?>
<link rel="stylesheet" href="https://net-hesten.dk/area/chat/styles/main.css?v=<?= time(); ?>" />

<header>
	<a href="https://net-hesten.dk/area/chat/global/">Live chat</a>
	Side: <?= $page; ?>
	<a href="?chat_page=<?= ($page + 1); ?>">Næste</a>
	<?php if ($page > 1) { ?>
		<a href="?chat_page=<?= ($page - 1); ?>">Forrige</a>
	<?php } ?>
</header>
<style>
    .msg.first {
		/* Permalink - use to edit and share this gradient: http://colorzilla.com/gradient-editor/#f3c5bd+0,ff6600+51,c68477+100&0.5+0,0+6,0.5+13,0+21,0.5+29,0+37,0.5+45,0+52,0.5+58,0+66,0.5+72,0+80,0.5+88,0+94,0.5+100 */
		background: -moz-linear-gradient(-45deg,  rgba(243,197,189,0.5) 0%, rgba(244,186,167,0) 6%, rgba(246,173,141,0.5) 13%, rgba(248,158,111,0) 21%, rgba(250,143,81,0.5) 29%, rgba(252,128,52,0) 37%, rgba(254,113,22,0.5) 45%, rgba(255,102,0,0.07) 51%, rgba(254,103,2,0) 52%, rgba(247,107,17,0.5) 58%, rgba(238,112,36,0) 66%, rgba(231,116,51,0.5) 72%, rgba(222,121,70,0) 80%, rgba(212,125,90,0.5) 88%, rgba(205,129,105,0) 94%, rgba(198,132,119,0.5) 100%); /* FF3.6-15 */
		background: -webkit-linear-gradient(-45deg,  rgba(243,197,189,0.5) 0%,rgba(244,186,167,0) 6%,rgba(246,173,141,0.5) 13%,rgba(248,158,111,0) 21%,rgba(250,143,81,0.5) 29%,rgba(252,128,52,0) 37%,rgba(254,113,22,0.5) 45%,rgba(255,102,0,0.07) 51%,rgba(254,103,2,0) 52%,rgba(247,107,17,0.5) 58%,rgba(238,112,36,0) 66%,rgba(231,116,51,0.5) 72%,rgba(222,121,70,0) 80%,rgba(212,125,90,0.5) 88%,rgba(205,129,105,0) 94%,rgba(198,132,119,0.5) 100%); /* Chrome10-25,Safari5.1-6 */
		background: linear-gradient(135deg,  rgba(243,197,189,0.5) 0%,rgba(244,186,167,0) 6%,rgba(246,173,141,0.5) 13%,rgba(248,158,111,0) 21%,rgba(250,143,81,0.5) 29%,rgba(252,128,52,0) 37%,rgba(254,113,22,0.5) 45%,rgba(255,102,0,0.07) 51%,rgba(254,103,2,0) 52%,rgba(247,107,17,0.5) 58%,rgba(238,112,36,0) 66%,rgba(231,116,51,0.5) 72%,rgba(222,121,70,0) 80%,rgba(212,125,90,0.5) 88%,rgba(205,129,105,0) 94%,rgba(198,132,119,0.5) 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
		filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#80f3c5bd', endColorstr='#80c68477',GradientType=1 ); /* IE6-9 fallback on horizontal gradient */

    }
	pre {
		max-width: 100%;
		white-space: normal;
	}
</style>
<ul class="message_list">
	<?php
	foreach (chat::get_messages(['page' => $page]) AS $message) {
		?>
		<li>
			<div class="poster"> 
				<span class="username <?= (in_array(strtolower($message['creator']), $admin_colors)) ? 'admin' : ''; ?>"><?= $message['creator']; ?>:</span>	<?= $message['date']; ?>
			</div>
			<div class="msg"><?= str_replace(["\n", "\r"], ['<br />', ''], $message['text']); ?></div>
		</li>
		<?php
	}
	?>
</ul>
<?php
require "$basepath/net-hesten.dk/area/chat/elements/footer.php";
