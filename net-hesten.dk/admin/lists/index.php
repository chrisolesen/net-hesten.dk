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
        margin-bottom:0.5em;
        display:block;
        width:260px;
    }
</style>
<section class="admin_panel">
    <h1>System - lister</h1>
    <a class="btn btn-info" href="/admin/">Tilbage</a>
<?php
if (in_array('tech_admin', $_SESSION['rights'])) {
    ?>
    <a class="btn btn-info" href="/admin/lists/tabel_stability.php">Tabel Oversigt[T]</a>
    <a class="btn btn-info" href="/admin/lists/user_reminders.php">User reminders[T]</a>
    <?php

}
?>
    <a class="btn btn-info" href="/area/world/paypal_thanks/">User donations</a>
    <a class="btn btn-info" href="/admin/management/user_cleanup.php">Bruger oprydning</a>
    <a class="btn btn-info" href="/admin/management/error_checker.php">Fejl tjekker</a>
    <a class="btn btn-info" href="/admin/lists/phh_monitor.php">Overvågning - PHH</a>
    <a class="btn btn-info" href="/admin/lists/online_times.php">Online Tid</a>
</section>
<?php
require "{$basepath}/global_modules/footer.php";
