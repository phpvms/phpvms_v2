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
	`phasedetail` VARCHAR( 255 ) NOT NULL ,
	`online` VARCHAR( 10 ) NOT NULL ,
	`messagelog` TEXT NOT NULL,
	`lastupdate` DATETIME NOT NULL,
	`client` VARCHAR( 15 ) NOT NULL,
	PRIMARY KEY ( `id` ) ,
	INDEX ( `pilotid` )
)ENGINE=INNODB; 

CREATE TABLE `phpvms_airlines` (
	`id` INT NOT NULL AUTO_INCREMENT ,
	`code` VARCHAR( 5 ) NOT NULL ,
	`name` VARCHAR( 30 ) NOT NULL ,
	`enabled` smallint(6) NOT NULL default '1',
	PRIMARY KEY ( `id` ),
	UNIQUE KEY `code` (`code`)
)ENGINE=INNODB;

CREATE TABLE `phpvms_aircraft` (
	`id` INT NOT NULL AUTO_INCREMENT ,
	`icao` VARCHAR( 4 ) NOT NULL,
	`name` VARCHAR( 12 ) NOT NULL,
	`fullname` VARCHAR( 50 ) NOT NULL,
	`range` VARCHAR( 15 ) NOT NULL,
	`weight` VARCHAR( 15 ) NOT NULL,
	`cruise` VARCHAR( 15 ) NOT NULL,
	PRIMARY KEY ( `id` ),
	UNIQUE KEY `name` (`name`)
)ENGINE=INNODB;

CREATE TABLE `phpvms_airports` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`icao` VARCHAR( 5 ) NOT NULL,
	`name` text NOT NULL,
	`country` VARCHAR( 50 ) NOT NULL,
	`lat` FLOAT( 10 ) NOT NULL,
	`lng` FLOAT( 10 ) NOT NULL,
	`hub` smallint(6) NOT NULL default '0',
	PRIMARY KEY ( `id` ),
	UNIQUE KEY `icao` (`icao`)
)ENGINE=INNODB;

CREATE TABLE `phpvms_schedules` (
	`id` int(11) NOT NULL auto_increment,
	`code` varchar(3) NOT NULL default '',
	`flightnum` varchar(10) NOT NULL default '0',
	`leg` smallint(6) NOT NULL default '1',
	`depicao` varchar(4) NOT NULL default '',
	`arricao` varchar(4) NOT NULL default '',
	`route` text NOT NULL,
	`aircraft` varchar(12) NOT NULL default '',
	`distance` float NOT NULL default '0',
	`deptime` varchar(15) NOT NULL default '',
	`arrtime` varchar(15) NOT NULL default '',
	`flighttime` int(11) NOT NULL default '0',
	`timesflown` int(11) NOT NULL default '0',
    `notes` TEXT NOT NULL,
	`enabled` smallint(6) NOT NULL default '1',
	`log` TEXT NOT NULL,
	PRIMARY KEY  (`id`),
	INDEX `depicao` (`depicao`),
	INDEX `flightnum` (`flightnum`),
	INDEX `depicao_arricao` (`depicao`, `arricao`),
	FOREIGN KEY (`code`) REFERENCES phpvms_airlines(`code`) ON UPDATE CASCADE,
	FOREIGN KEY (`aircraft`) REFERENCES phpvms_aircraft(`name`) ON UPDATE CASCADE
)ENGINE=INNODB;

CREATE TABLE `phpvms_news` (
	`id` INT NOT NULL AUTO_INCREMENT ,
	`subject` VARCHAR( 30 ) NOT NULL ,
	`body` TEXT NOT NULL ,
	`postdate` DATETIME NOT NULL ,
	`postedby` VARCHAR( 50 ) NOT NULL,
	PRIMARY KEY ( `id` )
)ENGINE=INNODB;

CREATE TABLE `phpvms_pages` (
	`pageid` int(11) NOT NULL auto_increment,
	`pagename` varchar(30) NOT NULL default '',
	`filename` varchar(30) NOT NULL default '',
	`order` smallint(6) NOT NULL default '0',
	`postedby` varchar(50) NOT NULL default '',
	`postdate` datetime NOT NULL default '0000-00-00 00:00:00',
	`public` smallint(6) NOT NULL default '0',
	`enabled` smallint(6) NOT NULL default '1',
	PRIMARY KEY  (`pageid`),
	UNIQUE KEY `pagename` (`pagename`)
)ENGINE=INNODB;

