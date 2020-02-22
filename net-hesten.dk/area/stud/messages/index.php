<?php
$basepath = '../../../..';
$title = 'Messages';
require "$basepath/app_core/object_loader.php";
require "$basepath/global_modules/header.php";
?>
<style>
	body ul.thread_list {
		height: calc(100% - 65px);
	}
</style>
<link rel="stylesheet" href="/style/messages.css?v=<?= time(); ?>" />

<ul class="thread_list">
	<?php
	if (is_array(private_messages::get_threads(['user_id' => $_SESSION['user_id']]))) {

		foreach (private_messages::get_threads(['user_id' => $_SESSION['user_id']]) as $user_id => $new_messages) {
	?>
			<li class="thread">
				<span class="username"><a href="/area/stud/messages/thread.php?send_to=<?= $user_id; ?>"><?= mb_convert_encoding(user::get_info(['user_id' => $user_id])->username, 'UTF-8', 'latin1'); ?> (<?= $new_messages; ?>)</a></span>
			</li>
	<?php

		}
	}
	?>
</ul>
<div class="new_thread">
	<form action="/area/stud/messages/thread.php" method="post">
		<input type="text" name="write_to" list="usernames" placeholder="Stutteri Navn" />
		<input class="btn btn-green" type="submit" name="open_thread" value="Skriv til" />
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

	last_name = '';
	$('[name="write_to"]').keyup(function() {
		name = $('[name="write_to"]').val();
		if (name !== last_name) {
			last_name = name;
			console.log('[name = ' + name + ']');
			$.get("https://ajax.net-hesten.dk/index.php?request=suggest_user_names&partial_name=" + name, function(data) {
				$("#usernames").html(data);
			});
		}
	});
</script>

<?php
require "{$basepath}/global_modules/footer.php";
