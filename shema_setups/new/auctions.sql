CREATE TABLE `game_data_auctions` (
	`id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
	`creator` bigint(20) unsigned NOT NULL,
	`status_code` smallint(5) unsigned NOT NULL,
	`object_id` bigint(20) unsigned NOT NULL,
	`object_type` bigint(20) unsigned NOT NULL,
	`minimum_price` bigint(20) unsigned NOT NULL,
	`instant_price` bigint(20) unsigned NOT NULL,
	`creation_date` datetime NOT NULL,
	`end_date` datetime NOT NULL,
	`highest_bidder` bigint(20) unsigned DEFAULT NULL,
	`highest_bid` bigint(20) unsigned DEFAULT NULL,
	PRIMARY KEY (`id`),
	UNIQUE KEY `id_UNIQUE` (`id`)
) AUTO_INCREMENT 5 ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `game_data_auction_bids` (
	`creator` bigint(20) unsigned NOT NULL,
	`auction` bigint(20) unsigned NOT NULL,
	`bid_amount` bigint(20) unsigned NOT NULL,
	`bid_date` datetime NOT NULL,
	`status_code` smallint(5) unsigned NOT NULL,
	UNIQUE KEY `idx_game_data_auction_bids_bid_date_bid_amount_auction_creator` (`bid_date`,`bid_amount`,`auction`,`creator`)
) AUTO_INCREMENT 5 ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
 