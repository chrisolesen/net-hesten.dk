<style>
	.modal {
		position: fixed;
		z-index: 5;
		top: 0;
		bottom: 0;
		left: 0;
		right: 0;
		display: none;
	}

	.modal .shadow {
		background: rgba(0, 0, 0, 0.4);
		height: 100%;
		width: 100%;
		z-index: 1;
	}

	.modal .content {
		z-index: 2;
		width: 500px;
		min-height: 300px;
		padding: 20px;
		background: white;
		position: fixed;
		left: 50%;
		top: 50%;
		transform: translateX(-50%) translateY(-50%);
		box-shadow: 1px 1px 2px 1px rgba(0, 0, 0, 0.2);
	}

	.modal.active {
		display: block;
	}

	.label {
		color: #1d8038;
		line-height: 1.3em;
		width: 120px;
		display: inline-block;
	}

	h1,
	h2,
	h3,
	h4,
	h5,
	label,
	.label,
	input,
	#top_menu,
	#left_menu a,
	button,
	span.name {
		font-family: 'Merienda One', cursive;
	}

	#page-content,
	p,
	span {
		font-family: 'Roboto', sans-serif;
	}

	.btn {
		text-shadow: -1px -1px rgba(0, 0, 0, 0.5);
	}

	h2 {
		font-size: 24px;
		margin-bottom: 0.5em;
	}

	h1 {
		margin-bottom: 0.5em;
		font-weight: normal;
		font-size: 32px;
	}

	p {
		margin-bottom: 1em;
		font-weight: normal;
		font-size: 20px;
	}

	form .btn-success {
		float: right;
	}
</style>

<?php
if (($_SESSION['settings']['banner_size'] ?? false) == 'hide' || ($force_banner_off ?? false)) {
?>
	<style>
		#top_banner {
			display: none;
		}

		#left_menu {
			height: calc(100vh - 70px);
			padding-bottom: 25px;
		}

		#page-content {
			height: calc(100vh - 70px);
			padding-bottom: 25px;
		}

		#chat-frame {
			height: calc(100vh - 70px - 20px);
		}
	</style>
<?php
} else {
?>
	<style>
		#chat-frame {
			height: calc(100vh - 200px - 70px - 20px);
		}

		#left_menu {
			height: calc(100vh - 200px - 70px);
			padding-bottom: 25px;
		}

		#page-content {
			height: calc(100vh - 200px - 70px);
			padding-bottom: 25px;
		}
	</style>
<?php
}
?>
<style>
	section.tabs {
		padding-bottom: 10px;
	}

	#bottom_streamer span {
		font-size: 12px;
		line-height: 25px;
		color: whitesmoke;
		margin-left: 10px;
		display: inline-block;
		font-family: 'Merienda One', cursive;
	}

	#bottom_streamer {
		z-index: 20;
		position: absolute;
		bottom: 0;
		left: 0;
		right: 0;
		display: block;
		height: 25px;
		background: #333;
	}
</style>
<?php if (($_SESSION['settings']['display_width'] ?? false) == 'slim') { ?>
	<style>
		body {
			max-height: 100%;
			height: 100%;
			box-shadow: 0 0 5px 2px black;
			position: relative;
			max-width: 1280px;
			margin: 0 auto;
		}

		#page-wrap:after {
			content: initial !important;
		}
	</style>
<?php } ?>
<?php
foreach ($script_feedback as $key => $value) {
	if ($value == false) {
		unset($script_feedback[$key]);
	}
}
?>
<div id="script_feedback_modal" class="modal <?= (count($script_feedback) !== 0 ? 'active' : '') ?>">
	<div class="shadow"></div>
	<div class="content">
		<h2>Besked fra systemet</h2>
		<ul>
			<?php
			foreach ($script_feedback as $feedback) {
				if (is_array($feedback[0])) {
					foreach ($feedback as $sub_feedback) {
			?>
						<li class="<?= $sub_feedback[1]; ?>"><?= $sub_feedback[0]; ?></li>
					<?php
					}
				} else {
					?>
					<li class="<?= $feedback[1]; ?>"><?= $feedback[0]; ?></li>
			<?php
				}
			}
			?>
		</ul>
	</div>
</div>
<?php if (isset($_SESSION['impersonator_id']) && $_SESSION['impersonator_id'] !== false) { ?>
	<div id="impersonations_modal" style="display:block;position: fixed;top:1em;right:1em;background:white;border:1px solid black;padding:1em;z-index: 10;">
		<div>Impersonating</div>
		<div>(<?= $_SESSION['user_id']; ?>): <?= $_SESSION['username']; ?></div>
		<a href="?action=impersonate_stop">Return</a>
	</div>
<?php }
?>
</section>
</section>
<?php
//require_once("{$basepath}/global_modules/modals/json_feedback.php");
?>
<section id="bottom_streamer">
	<span>Net-Hesten &bullet;&nbsp;Drives&nbsp;som&nbsp;et&nbsp;privat&nbsp;projekt &bullet;&nbsp;<a href="/infosheets/data_regulative.php">Data politik</a> &bullet;&nbsp;<a href="/infosheets/for_parents.php">Forældreinfo</a> &bullet;&nbsp;<a href="/infosheets/regler.php">Regler</a> &bullet;&nbsp;<a href="/infosheets/help.php">Hjælp</a></span>
