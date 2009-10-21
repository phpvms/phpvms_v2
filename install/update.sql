--
-- phpVMS Update file;


-- add sessions table;
CREATE TABLE `phpvms_sessions` (
   `id` INT NOT NULL ,
   `pilotid` INT NOT NULL ,
   `ipaddress` VARCHAR( 25 ) NOT NULL ,
   `logintime` DATETIME NOT NULL
) ENGINE = MYISAM ;

-- add permissions and default permission for admin group;

ALTER TABLE `phpvms_groups` ADD `permissions` INT NOT NULL ;
UPDATE `phpvms_groups` SET `permissions` = '35651519' WHERE `groupid` =1 LIMIT 1 ;

-- PIREP update for invdividual fuel cost per unit;
ALTER TABLE `phpvms_pireps` ADD `flighttime_stamp` TIME NOT NULL AFTER `flighttime` ;
ALTER TABLE `phpvms_pireps` ADD `fuelunitcost` FLOAT NOT NULL AFTER `fuelused` ;
ALTER TABLE `phpvms_pireps` ADD `exported` TINYINT NOT NULL;
ALTER TABLE `phpvms_pireps` ADD `revenue` FLOAT NOT NULL AFTER `expenselist` ;

-- Add total hours;
INSERT INTO `phpvms_settings` (`id` ,`friendlyname` ,`name` , `value` ,`descrip` ,`core`)
	VALUES (NULL , 'Total VA Hours', 'TOTAL_HOURS', '0', 'Your VA''s Total Hours', '0');

-- misc updates;
DELETE FROM `phpvms_settings` WHERE `name`='PHPVMS_API_KEY';