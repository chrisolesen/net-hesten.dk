<?php

$basepath = '../../..';
require "{$basepath}/app_core/object_loader.php";
require "{$basepath}/global_modules/header.php";

if (!in_array('global_admin', $_SESSION['rights'])) {
	ob_end_clean();
	header('Location: /');
	exit();
}

$remind_users = $link_new->query("SELECT `nt`.`value`, `ot`.`stutteri`, `ot`.`email`, `ot`.`id` 
FROM `praktisk_nethest_new`.`user_data_timing` `nt` 
LEFT JOIN `praktisk_nethest_old_db`.`Brugere` `ot` ON `nt`.`parent_id` = `ot`.`id` 
WHERE `nt`.`name` = 'last_active' 
AND `nt`.`parent_id` NOT IN ({$GLOBALS['hidden_system_users_sql']}) 
AND `ot`.`stutteri` IS NOT NULL 
ORDER BY `nt`.`value` ASC;
");

while ($remind_user = $remind_users->fetch_object()) {
	echo $remind_user->stutteri . '<br />';
	echo $remind_user->email . '<br />';
	echo (new DateTime($remind_user->value))->format('j/n - Y H:i:s') . '<br />';
	echo '<br />';
}

require "{$basepath}/global_modules/footer.php";
