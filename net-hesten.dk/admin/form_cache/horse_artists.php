<?php
$basepath = '../../..';
require "$basepath/app_core/object_loader.php";
require "$basepath/global_modules/header.php";
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
    <h1>Heste tegner cache opdateret</h1>
    <a class="btn btn-info" href="/admin/">Tilbage</a>
    <?php

    $result = $link_new->query("SELECT DISTINCT `tegner` FROM `{$GLOBALS['DB_NAME_OLD']}`.`Heste` WHERE `status` <> 'død'");
    $file_content = '<?php' . PHP_EOL;
    $file_content .= '/* This file is auto generated by "/admin/from_cache/horse_artists.php" */' . PHP_EOL;
    $file_content .= '$cached_artists = [];' . PHP_EOL;
    while ($artist = $result->fetch_object()) {
        $file_content .= "\$cached_artists[] = (object) ['name' => '{$artist->tegner}'];" . PHP_EOL;
    }
    file_put_contents("{$basepath}/files.net-hesten.dk/cache_data/cached_artists.php", $file_content);

    ?>
</section>
<?php
require "$basepath/global_modules/footer.php";
