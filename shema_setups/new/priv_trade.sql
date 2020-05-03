CREATE TABLE `game_data_private_trade` (
	`id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
	`seller` bigint(20) unsigned NOT NULL,
	`buyer` bigint(20) unsigned NOT NULL,
	`horse_id` bigint(20) unsigned NOT NULL,
	`price` bigint(20) unsigned NOT NULL,
	`creation_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`end_date` datetime DEFAULT NULL,
	`status_code` smallint(5) unsigned DEFAULT '38',
	PRIMARY KEY (`id`,`seller`,`buyer`),
	UNIQUE KEY `id_UNIQUE` (`id`)
) AUTO_INCREMENT 5 ENGINE=InnoDB DEFAULT CHARSET=latin1;
