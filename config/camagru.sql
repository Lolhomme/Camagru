SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

DROP SCHEMA IF EXISTS `camagru` ;

CREATE SCHEMA IF NOT EXISTS `camagru` DEFAULT CHARACTER SET utf8 ;
USE `camagru` ;

DROP TABLE IF EXISTS `camagru`.`users` ;

CREATE TABLE IF NOT EXISTS `camagru`.`users` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `mail` VARCHAR(255) NULL,
  `password` VARCHAR(255) NULL,
  `salt` VARCHAR(255) NULL,
  `username` VARCHAR(45) NULL,
  `confirmKey` VARCHAR(45) NULL,
  `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  `activate` INT NULL DEFAULT 0,
  `token` VARCHAR(45) NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  UNIQUE INDEX `mail_UNIQUE` (`mail` ASC),
  UNIQUE INDEX `username_UNIQUE` (`username` ASC))
ENGINE = InnoDB;

DROP TABLE IF EXISTS `camagru`.`pictures` ;

CREATE TABLE IF NOT EXISTS `camagru`.`pictures` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `users_id` INT NOT NULL,
  `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  INDEX `fk_pictures_users_idx` (`users_id` ASC),
  CONSTRAINT `fk_pictures_users`
    FOREIGN KEY (`users_id`)
    REFERENCES `camagru`.`users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

DROP TABLE IF EXISTS `camagru`.`comment` ;

CREATE TABLE IF NOT EXISTS `camagru`.`comment` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `users_id` INT NOT NULL,
  `pictures_id` INT NOT NULL,
  `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  INDEX `fk_comment_users1_idx` (`users_id` ASC),
  INDEX `fk_comment_pictures1_idx` (`pictures_id` ASC),
  CONSTRAINT `fk_comment_users1`
    FOREIGN KEY (`users_id`)
    REFERENCES `camagru`.`users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_comment_pictures1`
    FOREIGN KEY (`pictures_id`)
    REFERENCES `camagru`.`pictures` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

DROP TABLE IF EXISTS `camagru`.`like` ;

CREATE TABLE IF NOT EXISTS `camagru`.`like` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `users_id` INT NOT NULL,
  `pictures_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  INDEX `fk_like_pictures1_idx` (`pictures_id` ASC),
  INDEX `fk_like_users1_idx` (`users_id` ASC),
  CONSTRAINT `fk_like_users1`
    FOREIGN KEY (`users_id`)
    REFERENCES `camagru`.`users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_like_pictures1`
    FOREIGN KEY (`pictures_id`)
    REFERENCES `camagru`.`pictures` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
