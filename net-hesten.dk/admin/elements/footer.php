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
		<header><h2 class="raised">Script feedback</h2></header>
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
	jQuery('.modal-overlay').click(function () {
		jQuery(this).parent().removeClass('visible');
	});
</script>
</section>
</body>
</html>
<?php
ob_flush();
ob_end_clean();
