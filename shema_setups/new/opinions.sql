CREATE TABLE `game_date_opinion_pools` (
	`id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
	`status` smallint(5) unsigned NOT NULL,
	`content` blob NOT NULL,
	`start_date` datetime NOT NULL,
	`end_date` datetime NOT NULL,
	PRIMARY KEY (`id`),
	UNIQUE KEY `id_UNIQUE` (`id`)
) AUTO_INCREMENT 5 ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT=' ';
