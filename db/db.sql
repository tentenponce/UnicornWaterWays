-- MySQL dump 10.13  Distrib 5.6.26, for Win32 (x86)
--
-- Host: localhost    Database: unicornic
-- ------------------------------------------------------
-- Server version	5.6.26

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `amenities`
--

DROP TABLE IF EXISTS `amenities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `amenities` (
  `id` int(1) NOT NULL,
  `name` varchar(30) DEFAULT NULL,
  `dayPrice` double(6,2) DEFAULT NULL,
  `nightPrice` double(6,2) DEFAULT NULL,
  `minPerson` int(2) DEFAULT NULL,
  `maxPerson` int(2) DEFAULT NULL,
  `count` int(2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `amenities`
--

LOCK TABLES `amenities` WRITE;
/*!40000 ALTER TABLE `amenities` DISABLE KEYS */;
INSERT INTO `amenities` VALUES (1,'Cottages',500.00,1000.00,2,5,10),(2,'Regular Rooms',1000.00,1500.00,5,10,10),(3,'Grande Rooms',2000.00,2000.00,10,15,10),(4,'Supreme Rooms',2000.00,2000.00,15,30,10);
/*!40000 ALTER TABLE `amenities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bank`
--

DROP TABLE IF EXISTS `bank`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bank` (
  `accnum` varchar(10) DEFAULT NULL,
  `pin` varchar(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bank`
--

LOCK TABLES `bank` WRITE;
/*!40000 ALTER TABLE `bank` DISABLE KEYS */;
INSERT INTO `bank` VALUES ('4221910296','221096'),('9904221996','042299'),('5612894307','703498');
/*!40000 ALTER TABLE `bank` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `clients`
--

DROP TABLE IF EXISTS `clients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `clients` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `FirstName` varchar(30) DEFAULT NULL,
  `LastName` varchar(30) DEFAULT NULL,
  `PhoneNo` varchar(11) DEFAULT NULL,
  `Email` varchar(40) DEFAULT NULL,
  `Password` varchar(12) DEFAULT NULL,
  `Address` varchar(50) DEFAULT NULL,
  `money` double(9,2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clients`
--

LOCK TABLES `clients` WRITE;
/*!40000 ALTER TABLE `clients` DISABLE KEYS */;
INSERT INTO `clients` VALUES (1,'Tenten','Ponce','09075204777','poncetenten10@gmail.com','tentenponce','Malabon City',-23261.00),(2,'Marinella','Nadurata','09269809425','marinella_nadurata@yahoo.com','rankudo04229','Navotas City',103379.00),(3,'Portia','Ponce','09758512316','portiangelica@gmail.com','reyesportiap','Malabon Cit',105569.00),(4,'Clarisse','Ponce','12345678900','clarissevponce@gmail.com','clarisse','Malabon CIty',105119.00),(5,'Christopher','Esmero','12313141213','kaintae@gmail.com','1234567','barangay saksakan  val, city',109999.00),(6,'Bon','Cardenas','123123123','boncardenas@yahoo.com','12345678','malabon',108699.00),(7,'Kim','Albino','09075204777','kim@gmail.com','123123123','alam mo na ',103399.00);
/*!40000 ALTER TABLE `clients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `discount`
--

DROP TABLE IF EXISTS `discount`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `discount` (
  `roomID` int(1) NOT NULL,
  `percent` int(3) DEFAULT NULL,
  PRIMARY KEY (`roomID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `discount`
--

LOCK TABLES `discount` WRITE;
/*!40000 ALTER TABLE `discount` DISABLE KEYS */;
/*!40000 ALTER TABLE `discount` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `feedback`
--

DROP TABLE IF EXISTS `feedback`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `feedback` (
  `userID` int(4) NOT NULL,
  `msg` varchar(999) DEFAULT NULL,
  `date` varchar(30) DEFAULT NULL,
  `love` int(1) DEFAULT NULL,
  PRIMARY KEY (`userID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `feedback`
--

LOCK TABLES `feedback` WRITE;
/*!40000 ALTER TABLE `feedback` DISABLE KEYS */;
INSERT INTO `feedback` VALUES (1,'Feedback testing toot toot FeeeedBack','2016-03-01T22:14',0),(3,'So clean so good!','2016-02-16T9:00',0),(5,'tanginang  mukha yan san ba gawa yan ','2016-02-16T9:00',1),(7,'ANG LUFET NI ESME ','2016-02-16T9:00',1);
/*!40000 ALTER TABLE `feedback` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `motto`
--

DROP TABLE IF EXISTS `motto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `motto` (
  `motto` varchar(999) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `motto`
--

LOCK TABLES `motto` WRITE;
/*!40000 ALTER TABLE `motto` DISABLE KEYS */;
INSERT INTO `motto` VALUES ('Punch Line and Motto Here.');
/*!40000 ALTER TABLE `motto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `prices`
--

DROP TABLE IF EXISTS `prices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prices` (
  `adult` double(6,2) DEFAULT NULL,
  `discount` int(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prices`
--

LOCK TABLES `prices` WRITE;
/*!40000 ALTER TABLE `prices` DISABLE KEYS */;
INSERT INTO `prices` VALUES (200.00,30);
/*!40000 ALTER TABLE `prices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reservation`
--

DROP TABLE IF EXISTS `reservation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reservation` (
  `code` varchar(30) NOT NULL,
  `room` varchar(30) DEFAULT NULL,
  `checkIn` varchar(30) DEFAULT NULL,
  `checkOut` varchar(30) DEFAULT NULL,
  `stayPrice` double(8,2) DEFAULT NULL,
  `adultCount` int(2) DEFAULT NULL,
  `adultPrice` double(6,2) DEFAULT NULL,
  `childCount` int(2) DEFAULT NULL,
  `childPrice` double(6,2) DEFAULT NULL,
  `totalPrice` double(8,2) DEFAULT NULL,
  `clientID` int(4) DEFAULT NULL,
  `date` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reservation`
--

LOCK TABLES `reservation` WRITE;
/*!40000 ALTER TABLE `reservation` DISABLE KEYS */;
INSERT INTO `reservation` VALUES ('20160117T0100C10','Cottages','2016-01-17T01:00','2016-01-18T02:00',1000.00,2,400.00,2,120.00,1520.00,1,'2016-02-10T1:00'),('20160117T0100C7','Cottages','2016-01-17T01:00','2016-01-20T13:00',6000.00,2,400.00,1,60.00,6460.00,1,'2016-02-10T1:00'),('20160117T1300C9','Cottages','2016-01-17T13:00','2016-01-19T13:00',3500.00,2,400.00,0,0.00,3900.00,1,'2016-02-10T1:00'),('20160118T0100C5','Cottages','2016-01-18T01:00','2016-01-18T02:00',1000.00,2,400.00,3,180.00,1580.00,1,'2016-02-10T1:00'),('20160118T0100C6','Cottages','2016-01-18T01:00','2016-01-18T02:00',1000.00,2,400.00,3,180.00,1580.00,1,'2016-02-10T1:00'),('20160118T0100C7','Cottages','2016-01-18T01:00','2016-01-18T02:00',1000.00,2,400.00,2,120.00,1520.00,1,'2016-02-10T1:00'),('20160119T0100C9','Cottages','2016-01-19T01:00','2016-01-19T02:00',1000.00,2,400.00,3,180.00,1580.00,1,'2016-02-10T1:00'),('20160119T1400G10','Grande Rooms','2016-01-19T14:00','2016-01-20T13:00',6000.00,10,2000.00,0,0.00,8000.00,2,'2016-02-10T1:00'),('20160125T0100C10','Cottages','2016-01-25T01:00','2016-01-26T02:00',2500.00,3,600.00,0,0.00,3100.00,4,'2016-02-10T1:00'),('20160125T0900G10','Grande Rooms','2016-01-25T09:00','2016-01-26T21:00',8000.00,4,800.00,1,60.00,8860.00,3,'2016-02-10T1:00'),('20160203T0100C10','Cottages','2016-02-03T01:00','2016-02-03T13:00',1500.00,1,200.00,0,0.00,1700.00,1,'2016-02-10T1:00'),('20160203T0100G10','Grande Rooms','2016-02-03T01:00','2016-02-03T22:00',6000.00,2,400.00,2,120.00,6520.00,1,'2016-02-10T1:00'),('20160203T0100G9','Grande Rooms','2016-02-03T01:00','2016-02-03T22:00',6000.00,2,400.00,2,120.00,6520.00,1,'2016-02-10T1:00'),('20160203T0100R10','Regular Rooms','2016-02-03T01:00','2016-02-03T23:00',4000.00,4,800.00,0,0.00,4800.00,1,'2016-02-10T1:00'),('20160203T0100R9','Regular Rooms','2016-02-03T01:00','2016-02-03T23:00',4000.00,4,800.00,0,0.00,4800.00,1,'2016-02-10T1:00'),('20160212T0100C1','Cottages','2016-02-12T01:00','2016-02-12T02:00',1000.00,1,200.00,0,0.00,1200.00,7,'2016-02-10T1:00'),('20160212T0100C10','Cottages','2016-02-12T01:00','2016-02-12T02:00',1000.00,1,200.00,0,0.00,1200.00,7,'2016-02-10T1:00'),('20160212T0100C2','Cottages','2016-02-12T01:00','2016-02-12T02:00',1000.00,1,200.00,0,0.00,1200.00,7,'2016-02-10T1:00'),('20160212T0100C3','Cottages','2016-02-12T01:00','2016-02-12T02:00',1000.00,1,200.00,0,0.00,1200.00,7,'2016-02-10T1:00'),('20160212T0100C4','Cottages','2016-02-12T01:00','2016-02-12T02:00',1000.00,1,200.00,0,0.00,1200.00,7,'2016-02-10T1:00'),('20160212T0100C5','Cottages','2016-02-12T01:00','2016-02-12T02:00',1000.00,1,200.00,0,0.00,1200.00,7,'2016-02-10T1:00'),('20160212T0100C6','Cottages','2016-02-12T01:00','2016-02-12T02:00',1000.00,1,200.00,0,0.00,1200.00,7,'2016-02-10T1:00'),('20160212T0100C7','Cottages','2016-02-12T01:00','2016-02-12T02:00',1000.00,1,200.00,0,0.00,1200.00,7,'2016-02-10T1:00'),('20160212T0100C8','Cottages','2016-02-12T01:00','2016-02-12T02:00',1000.00,1,200.00,0,0.00,1200.00,7,'2016-02-10T1:00'),('20160212T0100C9','Cottages','2016-02-12T01:00','2016-02-12T02:00',1000.00,1,200.00,0,0.00,1200.00,7,'2016-02-10T1:00'),('20160213T1111C10','Cottages','2016-02-13T11:11','2016-02-20T14:22',11000.00,5,1000.00,0,0.00,12000.00,7,'2016-02-10T1:00'),('20160217T1000S10','Supreme Rooms','2016-02-17T10:00','2016-02-20T09:00',12000.00,7,1400.00,0,0.00,13400.00,1,'2016-02-17T21:39'),('20160301T0100C3','Cottages','2016-03-01T01:00','2016-03-15T10:00',22500.00,2,400.00,3,180.00,23080.00,1,'2016-03-01T08:40'),('20160301T0100C4','Cottages','2016-03-01T01:00','2016-03-15T10:00',22500.00,2,400.00,3,180.00,23080.00,1,'2016-03-01T08:39'),('20160301T0100C5','Cottages','2016-03-01T01:00','2016-03-15T10:00',22500.00,2,400.00,3,180.00,23080.00,1,'2016-03-01T08:27'),('20160301T0100C6','Cottages','2016-03-01T01:00','2016-03-15T10:00',22500.00,2,400.00,3,180.00,23080.00,1,'2016-03-01T08:24'),('20160301T0100C7','Cottages','2016-03-01T01:00','2016-03-15T10:00',22500.00,2,400.00,3,180.00,23080.00,1,'2016-03-01T08:21'),('20160301T0100C8','Cottages','2016-03-01T01:00','2016-03-15T10:00',22500.00,2,400.00,3,180.00,23080.00,1,'2016-03-01T08:19'),('20160301T0100C9','Cottages','2016-03-01T01:00','2016-03-15T10:00',22500.00,2,400.00,3,180.00,23080.00,1,'2016-03-01T08:18'),('20160301T0100S10','Supreme Rooms','2016-03-01T01:00','2016-03-31T01:00',124000.00,21,4200.00,0,0.00,128200.00,1,'2016-03-01T22:50'),('20160301T1000C1','Cottages','2016-03-01T10:00','2016-03-20T01:00',28500.00,3,600.00,0,0.00,29100.00,1,'2016-03-01T11:53'),('20160301T1000C2','Cottages','2016-03-01T10:00','2016-03-20T01:00',28500.00,3,600.00,0,0.00,29100.00,1,'2016-03-01T11:51'),('20160303T0100C10','Cottages','2016-03-03T01:00','2016-03-03T21:00',2500.00,2,400.00,0,0.00,2900.00,1,'2016-02-10T1:00'),('20160303T0900R10','Regular Rooms','2016-03-03T09:00','2016-03-05T13:06',6000.00,3,600.00,1,60.00,6660.00,4,'2016-02-10T1:00'),('20160303T0900R9','Regular Rooms','2016-03-03T09:00','2016-03-05T13:06',6000.00,3,600.00,1,60.00,6660.00,4,'2016-02-10T1:00'),('20161021T0600R10','Regular Rooms','2016-10-21T06:00','2016-10-21T06:00',1000.00,8,1600.00,0,0.00,2600.00,6,'2016-02-10T1:00');
/*!40000 ALTER TABLE `reservation` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-03-01 23:04:45
