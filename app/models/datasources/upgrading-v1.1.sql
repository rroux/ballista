SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

-- -----------------------------------------------------
-- Table `ballista`.`tags`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ballista`.`tags` ;

CREATE TABLE `ballista`.`tags` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tag` varchar(64) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tag` (`tag`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

-- -----------------------------------------------------
-- Table `ballista`.`projects_tags`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ballista`.`projects_tags` ;

CREATE TABLE `ballista`.`projects_tags` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` INT NOT NULL,
  `tag_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_project_id` (`project_id`),
  INDEX `fk_tag_id` (`tag_id`),
  CONSTRAINT `fk_project_id`
    FOREIGN KEY (`project_id` )
    REFERENCES `ballista`.`projects` (`id` )
    ON DELETE NO ACTION
    ON UPDATE CASCADE,
  CONSTRAINT `fk_tag_id`
    FOREIGN KEY (`tag_id` )
    REFERENCES `ballista`.`tags` (`id` )
    ON DELETE NO ACTION
    ON UPDATE CASCADE) 
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;