CREATE TABLE `phpvms_downloads` (
   `id` INT AUTO_INCREMENT,
   `pid` INT,
   `name` VARCHAR(50),
   `link` TEXT ASCII,
   `image` TEXT ASCII,
   `hits` INT,
  PRIMARY KEY (id)
) ENGINE = MyISAM;

CREATE TABLE `phpvms_expenses` (
	`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
	`name` VARCHAR( 25 ) NOT NULL ,
	`cost` FLOAT NOT NULL ,
	`fixed` INT NOT NULL DEFAULT 0
) ENGINE = MYISAM;

ALTER TABLE `phpvms_aircraft` ADD `enabled` INT NOT NULL DEFAULT 1;

ALTER TABLE `phpvms_schedules` 
	ADD `maxload` INT NOT NULL AFTER `flighttime` ,
	ADD `price` FLOAT NOT NULL AFTER `maxload`,
	ADD `flighttype` VARCHAR( 1 ) NOT NULL DEFAULT 'P' AFTER `price`;

ALTER TABLE `phpvms_pireps` 
	ADD `load` INT NOT NULL ,
	ADD `price` FLOAT NOT NULL, 
	ADD `flighttype` VARCHAR( 1 ) NOT NULL DEFAULT 'P',
	ADD `pilotpay` FLOAT NOT NULL;