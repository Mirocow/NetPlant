SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';


-- -----------------------------------------------------
-- Table `Account`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Account` ;

CREATE  TABLE IF NOT EXISTS `Account` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `dateRegistered` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ,
  `name` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `name_UNIQUE` (`name` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `User`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `User` ;

CREATE  TABLE IF NOT EXISTS `User` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `username` VARCHAR(45) NOT NULL ,
  `active` TINYINT(1) NULL DEFAULT 0 ,
  `dateRegistered` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `username_UNIQUE` (`username` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Session`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Session` ;

CREATE  TABLE IF NOT EXISTS `Session` (
  `id` CHAR(32) NOT NULL ,
  `expire` INT(11) NULL DEFAULT NULL ,
  `data` LONGBLOB NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `Server`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Server` ;

CREATE  TABLE IF NOT EXISTS `Server` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` TINYTEXT NOT NULL ,
  `ip` VARCHAR(45) NOT NULL ,
  `description` TEXT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `ip_UNIQUE` (`ip` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Platform`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Platform` ;

CREATE  TABLE IF NOT EXISTS `Platform` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `dateRegistered` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ,
  `name` TINYTEXT NULL ,
  `systemUser` VARCHAR(45) NOT NULL ,
  `allowSsh` TINYINT(1) NULL DEFAULT 0 ,
  `Account_id` INT UNSIGNED NOT NULL ,
  `Server_id` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_Platform_Account_idx` (`Account_id` ASC) ,
  INDEX `fk_Platform_Server1_idx` (`Server_id` ASC) ,
  CONSTRAINT `fk_Platform_Account`
    FOREIGN KEY (`Account_id` )
    REFERENCES `Account` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Platform_Server1`
    FOREIGN KEY (`Server_id` )
    REFERENCES `Server` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `SiteConfiguration`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `SiteConfiguration` ;

CREATE  TABLE IF NOT EXISTS `SiteConfiguration` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `name` TINYTEXT NOT NULL ,
  `handlerClass` VARCHAR(80) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Site`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Site` ;

CREATE  TABLE IF NOT EXISTS `Site` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(120) NOT NULL ,
  `aliases` TEXT NULL ,
  `active` TINYINT(1) NULL DEFAULT 1 ,
  `deleted` TINYINT(1) NULL DEFAULT 0 ,
  `Platform_id` INT UNSIGNED NOT NULL ,
  `SiteConfiguration_id` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_Site_Platform1_idx` (`Platform_id` ASC) ,
  INDEX `fk_Site_SiteConfiguration1_idx` (`SiteConfiguration_id` ASC) ,
  CONSTRAINT `fk_Site_Platform1`
    FOREIGN KEY (`Platform_id` )
    REFERENCES `Platform` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Site_SiteConfiguration1`
    FOREIGN KEY (`SiteConfiguration_id` )
    REFERENCES `SiteConfiguration` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `AccountUser`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `AccountUser` ;

CREATE  TABLE IF NOT EXISTS `AccountUser` (
  `Account_id` INT UNSIGNED NOT NULL ,
  `User_id` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`Account_id`, `User_id`) ,
  INDEX `fk_Account_has_User_User1_idx` (`User_id` ASC) ,
  INDEX `fk_Account_has_User_Account1_idx` (`Account_id` ASC) ,
  CONSTRAINT `fk_Account_has_User_Account1`
    FOREIGN KEY (`Account_id` )
    REFERENCES `Account` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Account_has_User_User1`
    FOREIGN KEY (`User_id` )
    REFERENCES `User` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ConfigTemplate`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ConfigTemplate` ;

CREATE  TABLE IF NOT EXISTS `ConfigTemplate` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NOT NULL ,
  `view` TINYTEXT NOT NULL ,
  `SiteConfiguration_id` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_ConfigTemplate_SiteConfiguration1_idx` (`SiteConfiguration_id` ASC) ,
  CONSTRAINT `fk_ConfigTemplate_SiteConfiguration1`
    FOREIGN KEY (`SiteConfiguration_id` )
    REFERENCES `SiteConfiguration` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Database`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Database` ;

CREATE  TABLE IF NOT EXISTS `Database` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NOT NULL ,
  `active` TINYINT(1) NULL DEFAULT 1 ,
  `Platform_id` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_Database_Platform1_idx` (`Platform_id` ASC) ,
  CONSTRAINT `fk_Database_Platform1`
    FOREIGN KEY (`Platform_id` )
    REFERENCES `Platform` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `FTPUser`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `FTPUser` ;

