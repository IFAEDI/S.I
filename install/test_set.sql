-- MySQL dump 10.13  Distrib 5.5.24, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: aedi
-- ------------------------------------------------------
-- Server version	5.5.24-0ubuntu0.12.04.1

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
-- Table structure for table `COMMENTAIRE_ENTREPRISE`
--

DROP TABLE IF EXISTS `COMMENTAIRE_ENTREPRISE`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `COMMENTAIRE_ENTREPRISE` (
  `ID_COMMENTAIRE` int(11) NOT NULL AUTO_INCREMENT,
  `ID_PERSONNE` int(11) NOT NULL,
  `ID_ENTREPRISE` int(11) NOT NULL,
  `DATE` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `CONTENU` text NOT NULL,
  `CATEGORIE` tinyint(4) NOT NULL,
  PRIMARY KEY (`ID_COMMENTAIRE`),
  KEY `ID_ENTREPRISE` (`ID_ENTREPRISE`),
  KEY `ID_PERSONNE` (`ID_PERSONNE`),
  CONSTRAINT `commentaire_entreprise_ibfk_4` FOREIGN KEY (`ID_PERSONNE`) REFERENCES `PERSONNE` (`ID_PERSONNE`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `commentaire_entreprise_ibfk_6` FOREIGN KEY (`ID_ENTREPRISE`) REFERENCES `ENTREPRISE` (`ID_ENTREPRISE`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `COMMENTAIRE_ENTREPRISE`
--

LOCK TABLES `COMMENTAIRE_ENTREPRISE` WRITE;
/*!40000 ALTER TABLE `COMMENTAIRE_ENTREPRISE` DISABLE KEYS */;
INSERT INTO `COMMENTAIRE_ENTREPRISE` VALUES (1,17,2,'2013-02-14 14:42:32','Participation aux RIFs en 2006',2);
/*!40000 ALTER TABLE `COMMENTAIRE_ENTREPRISE` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `CONTACT_ENTREPRISE`
--

DROP TABLE IF EXISTS `CONTACT_ENTREPRISE`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `CONTACT_ENTREPRISE` (
  `ID_CONTACT` int(11) NOT NULL AUTO_INCREMENT,
  `ID_ENTREPRISE` int(11) NOT NULL,
  `ID_PERSONNE` int(11) NOT NULL,
  `ID_VILLE` int(11) DEFAULT NULL,
  `FONCTION` varchar(35) DEFAULT NULL,
  `PRIORITE` int(2) DEFAULT NULL,
  `COMMENTAIRE` text,
  PRIMARY KEY (`ID_CONTACT`),
  KEY `ID_PERSONNE` (`ID_PERSONNE`),
  KEY `ID_ENTREPRISE` (`ID_ENTREPRISE`),
  KEY `ID_VILLE` (`ID_VILLE`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `CONTACT_ENTREPRISE`
--

LOCK TABLES `CONTACT_ENTREPRISE` WRITE;
/*!40000 ALTER TABLE `CONTACT_ENTREPRISE` DISABLE KEYS */;
INSERT INTO `CONTACT_ENTREPRISE` VALUES (1,2,27,1,'Chef de Projet',2,''),(2,3,28,6,'Charg&eacute;e de Recrutement',2,''),(3,3,29,7,'Manager BI',1,'Tr&egrave;s occup&eacute;');
/*!40000 ALTER TABLE `CONTACT_ENTREPRISE` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ENTREPRISE`
--

DROP TABLE IF EXISTS `ENTREPRISE`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ENTREPRISE` (
  `ID_ENTREPRISE` int(11) NOT NULL AUTO_INCREMENT,
  `NOM` varchar(35) NOT NULL,
  `DESCRIPTION` text,
  `SECTEUR` varchar(50) DEFAULT NULL,
  `COMMENTAIRE` text,
  PRIMARY KEY (`ID_ENTREPRISE`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ENTREPRISE`
--

LOCK TABLES `ENTREPRISE` WRITE;
/*!40000 ALTER TABLE `ENTREPRISE` DISABLE KEYS */;
INSERT INTO `ENTREPRISE` VALUES (2,'SQLI','D&eacute;veloppement PHP / Java principalement.','SSII',NULL),(3,'Accenture','Conseil pour les grands comptes.','Conseil',NULL);
/*!40000 ALTER TABLE `ENTREPRISE` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `MAIL`
--

DROP TABLE IF EXISTS `MAIL`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `MAIL` (
  `ID_PERSONNE` int(11) NOT NULL,
  `INTITULE` varchar(15) DEFAULT NULL,
  `MAIL` varchar(50) NOT NULL,
  `PRIORITE` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `MAIL`
--

LOCK TABLES `MAIL` WRITE;
/*!40000 ALTER TABLE `MAIL` DISABLE KEYS */;
INSERT INTO `MAIL` VALUES (27,'Pro','robert.delacroix@sqli.com',0),(28,'Pro','claire.delcagne@accenture.com',0),(29,'Pro','vincent.joly@accenture.com',0);
/*!40000 ALTER TABLE `MAIL` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `PERSONNE`
--

DROP TABLE IF EXISTS `PERSONNE`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PERSONNE` (
  `ID_PERSONNE` int(11) NOT NULL AUTO_INCREMENT,
  `NOM` varchar(35) DEFAULT NULL,
  `PRENOM` varchar(35) DEFAULT NULL,
  `ID_UTILISATEUR` int(11) DEFAULT NULL,
  `ROLE` int(4) DEFAULT '0',
  `PREMIERE_CONNEXION` int(1) DEFAULT '1',
  PRIMARY KEY (`ID_PERSONNE`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `PERSONNE`
--

LOCK TABLES `PERSONNE` WRITE;
/*!40000 ALTER TABLE `PERSONNE` DISABLE KEYS */;
INSERT INTO `PERSONNE` VALUES (17,'Administrateur','Système',13,3,0),(27,'Delacroix','Robert',NULL,2,0),(28,'Delcagne','Claire',NULL,2,0),(29,'Joly','Vincent',NULL,2,0),(30,'Etudiant','Test',14,0,1),(31,'AEDI','Test',15,4,1),(32,'Entreprise','Test',16,2,1),(33,'Enseignant','Test',17,1,1);
/*!40000 ALTER TABLE `PERSONNE` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `STAGE`
--

DROP TABLE IF EXISTS `STAGE`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `STAGE` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `TITRE` varchar(200) NOT NULL,
  `ANNEE` int(1) NOT NULL,
  `DESCRIPTION` text,
  `MOTS_CLES` varchar(100) DEFAULT NULL,
  `DOMAINE` varchar(100) DEFAULT NULL,
  `LIEN_FICHIER` varchar(255) DEFAULT NULL,
  `LIEU` varchar(75) NOT NULL,
  `DUREE` int(2) DEFAULT NULL,
  `ENTREPRISE` varchar(100) NOT NULL,
  `CONTACT` varchar(150) NOT NULL,
  `ID_ENTREPRISE` int(10) unsigned DEFAULT NULL,
  `ID_CONTACT` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `STAGE`
--

LOCK TABLES `STAGE` WRITE;
/*!40000 ALTER TABLE `STAGE` DISABLE KEYS */;
INSERT INTO `STAGE` VALUES (1,'Stage Corporate SAP',4,'Pour intégrer la mutualisation budget les managers décident de solutionner les opportunités stratégie.','pipo conseil consulting erp','ERP',NULL,'Paris',6,'Expersolusness','example@domain.org',NULL,NULL),(2,'Codage d\'un outil de test',3,'Superbe stage de codage d\'un outil de test pour quelque chose d\'important, développement en Visual Basic.','code maçon ouvrier plombier','Outil de test',NULL,'La Bégude de Mazenc (dans la drôme)',3,'Microgiciel','boss@microgiciel.fr',NULL,NULL),(3,'Recherche de valeur dans la qualité du système d\'informations',5,'PFE trèèèèèèèèèès intéressant.','pipo qualité aubry','Qualité',NULL,'Mbabane, Zwaziland',6,'MétaPipo','direction.generale.zwaziland@metapipo.com',NULL,NULL);
/*!40000 ALTER TABLE `STAGE` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `TELEPHONE`
--

DROP TABLE IF EXISTS `TELEPHONE`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `TELEPHONE` (
  `ID_PERSONNE` int(11) NOT NULL,
  `INTITULE` varchar(15) DEFAULT NULL,
  `NUMERO` varchar(15) NOT NULL,
  `PRIORITE` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `TELEPHONE`
--

LOCK TABLES `TELEPHONE` WRITE;
/*!40000 ALTER TABLE `TELEPHONE` DISABLE KEYS */;
INSERT INTO `TELEPHONE` VALUES (27,'Bureau','0425642834',0),(28,'Bureau','0178563434',0),(29,'Bureau','0320474739',0);
/*!40000 ALTER TABLE `TELEPHONE` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `UTILISATEUR`
--

DROP TABLE IF EXISTS `UTILISATEUR`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `UTILISATEUR` (
  `ID_UTILISATEUR` int(11) NOT NULL AUTO_INCREMENT,
  `LOGIN` varchar(35) NOT NULL,
  `PASSWD` varchar(50) DEFAULT NULL,
  `AUTH_SERVICE` int(1) NOT NULL,
  `BANNI` int(1) DEFAULT '0',
  PRIMARY KEY (`ID_UTILISATEUR`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `UTILISATEUR`
--

LOCK TABLES `UTILISATEUR` WRITE;
/*!40000 ALTER TABLE `UTILISATEUR` DISABLE KEYS */;
INSERT INTO `UTILISATEUR` VALUES (13,'root','435b41068e8665513a20070c033b08b9c66e4332',2,0),(14,'etutest','a94a8fe5ccb19ba61c4c0873d391e987982fbbd3',2,0),(15,'aeditest','a94a8fe5ccb19ba61c4c0873d391e987982fbbd3',2,0),(16,'entreprise','a94a8fe5ccb19ba61c4c0873d391e987982fbbd3',2,0),(17,'enstest','a94a8fe5ccb19ba61c4c0873d391e987982fbbd3',2,0);
/*!40000 ALTER TABLE `UTILISATEUR` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `VILLE`
--

DROP TABLE IF EXISTS `VILLE`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `VILLE` (
  `ID_VILLE` int(11) NOT NULL AUTO_INCREMENT,
  `LIBELLE_VILLE` varchar(75) DEFAULT NULL,
  `CP_VILLE` varchar(8) DEFAULT NULL,
  `PAYS_VILLE` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`ID_VILLE`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `VILLE`
--

LOCK TABLES `VILLE` WRITE;
/*!40000 ALTER TABLE `VILLE` DISABLE KEYS */;
INSERT INTO `VILLE` VALUES (1,'Lyon','69009','France'),(2,'Lyon','69009','Lyon'),(3,'Auxerre','89000','France'),(4,'Dijon','21000','France'),(5,'Lyon','69009','FR'),(6,'Paris','75008','France'),(7,'Lille','59800','France');
/*!40000 ALTER TABLE `VILLE` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2013-02-14 15:47:04
