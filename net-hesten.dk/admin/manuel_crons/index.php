<?php
$basepath = '../../..';
require "$basepath/app_core/object_loader.php";
require "$basepath/global_modules/header.php";
?>
<?php
if (
	!in_array('global_admin', $_SESSION['rights'])
	&& !in_array('hestetegner_admin', $_SESSION['rights'])
	&& !in_array('admin_template_helper', $_SESSION['rights'])
	&& !in_array('site_helper', $_SESSION['rights'])
) {
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
	<h1>Manuelle Cron</h1>
	<a class="btn btn-info" href="/admin/">Tilbage</a>
	<?php
	if (in_array('global_admin', $_SESSION['rights']) || in_array('tech_admin', $_SESSION['rights'])) {
	?>
		<a class="btn btn-info" href="/admin/manuel_crons/competitions.php">Stævner</a>
	<?php
	}
	if (in_array('global_admin', $_SESSION['rights']) || in_array('tech_admin', $_SESSION['rights']) || in_array('site_helper', $_SESSION['rights'])) {
	?>
		<a class="btn btn-info" href="/admin/manuel_crons/breeding.php">Avl</a>
	<?php
	}
	?>
	<?php
	if (in_array('tech_admin', $_SESSION['rights'])) {
	?>
	<?php
	}
	?>
</section>
<?php
require "$basepath/global_modules/footer.php";
