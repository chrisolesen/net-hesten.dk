<?php
$basepath = '../../../..';
$title = 'Private Inbox';
require "{$basepath}/app_core/object_loader.php";
require "{$basepath}/net-hesten.dk/area/chat/elements/header.php";
?>
<?php
if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
	user::register_session(['user_id' => $_SESSION['user_id']]);
}
?>
<link rel="stylesheet" href="/area/chat/styles/version_two.css?v=<?= time(); ?>" />

<header><a href="/area/chat/global/">Live chat</a></header>
<ul class="thread_list">
	<?php
	foreach (private_messages::get_threads(['user_id' => $_SESSION['user_id']]) AS $user_id => $new_messages) {
		?>
		<li class="thread">
			<span class="username"><a href="/area/chat/pb/thread.php?send_to=<?= $user_id; ?>"><?=  user::get_info(['user_id' => $user_id])->username; ?> (<?= $new_messages; ?>)</a></span>	
		</li>
		<?php
	}
	?>
</ul>
<div class="new_thread">
	<form action="/area/chat/pb/thread.php" method="post">
		<input type="text" name="write_to" list="active_usernames" placeholder="Stutteri Navn" />
		<input class="btn btn-green" type="submit" name="open_thread" value="Skriv til" />
	</form>
	<div class="result">

	</div>
</div>

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
require "{$basepath}/net-hesten.dk/area/chat/elements/footer.php";