CREATE TABLE `phpvms_ranks` (
	`rankid` int(11) NOT NULL auto_increment,
	`rank` varchar(32) NOT NULL default '',
	`rankimage` TEXT NOT NULL ,
	`minhours` smallint(6) NOT NULL default '0',
	PRIMARY KEY  (`rankid`),
	UNIQUE KEY `rank` (`rank`)
)ENGINE=INNODB;

INSERT INTO `phpvms_ranks` VALUES(1, 'New Hire','', 0);

CREATE TABLE `phpvms_pilots` (
	`pilotid` int(11) NOT NULL auto_increment,
	`firstname` varchar(25) NOT NULL default '',
	`lastname` varchar(25) NOT NULL default '',
	`email` varchar(32) NOT NULL default '',
	`code` varchar(5) NOT NULL default '',
	`location` varchar(32) NOT NULL default '',
	`hub` varchar(4) NOT NULL default '',
	`password` varchar(32) NOT NULL default '',
	`salt` varchar(32) NOT NULL default '',
	`lastlogin` date NOT NULL default '0000-00-00',
	`totalflights` int(11) NOT NULL default '0',
	`totalhours` float NOT NULL default '0',
	`rank` varchar(32) NOT NULL default '',
	`confirmed`smallint(6) NOT NULL default '0',
	`retired` smallint(6) NOT NULL default '0',
	PRIMARY KEY  (`pilotid`),
	UNIQUE KEY `email` (`email`),
	FOREIGN KEY (`code`) REFERENCES phpvms_airlines(`code`) ON UPDATE CASCADE
)ENGINE=INNODB;

CREATE TABLE `phpvms_pireps` (
	`pirepid` INT (11) NOT NULL AUTO_INCREMENT ,
	`pilotid` INT NOT NULL ,
	`code` VARCHAR( 3 ) NOT NULL ,
	`flightnum` varchar(10) NOT NULL default '0',
	`depicao` VARCHAR( 4 ) NOT NULL ,
	`arricao` VARCHAR( 4 ) NOT NULL ,
	`aircraft` VARCHAR( 12 ) NOT NULL ,
	`flighttime` VARCHAR( 10 ) NOT NULL ,
	`distance` SMALLINT NOT NULL ,
	`submitdate` DATETIME NOT NULL ,
	`accepted` SMALLINT NOT NULL ,
	`log` TEXT NOT NULL ,
	PRIMARY KEY ( `pirepid` ),
	INDEX `code` (`code`),
	INDEX `pilotid` (`pilotid`),
	FOREIGN KEY (`code`) REFERENCES phpvms_airlines(`code`) ON UPDATE CASCADE,
	FOREIGN KEY (`pilotid`) REFERENCES phpvms_pilots(`pilotid`) ON UPDATE CASCADE ON DELETE CASCADE
)ENGINE=INNODB;

CREATE TABLE `phpvms_pirepcomments` (
	`id` INT NOT NULL AUTO_INCREMENT ,
	`pirepid` INT NOT NULL ,
	`pilotid` INT NOT NULL ,
	`comment` TEXT NOT NULL ,
	`postdate` DATETIME NOT NULL ,
	PRIMARY KEY ( `id` ),
	FOREIGN KEY (`pirepid`) REFERENCES phpvms_pireps(`pirepid`) ON UPDATE CASCADE ON DELETE CASCADE
)ENGINE=INNODB;

CREATE TABLE `phpvms_customfields` (
	`fieldid` INT NOT NULL AUTO_INCREMENT ,
	`title` VARCHAR( 75 ) NOT NULL ,
	`fieldname` VARCHAR( 75 ) NOT NULL ,
	`type` VARCHAR( 25 ) NOT NULL DEFAULT 'text',
	`public` smallint(6) NOT NULL default '0',
	`showonregister` smallint(6) NOT NULL default '0',
	PRIMARY KEY ( `fieldid` ),
	UNIQUE KEY `fieldname` (`fieldname`)
)ENGINE=INNODB;

CREATE TABLE `phpvms_fieldvalues` (
	`id` INT NOT NULL AUTO_INCREMENT ,
	`fieldid` INT NOT NULL ,
	`pilotid` INT NOT NULL ,
	`value` VARCHAR( 25 ) NOT NULL ,
	PRIMARY KEY ( `id` ),
	FOREIGN KEY (`fieldid`) REFERENCES phpvms_customfields(`fieldid`) ON DELETE CASCADE,
	FOREIGN KEY (`pilotid`) REFERENCES phpvms_pilots(`pilotid`) ON DELETE CASCADE
)ENGINE=INNODB;

