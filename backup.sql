-- MySQL Administrator dump 1.4
--
-- ------------------------------------------------------
-- Server version	5.5.59


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


--
-- Create schema gestionfaena
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ gestionfaena;
USE gestionfaena;

--
-- Table structure for table `gestionfaena`.`sp_art_proc_fa`
--

DROP TABLE IF EXISTS `sp_art_proc_fa`;
CREATE TABLE `sp_art_proc_fa` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_art_abs` int(11) DEFAULT NULL,
  `id_proc_fan` int(11) DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `IDX_78009BC60344CDA` (`id_art_abs`),
  KEY `IDX_78009BCCCB66DA3` (`id_proc_fan`),
  CONSTRAINT `FK_78009BCCCB66DA3` FOREIGN KEY (`id_proc_fan`) REFERENCES `sp_proc_fan` (`id`),
  CONSTRAINT `FK_78009BC60344CDA` FOREIGN KEY (`id_art_abs`) REFERENCES `sp_gst_art` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `gestionfaena`.`sp_art_proc_fa`
--

/*!40000 ALTER TABLE `sp_art_proc_fa` DISABLE KEYS */;
/*!40000 ALTER TABLE `sp_art_proc_fa` ENABLE KEYS */;


--
-- Table structure for table `gestionfaena`.`sp_atr_for_art`
--

DROP TABLE IF EXISTS `sp_atr_for_art`;
CREATE TABLE `sp_atr_for_art` (
  `id_art_proc_fan` int(11) NOT NULL,
  `id_atr_art` int(11) NOT NULL,
  PRIMARY KEY (`id_art_proc_fan`,`id_atr_art`),
  UNIQUE KEY `UNIQ_8DC957CEED8BDD95` (`id_atr_art`),
  KEY `IDX_8DC957CEE6AB8D52` (`id_art_proc_fan`),
  CONSTRAINT `FK_8DC957CEED8BDD95` FOREIGN KEY (`id_atr_art`) REFERENCES `sp_atr_proc` (`id`),
  CONSTRAINT `FK_8DC957CEE6AB8D52` FOREIGN KEY (`id_art_proc_fan`) REFERENCES `sp_art_proc_fa` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `gestionfaena`.`sp_atr_for_art`
--

/*!40000 ALTER TABLE `sp_atr_for_art` DISABLE KEYS */;
/*!40000 ALTER TABLE `sp_atr_for_art` ENABLE KEYS */;


--
-- Table structure for table `gestionfaena`.`sp_atr_proc`
--

DROP TABLE IF EXISTS `sp_atr_proc`;
CREATE TABLE `sp_atr_proc` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_atr` int(11) DEFAULT NULL,
  `orden` int(11) DEFAULT '0',
  `decimales` int(11) NOT NULL DEFAULT '-1',
  `mostrar` tinyint(1) NOT NULL,
  `acumula` tinyint(1) NOT NULL,
  `promedia` tinyint(1) NOT NULL,
  `controlaStock` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_A5BE75C4DDA83CBD` (`id_atr`),
  CONSTRAINT `FK_A5BE75C4DDA83CBD` FOREIGN KEY (`id_atr`) REFERENCES `sp_gst_atr_abs` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `gestionfaena`.`sp_atr_proc`
--

/*!40000 ALTER TABLE `sp_atr_proc` DISABLE KEYS */;
/*!40000 ALTER TABLE `sp_atr_proc` ENABLE KEYS */;


--
-- Table structure for table `gestionfaena`.`sp_fan_day`
--

DROP TABLE IF EXISTS `sp_fan_day`;
CREATE TABLE `sp_fan_day` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user_open` int(11) DEFAULT NULL,
  `id_user_close` int(11) DEFAULT NULL,
  `fechaFaena` date NOT NULL,
  `isActive` tinyint(1) NOT NULL,
  `fechaAlta` datetime NOT NULL,
  `fechaCierre` datetime DEFAULT NULL,
  `finalizada` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_612F7285EC4734A` (`id_user_open`),
  KEY `IDX_612F7285449FA4B` (`id_user_close`),
  CONSTRAINT `FK_612F7285449FA4B` FOREIGN KEY (`id_user_close`) REFERENCES `usuarios` (`id`),
  CONSTRAINT `FK_612F7285EC4734A` FOREIGN KEY (`id_user_open`) REFERENCES `usuarios` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `gestionfaena`.`sp_fan_day`
--

/*!40000 ALTER TABLE `sp_fan_day` DISABLE KEYS */;
INSERT INTO `sp_fan_day` (`id`,`id_user_open`,`id_user_close`,`fechaFaena`,`isActive`,`fechaAlta`,`fechaCierre`,`finalizada`) VALUES 
 (2,1,NULL,'2020-03-24',1,'2020-03-25 16:45:32',NULL,0),
 (3,1,NULL,'2020-03-25',1,'2020-03-25 16:50:27',NULL,0),
 (4,1,NULL,'2020-03-28',1,'2020-03-28 22:11:25',NULL,0);
/*!40000 ALTER TABLE `sp_fan_day` ENABLE KEYS */;


--
-- Table structure for table `gestionfaena`.`sp_gst_art`
--

DROP TABLE IF EXISTS `sp_gst_art`;
CREATE TABLE `sp_gst_art` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `gestionfaena`.`sp_gst_art`
--

/*!40000 ALTER TABLE `sp_gst_art` DISABLE KEYS */;
INSERT INTO `sp_gst_art` (`id`,`nombre`) VALUES 
 (1,'Ave');
/*!40000 ALTER TABLE `sp_gst_art` ENABLE KEYS */;


--
-- Table structure for table `gestionfaena`.`sp_gst_atr_abs`
--

DROP TABLE IF EXISTS `sp_gst_atr_abs`;
CREATE TABLE `sp_gst_atr_abs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_artatrcon` int(11) DEFAULT NULL,
  `id_atr_abs` int(11) DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `nombre` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `asignado` tinyint(1) NOT NULL DEFAULT '0',
  `posicion` int(11) NOT NULL,
  `mostrar` tinyint(1) NOT NULL DEFAULT '0',
  `eliminado` tinyint(1) DEFAULT '0',
  `type` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_8CAAB45AD744E312` (`id_artatrcon`),
  KEY `IDX_8CAAB45A392D5A67` (`id_atr_abs`),
  CONSTRAINT `FK_8CAAB45A392D5A67` FOREIGN KEY (`id_atr_abs`) REFERENCES `sp_gst_atr_abst` (`id`),
  CONSTRAINT `FK_8CAAB45AD744E312` FOREIGN KEY (`id_artatrcon`) REFERENCES `sp_st_art_atr_con` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `gestionfaena`.`sp_gst_atr_abs`
--

