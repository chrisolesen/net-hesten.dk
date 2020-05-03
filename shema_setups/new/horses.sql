CREATE TABLE `horse_metadata` (
  `horse_id` bigint(20) unsigned NOT NULL,
  `meta_key` varchar(250) NOT NULL,
  `meta_value` longblob,
  `meta_date` datetime NOT NULL,
  PRIMARY KEY (`horse_id`,`meta_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `horse_races` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `max_height` smallint(5) unsigned NOT NULL,
  `min_height` smallint(5) unsigned NOT NULL,
  `description` longtext CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idhorse_races_UNIQUE` (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `horse_templates` (
	`id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
	`image` varchar(255) NOT NULL,
	`stance` varchar(100) NOT NULL,
	`status` smallint(5) unsigned NOT NULL,
	`fetlock` bit(1) DEFAULT NULL,
	`pixel` bit(1) DEFAULT NULL,
	`foel` bit(1) DEFAULT NULL,
	`pony` bit(1) DEFAULT NULL,
	`special_note` varchar(100) DEFAULT NULL,
	`suggested_races` blob,
	`date` datetime DEFAULT NULL,
	PRIMARY KEY (`id`),
	UNIQUE KEY `id_UNIQUE` (`id`),
	UNIQUE KEY `image_UNIQUE` (`image`)
) AUTO_INCREMENT 5 ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `horse_types` (
	`id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
	`template` bigint(20) unsigned DEFAULT NULL,
	`race` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
	`image` varchar(255) CHARACTER SET utf8 NOT NULL,
	`status` tinyint(3) unsigned DEFAULT NULL,
	`allowed_gender` tinyint(3) unsigned DEFAULT '1',
	`archived` tinyint(1) NOT NULL DEFAULT '0',
	`artists` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
	`height` smallint(5) unsigned DEFAULT NULL,
	`width` smallint(5) unsigned DEFAULT NULL,
	`date` datetime NOT NULL,
	PRIMARY KEY (`id`),
	UNIQUE KEY `id_UNIQUE` (`id`),
	UNIQUE KEY `image_UNIQUE` (`image`)
) AUTO_INCREMENT 5 ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
