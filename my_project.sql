/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19-11.7.2-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: projectDB
-- ------------------------------------------------------
-- Server version	11.7.2-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*M!100616 SET @OLD_NOTE_VERBOSITY=@@NOTE_VERBOSITY, NOTE_VERBOSITY=0 */;

--
-- Table structure for table `artworks`
--

DROP TABLE IF EXISTS `artworks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `artworks` (
  `art_id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned DEFAULT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `upload_date` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`art_id`),
  KEY `uid` (`uid`),
  CONSTRAINT `artworks_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `userInfo` (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `artworks`
--

LOCK TABLES `artworks` WRITE;
/*!40000 ALTER TABLE `artworks` DISABLE KEYS */;
INSERT INTO `artworks` VALUES
(26,1,'z1vji92urpea1.jpg','../uploads/z1vji92urpea1.jpg','Hutao','Hutao Dayo!!!','2025-04-14 15:12:54'),
(27,1,'20240907164348.png','../uploads/20240907164348.png','Genshin Scenery','It\'s Pretty','2025-04-14 15:20:08'),
(28,1,'20241121190215.png','../uploads/20241121190215.png','pretty','scenery','2025-04-16 06:56:40'),
(29,1,'20240201191750.png','../uploads/20240201191750.png','genshin','chenyu vale','2025-04-16 06:57:09'),
(31,1,'20240830093942.png','../uploads/20240830093942.png','','','2025-04-16 06:58:08'),
(32,1,'20240929140248.png','../uploads/20240929140248.png','','','2025-04-16 06:59:39'),
(41,10,'comfy.jpg','../uploads/art_68013214653c94.27177484.jpg','','','2025-04-17 16:53:40'),
(42,10,'road.jpg','../uploads/art_68013219c48438.97637533.jpg','','','2025-04-17 16:53:45'),
(43,10,'pagoda.jpg','../uploads/art_6801321dbcf815.89425431.jpg','','','2025-04-17 16:53:49'),
(44,10,'mualani.jpeg','../uploads/art_68013223e3edd4.56359836.jpeg','','','2025-04-17 16:53:55'),
(46,2,'110180948_p1_master1200.jpg','../uploads/art_6801325a019654.76733763.jpg','','','2025-04-17 16:54:50'),
(47,2,'1220394.jpg','../uploads/art_68013261e6a6b0.16488083.jpg','','','2025-04-17 16:54:57'),
(48,4,'1620393825542.jpg','../uploads/art_6801327532a093.44023385.jpg','','','2025-04-17 16:55:17'),
(49,4,'2766987.jpg','../uploads/art_680132926a1b35.62993446.jpg','','','2025-04-17 16:55:46'),
(50,9,'20230112132037.png','../uploads/art_680132df9396d6.25766926.png','','','2025-04-17 16:57:03'),
(51,9,'20220829131732.png','../uploads/art_680132f6b2de72.07261159.png','','','2025-04-17 16:57:26'),
(52,12,'20230119175319.png','../uploads/art_68034dadafafb9.66477638.png','','','2025-04-19 07:15:57'),
(53,1,'20221018141134.png','../uploads/art_6803d2d59fd102.85679409.png','abc','asdb','2025-04-19 16:44:05'),
(56,1,'20220822213342.png','../uploads/art_6804ef2d8ffe76.64081303.png','abc','abc','2025-04-20 12:57:17');
/*!40000 ALTER TABLE `artworks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `comments` (
  `comment_id` int(11) NOT NULL AUTO_INCREMENT,
  `art_id` int(11) NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `comment_text` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`comment_id`),
  KEY `art_id` (`art_id`),
  KEY `uid` (`uid`),
  CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`art_id`) REFERENCES `artworks` (`art_id`),
  CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`uid`) REFERENCES `userInfo` (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comments`
--

LOCK TABLES `comments` WRITE;
/*!40000 ALTER TABLE `comments` DISABLE KEYS */;
INSERT INTO `comments` VALUES
(2,32,2,'GUOBA GET THEM !!!','2025-04-17 18:56:11'),
(3,32,2,'Nice And Spicy !!!','2025-04-17 18:57:44'),
(4,32,10,'OUT OF THE FRYING PAN INTO THE FIRE!!','2025-04-17 18:58:47'),
(5,32,9,'EAT THIS!!','2025-04-17 18:59:29'),
(9,48,12,'Think Bondrewd Think!!!','2025-04-18 04:43:01'),
(10,32,12,'admin','2025-04-18 07:38:29'),
(11,26,1,'wow','2025-04-19 13:26:53');
/*!40000 ALTER TABLE `comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `userInfo`
--

DROP TABLE IF EXISTS `userInfo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `userInfo` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`uid`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `userInfo`
--

LOCK TABLES `userInfo` WRITE;
/*!40000 ALTER TABLE `userInfo` DISABLE KEYS */;
INSERT INTO `userInfo` VALUES
(1,'r15u','r15u@gmail.com','$2y$12$wwdZCTEVF5Ipo8gsxkGuWOBuWOH916Tid6gUpN4Mt1fVlSMfrnum.','2025-04-12 04:22:28'),
(2,'rishu','rishu@gmail.com','$2y$12$uvCWZt7ZzDznbRYkpSKlHe3sZ9A8t2dxwpAMtRuy/mrCHFQ5RMvTa','2025-04-12 06:17:14'),
(4,'tanmay','tan@may.com','$2y$12$PACbvjmjW/emc3UZugRLQefzlYWzXaHUj9YLqgkR8hzD4XKX/QGBe','2025-04-12 16:03:29'),
(9,'amrutha','amu@123.com','$2y$12$A9O7F3MGp4UgiUDxEILt9O36ZyHhUgqtwGknFzGh3kWMKyPObUaem','2025-04-13 10:56:44'),
(10,'hargun','har@123.com','$2y$12$RavGVYKmYpFI7Jqx8DceBuUWIxM95SjKR.MEQBgfQlOuxG33YCZTy','2025-04-13 12:10:32'),
(11,'teacher','teach@123.com','$2y$12$gKA6CKjehNFSqB1p.F7V/.1rp7AkdQAIKNZJWcMS2R/jD/3CRVtiy','2025-04-17 07:53:04'),
(12,'admin','admin@pictorio.com','$2y$12$fvjI1rJRq82VLy52yJ42be2B5dXxNSWQ3hA3b3XNnAWZPku9Tp7Me','2025-04-17 08:36:10');
/*!40000 ALTER TABLE `userInfo` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*M!100616 SET NOTE_VERBOSITY=@OLD_NOTE_VERBOSITY */;

-- Dump completed on 2025-04-21 11:17:24
