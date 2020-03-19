ALTER TABLE `TABLENEW`.`game_data_auctions` 
ADD COLUMN `highest_bidder` BIGINT(20) UNSIGNED NULL AFTER `end_date`,
ADD COLUMN `highest_bid` BIGINT(20) UNSIGNED NULL AFTER `highest_bidder`,
CHANGE COLUMN `creation_date` `creation_date` DATETIME NOT NULL DEFAULT NOW() ;