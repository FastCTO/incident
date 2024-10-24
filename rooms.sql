/*!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19  Distrib 10.6.18-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: guardian
-- ------------------------------------------------------
-- Server version	10.6.18-MariaDB-0ubuntu0.22.04.1

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
-- Table structure for table `rooms`
--

DROP TABLE IF EXISTS `rooms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rooms` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `room_full_name` varchar(255) NOT NULL,
  `room_number` varchar(50) DEFAULT NULL,
  `max_capacity` int(11) DEFAULT NULL,
  `occpd_normal` int(11) DEFAULT NULL,
  `last_occ_update` timestamp NULL DEFAULT NULL,
  `evac_zone` varchar(100) DEFAULT NULL,
  `room_status` varchar(50) DEFAULT NULL,
  `room_type` varchar(100) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rooms`
--

LOCK TABLES `rooms` WRITE;
/*!40000 ALTER TABLE `rooms` DISABLE KEYS */;
INSERT INTO `rooms` VALUES (1,'Classroom_101','101',30,1,NULL,'1','','Classroom',NULL,'2024-10-20 02:08:22','2024-10-20 02:08:22'),(2,'Classroom_102','102',30,1,NULL,'1','','Classroom',NULL,'2024-10-20 02:08:22','2024-10-20 02:08:22'),(3,'Classroom_103','103',30,1,NULL,'1','','Classroom',NULL,'2024-10-20 02:08:22','2024-10-20 02:08:22'),(4,'Classroom_104','104',30,1,NULL,'1','','Classroom',NULL,'2024-10-20 02:08:22','2024-10-20 02:08:22'),(5,'Classroom_105','105',30,1,NULL,'1','','Classroom',NULL,'2024-10-20 02:08:22','2024-10-20 02:08:22'),(6,'Classroom_106','106',30,1,NULL,'1','','Classroom',NULL,'2024-10-20 02:08:22','2024-10-20 02:08:22'),(7,'Classroom_107','107',30,1,NULL,'1','','Classroom',NULL,'2024-10-20 02:08:22','2024-10-20 02:08:22'),(8,'Classroom_108','108',30,1,NULL,'1','','Classroom',NULL,'2024-10-20 02:08:22','2024-10-20 02:08:22'),(9,'Classroom_109','109',30,1,NULL,'2','','Classroom',NULL,'2024-10-20 02:08:22','2024-10-20 02:08:22'),(10,'Classroom_110','110',30,1,NULL,'2','','Classroom',NULL,'2024-10-20 02:08:22','2024-10-20 02:08:22'),(11,'Classroom_111','111',30,1,NULL,'2','','Classroom',NULL,'2024-10-20 02:08:22','2024-10-20 02:08:22'),(12,'Classroom_112','112',30,1,NULL,'2','','Classroom',NULL,'2024-10-20 02:08:22','2024-10-20 02:08:22'),(13,'Classroom_113','113',30,1,NULL,'2','','Classroom',NULL,'2024-10-20 02:08:22','2024-10-20 02:08:22'),(14,'Classroom_114','114',30,1,NULL,'2','','Classroom',NULL,'2024-10-20 02:08:22','2024-10-20 02:08:22'),(15,'Classroom_115','115',30,1,NULL,'3','','Classroom',NULL,'2024-10-20 02:08:22','2024-10-20 02:08:22'),(16,'Classroom_116','116',30,1,NULL,'3','','Classroom',NULL,'2024-10-20 02:08:22','2024-10-20 02:08:22'),(17,'Classroom_117','117',30,1,NULL,'3','','Classroom',NULL,'2024-10-20 02:08:22','2024-10-20 02:08:22'),(18,'Classroom_118','118',30,1,NULL,'3','','Classroom',NULL,'2024-10-20 02:08:22','2024-10-20 02:08:22'),(19,'Classroom_119','119',30,1,NULL,'3','','Classroom',NULL,'2024-10-20 02:08:22','2024-10-20 02:08:22'),(20,'Classroom_120','120',30,1,NULL,'3','','Classroom',NULL,'2024-10-20 02:08:22','2024-10-20 02:08:22'),(21,'Office_201','201',4,1,NULL,'4','','Office',NULL,'2024-10-20 02:08:22','2024-10-20 02:08:22'),(22,'Office_202','202',4,1,NULL,'4','','Office',NULL,'2024-10-20 02:08:22','2024-10-20 02:08:22'),(23,'Office_203','203',4,1,NULL,'4','','Office',NULL,'2024-10-20 02:08:22','2024-10-20 02:08:22'),(24,'Office_204','204',4,1,NULL,'4','','Office',NULL,'2024-10-20 02:08:22','2024-10-20 02:08:22'),(25,'Specialty_301','301',200,1,NULL,'5','','Specialty',NULL,'2024-10-20 02:08:22','2024-10-20 02:08:22'),(26,'Specialty_302','302',35,1,NULL,'5','','Specialty',NULL,'2024-10-20 02:08:22','2024-10-20 02:08:22'),(27,'Specialty_303','303',350,1,NULL,'5','','Specialty',NULL,'2024-10-20 02:08:22','2024-10-20 02:08:22'),(28,'Specialty_304','304',40,1,NULL,'5','','Specialty',NULL,'2024-10-20 02:08:22','2024-10-20 02:08:22'),(29,'Unattended_401','401',2,0,NULL,'6','','Empty',NULL,'2024-10-20 02:08:22','2024-10-20 02:08:22'),(30,'Unattended_402','402',2,0,NULL,'6','','Empty',NULL,'2024-10-20 02:08:22','2024-10-20 02:08:22'),(31,'Unattended_403','403',2,0,NULL,'6','','Empty',NULL,'2024-10-20 02:08:22','2024-10-20 02:08:22'),(32,'Unattended_404','404',4,0,NULL,'6','','Empty',NULL,'2024-10-20 02:08:22','2024-10-20 02:08:22'),(33,'Unattended_405','405',4,0,NULL,'6','','Empty',NULL,'2024-10-20 02:08:22','2024-10-20 02:08:22'),(34,'Unattended_406','406',4,0,NULL,'6','','Empty',NULL,'2024-10-20 02:08:22','2024-10-20 02:08:22'),(35,'Unattended_407','407',4,0,NULL,'6','','Empty',NULL,'2024-10-20 02:08:22','2024-10-20 02:08:22'),(36,'Unattended_408','408',4,0,NULL,'6','','Empty',NULL,'2024-10-20 02:08:22','2024-10-20 02:08:22'),(37,'Unattended_409','409',4,0,NULL,'6','','Empty',NULL,'2024-10-20 02:08:22','2024-10-20 02:08:22'),(38,'Unattended_410','410',4,0,NULL,'6','','Empty',NULL,'2024-10-20 02:08:22','2024-10-20 02:08:22'),(39,'Unattended_411','411',4,0,NULL,'6','','Empty',NULL,'2024-10-20 02:08:22','2024-10-20 02:08:22'),(40,'Unattended_412','412',2,0,NULL,'6','','Empty',NULL,'2024-10-20 02:08:22','2024-10-20 02:08:22'),(41,'Unattended_413','413',2,0,NULL,'6','','Empty',NULL,'2024-10-20 02:08:22','2024-10-20 02:08:22'),(42,'Unattended_414','414',2,0,NULL,'6','','Empty',NULL,'2024-10-20 02:08:22','2024-10-20 02:08:22'),(43,'Unattended_415','415',2,0,NULL,'6','','Empty',NULL,'2024-10-20 02:08:22','2024-10-20 02:08:22'),(44,'Unattended_416','416',2,0,NULL,'6','','Empty',NULL,'2024-10-20 02:08:22','2024-10-20 02:08:22');
/*!40000 ALTER TABLE `rooms` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-10-23 10:44:09