CREATE  TABLE IF NOT EXISTS `FTPUser` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `username` VARCHAR(45) NOT NULL ,
  `chroot` TINYTEXT NULL ,
  `Platform_id` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_FTPUser_Platform1_idx` (`Platform_id` ASC) ,
  CONSTRAINT `fk_FTPUser_Platform1`
    FOREIGN KEY (`Platform_id` )
    REFERENCES `Platform` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `UserAuth`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `UserAuth` ;

CREATE  TABLE IF NOT EXISTS `UserAuth` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `userId` INT UNSIGNED NOT NULL ,
  `identityClass` TINYTEXT NOT NULL ,
  `password` VARCHAR(255) NULL ,
  `salt` VARCHAR(128) NULL ,
  `identity` VARCHAR(255) NULL ,
  `provider` VARCHAR(255) NULL ,
  `additionalData` TEXT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `UserProfile`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `UserProfile` ;

CREATE  TABLE IF NOT EXISTS `UserProfile` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `userId` INT UNSIGNED NULL ,
  `firstName` TINYTEXT NULL ,
  `lastname` TINYTEXT NULL ,
  `email` VARCHAR(255) NULL ,
  `updateTime` DATETIME NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `AuthItem`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `AuthItem` ;

CREATE  TABLE IF NOT EXISTS `AuthItem` (
  `name` VARCHAR(64) NOT NULL ,
  `type` INT(11) NULL ,
  `description` TEXT NULL ,
  `bizrule` TEXT NULL ,
  `data` TEXT NULL ,
  PRIMARY KEY (`name`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `AuthItemChild`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `AuthItemChild` ;

CREATE  TABLE IF NOT EXISTS `AuthItemChild` (
  `parent` VARCHAR(64) NOT NULL ,
  `child` VARCHAR(64) NULL ,
  PRIMARY KEY (`parent`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ExecutionQueue`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ExecutionQueue` ;

CREATE  TABLE IF NOT EXISTS `ExecutionQueue` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `dateAdded` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ,
  `complete` TINYINT(1) NULL DEFAULT 0 ,
  `dateComplete` TIMESTAMP NULL ,
  `comment` TEXT NULL ,
  `User_id` INT UNSIGNED NOT NULL ,
  `filename` TINYTEXT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_ExecutionQueue_User1_idx` (`User_id` ASC) ,
  CONSTRAINT `fk_ExecutionQueue_User1`
    FOREIGN KEY (`User_id` )
    REFERENCES `User` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- -----------------------------------------------------
-- Data for table `Account`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `Account` (`id`, `dateRegistered`, `name`) VALUES (1, '2013-02-20 22:09:01', 'John Doe');

COMMIT;

-- -----------------------------------------------------
-- Data for table `User`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `User` (`id`, `username`, `active`, `dateRegistered`) VALUES (1, 'Admin', 1, '2013-02-20 21:47:25');

COMMIT;

-- -----------------------------------------------------
-- Data for table `Server`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `Server` (`id`, `name`, `ip`, `description`) VALUES (1, 'Localhost', '127.0.0.1', 'local server');

COMMIT;

-- -----------------------------------------------------
-- Data for table `Platform`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `Platform` (`id`, `dateRegistered`, `name`, `systemUser`, `allowSsh`, `Account_id`, `Server_id`) VALUES (1, '2013-02-20 22:10:09', 'Testing', 'test', NULL, 1, 1);

COMMIT;

-- -----------------------------------------------------
-- Data for table `SiteConfiguration`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `SiteConfiguration` (`id`, `name`, `handlerClass`) VALUES (1, 'nginxOnly', 'NginxOnlyConfiguration');

COMMIT;

-- -----------------------------------------------------
-- Data for table `AccountUser`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `AccountUser` (`Account_id`, `User_id`) VALUES (1, 1);

COMMIT;

-- -----------------------------------------------------
-- Data for table `ConfigTemplate`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `ConfigTemplate` (`id`, `name`, `view`, `SiteConfiguration_id`) VALUES (1, 'nginx', 'application.configTemplates.nginxOnly.nginx', 1);
INSERT INTO `ConfigTemplate` (`id`, `name`, `view`, `SiteConfiguration_id`) VALUES (2, 'phpFCGI', 'application.configTemplates.nginxOnly.phpFCGI', 1);

COMMIT;

-- -----------------------------------------------------
-- Data for table `UserAuth`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `UserAuth` (`id`, `userId`, `identityClass`, `password`, `salt`, `identity`, `provider`, `additionalData`) VALUES (1, 1, 'StandardIdentity', 'a7728449cc4e04d9fd776a59f1620a26', '51250c2d7b8852.01404637', NULL, NULL, NULL);

COMMIT;

-- -----------------------------------------------------
-- Data for table `UserProfile`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `UserProfile` (`id`, `userId`, `firstName`, `lastname`, `email`, `updateTime`) VALUES (1, 1, 'Admin', 'Administrator', 'admin@devgroup.ru', '2013-02-20 22:09:04');

COMMIT;
