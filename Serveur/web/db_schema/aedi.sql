-- phpMyAdmin SQL Dump
-- version 3.4.3.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 24, 2012 at 02:24 PM
-- Server version: 5.5.21
-- PHP Version: 5.3.10

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `aedi`
--

-- --------------------------------------------------------

--
-- Table structure for table `CERTIF_LNG`
--

CREATE TABLE IF NOT EXISTS `CERTIF_LNG` (
  `ID_CERTIF` int(11) NOT NULL AUTO_INCREMENT,
  `LIBELLE_CERTIF` varchar(15) DEFAULT NULL,
  `MAX_SCORE_CERTIF` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`ID_CERTIF`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=18 ;

--
-- Dumping data for table `CERTIF_LNG`
--

INSERT INTO `CERTIF_LNG` (`ID_CERTIF`, `LIBELLE_CERTIF`, `MAX_SCORE_CERTIF`) VALUES
(5, 'TOEIC', '990'),
(2, 'TOEFL', '677'),
(3, 'IELTS', '9'),
(4, 'CLES', NULL),
(1, 'Aucun', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `CV`
--

CREATE TABLE IF NOT EXISTS `CV` (
  `ID_CV` int(11) NOT NULL AUTO_INCREMENT,
  `TITRE_CV` varchar(75) DEFAULT NULL,
  `ID_MOBILITE` int(4) DEFAULT NULL,
  `LOISIRS_CV` tinytext,
  `AGREEMENT` tinyint(1) DEFAULT NULL,
  `MOTS_CLEF` varchar(100) NOT NULL,
  `ANNEE` int(1) NOT NULL,
  PRIMARY KEY (`ID_CV`),
  KEY `ID_MOBILITE` (`ID_MOBILITE`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=43 ;

--
-- Dumping data for table `CV`
--

INSERT INTO `CV` (`ID_CV`, `TITRE_CV`, `ID_MOBILITE`, `LOISIRS_CV`, `AGREEMENT`, `MOTS_CLEF`, `ANNEE`) VALUES
(42, '', 1, '', 1, 'dlkdfsj test lfdsjgm', 4);

-- --------------------------------------------------------

--
-- Table structure for table `CV_DIPLOME`
--

CREATE TABLE IF NOT EXISTS `CV_DIPLOME` (
  `ID_CVDIPLOME` int(11) NOT NULL AUTO_INCREMENT,
  `ANNEE_DIPLOME` int(4) NOT NULL,
  `ID_MENTION` int(11) DEFAULT NULL,
  `LIBELLE_DIPLOME` varchar(50) DEFAULT NULL,
  `ID_CV` int(11) NOT NULL,
  `INSTITUT` varchar(50) NOT NULL,
  `ID_VILLE` int(11) NOT NULL,
  PRIMARY KEY (`ID_CVDIPLOME`),
  KEY `ID_MENTION` (`ID_MENTION`),
  KEY `ID_CV` (`ID_CV`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=85 ;

--
-- Dumping data for table `CV_DIPLOME`
--

INSERT INTO `CV_DIPLOME` (`ID_CVDIPLOME`, `ANNEE_DIPLOME`, `ID_MENTION`, `LIBELLE_DIPLOME`, `ID_CV`, `INSTITUT`, `ID_VILLE`) VALUES
(84, 2007, 2, 'Bac Scientifique', 42, 'Lycée Paul Cézane', 277);

-- --------------------------------------------------------

--
-- Table structure for table `CV_FORMATION`
--

CREATE TABLE IF NOT EXISTS `CV_FORMATION` (
  `ID_CVFORMATION` int(11) NOT NULL AUTO_INCREMENT,
  `DEBUT_FORMATION` varchar(11) NOT NULL,
  `FIN_FORMATION` varchar(11) NOT NULL,
  `ANNEE_FORMATION` varchar(100) DEFAULT NULL,
  `ID_CV` int(11) NOT NULL,
  `INSTITUT` varchar(50) NOT NULL,
  `ID_VILLE` int(11) NOT NULL,
  PRIMARY KEY (`ID_CVFORMATION`),
  KEY `ID_CV` (`ID_CV`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=73 ;

--
-- Dumping data for table `CV_FORMATION`
--

INSERT INTO `CV_FORMATION` (`ID_CVFORMATION`, `DEBUT_FORMATION`, `FIN_FORMATION`, `ANNEE_FORMATION`, `ID_CV`, `INSTITUT`, `ID_VILLE`) VALUES
(71, '2009', '2011', '4ème année Departement Informatique', 42, 'INSA', 2),
(72, '2007', '2009', 'Classe préparatoire option Physique Science de l''Ingenieur', 42, 'Lycée Vauvenargue', 123);

-- --------------------------------------------------------

--
-- Table structure for table `CV_LANGUE`
--

CREATE TABLE IF NOT EXISTS `CV_LANGUE` (
  `ID_CVLANGUE` int(11) NOT NULL AUTO_INCREMENT,
  `ID_LANGUE` int(11) NOT NULL,
  `ID_NIVEAU` int(11) DEFAULT NULL,
  `ID_CERTIF` int(11) DEFAULT NULL,
  `SCORE_CERTIF` char(5) DEFAULT NULL,
  `ID_CV` int(11) NOT NULL,
  PRIMARY KEY (`ID_CVLANGUE`),
  KEY `ID_LANGUE` (`ID_LANGUE`),
  KEY `ID_NIVEAU` (`ID_NIVEAU`),
  KEY `ID_CERTIF` (`ID_CERTIF`),
  KEY `ID_CV` (`ID_CV`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=109 ;

--
-- Dumping data for table `CV_LANGUE`
--

INSERT INTO `CV_LANGUE` (`ID_CVLANGUE`, `ID_LANGUE`, `ID_NIVEAU`, `ID_CERTIF`, `SCORE_CERTIF`, `ID_CV`) VALUES
(108, 12, 3, 1, '', 42),
(107, 2, 5, 1, '', 42);

-- --------------------------------------------------------

--
-- Table structure for table `CV_XP`
--

CREATE TABLE IF NOT EXISTS `CV_XP` (
  `ID_CVXP` int(11) NOT NULL AUTO_INCREMENT,
  `DEBUT_XP` varchar(11) NOT NULL,
  `FIN_XP` varchar(11) DEFAULT NULL,
  `TITRE_XP` varchar(75) DEFAULT NULL,
  `DESC_XP` varchar(375) DEFAULT NULL,
  `ID_CV` int(11) NOT NULL,
  `ENTREPRISE` varchar(50) NOT NULL,
  `ID_VILLE` int(11) NOT NULL,
  PRIMARY KEY (`ID_CVXP`),
  KEY `ID_CV` (`ID_CV`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=72 ;

--
-- Dumping data for table `CV_XP`
--

INSERT INTO `CV_XP` (`ID_CVXP`, `DEBUT_XP`, `FIN_XP`, `TITRE_XP`, `DESC_XP`, `ID_CV`, `ENTREPRISE`, `ID_VILLE`) VALUES
(70, '06/2011', '08/2011', 'Conception d’un prototype', ' - Développement du système électronique de gestion du vide<br> - Développement du logiciel de gestion du vide en C#', 42, 'Orsayphysics nano solution', 249),
(71, '05/2011', '08/2011', 'Réalisation d’une plate-forme de gestion d’un parc d’équipements', ' - Développement d’une plateforme SaaS permettant l’administration et la visualisation des données liées aux parcs d’équipements et aux interventions effectuées en WebDev et MySQL <br>- Développement d’un firmware pour la lecture asynchrone de tag RFID sur un système embarqué en C', 42, 'Tagproduct', 2);

-- --------------------------------------------------------

--
-- Table structure for table `ETUDIANT`
--

CREATE TABLE IF NOT EXISTS `ETUDIANT` (
  `ID_ETUDIANT` int(11) NOT NULL,
  `NOM_ETUDIANT` varchar(50) NOT NULL,
  `PRENOM_ETUDIANT` varchar(50) NOT NULL,
  `SEXE_ETUDIANT` varchar(1) DEFAULT NULL,
  `ADRESSE1_ETUDIANT` varchar(100) DEFAULT NULL,
  `ADRESSE2_ETUDIANT` varchar(100) DEFAULT NULL,
  `ID_VILLE` int(11) NOT NULL,
  `TEL_ETUDIANT` varchar(15) DEFAULT NULL,
  `MAIL_ETUDIANT` varchar(75) NOT NULL,
  `ANNIV_ETUDIANT` varchar(11) DEFAULT NULL,
  `ID_VILLE_NAISSANCE` int(11) NOT NULL,
  `NATIONALITE_ETUDIANT` varchar(75) DEFAULT NULL,
  `ID_MARITAL` int(11) DEFAULT NULL,
  `ID_PERMIS` int(11) DEFAULT NULL,
  `PHOTO_ETUDIANT` varchar(32) DEFAULT NULL,
  `ID_CV` int(11) NOT NULL,
  KEY `ID_VILLE` (`ID_VILLE`),
  KEY `ID_VILLE_NAISSANCE` (`ID_VILLE_NAISSANCE`),
  KEY `ID_MARITAL` (`ID_MARITAL`),
  KEY `ID_PERMIS` (`ID_PERMIS`),
  KEY `ID_CV` (`ID_CV`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ETUDIANT`
--

INSERT INTO `ETUDIANT` (`ID_ETUDIANT`, `NOM_ETUDIANT`, `PRENOM_ETUDIANT`, `SEXE_ETUDIANT`, `ADRESSE1_ETUDIANT`, `ADRESSE2_ETUDIANT`, `ID_VILLE`, `TEL_ETUDIANT`, `MAIL_ETUDIANT`, `ANNIV_ETUDIANT`, `ID_VILLE_NAISSANCE`, `NATIONALITE_ETUDIANT`, `ID_MARITAL`, `ID_PERMIS`, `PHOTO_ETUDIANT`, `ID_CV`) VALUES
(1, 'Gevrey', 'Loïc', '0', '20 avenue Albert Einstein', 'I168 - INSA de Lyon', 1, '0645367787', 'mail.mail@mail.com', '02/04/1988', 2, 'Français', 1, 1, NULL, 42);

-- --------------------------------------------------------

--
-- Table structure for table `LANGUE`
--

CREATE TABLE IF NOT EXISTS `LANGUE` (
  `ID_LANGUE` int(11) NOT NULL AUTO_INCREMENT,
  `LIBELLE_LANGUE` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`ID_LANGUE`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=24 ;

--
-- Dumping data for table `LANGUE`
--

INSERT INTO `LANGUE` (`ID_LANGUE`, `LIBELLE_LANGUE`) VALUES
(1, 'Français'),
(2, 'Anglais'),
(3, 'Allemand'),
(4, 'Arabe'),
(5, 'Chinois'),
(6, 'Japonais'),
(7, 'Roumain'),
(8, 'Russe'),
(9, 'Italien'),
(18, 'Chinois (parlé)'),
(11, 'Breton'),
(12, 'Espagnol'),
(13, 'Catalan'),
(14, 'Coréen'),
(15, 'Vietnamien'),
(16, 'Portuguais'),
(19, 'Chinois (écrit)'),
(20, 'Japonais, Italien'),
(22, 'Langue des Signes');

-- --------------------------------------------------------

--
-- Table structure for table `MENTION`
--

CREATE TABLE IF NOT EXISTS `MENTION` (
  `ID_MENTION` int(11) NOT NULL AUTO_INCREMENT,
  `LIBELLE_MENTION` varchar(25) DEFAULT NULL,
  PRIMARY KEY (`ID_MENTION`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=21 ;

--
-- Dumping data for table `MENTION`
--

INSERT INTO `MENTION` (`ID_MENTION`, `LIBELLE_MENTION`) VALUES
(1, 'Passable'),
(2, 'Assez bien'),
(3, 'Bien'),
(4, 'Très bien'),
(5, 'Félicitation du jury');

-- --------------------------------------------------------

--
-- Table structure for table `MOBILITE`
--

CREATE TABLE IF NOT EXISTS `MOBILITE` (
  `ID_MOBILITE` int(4) NOT NULL AUTO_INCREMENT,
  `LIBELLE_MOBILITE` varchar(75) DEFAULT NULL,
  PRIMARY KEY (`ID_MOBILITE`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `MOBILITE`
--

INSERT INTO `MOBILITE` (`ID_MOBILITE`, `LIBELLE_MOBILITE`) VALUES
(1, 'Aucune'),
(2, 'Régionale'),
(3, 'Nationale'),
(4, 'Internationale');

-- --------------------------------------------------------

--
-- Table structure for table `NIVEAU_LANGUE`
--

CREATE TABLE IF NOT EXISTS `NIVEAU_LANGUE` (
  `ID_NIVEAU` int(11) NOT NULL AUTO_INCREMENT,
  `LIBELLE_NIVEAU` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`ID_NIVEAU`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `NIVEAU_LANGUE`
--

INSERT INTO `NIVEAU_LANGUE` (`ID_NIVEAU`, `LIBELLE_NIVEAU`) VALUES
(1, 'Langue Maternelle'),
(2, 'Débutant'),
(3, 'Scolaire'),
(4, 'Bon niveau'),
(5, 'Courant');

-- --------------------------------------------------------

--
-- Table structure for table `PERMIS`
--

CREATE TABLE IF NOT EXISTS `PERMIS` (
  `ID_PERMIS` int(11) NOT NULL AUTO_INCREMENT,
  `LIBELLE_PERMIS` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`ID_PERMIS`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=17 ;

--
-- Dumping data for table `PERMIS`
--

INSERT INTO `PERMIS` (`ID_PERMIS`, `LIBELLE_PERMIS`) VALUES
(1, 'Permis B avec voiture'),
(2, 'Permis B'),
(3, 'Véhicule'),
(4, 'Transport en commun');

-- --------------------------------------------------------

--
-- Table structure for table `STAGE`
--

CREATE TABLE IF NOT EXISTS `Stage` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `titre` varchar(200) NOT NULL,
  `annee` int(1) NOT NULL,
  `description` text NOT NULL,
  `mots_cles` varchar(100) DEFAULT NULL,
  `lien_fichier` varchar(200) DEFAULT NULL,
  `lieu` varchar(50) NOT NULL,
  `duree` int(2) NOT NULL,
  `entreprise` varchar(100) NOT NULL,
  `mail_contact` varchar(150) NOT NULL,
  `id_entreprise` int(10) unsigned DEFAULT NULL,
  `id_contact` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  FULLTEXT KEY `recherche_mots_cles` (`titre`,`description`,`mots_cles`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Contenu de la table `Stage`
--

INSERT INTO `Stage` (`id`, `titre`, `annee`, `description`, `mots_cles`, `domaine`, `lien_fichier`, `lieu`, `duree`, `entreprise`, `mail_contact`, `id_entreprise`, `id_contact`) VALUES
(1, 'Stage Corporate SAP', 4, 'Pour intégrer la mutualisation budget les managers décident de solutionner les opportunités stratégie.', 'pipo conseil consulting erp', 'ERP', NULL, 'Paris', 6, 'Expersolusness', 'example@domain.org', NULL, NULL),
(2, 'Codage d''un outil de test', 3, 'Superbe stage de codage d''un outil de test pour quelque chose d''important, développement en Visual Basic.', 'code maçon ouvrier plombier', 'Outil de test', NULL, 'La Bégude de Mazenc (dans la drôme)', 3, 'Microgiciel', 'boss@microgiciel.fr', NULL, NULL),
(3, 'Recherche de valeur dans la qualité du système d''informations', 5, 'PFE trèèèèèèèèèès intéressant.', 'pipo qualité aubry', 'Qualité', NULL, 'Mbabane, Zwaziland', 6, 'MétaPipo', 'direction.generale.zwaziland@metapipo.com', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `STATUT_MARITAL`
--

CREATE TABLE IF NOT EXISTS `STATUT_MARITAL` (
  `ID_MARITAL` int(11) NOT NULL AUTO_INCREMENT,
  `LIBELLE_MARITAL` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`ID_MARITAL`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=17 ;

--
-- Dumping data for table `STATUT_MARITAL`
--

INSERT INTO `STATUT_MARITAL` (`ID_MARITAL`, `LIBELLE_MARITAL`) VALUES
(1, 'Célibataire'),
(2, 'Fiancé'),
(3, 'Marié'),
(4, 'Marié avec enfants');

-- --------------------------------------------------------

--
-- Table structure for table `Utilisateur`
--

CREATE TABLE IF NOT EXISTS `Utilisateur` (
  `id` int(11) NOT NULL,
  `nom` varchar(20) NOT NULL,
  `prenom` varchar(20) NOT NULL,
  `annee` varchar(10) NOT NULL,
  `mail` varchar(50) NOT NULL,
  UNIQUE KEY `id_utilisateur` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Utilisateur`
--

INSERT INTO `Utilisateur` (`id`, `nom`, `prenom`, `annee`, `mail`) VALUES
(1, 'TestNom', 'TestPrenom', '3IF', 'test.test@test.com');

-- --------------------------------------------------------

--
-- Table structure for table `VILLE`
--

CREATE TABLE IF NOT EXISTS `VILLE` (
  `ID_VILLE` int(11) NOT NULL AUTO_INCREMENT,
  `LIBELLE_VILLE` varchar(75) DEFAULT NULL,
  `CP_VILLE` varchar(8) DEFAULT NULL,
  `PAYS_VILLE` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`ID_VILLE`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=278 ;

--
-- Dumping data for table `VILLE`
--

INSERT INTO `VILLE` (`ID_VILLE`, `LIBELLE_VILLE`, `CP_VILLE`, `PAYS_VILLE`) VALUES
(1, 'VILLEURBANNE', '69100', 'France'),
(2, 'LYON', '69001', 'France'),
(3, 'LYON', '69002', 'France'),
(4, 'LYON', '69003', 'France'),
(5, 'LYON', '69004', 'France'),
(6, 'LYON', '69005', 'France'),
(7, 'LYON', '69006', 'France'),
(8, 'LYON', '69007', 'France'),
(9, 'LYON', '69008', 'France'),
(10, 'LYON', '69009', 'France'),
(11, 'GRENOBLE', '38000', 'France'),
(12, 'GRENOBLE', '38100', 'France'),
(13, 'SAINT-ETIENNE', '42000', 'France'),
(14, 'VALENCE', '26000', 'France'),
(15, 'MACON', '71000', 'France'),
(16, 'DIJON', '21000', 'France'),
(17, 'AUXERRE', '89000', 'France'),
(18, 'METZ', '57000', 'France'),
(19, 'STRASBOURG', '67000', 'France'),
(20, 'BOURGES', '18000', 'France'),
(21, 'ORLEANS', '45000', 'France'),
(22, 'BESANCON', '25000', 'France'),
(23, 'TROYES', '10000', 'France'),
(24, 'AVIGNON', '84000', 'France'),
(25, 'TOULON', '83000', 'France'),
(26, 'MONTPELLIER', '34000', 'France'),
(27, 'MELUN', '77000', 'France'),
(28, 'BOURG-EN-BRESSE', '01000', 'France'),
(29, 'MOULINS', '03000', 'France'),
(30, 'GAP', '05000', 'France'),
(31, 'NICE', '06000', 'France'),
(32, 'NICE', '06100', 'France'),
(33, 'MARSEILLE', '13001', 'France'),
(34, 'MARSEILLE', '13002', 'France'),
(35, 'MARSEILLE', '13003', 'France'),
(36, 'MARSEILLE', '13004', 'France'),
(37, 'MARSEILLE', '13005', 'France'),
(38, 'MARSEILLE', '13006', 'France'),
(39, 'MARSEILLE', '13007', 'France'),
(40, 'MARSEILLE', '13008', 'France'),
(41, 'MARSEILLE', '13009', 'France'),
(42, 'MARSEILLE', '13010', 'France'),
(43, 'MARSEILLE', '13011', 'France'),
(44, 'MARSEILLE', '13012', 'France'),
(45, 'MARSEILLE', '13013', 'France'),
(46, 'MARSEILLE', '13014', 'France'),
(47, 'MARSEILLE', '13015', 'France'),
(48, 'MARSEILLE', '13016', 'France'),
(49, 'AURILLAC', '15000', 'France'),
(50, 'NIMES', '30000', 'France'),
(51, 'TOULOUSE', '31000', 'France'),
(52, 'TOULOUSE', '31100', 'France'),
(53, 'TOULOUSE', '31200', 'France'),
(54, 'TOULOUSE', '31300', 'France'),
(55, 'BORDEAU', '33000', 'France'),
(56, 'REIMS', '51000', 'France'),
(57, 'NANCY', '54000', 'France'),
(58, 'LILLE', '59000', 'France'),
(59, 'CLERMONT-FERRAND', '63000', 'France'),
(60, 'CHAMBERY', '73000', 'France'),
(61, 'ANNECY', '74000', 'France'),
(62, 'PARIS', '75001', 'France'),
(63, 'PARIS', '75002', 'France'),
(64, 'PARIS', '75003', 'France'),
(65, 'PARIS', '75004', 'France'),
(66, 'PARIS', '75005', 'France'),
(67, 'PARIS', '75006', 'France'),
(68, 'PARIS', '75007', 'France'),
(69, 'PARIS', '75008', 'France'),
(70, 'PARIS', '75009', 'France'),
(71, 'PARIS', '75010', 'France'),
(72, 'PARIS', '75011', 'France'),
(73, 'PARIS', '75012', 'France'),
(74, 'PARIS', '75013', 'France'),
(75, 'PARIS', '75014', 'France'),
(76, 'PARIS', '75015', 'France'),
(77, 'PARIS', '75016', 'France'),
(78, 'PARIS', '75017', 'France'),
(79, 'PARIS', '75018', 'France'),
(80, 'PARIS', '75019', 'France'),
(81, 'PARIS', '75020', 'France'),
(82, 'VERSAILLE', '78000', 'France'),
(83, 'BELFORT', '90000', 'France'),
(84, 'SAINT-DENIS', '93000', 'France'),
(85, 'SENS', '89100', 'France'),
(86, 'CASABLANCA', '20000', 'MAROC'),
(87, 'CASABLANCA', '20200', 'MAROC'),
(88, 'CASABLANCA', '20300', 'MAROC'),
(89, 'CASABLANCA', '20400', 'MAROC'),
(90, 'CASABLANCA', '20500', 'MAROC'),
(91, 'CASABLANCA', '20600', 'MAROC'),
(92, 'RABAT', '10000', 'MAROC'),
(93, 'TUNIS', '1000', 'TUNISIE'),
(94, 'SOUSSE', '4000', 'TUNISIE'),
(95, 'ALGER', '16000', 'ALGERIE'),
(96, 'TURIN', '10100', 'ITALIE'),
(97, 'ROME', '00100', 'ITALIE'),
(98, 'ORADEA', '410 100', 'ROUMANIE'),
(99, 'ANGOULEME', '16000', 'France'),
(100, 'LINDRY', '89240', 'France'),
(101, 'SECLIN', '59113', 'France'),
(102, 'MONTBONNOT-SAINT-MARTIN', '38330', 'France'),
(103, 'TOULON', '83200', 'France'),
(104, 'Edinburgh', 'HYASZ', 'United Kingdom'),
(105, 'New York', '100004', 'Etats Unis'),
(155, 'Bidart', '64210', 'France'),
(107, 'STRASBOURG', '67200', 'France'),
(108, 'RABAT', '99', 'MAROC'),
(109, 'GRIEC', '56630', 'France'),
(110, 'SALINS-LES-THERMES', '73600', 'France'),
(125, 'SEOUL', '11111', 'CORÃ©E'),
(128, 'PARIS', '75000', 'France'),
(154, 'Kathmandu', '', 'NÃ©pal'),
(115, 'LE VAL SAINT GERMAIN', '91530', 'France'),
(153, 'Pessac', '33607', 'France'),
(118, 'Dourdan', '91410', 'France'),
(119, 'Chaumont', '52000', 'France'),
(120, 'ECULLY', '69130', 'France'),
(121, 'ANDORRA LA VELLA', '000', 'ANDORRE'),
(122, 'ROUEN', '76100', 'France'),
(123, 'Aix-en-Provence', '13100', 'France'),
(124, 'Tokyo', '', 'Japon'),
(152, 'Anglet', '64600', 'France'),
(151, 'Albertville', '73200', 'France'),
(131, 'LE PLESSIS ROBINSON', '92350', 'France'),
(132, 'POITIERS', '86000', 'France'),
(133, 'Torino', 'TO', 'Italia'),
(136, 'Ho Chi Minh', '08', 'Vietnam'),
(137, 'Milano', '20100', 'Italie'),
(150, 'ST JEAN DE LUZ', '64500', 'France'),
(149, 'VILLEURBANNE', '69621', 'France'),
(148, 'MOÃ»TIERS', '73600', 'France'),
(141, 'Valbonne', '06560', 'France'),
(142, 'MURES', '547185', 'ROUMANIE'),
(143, 'Mures', '540304', 'Roumanie'),
(144, 'HYÃ¨RES', '83400', 'France'),
(145, 'MontÃ©limar', '26200', 'France'),
(146, 'Saint Fons', '69190', 'France'),
(147, 'Aberdeen', 'AB-10', 'Ã‰cosse'),
(156, 'LYON', '69000', 'France'),
(157, 'Orange', '84100', 'France'),
(158, 'Molsheim', '67120', 'France'),
(159, 'CORK', '000', 'IRLANDE'),
(160, 'SAINT-MAURICE-DE-GOURDANS', '01800', 'France'),
(161, 'DÃ©CINES-CHARPIEU', '69150', 'France'),
(162, 'La Boisse', '01120', 'France'),
(163, 'Beynost', '01700', 'France'),
(164, 'Montalieu-Vercieu', '38390', 'France'),
(165, 'PETITEFONTAINE', '90360', 'France'),
(166, 'FONTAINE', '90047', 'France'),
(167, 'LANNION', '22300', 'France'),
(168, 'GRENOBLE Cedex 9', '38041', 'France'),
(173, '', '3800', 'France'),
(172, '', '', 'France'),
(171, 'GRENOBLE', '38000 et', 'France'),
(174, 'ROMANÃ¨CHE THORINS', '71570', 'France'),
(175, 'ChÃ¢nes', '71570', 'France'),
(176, 'SAINT SÃ©BASTIEN SUR LOIRE', '44230', 'France'),
(177, 'HELFAUT', '62570', 'France'),
(178, 'FLERS', '61000', 'France'),
(179, 'Calais', '62100', 'France'),
(180, 'Longuenesse', '62219', 'France'),
(181, 'Nantes', '44300', 'France'),
(182, 'Nantes', '44041', 'France'),
(183, 'Middlesbrough', '----', 'Angleterre'),
(184, 'Middlesbrough', 'TS3', 'Royaume-Uni'),
(185, 'Nantes', '44000', 'France'),
(186, 'Tile Hill', '00000', 'Angleterre'),
(187, 'ARLES', '13200', 'France'),
(188, '', '', ''),
(189, 'CLAMART', '92140', 'France'),
(190, 'Sceaux', '92330', 'France'),
(191, 'VAREILLES', '23300', 'France'),
(192, 'GUÃ©RET', '23300', 'France'),
(193, 'La Souterraine', '23300', 'France'),
(194, 'GuÃ©ret', '23000', 'France'),
(195, 'Noth', '23300', 'France'),
(196, 'HO CHI MINH VILLE', '70000', 'Vietnam'),
(197, 'Tile Hill', 'CV4', 'Angleterre'),
(198, 'Sens', '89 100', 'France'),
(199, 'Orange', '691010', 'France'),
(200, 'LURE', '70200', 'France'),
(201, 'ANNEMASSE', '74100', 'France'),
(202, 'Saint Julien en Genevois', '74160', 'France'),
(203, 'Geneve', '1200', 'Suisse'),
(204, 'Frangy', '74270', 'France'),
(205, 'cran-gevrier', '74960', 'France'),
(206, 'CLUJ NAPOCA', '300348', 'ROUMANIE'),
(207, 'Cluj Napoca', '3600', 'Roumanie'),
(208, 'Cluj Napoca', '', 'Roumanie'),
(209, 'INSA de Lyon', '', 'France'),
(210, 'INSA de Lyon', '', ''),
(211, 'Toulon', '8300', 'France'),
(212, 'Toulon - Edimbourg', '', 'France - United Kingdom'),
(213, 'THIONVILLE', '57100', 'France'),
(214, 'Stockholm', '10', 'SuÃ¨de'),
(215, 'Stockholm', '.....', 'SuÃ¨de'),
(216, 'Stockholm', '22222', 'SuÃ¨de'),
(217, 'Stockholm', '12345', 'SuÃ¨de'),
(218, 'Stockholm', '88888', 'SuÃ¨de'),
(219, 'ROMANECHE THORINS', '71570', 'France'),
(220, 'Joinville', '52300', 'France'),
(221, 'SAINT GENIS LES OLLIÃ¨RES', '69290', 'France'),
(222, 'Tokyo', '0000', 'Japon'),
(223, 'Francheville', '69340', 'France'),
(224, 'Villeurbanne', '69622', 'France'),
(225, 'Saint Mandrier', '80130', 'France'),
(226, 'Arques', '62510', 'France'),
(227, 'New York', '10004', 'Etats Unis'),
(228, 'SAINT-DENIS', '97400', 'France'),
(229, 'Etang-SalÃ©', '97427', 'France'),
(230, 'SAINT-CHAMOND', '42400', 'France'),
(231, 'CHERBOURG', '50100', 'France'),
(232, 'CAEN', '14000', 'France'),
(233, 'LISIEUX', '14100', 'France'),
(234, 'PONTORSON', '50170', 'France'),
(235, 'VIRE', '14500', 'France'),
(236, 'London', 'SW1P 4QP', 'United Kingdom'),
(237, 'Massy', '91300', 'France'),
(238, 'ROUEN', '76000', 'France'),
(239, 'Aix-en-Provence', '13000', 'France'),
(240, 'SAINT CLAIR DU RHÃ´NE', '38370', 'France'),
(241, 'VIENNE', '38200', 'France'),
(242, 'Cournon d\\''Auvergne', '63800', 'France'),
(244, 'Cournon d Auvergne', '63800', 'France'),
(245, 'Bruxelles', '1050', 'Belgique'),
(246, 'Bruxelles', '1005', 'Belgique'),
(247, 'PUYLOUBIER', '13114', 'France'),
(248, 'Dardilly', '69570', 'France'),
(249, 'Rousset', '13790', 'France'),
(250, 'Cordoba', '.....', 'Argentine'),
(251, 'SAINT ROMAIN EN GAL', '69560', 'France'),
(252, 'Saint Mandrier', '83130', 'France'),
(253, 'Lyon', '69100', 'France'),
(254, 'Villeurbanne', '69100', ''),
(255, 'Torino', '', 'Italie'),
(256, 'Suresnes', '92150', 'France'),
(257, 'Ingrandes', '86100', 'France'),
(277, 'AIX', '', '');
