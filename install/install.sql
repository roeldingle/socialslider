CREATE TABLE IF NOT EXISTS `socialslider_settings`(
			 `idx` INT NOT NULL AUTO_INCREMENT,
			  `seq` INT NOT NULL,
			 `position` VARCHAR(15) NOT NULL,
			 `title` TINYINT NOT NULL,
			 `size` VARCHAR(15) NOT NULL,
			 `count` INT NOT NULL,
			  `target` VARCHAR(10) NOT NULL,
			 `icons` TEXT NOT NULL,
	PRIMARY KEY (`idx`) ); 
	