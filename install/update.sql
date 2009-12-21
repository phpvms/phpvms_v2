--
-- phpVMS Update file;
 
-- Add sessions table;
CREATE TABLE `phpvms_sessions` (
   `id` INT NOT NULL ,
   `pilotid` INT NOT NULL ,
   `ipaddress` VARCHAR( 25 ) NOT NULL ,
   `logintime` DATETIME NOT NULL
) ENGINE = MYISAM ;

-- Sessions additions;
DELETE FROM `phpvms_sessions`;
ALTER TABLE `phpvms_sessions` ADD PRIMARY KEY ( `id` ) ;
ALTER TABLE `phpvms_sessions` CHANGE `id` `id` INT( 11 ) NOT NULL AUTO_INCREMENT;

-- Cached fuel prices ;
DELETE FROM `phpvms_fuelprices`;

-- Add permissions and default permission for admin group;
ALTER TABLE `phpvms_groups` ADD `permissions` INT NOT NULL ;
UPDATE `phpvms_groups` SET `permissions` = '35651519' WHERE `groupid`=1 LIMIT 1 ;

-- PIREP update for invdividual fuel cost per unit;
ALTER TABLE `phpvms_pireps` ADD `flighttime_stamp` TIME NOT NULL AFTER `flighttime` ;
ALTER TABLE `phpvms_pireps` ADD `fuelunitcost` FLOAT NOT NULL AFTER `fuelused` ;
ALTER TABLE `phpvms_pireps` ADD `exported` TINYINT NOT NULL;
ALTER TABLE `phpvms_pireps` ADD `revenue` FLOAT NOT NULL AFTER `expenselist` ;
ALTER TABLE `phpvms_pireps` ADD `landingrate` FLOAT NOT NULL AFTER `distance`;
ALTER TABLE `phpvms_pireps` ADD `rawdata` TEXT NOT NULL;

-- Schedules tables updates;
ALTER TABLE `phpvms_schedules` ADD `flightlevel` VARCHAR( 6 ) NOT NULL AFTER `aircraft`;
ALTER TABLE `phpvms_schedules` DROP `leg`;

-- Default value for profile fields;
ALTER TABLE `phpvms_customfields` ADD `value` TEXT NOT NULL AFTER `fieldname`;

-- Date added for bids;
ALTER TABLE `phpvms_bids` ADD `dateadded` DATE NOT NULL;

-- Chart link for airport;
ALTER TABLE `phpvms_airports` ADD `chartlink` TEXT NOT NULL;		

-- Add total hours setting;
INSERT INTO `phpvms_settings` (`id` ,`friendlyname` ,`name` , `value` ,`descrip` ,`core`)
	VALUES (NULL , 'Total VA Hours', 'TOTAL_HOURS', '0', 'Your VA''s Total Hours', '0');
	
-- Misc updates;
DELETE FROM `phpvms_settings` WHERE `name`='PHPVMS_API_KEY';
ALTER TABLE `phpvms_aircraft` DROP INDEX `name`;
ALTER TABLE `phpvms_pilots` CHANGE `email` `email` VARCHAR( 100 ) NOT NULL;