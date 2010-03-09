

UPDATE phpvms_pireps s, phpvms_aircraft a SET s.aircraft=a.id WHERE s.aircraft=a.name;
ALTER TABLE `phpvms_pireps` CHANGE `aircraft` `aircraft` INT( 11 ) NOT NULL;