/*!40000 ALTER TABLE `sp_gst_atr_abs` DISABLE KEYS */;
INSERT INTO `sp_gst_atr_abs` (`id`,`id_artatrcon`,`id_atr_abs`,`activo`,`nombre`,`asignado`,`posicion`,`mostrar`,`eliminado`,`type`) VALUES 
 (1,1,5,1,'Granja',1,0,1,NULL,6),
 (2,1,8,1,'Galpon',1,0,1,NULL,7),
 (3,1,6,1,'O/C',1,0,1,NULL,7),
 (4,1,7,1,'Transportista',1,0,1,NULL,6),
 (5,1,9,1,'Aves',1,0,1,NULL,3),
 (6,1,10,1,'DT-E',1,0,1,NULL,7),
 (7,1,1,1,'Bruto',1,0,1,NULL,3),
 (8,1,3,1,'Tara',1,0,1,NULL,3),
 (9,1,2,1,'Neto',1,0,1,NULL,4),
 (10,1,4,1,'Promedio',1,0,1,NULL,4),
 (11,1,11,1,'Aves Dec. A.M.',1,0,1,NULL,3),
 (12,1,12,1,'Aves Dec. A.M. (Peso)',1,0,1,NULL,4),
 (13,2,2,1,'Peso',1,0,1,NULL,3);
/*!40000 ALTER TABLE `sp_gst_atr_abs` ENABLE KEYS */;


--
-- Table structure for table `gestionfaena`.`sp_gst_atr_abst`
--

DROP TABLE IF EXISTS `sp_gst_atr_abst`;
CREATE TABLE `sp_gst_atr_abst` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `atributo` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_E5848F6E6C7678A` (`atributo`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `gestionfaena`.`sp_gst_atr_abst`
--

/*!40000 ALTER TABLE `sp_gst_atr_abst` DISABLE KEYS */;
INSERT INTO `sp_gst_atr_abst` (`id`,`atributo`) VALUES 
 (9,'Cantidad'),
 (11,'Dec. A.M. (Aves)'),
 (12,'Dec. A.M. (Peso)'),
 (10,'DT-e'),
 (8,'Galpon'),
 (5,'Granja'),
 (6,'O/C'),
 (1,'P. Bruto'),
 (2,'P. Neto'),
 (4,'P. Promedio'),
 (3,'P. Tara'),
 (7,'Transportista');
/*!40000 ALTER TABLE `sp_gst_atr_abst` ENABLE KEYS */;


--
-- Table structure for table `gestionfaena`.`sp_gst_atr_inf`
--

DROP TABLE IF EXISTS `sp_gst_atr_inf`;
CREATE TABLE `sp_gst_atr_inf` (
  `id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `FK_43D14E05BF396750` FOREIGN KEY (`id`) REFERENCES `sp_gst_atr_abs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `gestionfaena`.`sp_gst_atr_inf`
--

/*!40000 ALTER TABLE `sp_gst_atr_inf` DISABLE KEYS */;
INSERT INTO `sp_gst_atr_inf` (`id`) VALUES 
 (1),
 (2),
 (3),
 (4),
 (6);
/*!40000 ALTER TABLE `sp_gst_atr_inf` ENABLE KEYS */;


--
-- Table structure for table `gestionfaena`.`sp_gst_atr_inf_arb`
--

DROP TABLE IF EXISTS `sp_gst_atr_inf_arb`;
CREATE TABLE `sp_gst_atr_inf_arb` (
  `id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `FK_7FA6CE73BF396750` FOREIGN KEY (`id`) REFERENCES `sp_gst_atr_abs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `gestionfaena`.`sp_gst_atr_inf_arb`
--

/*!40000 ALTER TABLE `sp_gst_atr_inf_arb` DISABLE KEYS */;
INSERT INTO `sp_gst_atr_inf_arb` (`id`) VALUES 
 (2),
 (3),
 (6);
/*!40000 ALTER TABLE `sp_gst_atr_inf_arb` ENABLE KEYS */;


--
-- Table structure for table `gestionfaena`.`sp_gst_atr_inf_ext`
--

DROP TABLE IF EXISTS `sp_gst_atr_inf_ext`;
CREATE TABLE `sp_gst_atr_inf_ext` (
  `id` int(11) NOT NULL,
  `claseExterna` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `FK_76943B74BF396750` FOREIGN KEY (`id`) REFERENCES `sp_gst_atr_abs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `gestionfaena`.`sp_gst_atr_inf_ext`
--

/*!40000 ALTER TABLE `sp_gst_atr_inf_ext` DISABLE KEYS */;
INSERT INTO `sp_gst_atr_inf_ext` (`id`,`claseExterna`) VALUES 
 (1,'GestionFaenaBundle\\Entity\\gestionBD\\Granja'),
 (4,'GestionFaenaBundle\\Entity\\gestionBD\\Transportista');
/*!40000 ALTER TABLE `sp_gst_atr_inf_ext` ENABLE KEYS */;


--
-- Table structure for table `gestionfaena`.`sp_gst_atr_med`
--

