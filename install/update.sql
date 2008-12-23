CREATE TABLE `phpvms_downloads` (
   `id` INT AUTO_INCREMENT,
   `pid` INT,
   `name` VARCHAR(50),
   `link` TEXT ASCII,
   `image` TEXT ASCII,
   `hits` INT,
  PRIMARY KEY (id)
) ENGINE = MyISAM;

