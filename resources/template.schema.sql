-- SET @host = `localhost`;
-- SET @db = `bloqs`;
-- SET @user = `bloqsuser`;
-- SET @passwd = `passwd`;

-- DROP DATABASE IF EXISTS @db;
-- CREATE DATABASE @db;
-- USE @db;
DROP DATABASE IF EXISTS `bloqs`;
CREATE DATABASE `bloqs`;
USE `bloqs`;

-- CREATE USER IF NOT EXISTS @user@@host IDENTIFIED BY @passwd;
-- GRANT SELECT, DELETE, INSERT, UPDATE ON @db.* TO @user@@host;
CREATE USER IF NOT EXISTS `bloquser`@`localhost` IDENTIFIED BY "passwd";
GRANT SELECT, DELETE, INSERT, UPDATE ON `bloqs`.* TO `bloquser`@`localhost`;

CREATE TABLE `client`(
	`id` VARCHAR(95),
	PRIMARY KEY(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
