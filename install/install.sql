CREATE TABLE `phpvms_downloads` (
   `id` INT NOT NULL AUTO_INCREMENT,
   `pid` INT,
   `name` VARCHAR(50),
   `link` TEXT ASCII,
   `image` TEXT ASCII,
   `hits` INT,
  PRIMARY KEY (`id`)
) ENGINE = MyISAM;

CREATE TABLE `phpvms_expenses` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`name` VARCHAR( 25 ) NOT NULL ,
	`cost` FLOAT NOT NULL ,
	`fixed` INT NOT NULL DEFAULT 0,
	`type` VARCHAR( 1 ) NOT NULL default 'M',
	PRIMARY KEY (`id`)
) ENGINE = MYISAM;

CREATE TABLE `phpvms_acarsdata` (
  `id` int(11) NOT NULL auto_increment,
  `pilotid` varchar(11) NOT NULL default '0',
  `flightnum` varchar(11) NOT NULL default '0',
  `pilotname` varchar(100) NOT NULL default '',
  `aircraft` varchar(12) NOT NULL default '',
  `lat` varchar(15) NOT NULL default '',
  `lng` varchar(15) NOT NULL default '',
  `heading` smallint(6) NOT NULL default '0',
  `alt` varchar(6) NOT NULL default '',
  `gs` int(11) NOT NULL default '0',
  `depicao` varchar(4) NOT NULL default '',
  `depapt` varchar(255) NOT NULL default '',
  `arricao` varchar(4) NOT NULL default '',
  `arrapt` text NOT NULL,
  `deptime` time NOT NULL default '00:00:00',
  `timeremaining` varchar(6) NOT NULL default '',
  `arrtime` time NOT NULL default '00:00:00',
  `distremain` varchar(6) NOT NULL default '',
  `phasedetail` varchar(255) NOT NULL default '',
  `online` varchar(10) NOT NULL default '',
  `messagelog` text NOT NULL,
  `lastupdate` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  KEY `pilotid` (`pilotid`)
) ENGINE=INNODB; 

