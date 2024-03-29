CREATE TABLE `game_data_status_codes` (
	`id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
	`name` varchar(255) CHARACTER SET utf8 NOT NULL,
	`decription` text CHARACTER SET latin1,
	PRIMARY KEY (`id`),
	UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `privilege_types` (
	`privilege_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
	`privilege_name` varchar(250) CHARACTER SET utf8 NOT NULL,
	PRIMARY KEY (`privilege_id`),
	UNIQUE KEY `privilege_id_UNIQUE` (`privilege_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `game_data_object_types` (
	`id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
	`name` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `game_data_entity_types` (
	`id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
	`name` varchar(255) DEFAULT NULL,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
