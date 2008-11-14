
ALTER TABLE `phpvms_pilots` DROP FOREIGN KEY `phpvms_pilots_ibfk_2`;
ALTER TABLE `phpvms_pilots` DROP INDEX `rank`;
ALTER TABLE `phpvms_pireps` ADD `log` TEXT NOT NULL;
ALTER TABLE `phpvms_pireps` DROP FOREIGN KEY `phpvms_pireps_ibfk_1`;
ALTER TABLE `phpvms_pireps` DROP INDEX `code`;
ALTER TABLE `phpvms_pireps` DROP FOREIGN KEY `phpvms_pireps_ibfk_3`;
ALTER TABLE `phpvms_pireps` DROP INDEX `aircraft`;
ALTER TABLE `phpvms_pireps` DROP INDEX `flightnum`;
ALTER TABLE `phpvms_schedules` DROP FOREIGN KEY `phpvms_schedules_ibfk_2`;
ALTER TABLE `phpvms_schedules` DROP INDEX `aircraft`;
ALTER TABLE `phpvms_pilots` ADD `totalpay` FLOAT NOT NULL DEFAULT '0' AFTER `totalhours`;
ALTER TABLE `phpvms_ranks` ADD `payrate` FLOAT NOT NULL DEFAULT '0';

CREATE TABLE `phpvms_acarsdata` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`pilotid` VARCHAR( 11 ) NOT NULL ,
	`flightnum` VARCHAR( 11 ) NOT NULL ,
	`pilotname` VARCHAR( 100 ) NOT NULL ,
	`aircraft` VARCHAR( 12 ) NOT NULL ,
	`lat` VARCHAR( 15 ) NOT NULL ,
	`lng` VARCHAR( 15 ) NOT NULL ,
	`heading` SMALLINT NOT NULL ,
	`alt` VARCHAR( 6 ) NOT NULL ,
	`gs` INT NOT NULL ,
	`depicao` VARCHAR( 4 ) NOT NULL ,
	`depapt` VARCHAR( 255 ) NOT NULL ,
	`arricao` VARCHAR( 4 ) NOT NULL ,
	`arrapt` TEXT NOT NULL ,
	`deptime` TIME NOT NULL ,
	`arrtime` TIME NOT NULL ,
	`distremain` VARCHAR( 6 ) NOT NULL ,
	`timeremaining` VARCHAR ( 6 ) NOT NULL ,
	`phasedetail` VARCHAR( 255 ) NOT NULL ,
	`online` VARCHAR( 10 ) NOT NULL ,
	`messagelog` TEXT NOT NULL,
	`lastupdate` DATETIME NOT NULL,
	`client` VARCHAR( 15 ) NOT NULL,
	PRIMARY KEY ( `id` ) ,
	INDEX ( `pilotid` )
)ENGINE=INNODB; 

ALTER TABLE `phpvms_acarsdata` ADD `timeremaining` VARCHAR( 6 ) NOT NULL AFTER `deptime`';