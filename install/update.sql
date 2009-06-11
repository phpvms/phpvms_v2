--
-- phpVMS Update file;


-- add permissions and default permission for admin group;

ALTER TABLE `phpvms_groups` ADD `permissions` INT NOT NULL ;
UPDATE `phpvms_groups` SET `permissions` = '35651519' WHERE `groupid` =1 LIMIT 1 ;

-- Add total hours
INSERT INTO `phpvms_test`.`phpvms_settings` (`id` ,`friendlyname` ,`name` , `value` ,`descrip` ,`core`)
	VALUES (NULL , 'Total VA Hours', 'TOTAL_HOURS', '0', 'Your VA''s Total Hours', '0');

-- misc updates;
DELETE FROM `phpvms_settings` WHERE `name`='PHPVMS_API_KEY';