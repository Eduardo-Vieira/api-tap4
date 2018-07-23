-- MySQL dump 10.13  Distrib 5.7.22, for Linux (x86_64)
--
-- Host: localhost    Database: mydb
-- ------------------------------------------------------
-- Server version	5.7.22-0ubuntu18.04.1

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
-- Table structure for table `tb_frameworks`
--

DROP TABLE IF EXISTS `tb_frameworks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tb_frameworks` (
  `id_frameworks` int(11) NOT NULL AUTO_INCREMENT,
  `id_liguagem` int(11) NOT NULL,
  `tx_nome` varchar(45) DEFAULT NULL,
  `tx_site` varchar(45) DEFAULT NULL,
  `tx_ano` varchar(45) DEFAULT NULL,
  `tx_criador` varchar(45) DEFAULT NULL,
  `tx_versao` varchar(45) DEFAULT NULL,
  `id_tipos` int(11) NOT NULL,
  `tx_opniao` varchar(255) DEFAULT NULL,
  `tx_pros` varchar(255) DEFAULT NULL,
  `tx_contra` varchar(225) DEFAULT NULL,
  `tx_urlimg` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_frameworks`,`id_liguagem`,`id_tipos`),
  KEY `fk_tb_frameworks_tb_liguagens_idx` (`id_liguagem`),
  KEY `fk_tb_frameworks_tb_tipos_idx` (`id_tipos`),
  CONSTRAINT `fk_tb_frameworks_tb_liguagens` FOREIGN KEY (`id_liguagem`) REFERENCES `tb_liguagens` (`id_liguagem`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_tb_frameworks_tb_tipos` FOREIGN KEY (`id_tipos`) REFERENCES `tb_tipos` (`id_tipos`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_frameworks`
--

LOCK TABLES `tb_frameworks` WRITE;
/*!40000 ALTER TABLE `tb_frameworks` DISABLE KEYS */;
INSERT INTO `tb_frameworks` VALUES (2,1,'Laravel 5','https://laravel.com','2015','n/a','5.5',2,'Muito bom para aplicaÃ§Ãµes de grande porte.','ORM bom.','Estrutura de arquivos nÃ£o bem trabalhado nÃ£o e Ã¡gil em desenvolvimento.',NULL),(3,3,'Express JS','http://expressjs.com','2014','n/a','4.16.3',1,'Muito bom para criaÃ§Ã£o de micro serviÃ§os','Desenvolvimento rÃ¡pido.','Tem dificuldades com cÃ¡lculos matemÃ¡ticos',NULL),(4,3,'Angular 2','https://angular.io/','2016','Google inc.','6',3,'Muti bom para desenvolvimento de front-end','OrganizaÃ§Ã£o de cÃ³digo Ã© o que tem de melhor nele.','DeclaraÃ§Ã£o de Modules podia ser melhor.',NULL),(5,1,'Yii 2 Framework','https://www.yiiframework.com/','2015','n/a','2.0',2,'Teste','Teste','Teste','/uploads/frameworks/yii2.png');
/*!40000 ALTER TABLE `tb_frameworks` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-07-23 13:57:40
