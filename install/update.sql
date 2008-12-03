

ALTER TABLE `phpvms_aircraft` ADD `registration` VARCHAR( 30 ) NOT NULL AFTER `fullname` ;
ALTER TABLE `phpvms_aircraft` ADD `downloadlink` TEXT NOT NULL AFTER `registration`;
ALTER TABLE `phpvms_aircraft` ADD `imagelink` TEXT NOT NULL AFTER `downloadlink` ;

ALTER TABLE `phpvms_schedules` CHANGE `aircraft` `aircraft` TEXT NOT NULL;

UPDATE phpvms_schedules s, phpvms_aircraft a SET s.aircraft=a.id WHERE s.aircraft=a.name;