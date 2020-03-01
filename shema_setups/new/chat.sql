CREATE TABLE `game_data_chat_messages` IF NOT EXISTS (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `creator` bigint(20) NOT NULL,
  `status_code` smallint(5) NOT NULL,
  `creation_date` datetime NOT NULL,
  `value` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `game_data_alias_chat` IF NOT EXISTS (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `alias_id` bigint(20) unsigned NOT NULL,
  `creator` bigint(20) unsigned NOT NULL,
  `status_code` tinyint(3) unsigned NOT NULL DEFAULT '11',
  `alias_type` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `creation_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `value` blob NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `game_data_private_messages` IF NOT EXISTS (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `status_code` smallint(5) unsigned NOT NULL,
  `hide` tinyint(3) unsigned NOT NULL,
  `origin` bigint(20) NOT NULL,
  `target` bigint(20) NOT NULL,
  `thread` bigint(20) unsigned NOT NULL DEFAULT '1',
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `message` longblob NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
