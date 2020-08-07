<?php
chdir(dirname(__FILE__));
$basepath = '../../..';
require "{$basepath}/app_core/object_loader.php";
require "{$basepath}/global_modules/header.php";
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

if (($script = filter_input(INPUT_GET, 'run_cron') ?? false)) {
	switch ($script) {
		case 'die':
			require_once("{$basepath}/app_core/cron_files/functions/die.php");
			break;
	}
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
		<a class="btn btn-info" href="/admin/manuel_crons/generate_horses.php">Generer heste</a>
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
		<a class="btn btn-info" href="/admin/manuel_crons/?run_cron=die">Dødsscript</a>
	<?php
	}
	?>
</section>
<?php
require "{$basepath}/global_modules/footer.php";
