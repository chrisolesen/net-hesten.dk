<?php
$basepath = '../../..';
require "{$basepath}/app_core/object_loader.php";
require "{$basepath}/global_modules/header.php";
?>
<?php
if (!in_array('global_admin', $_SESSION['rights'])) {
	ob_end_clean();
	header('Location: /');
}
?>
<style>
	.admin_panel .btn.btn-info {
		margin-bottom: 0.5em;
		display: block;
		width: 260px;
	}
</style>
<section class="admin_panel">
	<h1>Cache paneler</h1>
	<a class="btn btn-info" href="/admin/">Tilbage</a>
	<?php
	if (in_array('global_admin', $_SESSION['rights'])) {
	?>
		<a class="btn btn-info" href="/admin/form_cache/horse_artists.php">Opdater HT Cache</a>
	<?php
	}
	?>
</section>
<?php
require "{$basepath}/global_modules/footer.php";
