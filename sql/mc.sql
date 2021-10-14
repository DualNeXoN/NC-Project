-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema mc
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema mc
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `mc` DEFAULT CHARACTER SET utf8 ;
USE `mc` ;

-- -----------------------------------------------------
-- Table `mc`.`ticket_issues`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mc`.`ticket_issues` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `issueLabel` VARCHAR(50) NOT NULL,
  `issueDesc` VARCHAR(250) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mc`.`ticket_state`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mc`.`ticket_state` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `state` VARCHAR(50) NOT NULL,
  `color` VARCHAR(7) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mc`.`ranks`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mc`.`ranks` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(32) NOT NULL,
  `rank` INT NOT NULL DEFAULT 50000,
  `color` VARCHAR(7) NOT NULL DEFAULT '#ffffff',
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mc`.`users`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mc`.`users` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(20) NOT NULL,
  `pwd` VARCHAR(128) NOT NULL,
  `rankValue` INT NOT NULL DEFAULT 50000,
  `rankId` INT NOT NULL,
  `registrationDate` DATETIME NOT NULL DEFAULT current_timestamp(),
  `lastLoginWeb` DATETIME NULL,
  `lastActivityWeb` DATETIME NULL,
  `lastLoginServer` DATETIME NULL,
  `onlineServer` TINYINT NOT NULL DEFAULT 0,
  `playtime` BIGINT NOT NULL DEFAULT 0,
  `isBanned` TINYINT NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  INDEX `fk_users_ranks1_idx` (`rankId` ASC),
  CONSTRAINT `fk_users_ranks1`
    FOREIGN KEY (`rankId`)
    REFERENCES `mc`.`ranks` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mc`.`tickets`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mc`.`tickets` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `ticketStateId` INT NOT NULL,
  `ticketIssueId` INT NOT NULL,
  `userId` INT NOT NULL,
  `assigneeId` INT NULL,
  `createTime` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `fk_tickets_ticket_issues_idx` (`ticketIssueId` ASC),
  INDEX `fk_tickets_ticket_state1_idx` (`ticketStateId` ASC),
  INDEX `fk_tickets_users1_idx` (`userId` ASC),
  CONSTRAINT `fk_tickets_ticket_issues`
    FOREIGN KEY (`ticketIssueId`)
    REFERENCES `mc`.`ticket_issues` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_tickets_ticket_state1`
    FOREIGN KEY (`ticketStateId`)
    REFERENCES `mc`.`ticket_state` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_tickets_users1`
    FOREIGN KEY (`userId`)
    REFERENCES `mc`.`users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mc`.`ticket_messages`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mc`.`ticket_messages` (
  `id` VARCHAR(45) NOT NULL,
  `ticketId` INT NOT NULL,
  `userId` INT NOT NULL,
  `message` VARCHAR(1000) NOT NULL,
  `time` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX `fk_ticket_messages_tickets1_idx` (`ticketId` ASC),
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_ticket_messages_tickets1`
    FOREIGN KEY (`ticketId`)
    REFERENCES `mc`.`tickets` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- -----------------------------------------------------
-- Data for table `mc`.`ticket_issues`
-- -----------------------------------------------------
START TRANSACTION;
USE `mc`;
INSERT INTO `mc`.`ticket_issues` (`issueLabel`, `issueDesc`) VALUES ('Server', 'Problém, ktorý sa spája s herným serverom');
INSERT INTO `mc`.`ticket_issues` (`issueLabel`, `issueDesc`) VALUES ('Web', 'Problém, ktorý sa spája s webom');

COMMIT;


-- -----------------------------------------------------
-- Data for table `mc`.`ticket_state`
-- -----------------------------------------------------
START TRANSACTION;
USE `mc`;
INSERT INTO `mc`.`ticket_state` (`state`, `color`) VALUES ('Čakanie na otvorenie', '#ffffff');
INSERT INTO `mc`.`ticket_state` (`state`, `color`) VALUES ('Otvorený', '#ffffff');
INSERT INTO `mc`.`ticket_state` (`state`, `color`) VALUES ('Uzatvorený', '#ffffff');

COMMIT;


-- -----------------------------------------------------
-- Data for table `mc`.`ranks`
-- -----------------------------------------------------
START TRANSACTION;
USE `mc`;
INSERT INTO `mc`.`ranks` (`name`, `rank`, `color`) VALUES ('Admin', 0, '#ffffff');
INSERT INTO `mc`.`ranks` (`name`, `rank`, `color`) VALUES ('Hráč', DEFAULT, '#ffffff');

COMMIT;

