CREATE TABLE `phpvms_news` (
`id` INT NOT NULL AUTO_INCREMENT ,
`subject` VARCHAR( 30 ) NOT NULL ,
`body` TEXT NOT NULL ,
`postdate` DATETIME NOT NULL ,
`postedby` VARCHAR( 25 ) NOT NULL ,
PRIMARY KEY ( `id` )
);

CREATE TABLE `phpvms_users` (
`userid` INT NOT NULL AUTO_INCREMENT ,
`firstname` VARCHAR( 25 ) NOT NULL ,
`lastname` VARCHAR( 25 ) NOT NULL ,
`email` VARCHAR( 32 ) NOT NULL ,
`location` VARCHAR( 32 ) NOT NULL ,
`password` VARCHAR( 32 ) NOT NULL ,
`salt` VARCHAR( 32 ) NOT NULL ,
`lastlogin` DATE NOT NULL ,
`totalflights` INT NOT NULL ,
`totalhours` FLOAT NOT NULL ,
`confirmed` ENUM( 'y', 'n' ) NOT NULL ,
`retired` ENUM( 'y', 'n' ) NOT NULL ,
PRIMARY KEY ( `userid` )
);

CREATE TABLE `phpvms_customfields` (
`fieldid` INT NOT NULL AUTO_INCREMENT ,
`fieldname` VARCHAR( 25 ) NOT NULL ,
`type` VARCHAR( 25 ) NOT NULL DEFAULT 'text',
`public` ENUM( 'y', 'n' ) NOT NULL ,
`showonregister` ENUM( 'y', 'n' ) NOT NULL ,
PRIMARY KEY ( `fieldid` )
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
PRIMARY KEY ( `id` )
);


INSERT INTO `phpvms_groups` (`name`) VALUES ('Administrators');


CREATE TABLE `phpvms_groupmembers` (
`id` INT NOT NULL ,
`groupid` INT NOT NULL ,
`userid` INT NOT NULL
) 



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
INSERT INTO `phpvms_settings` VALUES(5, 'Website URL', 'SITE_URL', 'http://www.phpvms.net/test', 'This is the URL to the "base" of your site. Links are based off of this', 't');
INSERT INTO `phpvms_settings` VALUES(6, 'Current Skin', 'CURRENT_SKIN', 'default', 'Available skins', 't');
INSERT INTO `phpvms_settings` VALUES(7, 'Friendly URLs', 'FRIENDLY_URLS', 'false', 'Enable URL rewriting for clean URLs. MUST have mod_rewrite available, and .htaccess enabled', 't');
INSERT INTO `phpvms_settings` VALUES(8, 'Cache Templates', 'TEMPLATE_USE_CACHE', 'false', 'Cache database queries. Can alleviate alot of DB load on high-traffic sites', 't');
INSERT INTO `phpvms_settings` VALUES(9, 'Template Cache Timeout', 'TEMPLATE_CACHE_EXPIRE', '24', 'Number of hours to automatically refresh the display cache', 't');
INSERT INTO `phpvms_settings` VALUES(10, 'Cache Database Queries', 'DBASE_USE_CACHE', 'false', 'Cache database queries. Can alleviate alot of DB load on high-traffic sites', 't');
INSERT INTO `phpvms_settings` VALUES(11, 'Database Cache Timeout', 'DBASE_CACHE_TIMEOUT', '24', 'Number of hours to expire the cache in', 't');
INSERT INTO `phpvms_settings` VALUES(12, 'Cache Path', 'CACHE_PATH', '/home/nssliven/public_html/phpvms.net/test/core/cache', 'Absolute path to the database cache', 't');
INSERT INTO `phpvms_settings` VALUES(13, 'Default User Group', 'DEFAULT_GROUP', 'GeneralUsers', 'This is the default group if they are not explicitly denied', 't');
