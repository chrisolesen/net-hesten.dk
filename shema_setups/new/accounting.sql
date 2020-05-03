CREATE TABLE `game_data_accounting` ( 
	`id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
	`user_id` bigint(20) unsigned NOT NULL,
	`entry_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`line_meta` blob NOT NULL,
	`line_amount` bigint(20) NOT NULL,
	PRIMARY KEY (`id`)
) AUTO_INCREMENT 5 ENGINE=InnoDB DEFAULT CHARSET=utf8;
