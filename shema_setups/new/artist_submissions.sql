CREATE TABLE `artist_center_submissions` (  
	`id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
	`race` varchar(255) DEFAULT NULL,
	`image` varchar(255) NOT NULL,
	`status` tinyint(3) unsigned DEFAULT NULL,
	`artist` varchar(255) DEFAULT NULL,
	`height` smallint(5) unsigned DEFAULT NULL,
	`width` smallint(5) unsigned DEFAULT NULL,
	`date` datetime NOT NULL,
	`occasion` varchar(255) NOT NULL,
	`theme` varchar(255) NOT NULL,
	`type` varchar(255) NOT NULL,
	`admin_comment` mediumtext,
	PRIMARY KEY (`id`),
	UNIQUE KEY `id_UNIQUE` (`id`),
	UNIQUE KEY `image_UNIQUE` (`image`)
) AUTO_INCREMENT 5 ENGINE=InnoDB DEFAULT CHARSET=latin1;
