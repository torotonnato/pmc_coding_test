-- MariaDB dump 10.19  Distrib 10.5.18-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: pmc
-- ------------------------------------------------------
-- Server version	10.5.18-MariaDB-0+deb11u1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Current Database: `pmc`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `pmc` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;

USE `pmc`;

--
-- Table structure for table `market`
--

DROP TABLE IF EXISTS `market`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `market` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `model` varchar(32) NOT NULL,
  `year` int(4) NOT NULL,
  `price` int(9) NOT NULL,
  `km` int(7) NOT NULL,
  `seller_id` int(11) NOT NULL,
  `image` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `seller_id` (`seller_id`),
  CONSTRAINT `market_ibfk_1` FOREIGN KEY (`seller_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `market`
--

LOCK TABLES `market` WRITE;
/*!40000 ALTER TABLE `market` DISABLE KEYS */;
INSERT INTO `market` VALUES (1,'Volkswagen GTI',2022,44550,0,2,'/pmc/uploads/2022-Volkswagen_GTI.jpg'),(2,'Aston Martin DBX',2020,185000,1560,2,'/pmc/uploads/2020-Aston_Martin_DBX.jpg'),(3,'Rolls Royce Ghost',2020,249000,8740,2,'/pmc/uploads/2020-Rolls_Royce_Ghost.jpg'),(4,'BMW M8',2020,188600,0,2,'/pmc/uploads/2020-BMW_M8.jpg'),(5,'Ford Focus',2011,12500,45000,3,'/pmc/uploads/2011-Ford_Focus.jpg'),(6,'Toyota Aygo',2017,8500,37000,3,'/pmc/uploads/2017-Toyota_aygo.jpg'),(7,'Skoda Octavia vRS',2018,13800,17600,4,'/pmc/uploads/2018-Skoda_Octavia_vRS.jpg'),(8,'Hyundai Santro',2005,1200,190000,4,'/pmc/uploads/2005-Hyundai_Santro.jpg'),(9,'Mitsubishi EK Wagon',2007,1500,260000,4,'/pmc/uploads/2007-Mitsubishi Shi_Ek_wagon.jpg'),(10,'Skoda Octavia',2020,27000,5500,5,'/pmc/uploads/2020-Skoda_Octavia.jpg'),(11,'Toyota GR Supra',2020,57000,0,5,'/pmc/uploads/2020-Toyota_GR_Supra.jpg');
/*!40000 ALTER TABLE `market` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `seller_id` int(11) NOT NULL,
  `buyer_id` int(11) NOT NULL,
  `car_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `car_id` (`car_id`),
  KEY `seller_id` (`seller_id`),
  KEY `buyer_id` (`buyer_id`),
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`car_id`) REFERENCES `market` (`id`),
  CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`seller_id`) REFERENCES `users` (`id`),
  CONSTRAINT `orders_ibfk_3` FOREIGN KEY (`buyer_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (1,2,7,4);
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roles` (
  `id` int(2) NOT NULL,
  `role` varchar(16) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (0,'admin'),(1,'buyer'),(2,'seller');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(32) NOT NULL,
  `hash` char(60) NOT NULL,
  `email` varchar(32) NOT NULL,
  `auth_key` char(32) DEFAULT NULL,
  `role_id` int(2) NOT NULL,
  `auto_login` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `role_id` (`role_id`),
  KEY `idx_auth_key` (`auth_key`),
  CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'admin','$2y$12$L//8oNPQq7E9TuwWQXVyx.1j3EFOXD1dQzaPdPzIm6KtrK5f17sOW','admin@test.com','',0,0),(2,'andrea','$2y$12$CNIXIKEpU6GtykuLqthN9egdiC5WNNZDT7AQ10YJz7SrbzSf7UopG','andrea@gmail.com','tMLBHOAU4P1mS0aeU7ZDTC8ffk8VJjHb',2,1),(3,'lucia','$2y$12$98drR2YWqLd0b1nR4VTVh.7LdsaOTz1jxT72vb4xlu3qoSzhJE97y','lucia@email.org','4xF0J0ogE-Kvar-sYkrMplmAFfZxAQYa',2,1),(4,'marco','$2y$12$h8.EJPM3aw0xFPxUII91l.WBlwUEx9EorQ95HX2H23KYhGYyA5sbu','marco@msn.com','PtzAHcHcZMdplO51EXWkh3wocoQX9wzV',2,1),(5,'john','$2y$12$64ieayrbw0SNyj9wxZ9ZkOKqSMq4Owv/L25bxclJe6G66W9GPK9AW','john@aol.com','kVGVkUaQ9r33Nx8BFoh_n0trZ9KmtFDp',2,1),(6,'anna','$2y$12$0TTc0CSkwZckg.tUQssge.aJtVMgkdTKDtJFVHZKbIZQM3eXJ1Oku','anna@virgilio.it','F52scxy_s-RC--VJNKzBiDXYIm3ehZOx',1,1),(7,'stefano','$2y$12$FkHRDap9mRaYriPhR4Ug6.CB7Z04ityeB4wQBFmPPDFV0V22hgS32','stefano@yandex.ru','RGWXYenseBjY5uaq9dioez2-Aj0huno4',1,1),(8,'marta','$2y$12$F3F05D1EKi8b1nzVuI/Kx.188.oR8pJmxEJciWGruYVNx/Q/IGUbK','marta@protonmail.is',NULL,1,1),(9,'sonia','$2y$12$tfr/ZaV8cpUAHKcAzrChI.LFAeD9q3BIw0gF92hM40RhiW5p91JFq','sonia@ycombinator.com',NULL,1,1);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-02-08 10:17:52
