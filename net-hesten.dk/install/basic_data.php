<?php
$basepath = '../..';
require_once("{$basepath}/app_core/db_conf.php");
require_once("{$basepath}/app_core/functions/password_hash.php");
?>
<!DOCTYPE html>
<html>

<head>
    <title>Net-hesten - Project installer</title>
</head>

<body>
    <a href="/install/">Go back</a>
    <?php

    if (filter_input(INPUT_POST, 'install_action') === 'install_admin_user') {
        $sql = "INSERT INTO `{$GLOBALS['DB_NAME_OLD']}`.`Brugere` 
    (`stutteri`, `password`, `date`, `penge`) VALUES (:user_name, :user_pass, NOW(), 1000000)";
        $sth = $GLOBALS['pdo_old']->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(['user_name' => filter_input(INPUT_POST, 'username'), 'user_pass' => cbc_pwhash(filter_input(INPUT_POST, 'password'))]);
    }



    $sql = "SELECT id FROM `{$GLOBALS['DB_NAME_OLD']}`.`Brugere` LIMIT 1";
    $sth = $GLOBALS['pdo_old']->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $sth->execute();
    if (!$sth->rowCount()) {
        /* No admin user detected */
    ?>
        <form method="post" action="">
            <input type="hidden" name="install_action" value="install_admin_user" />
            <input type="text" name="username" placeholder="Username:" />
            <input type="text" name="password" placeholder="Password:" />
            <input type="submit" name="save_admin" />
        </form>
    <?php
    }
    /* Initialize horse races */
    $sql = "SELECT id FROM `{$GLOBALS['DB_NAME_NEW']}`.`horse_races` LIMIT 1";
    $sth = $GLOBALS['pdo_new']->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $sth->execute();
    if (!$sth->rowCount()) {
        /* Insert horse race */
        $sql = "INSERT INTO `{$GLOBALS['DB_NAME_NEW']}`.`horse_races` 
    (`name`, `min_height`, `max_height`, `description`) VALUES 
    ('Ghosts', 150, 170, 'Mythical creatures')";
        $sth = $GLOBALS['pdo_new']->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute();
    }
    /* Initialize horse types */
    $sql = "SELECT id FROM `{$GLOBALS['DB_NAME_NEW']}`.`horse_types` LIMIT 1";
    $sth = $GLOBALS['pdo_new']->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $sth->execute();
    if (!$sth->rowCount()) {
        /* Insert horse type */
        $sql = "INSERT INTO `{$GLOBALS['DB_NAME_NEW']}`.`horse_types` 
    (`race`, `image`, `status`, `date`, `allowed_gender`, `archived`, `artists`) VALUES 
    ('Ghosts', 'Ghost_01.png', 22, NOW(), 1,0,0)";
        $sth = $GLOBALS['pdo_new']->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute();
    }
    ?>
</body>

</html>