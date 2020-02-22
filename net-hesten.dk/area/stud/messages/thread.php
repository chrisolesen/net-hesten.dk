<?php
$basepath = '../../../..';
$title = 'Messages';
require "$basepath/app_core/object_loader.php";
require "$basepath/global_modules/header.php";

?>


<?php
if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
	user::register_session(['user_id' => $_SESSION['user_id']]);
}

if (isset($_GET['send_to'])) {
	$_POST['send_to'] = $_GET['send_to'];
}

if (isset($_GET['thread'])) {
	$_POST['thread'] = $_GET['thread'];
} elseif (!isset($_POST['thread'])) {
	$_POST['thread'] = 1;
}
if (isset($_POST['write_to'])) {
	$_POST['send_to'] = user::get_info(['user_id' => $_POST['write_to'], 'mode' => 'username'])->id;
}
if (isset($_GET['delete_message'])) {
	private_messages::hide_message(['msg_id' => $_GET['delete_message'], 'user_id' => $_SESSION['user_id']]);
}

private_messages::mark_as_read(['other_user' => $_POST['send_to'], 'user_id' => $_SESSION['user_id'], 'thread' => $_POST['thread']]);
?>
<link rel="stylesheet" href="/style/messages.css?v=<?= time(); ?>" />
<style>
	body ul.message_list {
		height: calc(100% - 190px)
	}
</style>
<header style="height:50px;"><a href="/area/stud/messages/">Oversigt</a>
	<span class="write_to">Skriver med: <?= mb_convert_encoding(user::get_info(['user_id' => $_POST['send_to']])->username, 'UTF-8', 'latin1'); ?></span>
	<div style="padding-top:5px;">
		<a style="text-decoration:underline;" href="?send_to=<?= $_POST['send_to']; ?>&thread_page=1&thread=1">Tråd 1<?= (private_messages::get_new_messages_count(['user_id' => $_SESSION['user_id'], 'origin' => $_POST['send_to'], 'thread' => 1]) > 0 ? '[Ny]' : ''); ?></a>
		<a style="text-decoration:underline;" href="?send_to=<?= $_POST['send_to']; ?>&thread_page=1&thread=2">Tråd 2<?= (private_messages::get_new_messages_count(['user_id' => $_SESSION['user_id'], 'origin' => $_POST['send_to'], 'thread' => 2]) > 0 ? '[Ny]' : ''); ?></a>
		<a style="text-decoration:underline;" href="?send_to=<?= $_POST['send_to']; ?>&thread_page=1&thread=3">Tråd 3<?= (private_messages::get_new_messages_count(['user_id' => $_SESSION['user_id'], 'origin' => $_POST['send_to'], 'thread' => 3]) > 0 ? '[Ny]' : ''); ?></a>
		<a style="text-decoration:underline;" href="?send_to=<?= $_POST['send_to']; ?>&thread_page=1&thread=4">Tråd 4<?= (private_messages::get_new_messages_count(['user_id' => $_SESSION['user_id'], 'origin' => $_POST['send_to'], 'thread' => 4]) > 0 ? '[Ny]' : ''); ?></a>
		<a style="text-decoration:underline;" href="?send_to=<?= $_POST['send_to']; ?>&thread_page=1&thread=5">Tråd 5<?= (private_messages::get_new_messages_count(['user_id' => $_SESSION['user_id'], 'origin' => $_POST['send_to'], 'thread' => 5]) > 0 ? '[Ny]' : ''); ?></a>
	</div>
</header>
<ul class="message_list">
	<?php
	$messages = private_messages::get_messages(['user_id' => $_SESSION['user_id'], 'other_user' => $_POST['send_to'], 'thread' => $_POST['thread'], 'limit' => 50, 'page' => 1]);
	if (is_array($messages)) {
		foreach ($messages as $message) {
	?>
			<li data-message_id="<?= $message->id; ?>" class="msg <?= $message->origin == $_SESSION['user_id'] ? 'mine' : 'theirs'; ?> status-<?= $message->status_code; ?>">
				<div class="poster">
					<span class="username"><?= mb_convert_encoding(user::get_info(['user_id' => $message->origin])->username, 'UTF-8', 'latin1'); ?>:</span> <?= $message->date; ?>
					<a href="/area/stud/messages/thread.php?delete_message=<?= $message->id; ?>&send_to=<?= $_POST['send_to']; ?>"><img class="delete_msg" src="https://files.net-hesten.dk/graphics/delete.png" height="20px" /></a>
				</div>
				<div class="msg"><?= str_replace(["\n", "\r"], ['<br />', ''], $message->message); ?></div>
			</li>
	<?php
		}
	}
	?>
</ul>
<style>
	.mine.status-17 {
		border-left-color: blue;
	}

	.mine.status-17 {
		border-left-color: red;
	}
</style>
<div class="new_message">
	<form action="/area/stud/messages/thread.php" method="post">
		<input name="action" value="post_private_message" type="hidden" />
		<input name="send_to" value="<?= $_POST['send_to']; ?>" type="hidden" />
		<input name="thread" value="<?= $_POST['thread']; ?>" type="hidden" />
		<textarea name="message_text" placeholder="Ny besked"></textarea>
		<input class="btn btn-green" type="submit" name="send_message" value="Send Besked" />
	</form>
	<div class="result">

	</div>
	<datalist id="usernames">
	</datalist>
</div>
<script type="text/javascript">
	// iOS Hover Event Class Fix
	if ((navigator.userAgent.match(/iPhone/i)) || (navigator.userAgent.match(/iPod/i)) || (navigator.userAgent.match(/iPad/i))) {
		$(".horse_square").click(function() {
			// Update '.change-this-class' to the class of your menu
			// Leave this empty, that's the magic sauce
		});
	}
	if ((navigator.userAgent.match(/iPhone/i)) || (navigator.userAgent.match(/iPod/i)) || (navigator.userAgent.match(/iPad/i))) {
		$(".icon-vcard").click(function() {
			// Update '.change-this-class' to the class of your menu
			// Leave this empty, that's the magic sauce
		});
	}
</script>
<?php
require "{$basepath}/global_modules/footer.php";
