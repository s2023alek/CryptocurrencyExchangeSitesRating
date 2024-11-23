CREATE TABLE `site_settings` (

`id` INT(10) NOT NULL AUTO_INCREMENT ,
`name` VARCHAR(55) NOT NULL ,
`value` VARCHAR(255) NOT NULL ,

PRIMARY KEY (`id`),
UNIQUE (`name`),
INDEX (`name`)
) ENGINE = InnoDB;

INSERT INTO `site_settings` (`id`, `name`, `value`) VALUES (NULL, 'cacheExpiryTimeCoins', '360'), (NULL, 'cacheExpiryTimeExchanges', '360');
