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
	<a href="/index.php">Go back</a>
	<?php

	if (filter_input(INPUT_POST, 'install_action') === 'install_admin_user') {
		$sql = "INSERT INTO `{$GLOBALS['DB_NAME_OLD']}`.`Brugere` 
			(`stutteri`, `password`, `date`, `penge`,`navn`,`email`,`alder`,`kon`,`hestetegner`,`beskrivelse`,`thumb`,`admin`) 
			VALUES 
			(:user_name, :user_pass, NOW(), 1000000, 'net-hesten', 'mail@net-hesten.local',18,'i',0,'','',1)";
		$sth = $GLOBALS['pdo_new']->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(['user_name' => filter_input(INPUT_POST, 'username'), 'user_pass' => cbc_pwhash(filter_input(INPUT_POST, 'password'))]);


		$sql = "SELECT id FROM `{$GLOBALS['DB_NAME_OLD']}`.`Heste` LIMIT 1";
		$sth = $GLOBALS['pdo_new']->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute();
		if (!$sth->rowCount()) {
			/* Insert horse race heights */
			$sql = "INSERT INTO `{$GLOBALS['DB_NAME_OLD']}`.`Heste`
			(`bruger`,
			`navn`,
			`race`,
			`kon`,
			`alder`,
			`pris`,
			`status`,
			`tegner`,
			`thumb`,
			`date`,
			`changedate`,
			`statuschangedate`,
			`height`,
			`egenskab`,
			`ulempe`,
			`talent`,
			`age_updated`,
			`beskrivelse`,`graesning`,`staevne`,`foersteplads`,`andenplads`,`tredieplads`,`kaaring`,`kaaringer`,
			`partnerid`,`farid`,`morid`,`salgsstatus`,`original`,`genereres`,`genfodes`,`unik`,`saelger`,`salgs_dato`,`alder_skift`,`status_skift`,
			`hh_ownership`,`death_date`,`random_height`
			)
			VALUES
			(:user_name,
			'Stallion one',
			'Ghosts',
			'Hingst',
			'4',
			'15000',
			'Hest',
			:user_name,
			'imgHorse/ghost_horse_r.png',
			NOW(),
			NOW(),
			NOW(),
			100,
			'Sød',
			'Drilsk',
			'Spring',
			NOW(),
			'','','',0,0,0,'','',
			0,0,0,0,0,0,0,0,'','0000-00-00','0000-00-00','0000-00-00',
			'0000-00-00 00:00:00','0000-00-00',''
			),
			(:user_name,
			'Mare one',
			'Ghosts',
			'Hoppe',
			'4',
			'15000',
			'Hest',
			:user_name,
			'imgHorse/ghost_horse.png',
			NOW(),
			NOW(),
			NOW(),
			100,
			'Sød',
			'Drilsk',
			'Spring',
			NOW(),
			'','','',0,0,0,'','',
			0,0,0,0,0,0,0,0,'','0000-00-00','0000-00-00','0000-00-00',
			'0000-00-00 00:00:00','0000-00-00',''
			),
			(:user_name,
			'Foel one',
			'Ghosts',
			'Hoppe',
			'0',
			'6000',
			'Føl',
			:user_name,
			'imgHorse/ghost_foel.png',
			NOW(),
			NOW(),
			NOW(),
			100,
			'Sød',
			'Drilsk',
			'Spring',
			NOW(),
			'','','',0,0,0,'','',
			0,0,0,0,0,0,0,0,'','0000-00-00','0000-00-00','0000-00-00',
			'0000-00-00 00:00:00','0000-00-00',''
			)
				;";
			$sth = $GLOBALS['pdo_new']->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
			$sth->execute(['user_name' => filter_input(INPUT_POST, 'username')]);
			echo "<br />Gave horse to admin";
		}
		echo "<br />Admin user setup correctly";
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

		$sql = "SELECT id FROM `{$GLOBALS['DB_NAME_OLD']}`.`Brugere` LIMIT 1";
		$sth = $GLOBALS['pdo_new']->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute();
		$user_id = $sth->fetchObject()->id;
		/* Initialize admin privilegde */

		$sql = "SELECT `start` FROM `{$GLOBALS['DB_NAME_NEW']}`.`user_privileges` WHERE `user_id` = :user_id AND `privilege_id` = 1 LIMIT 1";
		$sth = $GLOBALS['pdo_new']->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(['user_id' => $user_id]);
		if (!$sth->rowCount()) {
			$sql = "INSERT INTO `{$GLOBALS['DB_NAME_NEW']}`.`user_privileges` 
				(`user_id`, `privilege_id`, `start`, `end`) 
				VALUES (:user_id, 1, NOW(), '0000-00-00 00:00:00'),
				(:user_id, 11, NOW(), '0000-00-00 00:00:00')
				";
			$sth = $GLOBALS['pdo_new']->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
			$sth->execute(['user_id' => $user_id]);
			echo "<br />Privilege Tables initialised";
		}
		/* Initialize horse types */
		$sql = "SELECT id FROM `{$GLOBALS['DB_NAME_NEW']}`.`horse_types` WHERE `image` = 'ghost_horse.png' LIMIT 1";
		$sth = $GLOBALS['pdo_new']->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute();
		if (!$sth->rowCount()) {
			/* Insert horse type */
			$sql = "INSERT INTO `{$GLOBALS['DB_NAME_NEW']}`.`horse_types` 
			(`race`, `image`, `status`, `date`, `allowed_gender`, `archived`, `artists`) 
			VALUES 
			('Ghosts', 'ghost_horse.png', 22, NOW(), 1,0,:user_id),
			('Ghosts', 'ghost_horse_r.png', 22, NOW(), 1,0,:user_id),
			('Ghosts', 'ghost_foel.png', 26, NOW(), 1,0,:user_id),
			('Ghosts', 'ghost_foel_r.png', 26, NOW(), 1,0,:user_id)
        ";
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
			('Sød', 'Drilsk', 'Spring'), ('Elsker mad', 'Genert', 'Dressur'), ('Elsker sin ejer', 'Larmende', 'Western')";
			$sth = $GLOBALS['pdo_new']->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
			$sth->execute();
			echo "<br />Horse habit types added";
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
		echo "<br />Horse race added";
	}

	$sql = "SELECT id FROM `{$GLOBALS['DB_NAME_OLD']}`.`horse_height` LIMIT 1";
	$sth = $GLOBALS['pdo_new']->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
	$sth->execute();
	if (!$sth->rowCount()) {
		/* Insert horse race heights */
		$sql = "INSERT INTO `{$GLOBALS['DB_NAME_OLD']}`.`horse_height` 
    (`race`, `lower`, `upper`) VALUES  
    ('Ghosts', 120, 160)";
		$sth = $GLOBALS['pdo_new']->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute();
		echo "<br />Horse heights added";
	}

	$sql = "SELECT `privilege_id` FROM `{$GLOBALS['DB_NAME_NEW']}`.`privilege_types` WHERE `privilege_name` = 'global_admin' LIMIT 1";
	$sth = $GLOBALS['pdo_new']->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
	$sth->execute();
	if (!$sth->rowCount()) {
		$sql = "INSERT INTO `{$GLOBALS['DB_NAME_NEW']}`.`privilege_types` (`privilege_id`,`privilege_name`) 
			VALUES 
			(1, 'global_admin'),(11, 'tech_admin'),(7, 'admin_users_all'),(4, 'forum_admin'),
			(2, 'hestetegner_admin'),(13, 'ad_administrator'),
			(3, 'forum_moderator'),
			(9, 'site_helper'),(10, 'site_tester'),(12, 'admin_template_helper'),
			(8, 'admin_panel_access'),
			(5, 'hestetegner'),(6, 'blocked')
            ";
		$sth_priv_insert = $GLOBALS['pdo_new']->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth_priv_insert->execute();
		echo '<br/>privilege_types setup done';
	}

	$sql = "SELECT `name` FROM `{$GLOBALS['DB_NAME_NEW']}`.`game_data_status_codes` LIMIT 1";
	$sth = $GLOBALS['pdo_new']->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
	$sth->execute();
	if (!$sth->rowCount()) {
		$sql = "INSERT INTO `{$GLOBALS['DB_NAME_NEW']}`.`game_data_status_codes` (`id`,`name`) 
			VALUES 
			(1,'auction_live'),(2,'auction_ended'),(3,'auction_halted'),(4,'bid_accepted'),(5,'bid_refunded'),(6,'bid_won'),(10,'bid_placed'),
			(7,'object_allowed'),(8,'object_preferred'),(9,'object_disabled'),			
			(17,'pb_send'),(18,'pb_read'),(11,'mod_unmoderated'),(12,'mod_flagged'),(13,'mod_deleted'),(14,'mod_approved'),
			(15,'pool_live'),(16,'pool_closed'),
			(19,'type_unique'),(20,'type_generation'),(21,'type_rebirth'),(22,'type_rebirth_generation'),(23,'type_rebirth_unique'),(24,'type_ordinary'),(25,'type_foel'),(26,'type_foel_rebirth'),
			(27,'drawing_submitted'),(28,'drawing_approved'),(29,'drawing_rejected'),
			(30,'competition_ongoing'),(31,'competition_ended'),(32,'competition_in_que'),(41,'competion_type_horsetype'),
			(33,'shop_order_approved'),(34,'shop_order_waiting'),(35,'shop_order_rejected'),(36,'shop_order_aborted'),(37,'shop_order_error'),
			(38,'private_trade_open'),(39,'private_trade_closed'),(40,'private_trade_accepted'),(44,'private_trade_requested'),
			(42,'generel_live'),(43,'generel_ended')			
		";
		$sth_priv_insert = $GLOBALS['pdo_new']->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth_priv_insert->execute();
		echo '<br/>game_data_status_codes setup done';
	}
	?>
</body>

</html>
<!-- 
	INSERT INTO `old`.`Brugere` (`id`, `stutteri`, `navn`, `email`, `penge`, `date`) VALUES (53844, 'Konkurrencestyrelsen', 'Konkurrencestyrelsen', 'ks@',0, NOW());
	INSERT INTO `old`.`Brugere` (`id`, `stutteri`, `navn`, `penge`,`email`, `date`) VALUES (5, 'SystemPrivatHandel', 'Privat Hestehandler', 0,'sph@', NOW()); 
	INSERT INTO `old`.`Brugere` (`id`, `stutteri`, `navn`, `email`, `penge`, `date`) VALUES (52745, 'Auktionshuset', 'Auktionshuset', 'ah@',0, NOW());
	INSERT INTO `old`.`Chancen` (`chancetekst`,`penge`,`thumb`,`date`,`changedate`) VALUES ('test',1000,'',NOW(),NOW());
-->