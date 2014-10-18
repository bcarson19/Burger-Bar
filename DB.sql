# ************************************************************
# Sequel Pro SQL dump
# Version 4135
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: localhost (MySQL 5.5.38)
# Database: dbburger
# Generation Time: 2014-10-15 20:02:36 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table burger
# ------------------------------------------------------------
DROP DATABASE IF EXISTS DBBurger;
CREATE DATABASE DBBurger;
USE DBBurger;

DROP TABLE IF EXISTS `burger`;

CREATE TABLE `burger` (
  `burgerID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orderID` int(11) unsigned NOT NULL,
  `quantity` int(11) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`burgerID`),
  KEY `orderID FK` (`orderID`),
  CONSTRAINT `orderID FK` FOREIGN KEY (`orderID`) REFERENCES `foodorder` (`orderID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `burger` WRITE;
/*!40000 ALTER TABLE `burger` DISABLE KEYS */;

INSERT INTO `burger` (`burgerID`, `orderID`, `quantity`)
VALUES
	(1,1,1),
	(2,2,2),
	(3,3,1),
	(4,3,3),
	(6,5,2);

/*!40000 ALTER TABLE `burger` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table Food
# ------------------------------------------------------------

DROP TABLE IF EXISTS `Food`;

CREATE TABLE `Food` (
  `name` varchar(30) NOT NULL DEFAULT '',
  `id` int(11) NOT NULL DEFAULT '0',
  `price` double DEFAULT '0',
  `type` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`name`,`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `Food` WRITE;
/*!40000 ALTER TABLE `Food` DISABLE KEYS */;

INSERT INTO `Food` (`name`, `id`, `price`, `type`)
VALUES
	('1/2 lb. Beef',2,2.25,'Burger'),
	('1/3 lb. Beef',1,2.00,'Burger'),
    ('2/3 lb. Beef',26,2.50,'Burger'),
	('American',9,0.35,'Cheese'),
	('Bacon',15,0.00,'Topping'),
	('BBQ',22,0.00,'Sauce'),
	('Cheddar',8,0.35,'Cheese'),
	('French fries',23,2.00,'Sides'),
	('Jalapenos',18,0.00,'Topping'),
	('Ketchup',19,0.00,'Sauce'),
	('Lettuce',12,0.00,'Topping'),
	('Mayonnaise',21,0.00,'Sauce'),
	('Mushroms',17,0.00,'Topping'),
	('Mustard',20,0.00,'Sauce'),
	('Onion rings',25,1.00,'Sides'),
	('Onions',13,0.00,'Topping'),
	('Pickles',14,0.00,'Topping'),
	('Red onion',16,0.00,'Topping'),
	('Swiss',10,0.35,'Cheese'),
	('Tater tots',24,1.00,'Sides'),
	('Texas Toast',7,0.75,'Bun'),
	('Tomatoes',11,0.00,'Topping'),
	('Turkey',3,2.00,'Burger'),
	('Veggie',4,2.00,'Burger'),
	('Wheat',6,0.50,'Bun'),
	('White',5,0.50,'Bun');

/*!40000 ALTER TABLE `Food` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table BurgerDetail
# ------------------------------------------------------------

DROP TABLE IF EXISTS `BurgerDetail`;

CREATE TABLE `BurgerDetail` (
  `name` varchar(30) NOT NULL DEFAULT '',
  `burgerID` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`name`,`burgerID`),
  KEY `BurgerID FK` (`burgerID`),
  CONSTRAINT `BurgerID FK` FOREIGN KEY (`burgerID`) REFERENCES `burger` (`burgerID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `food FK` FOREIGN KEY (`name`) REFERENCES `Food` (`name`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `BurgerDetail` WRITE;
/*!40000 ALTER TABLE `BurgerDetail` DISABLE KEYS */;

INSERT INTO `BurgerDetail` (`name`, `burgerID`)
VALUES
	('Tomatoes',1),
	('Turkey',1),
	('Wheat',1),
	('Ketchup',2),
	('Texas Toast',2),
	('Veggie',2),
	('Mustard',3),
	('Onions',3),
	('Veggie',3),
	('Wheat',3),
	('Onions',4),
	('Texas Toast',4),
	('Veggie ',4),
	('French Fries',6),
	('Texas Toast',6),
	('Tomatoes',6),
	('Veggie',6);

/*!40000 ALTER TABLE `BurgerDetail` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table foodOrder
# ------------------------------------------------------------

DROP TABLE IF EXISTS `foodOrder`;

CREATE TABLE `foodOrder` (
  `orderID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`orderID`),
  KEY `User FK` (`username`),
  CONSTRAINT `User FK` FOREIGN KEY (`username`) REFERENCES `users` (`username`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `foodOrder` WRITE;
/*!40000 ALTER TABLE `foodOrder` DISABLE KEYS */;

INSERT INTO `foodOrder` (`orderID`, `username`)
VALUES
	(5,'Brandon'),
	(2,'Karoline'),
	(3,'Karoline'),
	(1,'Rolf');

/*!40000 ALTER TABLE `foodOrder` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table paymentInfo
# ------------------------------------------------------------

DROP TABLE IF EXISTS `paymentInfo`;

CREATE TABLE `paymentInfo` (
  `username` varchar(30) DEFAULT NULL,
  `paymentId` int(11) DEFAULT NULL,
  `cardNumber` int(11) DEFAULT NULL,
  `typeOfCard` varchar(30) DEFAULT NULL,
  `address` varchar(30) DEFAULT NULL,
  `zipCode` varchar(30) DEFAULT NULL,
  `state` varchar(30) DEFAULT NULL,
  `expireDate` varchar(30) DEFAULT NULL,
  KEY `username` (`username`),
  CONSTRAINT `paymentinfo_ibfk_1` FOREIGN KEY (`username`) REFERENCES `users` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `paymentInfo` WRITE;
/*!40000 ALTER TABLE `paymentInfo` DISABLE KEYS */;

INSERT INTO `paymentInfo` (`username`, `paymentId`, `cardNumber`, `typeOfCard`, `address`, `zipCode`, `state`, `expireDate`)
VALUES
	('Karoline',1,123456789,'Visa','3669 Asbury Street','75205','Dallas','7/7/12'),
	('Karoline',2,987654321,'MasterCard','3669 Asbury Street','75205','Dallas','7/7/12'),
	('Brandon',NULL,NULL,'American Express',NULL,NULL,NULL,NULL),
	('Karoline',NULL,NULL,'American Express',NULL,NULL,NULL,NULL);

/*!40000 ALTER TABLE `paymentInfo` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `username` varchar(30) NOT NULL DEFAULT '',
  `pw` varchar(30) DEFAULT NULL,
  `firstname` varchar(30) DEFAULT NULL,
  `lastname` varchar(30) DEFAULT NULL,
  `email` varchar(30) DEFAULT NULL,
  `recentorder` int(11) unsigned DEFAULT NULL,
  `phonenumber` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`username`),
  KEY `recentOrder FK` (`recentorder`),
  CONSTRAINT `recentOrder FK` FOREIGN KEY (`recentorder`) REFERENCES `foodorder` (`orderID`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1; ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;

INSERT INTO `users` (`username`, `pw`, `firstname`, `lastname`, `email`, INSERT INTO `users` (`username`, `pw`, `firstname`, `lastname`, `email`, `recentorder`, `phonenumber`)
VALUES
	('Anne','123456789','Anne','Egeland ','kskatteboe@smu.edu',NULL,NULL),
	('Brandon','test','Brandon','Carson','bcarson@smu.edu',NULL,NULL),
	('Karoline','123456789','Karoline','Skatteboe','kskatteboe@smu.edu',3,NULL),
	('Rolf','123','Rolf','Skatteboe','rolf.skatteboe@me.com',1,NULL),
	('sjskatte','Dropset31','Sigrid','Skatteboe','sjskar@gmail.com',NULL,'2147253728'),
	('Tom','password','Tom','Kennedy','tkennedy@smu.edu',NULL,NULL),
	('unloggedIn',NULL,NULL,NULL,NULL,NULL,NULL);

/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
