<?php
$basepath = '../..';
require_once("{$basepath}/app_core/db_conf.php");
require_once("{$basepath}/app_core/functions/password_hash.php");
?>
<a href="/install/">go back</a>
<?php

/*$sql = "SELECT id FROM `{$GLOBALS['DB_NAME_OLD']}`.`Brugere` WHERE id = :user_id";*/

if (filter_input(INPUT_POST, 'install_action') === 'install_admin_user') {
    $sql = "INSERT INTO `{$GLOBALS['DB_NAME_OLD']}`.`Brugere` 
        (`stutteri`, `password`, `date`) VALUES (:user_name, :user_pass, NOW())";
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
    /* while (
        $row = $sth->fetch(PDO::FETCH_OBJ)
    ) {
        var_dump($row);
    }
    echo 'found user 1'; */
}



?>