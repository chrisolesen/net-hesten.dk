<?php
$basepath = '../..';
require_once("{$basepath}/app_core/db_conf.php");

/*$sql = 'INSERT INTO praktisk_dev_nethest_new.horse_races';*/

$users = $link_old->query("SELECT id FROM `{$GLOBALS['DB_NAME_OLD']}`.`Brugere` WHERE id = 1");
while($row = $users->fetch_object()){
    var_dump($row);
}
echo "<br />result via mysqli<br />";


$sql = "SELECT id FROM `{$GLOBALS['DB_NAME_OLD']}`.`Brugere` WHERE id = ?";
$sth = $GLOBALS['pdo_old']->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
$sth->execute([':user_id' => 150]);
if ($sth->rowCount()) {
    echo '<br />found user 150<br />';
} else {
    echo '<br />no user 150<br />';
}

$sql = "SELECT id FROM `{$GLOBALS['DB_NAME_OLD']}`.`Brugere` WHERE id = ?";
$sth = $GLOBALS['pdo_old']->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
$sth->execute([':user_id' => 1]);
if ($sth->rowCount()) {
    while (
        $row = $sth->fetch(PDO::FETCH_OBJ)
    ) {
        var_dump($row);
    }
    echo 'found user 1';
} else {
    echo 'no user 1';
}
exit();

/*$result = $sth->fetchAll();*/
/* Insert a horse race */
$sql = "SELECT id FROM `{$GLOBALS['DB_NAME_NEW']}`.`horse_races` LIMIT 1";
try {
    $statement = $GLOBALS['pdo_new']->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY))->execute();
    $race = $statement->fetch();
} catch (Exception $e) {
    /* $GLOBALS['pdo_new']->rollback();*/
    throw $e;
}
var_dump($race);
if (!$race) {
    $sql = "INSERT INTO `{$GLOBALS['DB_NAME_NEW']}`.`horse_races` (`name`,`max_height`,`min_height`,`description`) VALUES (:name, :max_height, :min_height, :description)";
    try {
        $statement = $GLOBALS['pdo_new']->prepare($sql);
        $GLOBALS['pdo_new']->beginTransaction();
        $statement->execute(['name' => 'Ghosts', 'max_height' => 170, 'min_height' => 150, 'description' => 'Mythical creatures']);
        $GLOBALS['pdo_new']->commit();
    } catch (Exception $e) {
        $GLOBALS['pdo_new']->rollback();
        throw $e;
    }
}

$sql = "SELECT id FROM `{$GLOBALS['DB_NAME_OLD']}`.`Brugere` LIMIT 1";
$user = $GLOBALS['pdo_new']->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY))->fetch();
var_dump($user);
/*
INSERT into `{$GLOBALS['DB_NAME_OLD']}`.`Brugere` (stutteri, password, navn, email, hestetegner, beskrivelse, penge, date) VALUES ('admin_usr', 'pass', 'name', 'mail', 'ja', '', '100000000', NOW());
*//*
$stmt = $pdo->query("SELECT * FROM users ORDER BY id DESC LIMIT 1");
$user = $stmt->fetch();
*/
?>
<a href="/install/">go back</a>