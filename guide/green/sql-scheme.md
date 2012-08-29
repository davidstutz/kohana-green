# SQL Scheme

The following table will save all rules for Green to run:

	-- -----------------------------------------------------
	-- Table `rules`
	-- -----------------------------------------------------
	CREATE  TABLE `rules` (
	  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
	  `type` VARCHAR(255) NOT NULL ,
	  `key` VARCHAR(255) NOT NULL ,
	  `rule` VARCHAR(255) NOT NULL ,
	  PRIMARY KEY (`id`) )
	DEFAULT CHARACTER SET = utf8;
	
For the anatomy of a rule see [Rules](rules).
