CREATE TABLE `exchanges` (

`id` INT(10) NOT NULL AUTO_INCREMENT ,
`urlPTitle` VARCHAR(255) NOT NULL ,
`title` VARCHAR(255) NOT NULL ,
`imageUrl` VARCHAR(255) NOT NULL ,

PRIMARY KEY (`id`),
UNIQUE (`urlPTitle`),
INDEX (`urlPTitle`)
) ENGINE = InnoDB;
