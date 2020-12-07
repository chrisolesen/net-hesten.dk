<?php
$basepath = '../../..';
require "{$basepath}/app_core/object_loader.php";
require "{$basepath}/global_modules/header.php";

if (!in_array('global_admin', $_SESSION['rights'])) {
    ob_end_clean();
    header('Location: /');
	exit();
}
?>
<style>
    .admin_panel .btn.btn-info {
        margin-bottom:0.5em;
        display:block;
        width:260px;
    }
</style>
<section class="admin_panel">
    <h1>Konkurrance Admin</h1>
    <a class="btn btn-info" href="/admin/">Tilbage</a>
    <a class="btn btn-info" href="/admin/competitions/simple_competitions.php">Lodtrækninger</a>
</section>
<?php
require "{$basepath}/global_modules/footer.php";
