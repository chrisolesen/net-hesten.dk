<?php
$basepath = '../../..';
require "{$basepath}/app_core/object_loader.php";
require "{$basepath}/global_modules/header.php";
?>
<?php
if (!in_array('global_admin', $_SESSION['rights']) && !in_array('hestetegner_admin', $_SESSION['rights']) && !in_array('admin_template_helper', $_SESSION['rights'])) {
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
	<h1>Heste(tegner) paneler</h1>
	<a class="btn btn-info" href="/admin/">Tilbage</a>
	<?php
	if (in_array('global_admin', $_SESSION['rights']) || in_array('hestetegner_admin', $_SESSION['rights'])) {
	?>
		<a class="btn btn-info" href="/admin/hestetegner/admin_races.php">Administrer Heste Racer</a>
		<a class="btn btn-info" href="/admin/hestetegner/admin_types_version_two.php">Administrer Heste Typer</a>
		<a class="btn btn-info" href="/admin/hestetegner/admin_templates.php">Administrer skabeloner</a>
		<a class="btn btn-info" href="/admin/hestetegner/edit_horse.php">Rediger enkelt hest</a>
		<a class="btn btn-info" href="/admin/hestetegner/list_indsendte.php">Vis indsendte heste (v1.0)</a>
		<a class="btn btn-info" href="/admin/hestetegner/submitted_horses.php">Vis indsendte heste (v2.0)</a>
	<?php
	}
	?>
</section>
<?php
if (in_array('global_admin', $_SESSION['rights']) || in_array('hestetegner_admin', $_SESSION['rights'])) {
?>
	<section>
		<h1>Migrations paneler</h1>
		<a class="btn btn-info" href="/admin/hestetegner/move_to_types/index.php">"migrær" til types (hest 2.0 komponent)</a>
	</section>
<?php
}
?>
<?php
require "{$basepath}/global_modules/footer.php";
