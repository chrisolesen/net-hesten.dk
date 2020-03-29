<?php
$basepath = '..';
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
        $sth = $GLOBALS['pdo_new']->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(['user_name' => filter_input(INPUT_POST, 'username'), 'user_pass' => cbc_pwhash(filter_input(INPUT_POST, 'password'))]);
    }

    $sql = "SELECT id FROM `{$GLOBALS['DB_NAME_OLD']}`.`Brugere` LIMIT 1";
    $sth = $GLOBALS['pdo_new']->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
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
    } else {
        echo "<br />Admin user setup correctly";
        $user_id = $sth->fetchObject()->id;
        /* Initialize admin privilegde */
        $sql = "SELECT `privilege_id` FROM `{$GLOBALS['DB_NAME_NEW']}`.`privilege_types` WHERE `privilege_name` = 'global_admin' LIMIT 1";
        $sth = $GLOBALS['pdo_new']->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute();
        if (!$sth->rowCount()) {
            $sql = "INSERT INTO `{$GLOBALS['DB_NAME_NEW']}`.`privilege_types` (`privilege_name`) VALUES ('global_admin')";
            $sth_priv_insert = $GLOBALS['pdo_new']->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
            $sth_priv_insert->execute();
        }
        $priv_id = $sth->fetchObject()->privilege_id;

        $sql = "SELECT `start` FROM `{$GLOBALS['DB_NAME_NEW']}`.`user_privileges` WHERE `user_id` = :user_id AND `privilege_id` = :priv_id LIMIT 1";
        $sth = $GLOBALS['pdo_new']->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(['user_id' => $user_id, 'priv_id' => $priv_id]);
        if (!$sth->rowCount()) {
            $sql = "INSERT INTO `{$GLOBALS['DB_NAME_NEW']}`.`user_privileges` 
            (`user_id`, `privilege_id`, `start`, `end`) VALUES (:user_id, :priv_id, NOW(), '0000-00-00 00:00:00')";
            $sth = $GLOBALS['pdo_new']->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
            $sth->execute(['user_id' => $user_id, 'priv_id' => $priv_id]);
        } else {
            echo "<br />Privilege Tables initialised";
        }
        /* Initialize horse types */
        $sql = "SELECT id FROM `{$GLOBALS['DB_NAME_NEW']}`.`horse_types` WHERE `image` = 'ghost_horse.png' LIMIT 1";
        $sth = $GLOBALS['pdo_new']->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute();
        if (!$sth->rowCount()) {
            /* Insert horse type */
            $sql = "INSERT INTO `{$GLOBALS['DB_NAME_NEW']}`.`horse_types` 
        (`race`, `image`, `status`, `date`, `allowed_gender`, `archived`, `artists`) VALUES 
        ('Ghosts', 'ghost_horse.png', 22, NOW(), 1,0,:user_id)";
            $sth = $GLOBALS['pdo_new']->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
            $sth->execute(['user_id' => $user_id]);
            echo "<br />Horse type added";
        }

        $sql = "SELECT id FROM `{$GLOBALS['DB_NAME_OLD']}`.`horse_habits` LIMIT 1";
        $sth = $GLOBALS['pdo_new']->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute();
        if (!$sth->rowCount()) {
            /* Insert horse habit type */
            $sql = "INSERT INTO `{$GLOBALS['DB_NAME_OLD']}`.`horse_habits` 
        (`egenskab`, `ulempe`, `talent`) VALUES 
        ('Sød', 'Drilsk', 'Spring')";
            $sth = $GLOBALS['pdo_new']->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
            $sth->execute();
            echo "<br />Horse habit type added";
        }
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
    ?>
</body>

</html>
<!-- 
INSERT INTO `old`.`Brugere` (`id`, `stutteri`, `navn`, `penge`,`email`, `date`) VALUES (5, 'SystemPrivatHandel', 'Privat Hestehandler', 0,'sph@', NOW()); 
INSERT INTO `old`.`Brugere` (`id`, `stutteri`, `navn`, `email`, `penge`, `date`) VALUES (4, 'Auktionshuset', 'Auktionshuset', 'ah@',0, NOW());
INSERT INTO `old`.`Chancen` (`chancetekst`,`penge`,`thumb`,`date`,`changedate`) VALUES ('test',1000,'',NOW(),NOW());
-->