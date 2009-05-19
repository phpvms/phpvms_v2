-- add permissions and default permission for admin group;
ALTER TABLE `phpvms_groups` ADD `permissions` INT NOT NULL ;
UPDATE `phpvms_groups` SET `permissions` = '35651519' WHERE `groupid` =1 LIMIT 1 ;