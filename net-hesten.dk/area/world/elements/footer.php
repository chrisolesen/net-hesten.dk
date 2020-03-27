<?php
if (!$_SESSION['logged_in'] == true || !isset($basepath)) {
	ob_end_clean();
	header("Location: /");
} else {
?>
	<form action="" method="post">
		<input type="submit" name="logout" value="Log ud" />
	</form>
<?php
}
?>
<div id="script_feedback_modal" class="modal_boks<?= (count($script_feedback) !== 0 ? ' visible' : '') ?>">
	<div class="modal-overlay"></div>
	<section class="modal_content">
		<header>
			<h2 class="raised">Besked fra systemet</h2>
		</header>
		<ul>
			<?php
			foreach ($script_feedback as $feedback) {
			?>
				<li class="<?= $feedback[1]; ?>"><?= $feedback[0]; ?></li>
			<?php
			}
			?>
		</ul>
	</section>
</div>
<script type="text/javascript">
	jQuery('.modal-overlay').click(function() {
		jQuery(this).parent().removeClass('visible');
	});
	<?php if (!filter_input(INPUT_GET, 'iframe_mode')) { ?>
		if (window.top !== window.self) {
			console.log("switching to iFrame mode!" + window.location);
			window.location.assign(window.location.href + "?iframe_mode=true");

		}
	<?php } ?>
</script>
</section>
<style>
	/* Permalink - use to edit and share this gradient: http://colorzilla.com/gradient-editor/#feccb1+0,f17432+50,ea5507+51,fb955e+100;Red+Gloss+%232 */
	#chat-frame-activator {
		background: #feccb1;
		/* Old browsers */
		background: -moz-linear-gradient(top, #feccb1 0%, #f17432 50%, #ea5507 51%, #fb955e 100%);
		/* FF3.6-15 */
		background: -webkit-linear-gradient(top, #feccb1 0%, #f17432 50%, #ea5507 51%, #fb955e 100%);
		/* Chrome10-25,Safari5.1-6 */
		background: linear-gradient(to bottom, #feccb1 0%, #f17432 50%, #ea5507 51%, #fb955e 100%);
		/* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
		filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#feccb1', endColorstr='#fb955e', GradientType=0);
		/* IE6-9 */
		z-index: 3;
		position: fixed;
		bottom: 5px;
		right: 5px;
		color: #fff;
		padding: 5px;
		display: inline-block;
		border-radius: 5px;
		border: 1px darkgreen solid;
	}
</style>
<style>
	#chat_fullsize_toggle {
		position: absolute;
		top: 1em;
		right: 1em;
	}

	#chat-frame>header {
		width: 100%;
	}

	#chat-frame.large {
		top: 2em;
		bottom: 2em;
		right: 2em;
		left: 2em;
		height: initial;
		width: initial;
		box-shadow: 0 0 3em 1em darkgreen;
	}
</style>
<a id="chat-frame-activator" href="javascript:void(0);" onclick="jQuery('#chat-frame').toggleClass('visible');">(<?= chat::get_online(['mode' => 'count', 'time_mode' => 'h', 'time_val' => '1']); ?>) åben/luk chat</a>
<section id="chat-frame">
	<header>
		<h1 class="raised">Net-Hesten - Chat</h1><i id="chat_fullsize_toggle" class="fa fa-arrows-alt" aria-hidden="true"></i>
	</header>
	<iframe src="http://m.net-hesten.dk/area/chat/global/index.php?v=<?= time(); ?>"></iframe>
</section>
<script type="text/javascript">
	jQuery("#chat_fullsize_toggle").click(function() {
		jQuery(this).parent().parent().toggleClass('large');
	});
</script>
</body>

</html>
<?php
ob_flush();
ob_end_clean();
