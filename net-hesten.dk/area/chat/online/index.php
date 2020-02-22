<?php

$basepath = '../../../..';
$title = 'Online Liste';
require "$basepath/app_core/object_loader.php";
require "$basepath/net-hesten.dk/area/chat/elements/header.php";


chat::register_online(['user_id' => $_SESSION['user_id']]);
user::register_session(['user_id' => $_SESSION['user_id']]);

?>

<link rel="stylesheet" href="https://net-hesten.dk/area/chat/styles/version_two.css?v=<?= time(); ?>" />
<header><a href="https://net-hesten.dk/area/chat/global/">Tilbage til chatten</a></header>
<ul>
	<?php
	$segment = ['time_mode' => 'h', 'time_val' => '2'];
	if (in_array('global_admin', $_SESSION['rights'])) {
		$segment = ['time_mode' => 'd', 'time_val' => '7'];
	}
	foreach (chat::get_online($segment) as $online_user):
		?>
		<li>
			<div class="poster">
				<a href="/area/world/visit/visit.php?user=<?= $online_user->user_id; ?>" target="_top"><?= $online_user->username; ?></a>:	<?= $online_user->last_online; ?>
			</div>
		</li>
		<?php
	endforeach;
	?>
</ul>
<script type="text/javascript">
	// iOS Hover Event Class Fix
	if ((navigator.userAgent.match(/iPhone/i)) || (navigator.userAgent.match(/iPod/i)) || (navigator.userAgent.match(/iPad/i))) {
		$(".horse_square").click(function () {

			// Update '.change-this-class' to the class of your menu

			// Leave this empty, that's the magic sauce

		});

	}

	if ((navigator.userAgent.match(/iPhone/i)) || (navigator.userAgent.match(/iPod/i)) || (navigator.userAgent.match(/iPad/i))) {

		$(".icon-vcard").click(function () {

			// Update '.change-this-class' to the class of your menu

			// Leave this empty, that's the magic sauce

		});

	}
</script>
<?php

require "$basepath/net-hesten.dk/area/chat/elements/footer.php";
