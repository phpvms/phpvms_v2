CREATE TABLE `phpvms_airports` (
`id` INT NOT NULL AUTO_INCREMENT ,
`icao` VARCHAR( 5 ) NOT NULL ,
`name` VARCHAR( 30 ) NOT NULL ,
`country` VARCHAR( 50 ) NOT NULL ,
`lat` FLOAT( 10 ) NOT NULL ,
`lng` FLOAT( 10 ) NOT NULL ,
PRIMARY KEY ( `id` ),
UNIQUE KEY `icao` (`icao`)
);

CREATE TABLE `phpvms_news` (
`id` INT NOT NULL AUTO_INCREMENT ,
`subject` VARCHAR( 30 ) NOT NULL ,
`body` TEXT NOT NULL ,
`postdate` DATETIME NOT NULL ,
`postedby` VARCHAR( 25 ) NOT NULL ,
PRIMARY KEY ( `id` )
);

CREATE TABLE `phpvms_users` (
  `userid` int(11) NOT NULL auto_increment,
  `firstname` varchar(25) NOT NULL default '',
  `lastname` varchar(25) NOT NULL default '',
  `email` varchar(32) NOT NULL default '',
  `location` varchar(32) NOT NULL default '',
  `password` varchar(32) NOT NULL default '',
  `salt` varchar(32) NOT NULL default '',
  `lastlogin` date NOT NULL default '0000-00-00',
  `totalflights` int(11) NOT NULL default '0',
  `totalhours` float NOT NULL default '0',
  `confirmed` enum('y','n') NOT NULL default 'n',
  `retired` enum('y','n') NOT NULL default 'y',
  PRIMARY KEY  (`userid`),
  UNIQUE KEY `email` (`email`)
);

CREATE TABLE `phpvms_customfields` (
`fieldid` INT NOT NULL AUTO_INCREMENT ,
`fieldname` VARCHAR( 25 ) NOT NULL ,
`type` VARCHAR( 25 ) NOT NULL DEFAULT 'text',
`public` ENUM( 'y', 'n' ) NOT NULL ,
`showonregister` ENUM( 'y', 'n' ) NOT NULL ,
PRIMARY KEY ( `fieldid` ),
UNIQUE KEY `fieldname` (`fieldname`)
);


CREATE TABLE `phpvms_fieldvalues` (
`id` INT NOT NULL AUTO_INCREMENT ,
`fieldid` INT NOT NULL ,
`userid` INT NOT NULL ,
`value` VARCHAR( 25 ) NOT NULL ,
PRIMARY KEY ( `id` )
);


CREATE TABLE `phpvms_groups` (
`groupid` INT NOT NULL AUTO_INCREMENT ,
`name` VARCHAR( 25 ) NOT NULL ,
PRIMARY KEY ( `id` ),
UNIQUE KEY `name` (`name`)
);


INSERT INTO `phpvms_groups` (`name`) VALUES ('Administrators');
INSERT INTO `phpvms_groups` (`name`) VALUES ('Active Pilots');


CREATE TABLE `phpvms_groupmembers` (
  `id` int(11) NOT NULL auto_increment,
  `groupid` int(11) NOT NULL default '0',
  `userid` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
);

CREATE TABLE `phpvms_settings` (
  `id` int(11) NOT NULL auto_increment,
  `friendlyname` varchar(25) NOT NULL default '',
  `name` varchar(25) NOT NULL default '',
  `value` varchar(150) NOT NULL default '',
  `descrip` varchar(150) NOT NULL default '',
  `core` enum('t','f') NOT NULL default 'f',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) AUTO_INCREMENT=13 ;



INSERT INTO `phpvms_settings` VALUES(1, 'phpVMS Version', 'PHPVMS_VERSION', '0.0.1', '', 't');
INSERT INTO `phpvms_settings` VALUES(2, 'Virtual Airline Name', 'SITE_NAME', 'PHPVMS', 'The name of your site. This will show up in the browser title bar.', 't');
INSERT INTO `phpvms_settings` VALUES(3, 'Webmaster Email Address', 'ADMIN_EMAIL', '', 'This is the email address that email will get sent to/from', 't');
INSERT INTO `phpvms_settings` VALUES(4, 'Pilot ID Prefix', 'PID_PREFIX', 'VMS', 'This is the prefix for the pilot ID. For example, DVA.', 't');
INSERT INTO `phpvms_settings` VALUES(5, 'Date Format', 'DATE_FORMAT', 'm/d/Y', 'This is the date format to be used around the site.', 't');
INSERT INTO `phpvms_settings` VALUES(6, 'Website URL', 'SITE_URL', 'http://www.phpvms.net/test', 'This is the URL to the "base" of your site. Links are based off of this', 't');
INSERT INTO `phpvms_settings` VALUES(7, 'Current Skin', 'CURRENT_SKIN', 'default', 'Available skins', 't');
INSERT INTO `phpvms_settings` VALUES(8, 'Friendly URLs', 'FRIENDLY_URLS', 'false', 'Enable URL rewriting for clean URLs. MUST have mod_rewrite available, and .htaccess enabled', 't');
INSERT INTO `phpvms_settings` VALUES(9, 'Cache Templates', 'TEMPLATE_USE_CACHE', 'false', 'Cache database queries. Can alleviate alot of DB load on high-traffic sites', 't');
INSERT INTO `phpvms_settings` VALUES(10, 'Template Cache Timeout', 'TEMPLATE_CACHE_EXPIRE', '24', 'Number of hours to automatically refresh the display cache', 't');
INSERT INTO `phpvms_settings` VALUES(11, 'Cache Database Queries', 'DBASE_USE_CACHE', 'false', 'Cache database queries. Can alleviate alot of DB load on high-traffic sites', 't');
INSERT INTO `phpvms_settings` VALUES(12, 'Database Cache Timeout', 'DBASE_CACHE_TIMEOUT', '24', 'Number of hours to expire the cache in', 't');
INSERT INTO `phpvms_settings` VALUES(13, 'Cache Path', 'CACHE_PATH', '/home/nssliven/public_html/phpvms.net/test/core/cache', 'Absolute path to the database cache', 't');
INSERT INTO `phpvms_settings` VALUES(14, 'Default User Group', 'DEFAULT_GROUP', 'GeneralUsers', 'This is the default group if they are not explicitly denied', 't');
