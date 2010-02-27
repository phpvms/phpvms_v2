CREATE TABLE `phpvms_expenselog` (
	`dateadded` INT NOT NULL ,
	`name` VARCHAR( 25 ) NOT NULL ,
	`type` VARCHAR( 2 ) NOT NULL ,
	`cost` FLOAT NOT NULL ,
	INDEX ( `dateadded` )
) ENGINE = MYISAM ;

ALTER TABLE `phpvms_pireps` ADD `gross` FLOAT NOT NULL AFTER `flighttype`;

ALTER TABLE `phpvms_pireps` ADD `route` TEXT NOT NULL AFTER `arricao` ,
ADD `route_details` TEXT NOT NULL AFTER `route`;

ALTER TABLE `phpvms_acarsdata` ADD `route` TEXT NOT NULL AFTER `arrtime`,
ADD `route_details` TEXT NOT NULL AFTER `route` ;

ALTER TABLE `phpvms_schedules` ADD `route_details` TEXT NOT NULL AFTER `route`;

CREATE TABLE IF NOT EXISTS `phpvms_navdata` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(7) NOT NULL,
  `title` varchar(25) NOT NULL,
  `airway` varchar(7) DEFAULT NULL,
  `airway_type` varchar(1) DEFAULT NULL,
  `seq` int(11) NOT NULL,
  `loc` varchar(4) NOT NULL,
  `lat` float(8,6) NOT NULL,
  `lng` float(9,6) NOT NULL,
  `freq` varchar(7) NOT NULL,
  `type` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `airway` (`airway`)
) ENGINE=MyISAM;