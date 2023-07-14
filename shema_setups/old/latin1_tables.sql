CREATE TABLE `Brugere` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`stutteri` varchar(50) NOT NULL,
	`password` varchar(200) NOT NULL,
	`navn` varchar(32) NOT NULL,
	`email` varchar(50) NOT NULL,
	`alder` varchar(10) NOT NULL,
	`kon` varchar(10) NOT NULL,
	`hestetegner` varchar(4) NOT NULL,
	`beskrivelse` text NOT NULL,
	`penge` bigint(20) NOT NULL,
	`thumb` varchar(50) NOT NULL,
	`date` datetime NOT NULL DEFAULT NOW(),
	`admin` char(5) NOT NULL,
	PRIMARY KEY (`id`),
	KEY `stutteri` (`stutteri`),
	UNIQUE KEY `stutteri_UNIQUE` (`stutteri`),
	UNIQUE KEY `email_UNIQUE` (`email`)
) AUTO_INCREMENT 5 ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `Chancen` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`chancetekst` text NOT NULL,
	`penge` varchar(20) NOT NULL,
	`thumb` varchar(50) NOT NULL,
	`date` datetime NOT NULL DEFAULT NOW(),
	`changedate` datetime NOT NULL DEFAULT NOW(),
	PRIMARY KEY (`id`)
) AUTO_INCREMENT 5 ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `Heste` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`bruger` varchar(50) NOT NULL,
	`navn` varchar(50) NOT NULL,
	`race` varchar(50) NOT NULL,
	`kon` varchar(10) NOT NULL,
	`alder` varchar(10) NOT NULL,
	`beskrivelse` text NOT NULL,
	`pris` varchar(20) NOT NULL,
	`graesning` varchar(4) NOT NULL,
	`staevne` varchar(4) NOT NULL,
	`foersteplads` varchar(4) NOT NULL,
	`andenplads` varchar(4) NOT NULL,
	`tredieplads` varchar(4) NOT NULL,
	`kaaring` varchar(4) NOT NULL,
	`kaaringer` varchar(4) NOT NULL,
	`status` varchar(10) NOT NULL,
	`partnerid` varchar(10) NOT NULL,
	`farid` varchar(10) NOT NULL,
	`morid` varchar(10) NOT NULL,
	`salgsstatus` varchar(2) NOT NULL,
	`original` varchar(4) NOT NULL,
	`genereres` varchar(4) NOT NULL,
	`genfodes` varchar(4) NOT NULL,
	`unik` varchar(4) NOT NULL,
	`tegner` varchar(50) NOT NULL,
	`thumb` varchar(100) NOT NULL,
	`date` datetime NOT NULL DEFAULT NOW(),
	`changedate` datetime NOT NULL DEFAULT NOW(),
	`statuschangedate` datetime NOT NULL DEFAULT NOW(),
	`saelger` text NOT NULL,
	`salgs_dato` text NOT NULL,
	`alder_skift` text NOT NULL,
	`status_skift` text NOT NULL,
	`hh_ownership` datetime NOT NULL,
	`death_date` date NOT NULL,
	`height` int(11) NOT NULL,
	`random_height` varchar(5) NOT NULL,
	`egenskab` varchar(100) NOT NULL,
	`ulempe` varchar(100) NOT NULL,
	`talent` varchar(100) NOT NULL,
	`age_updated` datetime NOT NULL DEFAULT NOW(),
	PRIMARY KEY (`id`),
	KEY `bruger` (`bruger`),
	KEY `kon` (`kon`,`race`),
	KEY `race` (`race`),
	KEY `status` (`status`,`alder`),
	KEY `date` (`date`),
	KEY `morogfar` (`morid`,`farid`),
	KEY `morid` (`morid`),
	KEY `farid` (`farid`)
) AUTO_INCREMENT 5	ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `Hesteracer` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`hesterace` varchar(50) NOT NULL,
	`date` datetime NOT NULL DEFAULT NOW(),
	PRIMARY KEY (`id`),
	KEY `hesterace` (`hesterace`)
) AUTO_INCREMENT 5	ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `horse_habits` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`Egenskab` text NOT NULL,
	`Ulempe` text NOT NULL,
	`Talent` text NOT NULL,
	PRIMARY KEY (`id`)
) AUTO_INCREMENT 5 ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `horse_height` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`race` text NOT NULL,
	`lower` int(11) NOT NULL,
	`upper` int(11) NOT NULL,
	PRIMARY KEY (`id`)
) AUTO_INCREMENT 5 ENGINE=MyISAM DEFAULT CHARSET=latin1;
