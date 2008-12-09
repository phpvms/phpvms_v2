

ALTER TABLE `phpvms_schedules` DROP FOREIGN KEY `phpvms_schedules_ibfk_2`;
ALTER TABLE `phpvms_schedules` CHANGE `aircraft` `aircraft` INT( 11 ) NOT NULL;
ALTER TABLE `phpvms_aircraft` DROP INDEX `name` ;

ALTER TABLE `phpvms_aircraft` ADD `registration` VARCHAR( 30 ) NOT NULL AFTER `fullname` ;
ALTER TABLE `phpvms_aircraft` ADD `downloadlink` TEXT NOT NULL AFTER `registration`;
ALTER TABLE `phpvms_aircraft` ADD `imagelink` TEXT NOT NULL AFTER `downloadlink` ;

UPDATE phpvms_schedules s, phpvms_aircraft a SET s.aircraft=a.id WHERE s.aircraft=a.name;