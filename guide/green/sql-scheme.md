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
	
	-- -----------------------------------------------------
	-- Table `user_groups`
	-- -----------------------------------------------------
	CREATE TABLE IF NOT EXISTS `pl_user_groups` (
	  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
	  `name` VARCHAR(32) NOT NULL ,
	  -- Add additional columns.
	  `position` INT(11) NOT NULL , -- Will define the position of this group within the hierarchy.
	  PRIMARY KEY (`id`) ,
	  UNIQUE INDEX `uniq_name` (`name` ASC) );
	
For the anatomy of a rule see [Rules](rules).