DROP TABLE IF EXISTS `sp_gst_atr_med`;
CREATE TABLE `sp_gst_atr_med` (
  `id` int(11) NOT NULL,
  `id_unt_med` int(11) DEFAULT NULL,
  `decimales` int(11) NOT NULL DEFAULT '0',
  `acumula` tinyint(1) NOT NULL DEFAULT '0',
  `promedia` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `IDX_49225E3E4239DC83` (`id_unt_med`),
  CONSTRAINT `FK_49225E3EBF396750` FOREIGN KEY (`id`) REFERENCES `sp_gst_atr_abs` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_49225E3E4239DC83` FOREIGN KEY (`id_unt_med`) REFERENCES `sp_gst_unt_med` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `gestionfaena`.`sp_gst_atr_med`
--

/*!40000 ALTER TABLE `sp_gst_atr_med` DISABLE KEYS */;
INSERT INTO `sp_gst_atr_med` (`id`,`id_unt_med`,`decimales`,`acumula`,`promedia`) VALUES 
 (5,2,0,1,0),
 (7,1,2,1,0),
 (8,1,2,1,0),
 (9,1,0,1,0),
 (10,1,2,1,1),
 (11,2,0,1,0),
 (12,1,2,1,0),
 (13,1,2,0,0);
/*!40000 ALTER TABLE `sp_gst_atr_med` ENABLE KEYS */;


--
-- Table structure for table `gestionfaena`.`sp_gst_atr_med_auto`
--

DROP TABLE IF EXISTS `sp_gst_atr_med_auto`;
CREATE TABLE `sp_gst_atr_med_auto` (
  `id` int(11) NOT NULL,
  `id_factor_calculo_1` int(11) DEFAULT NULL,
  `id_factor_calculo_2` int(11) DEFAULT NULL,
  `operacion` enum('+','-','*','/','R') COLLATE utf8_unicode_ci DEFAULT NULL,
  `factorAjuste` double NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_142E4D55742FB11B` (`id_factor_calculo_1`),
  UNIQUE KEY `UNIQ_142E4D55ED26E0A1` (`id_factor_calculo_2`),
  CONSTRAINT `FK_142E4D55BF396750` FOREIGN KEY (`id`) REFERENCES `sp_gst_atr_abs` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_142E4D55742FB11B` FOREIGN KEY (`id_factor_calculo_1`) REFERENCES `sp_gst_fc_clc` (`id`),
  CONSTRAINT `FK_142E4D55ED26E0A1` FOREIGN KEY (`id_factor_calculo_2`) REFERENCES `sp_gst_fc_clc` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `gestionfaena`.`sp_gst_atr_med_auto`
--

/*!40000 ALTER TABLE `sp_gst_atr_med_auto` DISABLE KEYS */;
INSERT INTO `sp_gst_atr_med_auto` (`id`,`id_factor_calculo_1`,`id_factor_calculo_2`,`operacion`,`factorAjuste`) VALUES 
 (9,1,2,'-',0),
 (10,3,4,'/',0),
 (12,5,6,'*',0);
/*!40000 ALTER TABLE `sp_gst_atr_med_auto` ENABLE KEYS */;


--
-- Table structure for table `gestionfaena`.`sp_gst_atr_med_man`
--

DROP TABLE IF EXISTS `sp_gst_atr_med_man`;
CREATE TABLE `sp_gst_atr_med_man` (
  `id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `FK_FAADBD1EBF396750` FOREIGN KEY (`id`) REFERENCES `sp_gst_atr_abs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `gestionfaena`.`sp_gst_atr_med_man`
--

/*!40000 ALTER TABLE `sp_gst_atr_med_man` DISABLE KEYS */;
INSERT INTO `sp_gst_atr_med_man` (`id`) VALUES 
 (5),
 (7),
 (8),
 (11),
 (13);
/*!40000 ALTER TABLE `sp_gst_atr_med_man` ENABLE KEYS */;


--
-- Table structure for table `gestionfaena`.`sp_gst_atr_proc_by_conc`
--

DROP TABLE IF EXISTS `sp_gst_atr_proc_by_conc`;
CREATE TABLE `sp_gst_atr_proc_by_conc` (
  `id_atr_conc` int(11) NOT NULL,
  `id_atr_proc` int(11) NOT NULL,
  PRIMARY KEY (`id_atr_conc`,`id_atr_proc`),
  KEY `IDX_FBCBFC4988EB310A` (`id_atr_conc`),
  KEY `IDX_FBCBFC49C7A2C819` (`id_atr_proc`),
  CONSTRAINT `FK_FBCBFC49C7A2C819` FOREIGN KEY (`id_atr_proc`) REFERENCES `sp_atr_proc` (`id`),
  CONSTRAINT `FK_FBCBFC4988EB310A` FOREIGN KEY (`id_atr_conc`) REFERENCES `sp_gst_atr_prod_proc_con` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `gestionfaena`.`sp_gst_atr_proc_by_conc`
--

/*!40000 ALTER TABLE `sp_gst_atr_proc_by_conc` DISABLE KEYS */;
/*!40000 ALTER TABLE `sp_gst_atr_proc_by_conc` ENABLE KEYS */;


--
-- Table structure for table `gestionfaena`.`sp_gst_atr_prod_proc_con`
--

DROP TABLE IF EXISTS `sp_gst_atr_prod_proc_con`;
CREATE TABLE `sp_gst_atr_prod_proc_con` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_concepto` int(11) DEFAULT NULL,
  `id_art_proc_fan` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_F98ACB9C34586445` (`id_concepto`),
  KEY `IDX_F98ACB9CE6AB8D52` (`id_art_proc_fan`),
  CONSTRAINT `FK_F98ACB9CE6AB8D52` FOREIGN KEY (`id_art_proc_fan`) REFERENCES `sp_art_proc_fa` (`id`),
  CONSTRAINT `FK_F98ACB9C34586445` FOREIGN KEY (`id_concepto`) REFERENCES `sp_st_con_mov` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `gestionfaena`.`sp_gst_atr_prod_proc_con`
--

/*!40000 ALTER TABLE `sp_gst_atr_prod_proc_con` DISABLE KEYS */;
/*!40000 ALTER TABLE `sp_gst_atr_prod_proc_con` ENABLE KEYS */;


--
-- Table structure for table `gestionfaena`.`sp_gst_ent_ext`
--

DROP TABLE IF EXISTS `sp_gst_ent_ext`;
CREATE TABLE `sp_gst_ent_ext` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `valor` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `gestionfaena`.`sp_gst_ent_ext`
--

/*!40000 ALTER TABLE `sp_gst_ent_ext` DISABLE KEYS */;
INSERT INTO `sp_gst_ent_ext` (`id`,`valor`,`type`) VALUES 
 (1,'3001',3),
 (2,'3004',3),
 (3,'3007',3),
 (4,'3009',3),
 (5,'3010',3),
 (6,'3021',3),
 (7,'3023',3),
 (8,'3024',3),
 (9,'3025',3),
 (10,'3026',3),
 (11,'3027',3),
 (12,'3028',3),
 (13,'3029',3),
 (14,'3030',3),
 (15,'3032',3),
 (16,'3033',3),
 (17,'3035',3),
 (18,'Don Edgardo',2),
 (21,'YUSPE I',2);
/*!40000 ALTER TABLE `sp_gst_ent_ext` ENABLE KEYS */;


--
-- Table structure for table `gestionfaena`.`sp_gst_ent_ext_city`
--

DROP TABLE IF EXISTS `sp_gst_ent_ext_city`;
CREATE TABLE `sp_gst_ent_ext_city` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ciudad` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_CBB459CC8E86059E` (`ciudad`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `gestionfaena`.`sp_gst_ent_ext_city`
--

/*!40000 ALTER TABLE `sp_gst_ent_ext_city` DISABLE KEYS */;
INSERT INTO `sp_gst_ent_ext_city` (`id`,`ciudad`) VALUES 
 (1,'Abasto'),
 (2,'Abbott'),
 (3,'Alvarez Jonte'),
 (4,'Brandsen'),
 (5,'Cañuelas'),
 (6,'Domselaar'),
 (7,'Etcheverry'),
 (8,'Florencio Varela'),
 (9,'General Belgrano'),
 (10,'Gomez'),
 (22,'La Plata'),
 (11,'Martin Berraondo'),
 (12,'Norberto de la Riestra'),
 (13,'Polvaredas'),
 (14,'Ranchos'),
 (15,'Rauch'),
 (16,'Roque Perez'),
 (17,'Saladillo'),
 (18,'San Miguel del Monte'),
 (19,'San Vicente'),
 (20,'Varela'),
 (21,'Virrey del Pino');
/*!40000 ALTER TABLE `sp_gst_ent_ext_city` ENABLE KEYS */;


--
-- Table structure for table `gestionfaena`.`sp_gst_ent_ext_gja`
--

DROP TABLE IF EXISTS `sp_gst_ent_ext_gja`;
CREATE TABLE `sp_gst_ent_ext_gja` (
  `id` int(11) NOT NULL,
  `propia` tinyint(1) NOT NULL,
  `renspa` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `id_city` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_9B792F1BA67B1E36` (`id_city`),
  CONSTRAINT `FK_9B792F1BA67B1E36` FOREIGN KEY (`id_city`) REFERENCES `sp_gst_ent_ext_city` (`id`),
  CONSTRAINT `FK_9B792F1BBF396750` FOREIGN KEY (`id`) REFERENCES `sp_gst_ent_ext` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `gestionfaena`.`sp_gst_ent_ext_gja`
--

/*!40000 ALTER TABLE `sp_gst_ent_ext_gja` DISABLE KEYS */;
INSERT INTO `sp_gst_ent_ext_gja` (`id`,`propia`,`renspa`,`id_city`) VALUES 
 (18,0,'01-060-0-00382/02',1),
 (21,0,'01-118-0-00258/02',12);
/*!40000 ALTER TABLE `sp_gst_ent_ext_gja` ENABLE KEYS */;


--
-- Table structure for table `gestionfaena`.`sp_gst_ent_ext_trs`
--

DROP TABLE IF EXISTS `sp_gst_ent_ext_trs`;
CREATE TABLE `sp_gst_ent_ext_trs` (
  `id` int(11) NOT NULL,
  `cuit` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `chofer` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `titular` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `chasis` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `acoplado` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `FK_F4BBDB23BF396750` FOREIGN KEY (`id`) REFERENCES `sp_gst_ent_ext` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `gestionfaena`.`sp_gst_ent_ext_trs`
--

/*!40000 ALTER TABLE `sp_gst_ent_ext_trs` DISABLE KEYS */;
INSERT INTO `sp_gst_ent_ext_trs` (`id`,`cuit`,`chofer`,`titular`,`chasis`,`acoplado`) VALUES 
 (1,'20-24934719-2','Tejedor Jacinto','Transportes El Gringo','ISR091','AB254ML'),
 (2,'20-08464288-7','Cappararo Carlos','Cosmedi Miguel Angel','SKI 024','CIY 861'),
 (3,'30-70914155-0','Pahud Luis','Palevi S.R.L.','AIQ627','AB255VC'),
 (4,'20-24935045-2','Dorado Daniel','Transporte Chiqui','EBK896','SOQ 035'),
 (5,'27-23308169-3','Robledo Luis','Transporte Alfredo','CEG 651','XAF 733'),
 (6,'20-12602454-2','Gauchez Cristian','Transporte Reynaldo','WMN 301','DNY 063'),
 (7,'20-20616010-2','Rodriguez Sebastián','La Emilia','AD033PR','AB561FB'),
 (8,'30-70475612-3','Benegas Gerardo','Albanesi y Olgiati S.A.','MVH 188','SKI 079'),
 (9,'20-13237620-5','García Antonio','García Antonio','MZL906','NSH600'),
 (10,'20-13770352-2','Banegas Martin','El Gauchito','RTL922','SZM124'),
 (11,'20-32128235-1','Artiaga Fabián','Don Artiaga','LUX 302','WBF773'),
 (12,'20-20042989-4','Espinosa Diego','Transporte AV','AD148UM','AB788AS'),
 (13,'20-16427024-7','De Souza jorge','Transporte  Do Vale','CIT 985','RVF 251');
INSERT INTO `sp_gst_ent_ext_trs` (`id`,`cuit`,`chofer`,`titular`,`chasis`,`acoplado`) VALUES 
 (14,'20-24935045-2','Victor Martin','Transporte Chiqui','JDQ 484','CHV450'),
 (15,'20-05224019-1','Saccón Hector','Transporte Saccón','RAC 301','RMN 578'),
 (16,'20-17086560-0','Pasquali Marcelo Javier','Pasquali Marcelo','CLK064','VBW241'),
 (17,'20-23856923-1','Transporte El Vasco','Transporte El Vasco','RMK581','KFO 151');
/*!40000 ALTER TABLE `sp_gst_ent_ext_trs` ENABLE KEYS */;


--
-- Table structure for table `gestionfaena`.`sp_gst_fc_clc`
--

DROP TABLE IF EXISTS `sp_gst_fc_clc`;
CREATE TABLE `sp_gst_fc_clc` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_art` int(11) DEFAULT NULL,
  `id_atr_abst` int(11) DEFAULT NULL,
  `tipo_calculo` enum('S','P','U') COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_4E787ED62913E0E` (`id_art`),
  KEY `IDX_4E787ED56858309` (`id_atr_abst`),
  CONSTRAINT `FK_4E787ED56858309` FOREIGN KEY (`id_atr_abst`) REFERENCES `sp_gst_atr_abst` (`id`),
  CONSTRAINT `FK_4E787ED62913E0E` FOREIGN KEY (`id_art`) REFERENCES `sp_gst_art` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `gestionfaena`.`sp_gst_fc_clc`
--

/*!40000 ALTER TABLE `sp_gst_fc_clc` DISABLE KEYS */;
INSERT INTO `sp_gst_fc_clc` (`id`,`id_art`,`id_atr_abst`,`tipo_calculo`) VALUES 
 (1,1,1,'U'),
 (2,1,3,'U'),
 (3,1,2,'U'),
 (4,1,9,'U'),
 (5,1,11,'U'),
 (6,1,4,'U'),
 (7,1,2,NULL),
 (8,1,2,NULL),
 (9,1,2,NULL);
/*!40000 ALTER TABLE `sp_gst_fc_clc` ENABLE KEYS */;


--
-- Table structure for table `gestionfaena`.`sp_gst_tpo_mov_con`
--

DROP TABLE IF EXISTS `sp_gst_tpo_mov_con`;
CREATE TABLE `sp_gst_tpo_mov_con` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `instancia` int(11) NOT NULL,
  `transforma` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `gestionfaena`.`sp_gst_tpo_mov_con`
--

/*!40000 ALTER TABLE `sp_gst_tpo_mov_con` DISABLE KEYS */;
INSERT INTO `sp_gst_tpo_mov_con` (`id`,`nombre`,`instancia`,`transforma`) VALUES 
 (1,'Entrada Stock',2,0),
 (2,'Salida Stock',3,0),
 (3,'Transferir Stock',5,0);
/*!40000 ALTER TABLE `sp_gst_tpo_mov_con` ENABLE KEYS */;


--
-- Table structure for table `gestionfaena`.`sp_gst_unt_med`
--

DROP TABLE IF EXISTS `sp_gst_unt_med`;
CREATE TABLE `sp_gst_unt_med` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `unidad` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_F7BE32BEF3E6D02F` (`unidad`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `gestionfaena`.`sp_gst_unt_med`
--

/*!40000 ALTER TABLE `sp_gst_unt_med` DISABLE KEYS */;
INSERT INTO `sp_gst_unt_med` (`id`,`unidad`) VALUES 
 (1,'KG'),
 (2,'Unidad');
/*!40000 ALTER TABLE `sp_gst_unt_med` ENABLE KEYS */;


--
-- Table structure for table `gestionfaena`.`sp_man_stock_proc_fan`
--

DROP TABLE IF EXISTS `sp_man_stock_proc_fan`;
CREATE TABLE `sp_man_stock_proc_fan` (
  `id_proc_fan` int(11) NOT NULL,
  `id_fact_op` int(11) NOT NULL,
  PRIMARY KEY (`id_proc_fan`,`id_fact_op`),
  KEY `IDX_E1214CBFCCB66DA3` (`id_proc_fan`),
  KEY `IDX_E1214CBF877086A1` (`id_fact_op`),
  CONSTRAINT `FK_E1214CBF877086A1` FOREIGN KEY (`id_fact_op`) REFERENCES `sp_gst_fc_clc` (`id`),
  CONSTRAINT `FK_E1214CBFCCB66DA3` FOREIGN KEY (`id_proc_fan`) REFERENCES `sp_proc_fan` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `gestionfaena`.`sp_man_stock_proc_fan`
--

/*!40000 ALTER TABLE `sp_man_stock_proc_fan` DISABLE KEYS */;
INSERT INTO `sp_man_stock_proc_fan` (`id_proc_fan`,`id_fact_op`) VALUES 
 (1,8),
 (2,7),
 (3,9);
/*!40000 ALTER TABLE `sp_man_stock_proc_fan` ENABLE KEYS */;


--
-- Table structure for table `gestionfaena`.`sp_proc_end`
--

DROP TABLE IF EXISTS `sp_proc_end`;
CREATE TABLE `sp_proc_end` (
  `id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `FK_CBA19BF6BF396750` FOREIGN KEY (`id`) REFERENCES `sp_proc_fan` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `gestionfaena`.`sp_proc_end`
--

/*!40000 ALTER TABLE `sp_proc_end` DISABLE KEYS */;
INSERT INTO `sp_proc_end` (`id`) VALUES 
 (2);
/*!40000 ALTER TABLE `sp_proc_end` ENABLE KEYS */;


--
-- Table structure for table `gestionfaena`.`sp_proc_fan`
--

DROP TABLE IF EXISTS `sp_proc_fan`;
CREATE TABLE `sp_proc_fan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `type` int(11) NOT NULL,
  `permanente` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_AEAAD07E3A909126` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `gestionfaena`.`sp_proc_fan`
--

/*!40000 ALTER TABLE `sp_proc_fan` DISABLE KEYS */;
INSERT INTO `sp_proc_fan` (`id`,`nombre`,`activo`,`type`,`permanente`) VALUES 
 (1,'Recepcion en Playa',1,2,0),
 (2,'Administrar Camaras',1,4,1),
 (3,'Desplume y Eviscerado',1,3,0);
/*!40000 ALTER TABLE `sp_proc_fan` ENABLE KEYS */;


--
-- Table structure for table `gestionfaena`.`sp_proc_fan_day`
--

DROP TABLE IF EXISTS `sp_proc_fan_day`;
CREATE TABLE `sp_proc_fan_day` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_proc_fan` int(11) DEFAULT NULL,
  `ultimoMovimiento` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_CB240879CCB66DA3` (`id_proc_fan`),
  CONSTRAINT `FK_CB240879CCB66DA3` FOREIGN KEY (`id_proc_fan`) REFERENCES `sp_proc_fan` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `gestionfaena`.`sp_proc_fan_day`
--

/*!40000 ALTER TABLE `sp_proc_fan_day` DISABLE KEYS */;
INSERT INTO `sp_proc_fan_day` (`id`,`id_proc_fan`,`ultimoMovimiento`) VALUES 
 (1,1,'2020-03-25 16:45:32'),
 (2,2,'2020-03-25 16:45:32'),
 (3,1,'2020-03-28 20:27:47'),
 (4,1,'2020-03-28 22:11:25'),
 (5,3,'2020-03-28 22:11:25');
/*!40000 ALTER TABLE `sp_proc_fan_day` ENABLE KEYS */;


--
-- Table structure for table `gestionfaena`.`sp_proc_for_fan_day`
--

DROP TABLE IF EXISTS `sp_proc_for_fan_day`;
CREATE TABLE `sp_proc_for_fan_day` (
  `id_fan_day` int(11) NOT NULL,
  `id_proc_fan_day` int(11) NOT NULL,
  PRIMARY KEY (`id_fan_day`,`id_proc_fan_day`),
  KEY `IDX_5CD3AF37C7B2F574` (`id_fan_day`),
  KEY `IDX_5CD3AF3797575957` (`id_proc_fan_day`),
  CONSTRAINT `FK_5CD3AF3797575957` FOREIGN KEY (`id_proc_fan_day`) REFERENCES `sp_proc_fan_day` (`id`),
  CONSTRAINT `FK_5CD3AF37C7B2F574` FOREIGN KEY (`id_fan_day`) REFERENCES `sp_fan_day` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `gestionfaena`.`sp_proc_for_fan_day`
--

/*!40000 ALTER TABLE `sp_proc_for_fan_day` DISABLE KEYS */;
INSERT INTO `sp_proc_for_fan_day` (`id_fan_day`,`id_proc_fan_day`) VALUES 
 (2,1),
 (2,2),
 (3,2),
 (3,3),
 (4,2),
 (4,4),
 (4,5);
/*!40000 ALTER TABLE `sp_proc_for_fan_day` ENABLE KEYS */;


--
-- Table structure for table `gestionfaena`.`sp_proc_for_usr`
--

DROP TABLE IF EXISTS `sp_proc_for_usr`;
CREATE TABLE `sp_proc_for_usr` (
  `user_id` int(11) NOT NULL,
  `proceso_faena_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`,`proceso_faena_id`),
  KEY `IDX_A6FC7396A76ED395` (`user_id`),
  KEY `IDX_A6FC7396F1A5D562` (`proceso_faena_id`),
  CONSTRAINT `FK_A6FC7396F1A5D562` FOREIGN KEY (`proceso_faena_id`) REFERENCES `sp_proc_fan` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_A6FC7396A76ED395` FOREIGN KEY (`user_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `gestionfaena`.`sp_proc_for_usr`
--

/*!40000 ALTER TABLE `sp_proc_for_usr` DISABLE KEYS */;
INSERT INTO `sp_proc_for_usr` (`user_id`,`proceso_faena_id`) VALUES 
 (1,1),
 (1,2),
 (1,3);
/*!40000 ALTER TABLE `sp_proc_for_usr` ENABLE KEYS */;


--
-- Table structure for table `gestionfaena`.`sp_proc_ini`
--

DROP TABLE IF EXISTS `sp_proc_ini`;
CREATE TABLE `sp_proc_ini` (
  `id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `FK_BC0A1E2FBF396750` FOREIGN KEY (`id`) REFERENCES `sp_proc_fan` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `gestionfaena`.`sp_proc_ini`
--

/*!40000 ALTER TABLE `sp_proc_ini` DISABLE KEYS */;
INSERT INTO `sp_proc_ini` (`id`) VALUES 
 (1);
/*!40000 ALTER TABLE `sp_proc_ini` ENABLE KEYS */;


--
-- Table structure for table `gestionfaena`.`sp_proc_join`
--

DROP TABLE IF EXISTS `sp_proc_join`;
CREATE TABLE `sp_proc_join` (
  `proccess_sender_id` int(11) NOT NULL,
  `proccess_receiver_id` int(11) NOT NULL,
  PRIMARY KEY (`proccess_sender_id`,`proccess_receiver_id`),
  KEY `IDX_C012514C75551E29` (`proccess_sender_id`),
  KEY `IDX_C012514CE59DDA2E` (`proccess_receiver_id`),
  CONSTRAINT `FK_C012514CE59DDA2E` FOREIGN KEY (`proccess_receiver_id`) REFERENCES `sp_proc_fan` (`id`),
  CONSTRAINT `FK_C012514C75551E29` FOREIGN KEY (`proccess_sender_id`) REFERENCES `sp_proc_fan` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `gestionfaena`.`sp_proc_join`
--

/*!40000 ALTER TABLE `sp_proc_join` DISABLE KEYS */;
INSERT INTO `sp_proc_join` (`proccess_sender_id`,`proccess_receiver_id`) VALUES 
 (1,2),
 (1,3);
/*!40000 ALTER TABLE `sp_proc_join` ENABLE KEYS */;


--
-- Table structure for table `gestionfaena`.`sp_proc_mid`
--

DROP TABLE IF EXISTS `sp_proc_mid`;
CREATE TABLE `sp_proc_mid` (
  `id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `FK_8AF35C89BF396750` FOREIGN KEY (`id`) REFERENCES `sp_proc_fan` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `gestionfaena`.`sp_proc_mid`
--

/*!40000 ALTER TABLE `sp_proc_mid` DISABLE KEYS */;
INSERT INTO `sp_proc_mid` (`id`) VALUES 
 (3);
/*!40000 ALTER TABLE `sp_proc_mid` ENABLE KEYS */;


--
-- Table structure for table `gestionfaena`.`sp_st_art_atr_con`
--

DROP TABLE IF EXISTS `sp_st_art_atr_con`;
CREATE TABLE `sp_st_art_atr_con` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_articulo` int(11) DEFAULT NULL,
  `id_concepto` int(11) DEFAULT NULL,
  `asignado` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `IDX_B44530703932A204` (`id_articulo`),
  KEY `IDX_B445307034586445` (`id_concepto`),
  CONSTRAINT `FK_B445307034586445` FOREIGN KEY (`id_concepto`) REFERENCES `sp_st_con_mov_proc` (`id`),
  CONSTRAINT `FK_B44530703932A204` FOREIGN KEY (`id_articulo`) REFERENCES `sp_gst_art` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `gestionfaena`.`sp_st_art_atr_con`
--

/*!40000 ALTER TABLE `sp_st_art_atr_con` DISABLE KEYS */;
INSERT INTO `sp_st_art_atr_con` (`id`,`id_articulo`,`id_concepto`,`asignado`) VALUES 
 (1,1,1,1),
 (2,1,2,1),
 (3,1,3,1),
 (4,1,4,1);
/*!40000 ALTER TABLE `sp_st_art_atr_con` ENABLE KEYS */;


--
-- Table structure for table `gestionfaena`.`sp_st_con_mov`
--

DROP TABLE IF EXISTS `sp_st_con_mov`;
CREATE TABLE `sp_st_con_mov` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `concepto` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `gestionfaena`.`sp_st_con_mov`
--

/*!40000 ALTER TABLE `sp_st_con_mov` DISABLE KEYS */;
INSERT INTO `sp_st_con_mov` (`id`,`concepto`) VALUES 
 (1,'Ingreso a Planta'),
 (2,'Transferir');
/*!40000 ALTER TABLE `sp_st_con_mov` ENABLE KEYS */;


--
-- Table structure for table `gestionfaena`.`sp_st_con_mov_proc`
--

DROP TABLE IF EXISTS `sp_st_con_mov_proc`;
CREATE TABLE `sp_st_con_mov_proc` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_proc_fan` int(11) DEFAULT NULL,
  `id_concepto` int(11) DEFAULT NULL,
  `id_tipo_movimiento` int(11) DEFAULT NULL,
  `id_art_orig_trans` int(11) DEFAULT NULL,
  `automatico` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `IDX_9738B6FCCB66DA3` (`id_proc_fan`),
  KEY `IDX_9738B6F34586445` (`id_concepto`),
  KEY `IDX_9738B6FB027D1DA` (`id_tipo_movimiento`),
  KEY `IDX_9738B6FDF7ADEEF` (`id_art_orig_trans`),
  CONSTRAINT `FK_9738B6FDF7ADEEF` FOREIGN KEY (`id_art_orig_trans`) REFERENCES `sp_gst_art` (`id`),
  CONSTRAINT `FK_9738B6F34586445` FOREIGN KEY (`id_concepto`) REFERENCES `sp_st_con_mov` (`id`),
  CONSTRAINT `FK_9738B6FB027D1DA` FOREIGN KEY (`id_tipo_movimiento`) REFERENCES `sp_gst_tpo_mov_con` (`id`),
  CONSTRAINT `FK_9738B6FCCB66DA3` FOREIGN KEY (`id_proc_fan`) REFERENCES `sp_proc_fan` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `gestionfaena`.`sp_st_con_mov_proc`
--

/*!40000 ALTER TABLE `sp_st_con_mov_proc` DISABLE KEYS */;
INSERT INTO `sp_st_con_mov_proc` (`id`,`id_proc_fan`,`id_concepto`,`id_tipo_movimiento`,`id_art_orig_trans`,`automatico`) VALUES 
 (1,1,1,1,NULL,0),
 (2,1,2,3,NULL,0),
 (3,2,2,1,NULL,1),
 (4,1,2,2,NULL,1);
/*!40000 ALTER TABLE `sp_st_con_mov_proc` ENABLE KEYS */;


--
-- Table structure for table `gestionfaena`.`sp_st_ent_st`
--

DROP TABLE IF EXISTS `sp_st_ent_st`;
CREATE TABLE `sp_st_ent_st` (
  `id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `FK_46975EABF396750` FOREIGN KEY (`id`) REFERENCES `sp_st_mov_abst` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `gestionfaena`.`sp_st_ent_st`
--

/*!40000 ALTER TABLE `sp_st_ent_st` DISABLE KEYS */;
INSERT INTO `sp_st_ent_st` (`id`) VALUES 
 (1),
 (2),
 (6);
/*!40000 ALTER TABLE `sp_st_ent_st` ENABLE KEYS */;


--
-- Table structure for table `gestionfaena`.`sp_st_mov_abst`
--

DROP TABLE IF EXISTS `sp_st_mov_abst`;
CREATE TABLE `sp_st_mov_abst` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_proc_fan_day` int(11) DEFAULT NULL,
  `id_art_proc_fan` int(11) DEFAULT NULL,
  `visible` tinyint(1) NOT NULL DEFAULT '1',
  `eliminado` tinyint(1) DEFAULT '0',
  `type` int(11) NOT NULL,
  `id_fan_day` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_4CBCE68F97575957` (`id_proc_fan_day`),
  KEY `IDX_4CBCE68FE6AB8D52` (`id_art_proc_fan`),
  KEY `IDX_4CBCE68FC7B2F574` (`id_fan_day`),
  CONSTRAINT `FK_4CBCE68FC7B2F574` FOREIGN KEY (`id_fan_day`) REFERENCES `sp_fan_day` (`id`),
  CONSTRAINT `FK_4CBCE68F97575957` FOREIGN KEY (`id_proc_fan_day`) REFERENCES `sp_proc_fan_day` (`id`),
  CONSTRAINT `FK_4CBCE68FE6AB8D52` FOREIGN KEY (`id_art_proc_fan`) REFERENCES `sp_st_art_atr_con` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `gestionfaena`.`sp_st_mov_abst`
--

/*!40000 ALTER TABLE `sp_st_mov_abst` DISABLE KEYS */;
INSERT INTO `sp_st_mov_abst` (`id`,`id_proc_fan_day`,`id_art_proc_fan`,`visible`,`eliminado`,`type`,`id_fan_day`) VALUES 
 (1,3,1,1,0,2,3),
 (2,2,3,1,1,2,3),
 (3,3,4,1,1,3,3),
 (4,3,2,0,0,5,3),
 (5,3,4,1,0,3,3),
 (6,2,3,1,0,2,3),
 (7,3,2,0,0,5,3);
/*!40000 ALTER TABLE `sp_st_mov_abst` ENABLE KEYS */;


--
-- Table structure for table `gestionfaena`.`sp_st_mov_val_ext`
--

DROP TABLE IF EXISTS `sp_st_mov_val_ext`;
CREATE TABLE `sp_st_mov_val_ext` (
  `id` int(11) NOT NULL,
  `id_etntity_extern` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_902799BC7B905FBA` (`id_etntity_extern`),
  CONSTRAINT `FK_902799BCBF396750` FOREIGN KEY (`id`) REFERENCES `sp_val_atr_art_proc_fan` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_902799BC7B905FBA` FOREIGN KEY (`id_etntity_extern`) REFERENCES `sp_gst_ent_ext` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `gestionfaena`.`sp_st_mov_val_ext`
--

/*!40000 ALTER TABLE `sp_st_mov_val_ext` DISABLE KEYS */;
INSERT INTO `sp_st_mov_val_ext` (`id`,`id_etntity_extern`) VALUES 
 (2,1),
 (1,21);
/*!40000 ALTER TABLE `sp_st_mov_val_ext` ENABLE KEYS */;


--
-- Table structure for table `gestionfaena`.`sp_st_mov_val_nro`
--

DROP TABLE IF EXISTS `sp_st_mov_val_nro`;
CREATE TABLE `sp_st_mov_val_nro` (
  `id` int(11) NOT NULL,
  `id_unt_med` int(11) DEFAULT NULL,
  `valor` double NOT NULL,
  `decimales` int(11) NOT NULL DEFAULT '0',
  `acumula` tinyint(1) DEFAULT '1',
  `promedia` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `IDX_ECF8573B4239DC83` (`id_unt_med`),
  CONSTRAINT `FK_ECF8573BBF396750` FOREIGN KEY (`id`) REFERENCES `sp_val_atr_art_proc_fan` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_ECF8573B4239DC83` FOREIGN KEY (`id_unt_med`) REFERENCES `sp_gst_unt_med` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `gestionfaena`.`sp_st_mov_val_nro`
--

/*!40000 ALTER TABLE `sp_st_mov_val_nro` DISABLE KEYS */;
INSERT INTO `sp_st_mov_val_nro` (`id`,`id_unt_med`,`valor`,`decimales`,`acumula`,`promedia`) VALUES 
 (6,2,4669,0,NULL,NULL),
 (7,1,31580,0,NULL,NULL),
 (8,1,16880,0,NULL,NULL),
 (9,1,14700,0,NULL,NULL),
 (10,1,3.1484257871064,0,NULL,NULL),
 (11,2,22,0,NULL,NULL),
 (12,1,69.265367316342,0,NULL,NULL),
 (13,1,3000,0,1,NULL),
 (14,1,-3000,0,1,NULL),
 (15,1,3000,0,NULL,NULL),
 (16,1,100,2,1,NULL),
 (17,1,-100,2,1,NULL),
 (18,1,100,0,NULL,NULL);
/*!40000 ALTER TABLE `sp_st_mov_val_nro` ENABLE KEYS */;


--
-- Table structure for table `gestionfaena`.`sp_st_mov_val_txt`
--

DROP TABLE IF EXISTS `sp_st_mov_val_txt`;
CREATE TABLE `sp_st_mov_val_txt` (
  `id` int(11) NOT NULL,
  `valor` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `FK_8DC350FBBF396750` FOREIGN KEY (`id`) REFERENCES `sp_val_atr_art_proc_fan` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `gestionfaena`.`sp_st_mov_val_txt`
--

/*!40000 ALTER TABLE `sp_st_mov_val_txt` DISABLE KEYS */;
INSERT INTO `sp_st_mov_val_txt` (`id`,`valor`) VALUES 
 (3,'1'),
 (4,'129320'),
 (5,'017310539-8');
/*!40000 ALTER TABLE `sp_st_mov_val_txt` ENABLE KEYS */;


--
-- Table structure for table `gestionfaena`.`sp_st_sal_st`
--

DROP TABLE IF EXISTS `sp_st_sal_st`;
CREATE TABLE `sp_st_sal_st` (
  `id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `FK_C61B7CCDBF396750` FOREIGN KEY (`id`) REFERENCES `sp_st_mov_abst` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `gestionfaena`.`sp_st_sal_st`
--

/*!40000 ALTER TABLE `sp_st_sal_st` DISABLE KEYS */;
INSERT INTO `sp_st_sal_st` (`id`) VALUES 
 (3),
 (5);
/*!40000 ALTER TABLE `sp_st_sal_st` ENABLE KEYS */;


--
-- Table structure for table `gestionfaena`.`sp_st_trans_st`
--

DROP TABLE IF EXISTS `sp_st_trans_st`;
CREATE TABLE `sp_st_trans_st` (
  `id` int(11) NOT NULL,
  `id_proc_fan_day_dest` int(11) DEFAULT NULL,
  `id_entrada` int(11) DEFAULT NULL,
  `id_salida` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_7D707B4317D40B04` (`id_entrada`),
  UNIQUE KEY `UNIQ_7D707B43B3C372A5` (`id_salida`),
  KEY `IDX_7D707B43BE862E93` (`id_proc_fan_day_dest`),
  CONSTRAINT `FK_7D707B43BF396750` FOREIGN KEY (`id`) REFERENCES `sp_st_mov_abst` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_7D707B4317D40B04` FOREIGN KEY (`id_entrada`) REFERENCES `sp_st_mov_abst` (`id`),
  CONSTRAINT `FK_7D707B43B3C372A5` FOREIGN KEY (`id_salida`) REFERENCES `sp_st_mov_abst` (`id`),
  CONSTRAINT `FK_7D707B43BE862E93` FOREIGN KEY (`id_proc_fan_day_dest`) REFERENCES `sp_proc_fan_day` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `gestionfaena`.`sp_st_trans_st`
--

/*!40000 ALTER TABLE `sp_st_trans_st` DISABLE KEYS */;
INSERT INTO `sp_st_trans_st` (`id`,`id_proc_fan_day_dest`,`id_entrada`,`id_salida`) VALUES 
 (4,NULL,2,3),
 (7,NULL,6,5);
/*!40000 ALTER TABLE `sp_st_trans_st` ENABLE KEYS */;


--
-- Table structure for table `gestionfaena`.`sp_val_atr_art_proc_fan`
--

DROP TABLE IF EXISTS `sp_val_atr_art_proc_fan`;
CREATE TABLE `sp_val_atr_art_proc_fan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_atributo` int(11) DEFAULT NULL,
  `id_mv_st` int(11) DEFAULT NULL,
  `id_atributo_abstracto` int(11) DEFAULT NULL,
  `posicion` int(11) DEFAULT '0',
  `mostrar` tinyint(1) DEFAULT '1',
  `type` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_1A4B0F5EB61C8B1F` (`id_atributo`),
  KEY `IDX_1A4B0F5E33CB0C0C` (`id_mv_st`),
  KEY `IDX_1A4B0F5EB9238E71` (`id_atributo_abstracto`),
  CONSTRAINT `FK_1A4B0F5EB9238E71` FOREIGN KEY (`id_atributo_abstracto`) REFERENCES `sp_gst_atr_abst` (`id`),
  CONSTRAINT `FK_1A4B0F5E33CB0C0C` FOREIGN KEY (`id_mv_st`) REFERENCES `sp_st_mov_abst` (`id`),
  CONSTRAINT `FK_1A4B0F5EB61C8B1F` FOREIGN KEY (`id_atributo`) REFERENCES `sp_gst_atr_abs` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `gestionfaena`.`sp_val_atr_art_proc_fan`
--

/*!40000 ALTER TABLE `sp_val_atr_art_proc_fan` DISABLE KEYS */;
INSERT INTO `sp_val_atr_art_proc_fan` (`id`,`id_atributo`,`id_mv_st`,`id_atributo_abstracto`,`posicion`,`mostrar`,`type`) VALUES 
 (1,1,1,NULL,NULL,NULL,4),
 (2,4,1,NULL,NULL,NULL,4),
 (3,2,1,NULL,NULL,NULL,3),
 (4,3,1,NULL,NULL,NULL,3),
 (5,6,1,NULL,NULL,NULL,3),
 (6,5,1,NULL,NULL,NULL,2),
 (7,7,1,NULL,NULL,NULL,2),
 (8,8,1,NULL,NULL,NULL,2),
 (9,9,1,NULL,NULL,NULL,2),
 (10,10,1,NULL,NULL,NULL,2),
 (11,11,1,NULL,NULL,NULL,2),
 (12,12,1,NULL,NULL,NULL,2),
 (13,NULL,2,2,NULL,1,2),
 (14,NULL,3,2,NULL,1,2),
 (15,13,4,NULL,NULL,NULL,2),
 (16,NULL,6,2,NULL,1,2),
 (17,NULL,5,2,NULL,1,2),
 (18,13,7,NULL,NULL,NULL,2);
/*!40000 ALTER TABLE `sp_val_atr_art_proc_fan` ENABLE KEYS */;


--
-- Table structure for table `gestionfaena`.`usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `username_canonical` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `email_canonical` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `salt` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `confirmation_token` varchar(180) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password_requested_at` datetime DEFAULT NULL,
  `roles` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_EF687F292FC23A8` (`username_canonical`),
  UNIQUE KEY `UNIQ_EF687F2A0D96FBF` (`email_canonical`),
  UNIQUE KEY `UNIQ_EF687F2C05FB297` (`confirmation_token`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `gestionfaena`.`usuarios`
--

/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` (`id`,`username`,`username_canonical`,`email`,`email_canonical`,`enabled`,`salt`,`password`,`last_login`,`confirmation_token`,`password_requested_at`,`roles`) VALUES 
 (1,'leochabur','leochabur','leochabur@gmail.com','leochabur@gmail.com',1,NULL,'$2y$13$F9GBPDo.pDRyf.AZTePRV.vlZiWYAdWQccPHpb1BLDvilDqEXaZ4O','2020-03-29 09:43:15',NULL,NULL,'a:1:{i:0;s:16:\"ROLE_SUPER_ADMIN\";}'),
 (2,'admin','admin','admin@master.net','admin@master.net',1,NULL,'$2y$13$ZKcaqPwyM9.2JhhtZnye..rDy9rUtv4dteOcW210qFSqjrY0aTd9S','2020-01-27 15:24:06',NULL,NULL,'a:0:{}'),
 (3,'usuario1','usuario1','usuario1@sapucai.com','usuario1@sapucai.com',1,NULL,'$2y$13$wIEWzMaWamTU65LzBU9BI.xI2AGKKb03J8kcOEBD9Hms2RvBY/adq','2019-11-28 13:57:33',NULL,NULL,'a:0:{}');
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
