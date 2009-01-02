CREATE TABLE `phpvms_downloads` (
   `id` INT AUTO_INCREMENT,
   `pid` INT,
   `name` VARCHAR(50),
   `link` TEXT ASCII,
   `image` TEXT ASCII,
   `hits` INT,
  PRIMARY KEY (id)
) ENGINE = MyISAM;

ALTER TABLE `phpvms_aircraft` ADD `enabled` INT NOT NULL DEFAULT 1;

ALTER TABLE `phpvms_schedules` 
	ADD `maxload` INT NOT NULL AFTER `flighttime` ,
	ADD `price` FLOAT NOT NULL AFTER `maxload`,
	ADD `flighttype` VARCHAR( 1 ) NOT NULL DEFAULT 'P' AFTER `price`;

ALTER TABLE `phpvms_pireps` 
	ADD `load` INT NOT NULL ,
	ADD `price` FLOAT NOT NULL, 
	ADD `flighttype` VARCHAR( 1 ) NOT NULL DEFAULT 'P' AFTER `price`;