SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

CREATE SCHEMA IF NOT EXISTS `ballista` DEFAULT CHARACTER SET latin1 ;
USE `ballista` ;

-- -----------------------------------------------------
-- Table `ballista`.`users`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ballista`.`users` ;

CREATE  TABLE IF NOT EXISTS `ballista`.`users` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `username` VARCHAR(32) NOT NULL ,
  `password` VARCHAR(40) NOT NULL ,
  `firstname` VARCHAR(50) NULL DEFAULT NULL ,
  `lastname` VARCHAR(50) NULL DEFAULT NULL ,
  `email` VARCHAR(100) NULL DEFAULT NULL ,
  `active` TINYINT(1) NOT NULL DEFAULT 1 ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `username` (`username` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

-- ----------------------------------------------------
-- Creating admin user
-- ----------------------------------------------------

LOCK TABLES `users` WRITE;
INSERT INTO `users` VALUES (1,'admin','cc0335f4fb9719ab85ba52c7f688a3a94a594553','Ballista','Admin','',1);
UNLOCK TABLES;

-- -----------------------------------------------------
-- Table `ballista`.`servers`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ballista`.`servers` ;

CREATE  TABLE IF NOT EXISTS `ballista`.`servers` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `server` VARCHAR(45) NULL DEFAULT NULL ,
  `hostname` VARCHAR(100) NULL DEFAULT NULL ,
  `branches` TINYINT(1) NOT NULL DEFAULT 0 ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ballista`.`projects`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ballista`.`projects` ;

CREATE  TABLE IF NOT EXISTS `ballista`.`projects` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(50) NOT NULL ,
  `description` VARCHAR(255) NULL DEFAULT NULL ,
  `path` VARCHAR(255) NOT NULL ,
  `host` VARCHAR(50) NOT NULL DEFAULT 'Local' ,
  `notify` VARCHAR(255) NULL DEFAULT NULL ,
  `message` TEXT NULL DEFAULT NULL ,
  `active` TINYINT(1) NOT NULL DEFAULT 1 ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `name` (`name` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `ballista`.`instances`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ballista`.`instances` ;

CREATE  TABLE IF NOT EXISTS `ballista`.`instances` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `project_id` INT NOT NULL ,
  `server_id` INT NOT NULL ,
  `path` VARCHAR(255) NULL DEFAULT NULL ,
  `active` TINYINT(1) NOT NULL DEFAULT 1 ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_instance_servers1` (`server_id` ASC) ,
  INDEX `fk_instance_projects1` (`project_id` ASC) ,
  CONSTRAINT `fk_instance_servers1`
    FOREIGN KEY (`server_id` )
    REFERENCES `ballista`.`servers` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_instance_projects1`
    FOREIGN KEY (`project_id` )
    REFERENCES `ballista`.`projects` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `ballista`.`logs`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ballista`.`logs` ;

CREATE  TABLE IF NOT EXISTS `ballista`.`logs` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `user_id` INT NOT NULL ,
  `instance_id` INT NOT NULL ,
  `status` VARCHAR(10) NULL ,
  `commit` VARCHAR(40) NULL DEFAULT NULL ,
  `branch` VARCHAR(100) NULL DEFAULT NULL ,
  `comment` VARCHAR(150) NULL DEFAULT NULL ,
  `output` TEXT NULL ,
  `updated` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
  `logtime` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`) ,
  INDEX `user_id` (`user_id` ASC) ,
  INDEX `fk_logs_instance1` (`instance_id` ASC) ,
  CONSTRAINT `log_ibfk_3`
    FOREIGN KEY (`user_id` )
    REFERENCES `ballista`.`users` (`id` ),
  CONSTRAINT `fk_logs_instance1`
    FOREIGN KEY (`instance_id` )
    REFERENCES `ballista`.`instances` (`id` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `ballista`.`groups`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ballista`.`groups` ;

CREATE  TABLE IF NOT EXISTS `ballista`.`groups` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `group` VARCHAR(45) NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `group_UNIQUE` (`group` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Creating initial groups
-- -----------------------------------------------------

LOCK TABLES `groups` WRITE;
INSERT INTO `groups` VALUES (1,'Admin'),(2,'Developer'),(3,'Consultant');
UNLOCK TABLES;


-- -----------------------------------------------------
-- Table `ballista`.`users_groups`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ballista`.`users_groups` ;

CREATE  TABLE IF NOT EXISTS `ballista`.`users_groups` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `user_id` INT NOT NULL ,
  `group_id` INT NOT NULL ,
  INDEX `fk_users_groups_groups1` (`group_id` ASC) ,
  INDEX `fk_users_groups_users1` (`user_id` ASC) ,
  PRIMARY KEY (`id`) ,
  CONSTRAINT `fk_users_groups_users1`
    FOREIGN KEY (`user_id` )
    REFERENCES `ballista`.`users` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_users_groups_groups1`
    FOREIGN KEY (`group_id` )
    REFERENCES `ballista`.`groups` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Granting admin group to admin user
-- -----------------------------------------------------

LOCK TABLES `users_groups` WRITE;
INSERT INTO `users_groups` VALUES (1,1,1);
UNLOCK TABLES;

-- -----------------------------------------------------
-- Table `ballista`.`instances_groups`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ballista`.`instances_groups` ;

CREATE  TABLE IF NOT EXISTS `ballista`.`instances_groups` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `instance_id` INT NOT NULL ,
  `group_id` INT NOT NULL ,
  INDEX `fk_instances_groups_groups1` (`group_id` ASC) ,
  INDEX `fk_instances_groups_instances1` (`instance_id` ASC) ,
  PRIMARY KEY (`id`) ,
  CONSTRAINT `fk_instances_groups_instances1`
    FOREIGN KEY (`instance_id` )
    REFERENCES `ballista`.`instances` (`id` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_instances_groups_groups1`
    FOREIGN KEY (`group_id` )
    REFERENCES `ballista`.`groups` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

-- -----------------------------------------------------
-- Table `ballista`.`notifications`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ballista`.`notification` ;

CREATE  TABLE IF NOT EXISTS `ballista`.`notifications` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `log_id` INT(11) NOT NULL ,
  `notify` VARCHAR(255) NOT NULL ,
  `message` TEXT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_notifications_logs1` (`log_id` ASC) ,
  CONSTRAINT `fk_notifications_logs1`
    FOREIGN KEY (`log_id` )
    REFERENCES `ballista`.`logs` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
