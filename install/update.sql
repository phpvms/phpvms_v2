CREATE TABLE `phpvms_downloads` (
   `id` INT NOT NULL AUTO_INCREMENT,
   `pid` INT,
   `name` VARCHAR(50),
   `link` TEXT ASCII,
   `image` TEXT ASCII,
   `hits` INT,
  PRIMARY KEY (`id`)
) ENGINE = MyISAM;

CREATE TABLE `phpvms_expenses` (
	`id` INT NOT NULL AUTO_INCREMENT ,
	`name` VARCHAR( 25 ) NOT NULL ,
	`cost` FLOAT NOT NULL ,
	`fixed` INT NOT NULL DEFAULT 0,
	PRIMARY KEY (`id`)
) ENGINE = MYISAM;

ALTER TABLE `phpvms_aircraft` ADD `enabled` INT NOT NULL DEFAULT 1;

ALTER TABLE `phpvms_expenses` ADD `type` VARCHAR ( 1 ) NOT NULL DEFAULT 'M';

ALTER TABLE `phpvms_schedules` ADD `maxload` INT NOT NULL AFTER `flighttime`;
ALTER TABLE `phpvms_schedules` ADD `price` FLOAT NOT NULL AFTER `maxload`;
ALTER TABLE `phpvms_schedules` ADD `flighttype` VARCHAR( 1 ) NOT NULL DEFAULT 'P' AFTER `price`;

ALTER TABLE `phpvms_pireps` ADD `load` INT NOT NULL;
ALTER TABLE `phpvms_pireps` ADD `fuelused` VARCHAR ( 15 ) NOT NULL AFTER `load`;
ALTER TABLE `phpvms_pireps` ADD `price` FLOAT NOT NULL;
ALTER TABLE `phpvms_pireps` ADD `flighttype` VARCHAR( 1 ) NOT NULL DEFAULT 'P';
ALTER TABLE `phpvms_pireps` ADD `pilotpay` FLOAT NOT NULL;
ALTER TABLE `phpvms_pireps` ADD `expenses` FLOAT NOT NULL DEFAULT 0;
ALTER TABLE `phpvms_pireps` ADD `expenselist` BLOB NOT NULL;