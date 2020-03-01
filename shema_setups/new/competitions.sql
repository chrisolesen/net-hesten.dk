CREATE TABLE `game_data_competitions` IF NOT EXISTS (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `status_code` smallint(5) unsigned NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `allowed_races` varchar(255) DEFAULT NULL,
  `allowed_types` varchar(255) DEFAULT NULL,
  `allowed_min_age` tinyint(3) unsigned DEFAULT NULL,
  `allowed_max_age` tinyint(3) unsigned DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `description` longtext NOT NULL,
  `prices` longblob,
  `participant_structure` longblob,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `game_data_competition_participants` IF NOT EXISTS (
  `participant_id` bigint(20) unsigned NOT NULL,
  `competition_id` bigint(20) unsigned NOT NULL,
  `signup_date` datetime DEFAULT NULL,
  `points` longblob,
  PRIMARY KEY (`participant_id`,`competition_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `game_data_simple_competition` IF NOT EXISTS (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `competition_name` varchar(255) NOT NULL,
  `competition_description` text,
  `startdate` datetime NOT NULL,
  `enddate` datetime NOT NULL,
  `status_code` tinyint(3) unsigned NOT NULL,
  `data` blob NOT NULL,
  `winner` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `game_data_simple_competition_participants` IF NOT EXISTS (
  `competition_id` bigint(20) unsigned NOT NULL,
  `participant_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`competition_id`,`participant_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
