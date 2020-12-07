<?php
$basepath = '../..';
$title = 'Admin portal';
require "{$basepath}/app_core/object_loader.php";
require "{$basepath}/global_modules/header.php";

if (!in_array('global_admin', $_SESSION['rights']) && !in_array('admin_panel_access', $_SESSION['rights'])) {
	ob_end_clean();
	header('Location: /');
	exit();
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
	<h2>Admin Paneler</h2>
	<?php
	if (in_array('global_admin', $_SESSION['rights'])) {
	?>
		<a class="btn btn-info" href="/admin/management/user_management.php">Bruger administration</a>
	<?php

	}
	if (in_array('global_admin', $_SESSION['rights']) || in_array('admin_users_all', $_SESSION['rights'])) {
	?>
		<a class="btn btn-info" href="/admin/management/review_applications.php">Bruger ansøgninger</a>
	<?php

	}
	if (in_array('global_admin', $_SESSION['rights']) || in_array('hestetegner_admin', $_SESSION['rights']) || in_array('admin_template_helper', $_SESSION['rights'])) {
	?>
		<a class="btn btn-info" href="/admin/hestetegner/index.php">Heste(tegner) paneler</a>
	<?php

	}
	if (in_array('global_admin', $_SESSION['rights'])) {
	?>
		<a class="btn btn-info" href="/admin/lists/index.php">System - Lister</a>
	<?php

	}
	if (in_array('global_admin', $_SESSION['rights'])) {
	?>
		<a class="btn btn-info" href="/admin/competitions/index.php">Konkurrancer</a>
	<?php

	}
	?>
	<h2>Teknik Paneler</h2>
	<?php
	if (in_array('global_admin', $_SESSION['rights']) || in_array('site_helper', $_SESSION['rights'])) {
	?>
		<a class="btn btn-info" href="/admin/manuel_crons/index.php">Manuelle Cron</a>
	<?php
	}
	if (in_array('global_admin', $_SESSION['rights'])) {
		?>
		<a class="btn btn-info" href="/admin/form_cache/index.php">Cache kontrol</a>
	<?php
	}
	if (in_array('tech_admin', $_SESSION['rights'])) {
		?>
		<a class="btn btn-info" href="/admin/management/cron_logs.php">Cron logs[T]</a>
		<a class="btn btn-info" href="/admin/management/error_logs.php">Error Logs[T]</a>
		<a class="btn btn-info" href="/admin/management/lineage_cache_builder.php">Build horse cache[T]</a>
		<a class="btn btn-info" href="/admin/management/horse_cleanup.php">Horse Cleanup[T]</a>
	<?php

	}
	?>
</section>
<?php
require_once "{$basepath}/global_modules/footer.php";