CREATE TABLE `phpvms_airlines` (
  `id` int(11) NOT NULL auto_increment,
  `code` char(3) NOT NULL default '',
  `name` varchar(30) NOT NULL default '',
  `enabled` smallint(6) NOT NULL default '1',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB;

CREATE TABLE `phpvms_aircraft` (
  `id` int(11) NOT NULL auto_increment,
  `icao` varchar(4) NOT NULL default '',
  `name` varchar(12) NOT NULL default '',
  `fullname` varchar(50) NOT NULL default '',
  `registration` varchar(30) NOT NULL,
  `downloadlink` text NOT NULL,
  `imagelink` text NOT NULL,
  `range` varchar(15) NOT NULL default '0',
  `weight` varchar(15) NOT NULL default '0',
  `cruise` varchar(15) NOT NULL default '0',
  `enabled` smallint(6) NOT NULL default '1',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB ;

CREATE TABLE `phpvms_airports` (
  `id` int(11) NOT NULL auto_increment,
  `icao` varchar(5) NOT NULL default '',
  `name` text NOT NULL,
  `country` varchar(50) NOT NULL default '',
  `lat` float NOT NULL default '0',
  `lng` float NOT NULL default '0',
  `hub` smallint(6) NOT NULL default '0',
  `fuelprice` FLOAT NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `icao` (`icao`)
) ENGINE=InnoDB;


CREATE TABLE `phpvms_schedules` (
  `id` int(11) NOT NULL auto_increment,
  `code` char(3) NOT NULL default '',
  `flightnum` varchar(10) NOT NULL default '0',
  `leg` smallint(6) NOT NULL default '1',
  `depicao` varchar(4) NOT NULL default '',
  `arricao` varchar(4) NOT NULL default '',
  `route` text NOT NULL,
  `aircraft` text NOT NULL,
  `distance` float NOT NULL default '0',
  `deptime` varchar(15) NOT NULL default '',
  `arrtime` varchar(15) NOT NULL default '',
  `flighttime` int(11) NOT NULL default '0',
  `maxload` INT(11) NOT NULL,
  `price` FLOAT NOT NULL,
  `flighttype` VARCHAR( 1 ) NOT NULL DEFAULT 'P',
  `timesflown` int(11) NOT NULL default '0',
  `notes` text NOT NULL,
  `enabled` int(11) NOT NULL default '1',
  PRIMARY KEY  (`id`),
  KEY `depicao` (`depicao`),
  KEY `flightnum` (`flightnum`),
  KEY `depicao_arricao` (`depicao`,`arricao`),
  KEY `code` (`code`)
) ENGINE=InnoDB;

CREATE TABLE `phpvms_news` (
  `id` int(11) NOT NULL auto_increment,
  `subject` varchar(30) NOT NULL default '',
  `body` text NOT NULL,
  `postdate` datetime NOT NULL default '0000-00-00 00:00:00',
  `postedby` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB ;

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
) ENGINE=InnoDB ;

CREATE TABLE `phpvms_ranks` (
  `rankid` int(11) NOT NULL auto_increment,
  `rank` varchar(32) NOT NULL default '',
  `rankimage` text NOT NULL,
  `minhours` smallint(6) NOT NULL default '0',
  `payrate` float NOT NULL default '0',
  PRIMARY KEY  (`rankid`),
  UNIQUE KEY `rank` (`rank`)
) ENGINE=InnoDB ;

INSERT INTO `phpvms_ranks` VALUES(1, 'New Hire','', 0, 10.0);

CREATE TABLE `phpvms_pilots` (
  `pilotid` int(11) NOT NULL auto_increment,
  `firstname` varchar(25) NOT NULL default '',
  `lastname` varchar(25) NOT NULL default '',
  `email` varchar(32) NOT NULL default '',
  `code` char(3) NOT NULL default '',
  `location` varchar(32) NOT NULL default '',
  `hub` varchar(4) NOT NULL default '',
  `password` varchar(32) NOT NULL default '',
  `salt` varchar(32) NOT NULL default '',
  `lastlogin` date NOT NULL default '0000-00-00',
  `totalflights` int(11) NOT NULL default '0',
  `totalhours` float NOT NULL default '0',
  `totalpay` float NOT NULL default '0',
  `rank` varchar(32) NOT NULL default 'Trainee Pilot',
  `confirmed` smallint(5) unsigned NOT NULL default '0',
  `retired` smallint(6) NOT NULL default '0',
  PRIMARY KEY  (`pilotid`),
  UNIQUE KEY `email` (`email`),
  KEY `code` (`code`),
  KEY `rank` (`rank`)
) ENGINE=InnoDB;

CREATE TABLE `phpvms_pireps` (
  `pirepid` int(11) NOT NULL auto_increment,
  `pilotid` int(11) NOT NULL default '0',
  `code` char(3) NOT NULL default '',
  `flightnum` varchar(10) NOT NULL default '0',
  `depicao` varchar(4) NOT NULL default '',
  `arricao` varchar(4) NOT NULL default '',
  `aircraft` varchar(12) NOT NULL default '',
  `flighttime` varchar(10) NOT NULL default '',
  `distance` smallint(6) NOT NULL default '0',
  `submitdate` datetime NOT NULL default '0000-00-00 00:00:00',
  `accepted` smallint(6) NOT NULL default '0',
  `log` text NOT NULL,
  `load` INT(11) NOT NULL,
  `fuelused` VARCHAR ( 15 ) NOT NULL,
  `fuelprice` FLOAT NOT NULL DEFAULT 5.10,
  `price` FLOAT NOT NULL,
  `flighttype` VARCHAR( 1 ) NOT NULL DEFAULT 'P',
  `pilotpay` FLOAT NOT NULL,
  `expenses` FLOAT NOT NULL,
  `expenselist` BLOB NOT NULL,
  `source` VARCHAR(25) NOT NULL,
  PRIMARY KEY  (`pirepid`)
) ENGINE=InnoDB;

CREATE TABLE `phpvms_pirepcomments` (
	`id` INT NOT NULL AUTO_INCREMENT ,
	`pirepid` INT NOT NULL ,
	`pilotid` INT NOT NULL ,
	`comment` TEXT NOT NULL ,
	`postdate` DATETIME NOT NULL ,
	PRIMARY KEY ( `id` )
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
	PRIMARY KEY ( `id` )
)ENGINE=INNODB;

CREATE TABLE `phpvms_groups` (
  `groupid` int(11) NOT NULL auto_increment,
  `name` varchar(25) NOT NULL default '',
  PRIMARY KEY  (`groupid`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB ;

INSERT INTO `phpvms_groups` (`name`) VALUES ('Administrators');
INSERT INTO `phpvms_groups` (`name`) VALUES ('Active Pilots');

CREATE TABLE `phpvms_groupmembers` (
	`id` int(11) NOT NULL auto_increment,
	`groupid` int(11) NOT NULL default '0',
	`pilotid` int(11) NOT NULL default '0',
	PRIMARY KEY  (`id`)
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
	PRIMARY KEY ( `id` )
)ENGINE=INNODB;

CREATE TABLE `phpvms_bids` (
	`bidid` INT NOT NULL AUTO_INCREMENT ,
	`pilotid` int(11) NOT NULL default '0',
	`routeid` int(11) NOT NULL default '0',
	PRIMARY KEY ( `bidid` )
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

ALTER TABLE `phpvms_fieldvalues`
  ADD CONSTRAINT `phpvms_fieldvalues_ibfk_1` FOREIGN KEY (`fieldid`) REFERENCES `phpvms_customfields` (`fieldid`) ON DELETE CASCADE,
  ADD CONSTRAINT `phpvms_fieldvalues_ibfk_2` FOREIGN KEY (`pilotid`) REFERENCES `phpvms_pilots` (`pilotid`) ON DELETE CASCADE;

ALTER TABLE `phpvms_groupmembers`
  ADD CONSTRAINT `phpvms_groupmembers_ibfk_1` FOREIGN KEY (`groupid`) REFERENCES `phpvms_groups` (`groupid`) ON DELETE CASCADE,
  ADD CONSTRAINT `phpvms_groupmembers_ibfk_2` FOREIGN KEY (`pilotid`) REFERENCES `phpvms_pilots` (`pilotid`) ON DELETE CASCADE;

ALTER TABLE `phpvms_pilots`
  ADD CONSTRAINT `phpvms_pilots_ibfk_1` FOREIGN KEY (`code`) REFERENCES `phpvms_airlines` (`code`) ON UPDATE CASCADE;

ALTER TABLE `phpvms_pirepcomments`
  ADD CONSTRAINT `phpvms_pirepcomments_ibfk_1` FOREIGN KEY (`pirepid`) REFERENCES `phpvms_pireps` (`pirepid`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `phpvms_schedules`
  ADD CONSTRAINT `phpvms_schedules_ibfk_1` FOREIGN KEY (`code`) REFERENCES `phpvms_airlines` (`code`) ON UPDATE CASCADE;

INSERT INTO `phpvms_settings` VALUES(1, 'phpVMS Version', 'PHPVMS_VERSION', '1.1.<<REVISION>>', '', 't');
INSERT INTO `phpvms_settings` VALUES(2, 'Virtual Airline Name', 'SITE_NAME', 'PHPVMS', 'The name of your site. This will show up in the browser title bar.', 't');
INSERT INTO `phpvms_settings` VALUES(3, 'Webmaster Email Address', 'ADMIN_EMAIL', '', 'This is the email address that email will get sent to/from', 't');
INSERT INTO `phpvms_settings` VALUES(4, 'Date Format', 'DATE_FORMAT', 'm/d/Y', 'This is the date format to be used around the site.', 't');
INSERT INTO `phpvms_settings` VALUES(5, 'Notify for Updates', 'NOTIFY_UPDATE', 'true', 'This will notify in the admin panel if an update is available', 't');
INSERT INTO `phpvms_settings` VALUES(6, 'Current Skin', 'CURRENT_SKIN', 'crystal', 'Available skins', 't');
INSERT INTO `phpvms_settings` VALUES(7, 'Google API Key', 'GOOGLE_KEY', '', 'This is your Google API key. You need it for the maps functionality to work', 't');
INSERT INTO `phpvms_settings` VALUES(8, 'Default User Group', 'DEFAULT_GROUP', 'Active Pilots', 'This is the default group if they are not explicitly denied', 't');


