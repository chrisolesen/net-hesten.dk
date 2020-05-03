<?php
$basepath = '../../..';
require_once "$basepath/app_core/object_loader.php";
require_once "$basepath/global_modules/header.php";

if (!in_array('global_admin', $_SESSION['rights'])) {
    ob_end_clean();
    header('Location: /');
}

if (filter_input(INPUT_GET, 'do_man_cron') === 'generate_horses') {
    include "$basepath/app_core/cron_files/test.php";
    chdir(dirname(__FILE__));
    $basepath = '../../..';
}
?>
<section>
    <h1>Crons - Generering</h1><br />
    <a class="btn btn-success" href="?do_man_cron=generate_horses">Kør script</a>
    <a class="btn btn-danger" href="./index.php">Tilbage</a>
</section>

<?php
require_once "$basepath/global_modules/footer.php";
