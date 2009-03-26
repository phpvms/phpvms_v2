CREATE TABLE `phpvms_awards` (
	`awardid` INT NOT NULL AUTO_INCREMENT ,
	`name` VARCHAR( 25 ) NOT NULL ,
	`descrip` VARCHAR( 100 ) NOT NULL ,
	`image` TEXT NOT NULL ,
PRIMARY KEY ( `awardid` )
) ENGINE = MYISAM;

CREATE TABLE `phpvms_awardsgranted` (
`id` INT NOT NULL AUTO_INCREMENT ,
`awardid` INT NOT NULL ,
`pilotid` INT NOT NULL ,
`dateissued` DATETIME NOT NULL ,
PRIMARY KEY ( `id` )
) ENGINE = MYISAM;

CREATE TABLE IF NOT EXISTS `phpvms_fuelprices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `icao` varchar(4) NOT NULL,
  `lowlead` float NOT NULL,
  `jeta` float NOT NULL,
  `dateupdated` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM;


UPDATE `phpvms_pilots` SET retired=0;

ALTER TABLE `phpvms_pilots` ADD `transferhours` FLOAT NOT NULL AFTER `totalhours`;

ALTER TABLE `phpvms_schedules` ADD `daysofweek` VARCHAR( 7 ) NOT NULL DEFAULT '0123456' AFTER `flighttime`; 