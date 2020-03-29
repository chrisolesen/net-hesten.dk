<?php
require "$basepath/app_core/db_conf.php";
require "$basepath/app_core/user_validate.php";
if ((!$_SESSION['logged_in'] == true || !isset($basepath)) && filter_input(INPUT_SERVER, 'REQUEST_URI') !== '/') {
    header("Location: /");
}
ob_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <link href='https://fonts.googleapis.com/css?family=Work+Sans:400,600' rel='stylesheet' type='text/css'>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
        <link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
        <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
        <link rel="stylesheet" href="/admin/style/main.css" />
        <?php if ($responsive) { ?>
            <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1, user-scalable=no">
        <?php } ?>
    </head>
    <body>
        <section class="page_wrap">
            <header>
                <h1 class="raised">Net-Hesten - <?= $title ? $title : 'Backend panel'; ?></h1>
                <h2 class="raised">Velkommen til "<?= $_SESSION['username']; ?>"</h2>
            </header>
            <br />
            <a href="/">Til forsiden</a> <a href="/admin/">Til panelet</a>
            <br /><br />