CREATE TABLE `phpvms_groups` (
	`groupid` INT NOT NULL AUTO_INCREMENT ,
	`name` VARCHAR( 25 ) NOT NULL ,
	PRIMARY KEY ( `groupid` ),
	UNIQUE KEY `name` (`name`)
)ENGINE=INNODB;

INSERT INTO `phpvms_groups` (`name`) VALUES ('Administrators');
INSERT INTO `phpvms_groups` (`name`) VALUES ('Active Pilots');

CREATE TABLE `phpvms_groupmembers` (
	`id` int(11) NOT NULL auto_increment,
	`groupid` int(11) NOT NULL default '0',
	`pilotid` int(11) NOT NULL default '0',
	PRIMARY KEY  (`id`),
	FOREIGN KEY (`groupid`) REFERENCES phpvms_groups(`groupid`) ON DELETE CASCADE,
	FOREIGN KEY (`pilotid`) REFERENCES phpvms_pilots(`pilotid`) ON DELETE CASCADE
)ENGINE=INNODB;

CREATE TABLE `phpvms_pirepfields` (
	`fieldid` INT NOT NULL AUTO_INCREMENT ,
	`title` VARCHAR( 25 ) NOT NULL ,
	`name` VARCHAR( 25 ) NOT NULL ,
	`type` VARCHAR (25) NOT NULL,
	`options` TEXT NOT NULL,
	PRIMARY KEY ( `fieldid` ),
	UNIQUE KEY `name` (`name`)
) ENGINE = INNODB; 

CREATE TABLE `phpvms_pirepvalues` (
	`id` INT NOT NULL AUTO_INCREMENT ,
	`fieldid` INT NOT NULL ,
	`pirepid` INT NOT NULL ,
	`value` VARCHAR( 50 ) NOT NULL ,
	PRIMARY KEY ( `id` ),
	FOREIGN KEY (`fieldid`) REFERENCES phpvms_pirepfields(`fieldid`) ON DELETE CASCADE
)ENGINE=INNODB;

CREATE TABLE `phpvms_bids` (
	`bidid` INT NOT NULL AUTO_INCREMENT ,
	`pilotid` int(11) NOT NULL default '0',
	`routeid` int(11) NOT NULL default '0',
	PRIMARY KEY ( `bidid` ),
	FOREIGN KEY (`pilotid`) REFERENCES phpvms_pilots(`pilotid`) ON DELETE CASCADE,
	FOREIGN KEY (`routeid`) REFERENCES phpvms_schedules(`id`) ON DELETE CASCADE
) ENGINE = INNODB; 

CREATE TABLE `phpvms_settings` (
	`id` int(11) NOT NULL auto_increment,
	`friendlyname` varchar(25) NOT NULL default '',
	`name` varchar(25) NOT NULL default '',
	`value` varchar(150) NOT NULL default '',
	`descrip` varchar(150) NOT NULL default '',
	`core` smallint(6) NOT NULL default '1',
	PRIMARY KEY  (`id`),
	UNIQUE KEY `name` (`name`)
)ENGINE=INNODB;

INSERT INTO `phpvms_settings` VALUES(1, 'phpVMS Version', 'PHPVMS_VERSION', '1.0.371', '', 't');
INSERT INTO `phpvms_settings` VALUES(2, 'Virtual Airline Name', 'SITE_NAME', 'PHPVMS', 'The name of your site. This will show up in the browser title bar.', 't');
INSERT INTO `phpvms_settings` VALUES(3, 'Webmaster Email Address', 'ADMIN_EMAIL', '', 'This is the email address that email will get sent to/from', 't');
INSERT INTO `phpvms_settings` VALUES(4, 'Date Format', 'DATE_FORMAT', 'm/d/Y', 'This is the date format to be used around the site.', 't');
INSERT INTO `phpvms_settings` VALUES(5, 'Notify for Updates', 'NOTIFY_UPDATE', 'true', 'This will notify in the admin panel if an update is available', 't');
INSERT INTO `phpvms_settings` VALUES(6, 'Current Skin', 'CURRENT_SKIN', 'crystal', 'Available skins', 't');
INSERT INTO `phpvms_settings` VALUES(7, 'Google API Key', 'GOOGLE_KEY', '', 'This is your Google API key. You need it for the maps functionality to work', 't');
INSERT INTO `phpvms_settings` VALUES(8, 'Default User Group', 'DEFAULT_GROUP', 'Active Pilots', 'This is the default group if they are not explicitly denied', 't');