</section>
<?php
if (isset($modals)) {
	foreach ($modals as $modal) {
		echo $modal;
	}
}
if (isset($title)) {
	if (in_array(strtolower($title), ['visit'])) {
		require_once("{$basepath}/global_modules/modals/unprovoked_bid.php");
	}
	if (in_array(strtolower($title), ['auktioner', 'stutteri', 'hestehandleren', 'visit'])) {
		require_once("{$basepath}/global_modules/modals/horze_extended_info.php");
	}
	if (in_array(strtolower($title), ['auktioner', 'hestehandleren', 'visit'])) {
		require_once("{$basepath}/global_modules/modals/horse_linage.php");
	}
}
?>
<script>
	// iOS Hover Event Class Fix
	if ((navigator.userAgent.match(/iPhone/i)) || (navigator.userAgent.match(/iPod/i)) || (navigator.userAgent.match(/iPad/i))) {
		$(".horse_square").click(function() {
			// Update '.change-this-class' to the class of your menu
			// Leave this empty, that's the magic sauce
		});
		$(".icon-vcard").click(function() {
			// Update '.change-this-class' to the class of your menu
			// Leave this empty, that's the magic sauce
		});
	}
	jQuery('[data-button-type="modal_activator"]').click(function() {
		jQuery('#' + jQuery(this).attr('data-target')).addClass('active');
		function_name = jQuery(this).attr('data-target');
		window[function_name](this);
	});
	jQuery('.modal .shadow').click(function() {
		jQuery(this).parent().removeClass('active');
	});

	function ready_modal_activator(target) {
		jQuery(target).click(function() {
			jQuery('#' + jQuery(this).attr('data-target')).addClass('active');
			function_name = jQuery(this).attr('data-target');
			window[function_name](this);
		});
	}
</script>
<style>
	#chat-frame-activator {
		position: relative;
	}

	#chat-frame-activator[message_status="blink"]:before {
		content: "?";
		text-align: center;
		padding: 0 0 0 3px;
		line-height: 17px;
		font-size: 12px;
		position: absolute;
		top: -7px;
		right: -7px;
		display: block;
		border-radius: 50%;
		height: 14px;
		width: 14px;
		-webkit-box-shadow: 0 0 0px 2px #cfdbc3;
		box-shadow: 0 0 0px 2px #cfdbc3;
		background: white;
		-webkit-animation: blink_fade_keyframe2 2s linear infinite;
		animation: blink_fade_keyframe2 2s linear infinite;
		z-index: -1;
	}
</style>
<?php if (isset($_SESSION['user_id'])) { ?>
	<script>
		blinker = {};

		function check_new_messages() {
			$.get("//ajax.<?= HTTP_HOST; ?>/index.php?request=feed_live_content&user_id=" + <?= $_SESSION['user_id']; ?>, function(data) {
				blinker_data = JSON.parse(data);

				$("#chat-frame-activator").attr('message_status', blinker_data.main_chat);
				$("#left_menu [href='/area/stud/messages']").attr('message_status', blinker_data.private_messages);
				if (blinker_data.private_messages == 'blink') {
					blinker.pb = true;
				} else {
					delete blinker.pb;
				}
			});
		}
		check_new_messages();
		setInterval(check_new_messages, 5000);
		setInterval(checkFocus, 2000);
		var default_page_title = document.getElementById("page_title").innerHTML;

		function checkFocus() {
			if (document.hasFocus()) {
				page_title.innerHTML = default_page_title;
			} else {
				if (Object.keys(blinker).length > 0) {
					page_title.innerHTML = '(1) ' + default_page_title;
				} else {
					page_title.innerHTML = default_page_title;
				}
			}
		}
	</script>
	<?php
	$target_date = new DateTime('NOW');
	$target_date->sub(new DateInterval('P3M'));
	$target_date_display = $target_date->format('d/m/Y');
	$target_date = $target_date->format('Y-m-d');
	?>
	<datalist id="active_usernames">
		<?php
		$sql = "SELECT `old`.`stutteri` AS `username` 
		FROM `{$GLOBALS['DB_NAME_OLD']}`.`Brugere` AS `old` 
		LEFT JOIN `user_data_timing` AS `last_active` 
		ON `last_active`.`parent_id` = `old`.`id` AND `last_active`.`name` = 'last_active' 
		WHERE `last_active`.`value` > '{$target_date} 00:00:00' 
		AND `old`.`id` NOT IN ({$GLOBALS['hidden_system_users_sql']}) 
		ORDER BY `old`.`stutteri`";
		$result = $link_new->query($sql);

		if ($result) {
			while ($data = $result->fetch_object()) {
				echo "<option value='{$data->username}'>";
			}
		}

		?>
	</datalist>
<?php }
?>
</body>

</html>
