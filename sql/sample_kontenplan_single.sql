-- MySQL dump 10.13  Distrib 5.6.16, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: fibu
-- ------------------------------------------------------
-- Server version	5.6.16-1~exp1

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
-- Table structure for table `fi_konto`
--

DROP TABLE IF EXISTS `fi_konto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fi_konto` (
  `mandant_id` int(11) NOT NULL,
  `kontonummer` varchar(20) NOT NULL,
  `bezeichnung` varchar(256) NOT NULL,
  `kontenart_id` int(11) NOT NULL,
  PRIMARY KEY (`mandant_id`,`kontonummer`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fi_konto`
--

LOCK TABLES `fi_konto` WRITE;
/*!40000 ALTER TABLE `fi_konto` DISABLE KEYS */;
INSERT INTO `fi_konto` VALUES (1,'2400','Forderungen',1),(1,'0500','GrundstÃ¼cke',1),(1,'0600','GebÃ¤ude',1),(1,'0700','Auto',1),(1,'2800','Girokonto',1),(1,'2850','Festgeld/Schatzbriefe etc.',1),(1,'2880','Geldbeutel',1),(1,'5000','Gehalt',4),(1,'5100','NebentÃ¤tigkeit',4),(1,'5200','Sonstige ErtrÃ¤ge',4),(1,'6000','Lebensmittel',3),(1,'6010','Kantine',3),(1,'6020','GaststÃ¤tten',3),(1,'6100','Miete',3),(1,'6101','Nebenkosten Wohn.',3),(1,'6102','GEZ',3),(1,'6103','MÃ¶bel',3),(1,'6200','Auto Tanken',3),(1,'6201','Auto Reparaturen',3),(1,'6202','Auto Steuer',3),(1,'6203','Auto Sonstiges',3),(1,'6300','Freizeit',3),(1,'6301','Sport',3),(1,'6400','Versicherungen',3),(1,'6450','Steuern',3),(1,'6500','Kommunikation',3),(1,'6501','Internet, Telefon, Handy',3),(1,'6502','Hardware',3),(1,'6503','Literatur',3),(1,'6900','Sonstige Aufwendungen',3),(1,'6901','Aufwendungen fÃ¼r Geschenke',3),(1,'6902','Inventurdifferenz negativ',3),(1,'3000','Eigenkapital',2),(1,'4400','Unbezahlte Rechnungen',2),(1,'4500','Kredite',2);
/*!40000 ALTER TABLE `fi_konto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fi_quick_config`
--

DROP TABLE IF EXISTS `fi_quick_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fi_quick_config` (
  `mandant_id` int(11) NOT NULL,
  `config_id` int(11) NOT NULL AUTO_INCREMENT,
  `config_knz` varchar(50) NOT NULL,
  `sollkonto` varchar(50) DEFAULT NULL,
  `habenkonto` varchar(50) DEFAULT NULL,
  `buchungstext` varchar(256) DEFAULT NULL,
  `betrag` decimal(12,2) DEFAULT NULL,
  PRIMARY KEY (`config_id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fi_quick_config`
--

LOCK TABLES `fi_quick_config` WRITE;
/*!40000 ALTER TABLE `fi_quick_config` DISABLE KEYS */;
INSERT INTO `fi_quick_config` VALUES (1,2,'Ausgabe in Bar','6000','2880','Barausgabe',NULL),(1,3,'Gehalt','2800','5000','Gehalt',NULL),(1,4,'Barabhebung','2880','2800','Barabhebung',NULL),(1,5,'Einkauf Lebensmittel (bar)','6000','2880','Einkauf Lebensmittel',NULL),(1,6,'Miete','6100','2800','Miete',NULL),(1,7,'Kantine','6010','2880','Kantine',NULL);
/*!40000 ALTER TABLE `fi_quick_config` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-04-22 17:08:24
