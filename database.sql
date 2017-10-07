/*
SQLyog Community v12.4.3 (64 bit)
MySQL - 10.1.25-MariaDB : Database - restaurant
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`restaurant` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `restaurant`;

/*Table structure for table `emp_tel` */

DROP TABLE IF EXISTS `emp_tel`;

CREATE TABLE `emp_tel` (
  `tel_id` int(10) NOT NULL AUTO_INCREMENT,
  `tel_tel` int(10) DEFAULT NULL,
  `tel_ext` int(10) DEFAULT NULL,
  `tel_status` int(2) DEFAULT NULL,
  `tel_emp_id` int(10) DEFAULT NULL,
  PRIMARY KEY (`tel_id`),
  KEY `fk_tel_status` (`tel_status`),
  KEY `fk_tel_emp` (`tel_emp_id`),
  CONSTRAINT `fk_tel_emp` FOREIGN KEY (`tel_emp_id`) REFERENCES `res_employee` (`emp_id`),
  CONSTRAINT `fk_tel_status` FOREIGN KEY (`tel_status`) REFERENCES `res_status` (`stat_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `emp_tel` */

insert  into `emp_tel`(`tel_id`,`tel_tel`,`tel_ext`,`tel_status`,`tel_emp_id`) values 
(1,12354545,11,1,18),
(2,12354545,11,1,19);

/*Table structure for table `res_drink` */

DROP TABLE IF EXISTS `res_drink`;

CREATE TABLE `res_drink` (
  `drink_id` int(10) NOT NULL,
  `drink_name` char(20) DEFAULT NULL,
  `drink_number` int(10) DEFAULT NULL,
  `drink_price` int(10) DEFAULT NULL,
  `drink_status_id` int(2) DEFAULT NULL,
  `drink_unit_id` int(10) DEFAULT NULL,
  PRIMARY KEY (`drink_id`),
  KEY `fk_drink_status` (`drink_status_id`),
  KEY `fk_drink_unit` (`drink_unit_id`),
  CONSTRAINT `fk_drink_status` FOREIGN KEY (`drink_status_id`) REFERENCES `res_status` (`stat_id`),
  CONSTRAINT `fk_drink_unit` FOREIGN KEY (`drink_unit_id`) REFERENCES `res_unit` (`unit_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `res_drink` */

/*Table structure for table `res_employee` */

DROP TABLE IF EXISTS `res_employee`;

CREATE TABLE `res_employee` (
  `emp_id` int(10) NOT NULL AUTO_INCREMENT,
  `emp_firstname` char(20) DEFAULT NULL,
  `emp_lastname` char(20) DEFAULT NULL,
  `emp_user` varchar(10) DEFAULT NULL,
  `emp_pass` int(10) DEFAULT NULL,
  `emp_idcard` int(13) DEFAULT NULL,
  `emp_pos_id` int(10) DEFAULT NULL,
  `emp_status_id` int(10) DEFAULT NULL,
  PRIMARY KEY (`emp_id`),
  KEY `fk_emp_pos` (`emp_pos_id`),
  KEY `fk_emp_status` (`emp_status_id`),
  CONSTRAINT `fk_emp_pos` FOREIGN KEY (`emp_pos_id`) REFERENCES `res_position` (`pos_id`),
  CONSTRAINT `fk_emp_status` FOREIGN KEY (`emp_status_id`) REFERENCES `res_status` (`stat_id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;

/*Data for the table `res_employee` */

insert  into `res_employee`(`emp_id`,`emp_firstname`,`emp_lastname`,`emp_user`,`emp_pass`,`emp_idcard`,`emp_pos_id`,`emp_status_id`) values 
(1,'htt','test2','uyyt',0,125444,NULL,NULL),
(2,'htt','test2','uyyt',0,125444,NULL,NULL),
(6,'htty','test22','uyy',884,12544,1,1),
(7,'htty','test22','uyyoo',884,12544,1,1),
(10,'htty','test22','uyyoopp',884,12544,1,1),
(11,'httys','test22s','uyyooppss',2147483647,1254423,1,1),
(18,'httys','test22s','1',0,1254423,1,1),
(19,'httys','test22s','1ddd',0,1254423,1,1);

/*Table structure for table `res_food` */

DROP TABLE IF EXISTS `res_food`;

CREATE TABLE `res_food` (
  `food_id` int(10) NOT NULL AUTO_INCREMENT,
  `food_name` char(20) DEFAULT NULL,
  `food_price` int(10) DEFAULT NULL,
  `food_kind_id` int(10) DEFAULT NULL,
  `food_status_id` int(10) DEFAULT NULL,
  PRIMARY KEY (`food_id`),
  KEY `fk_food_kind` (`food_kind_id`),
  KEY `fk_food_status` (`food_status_id`),
  CONSTRAINT `fk_food_kind` FOREIGN KEY (`food_kind_id`) REFERENCES `res_kind` (`kind_id`),
  CONSTRAINT `fk_food_status` FOREIGN KEY (`food_status_id`) REFERENCES `res_status` (`stat_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `res_food` */

/*Table structure for table `res_kind` */

DROP TABLE IF EXISTS `res_kind`;

CREATE TABLE `res_kind` (
  `kind_id` int(10) NOT NULL AUTO_INCREMENT,
  `kind_name` char(20) DEFAULT NULL,
  `kind_status` int(10) DEFAULT NULL,
  PRIMARY KEY (`kind_id`),
  KEY `fk_kind_status` (`kind_status`),
  CONSTRAINT `fk_kind_status` FOREIGN KEY (`kind_status`) REFERENCES `res_kind` (`kind_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `res_kind` */

/*Table structure for table `res_position` */

DROP TABLE IF EXISTS `res_position`;

CREATE TABLE `res_position` (
  `pos_id` int(10) NOT NULL AUTO_INCREMENT,
  `pos_name` char(20) DEFAULT NULL,
  `pos_role_id` int(10) DEFAULT NULL,
  `pos_status_id` int(10) DEFAULT NULL,
  PRIMARY KEY (`pos_id`),
  KEY `fk_pos_role` (`pos_role_id`),
  KEY `fk_pos_status` (`pos_status_id`),
  CONSTRAINT `fk_pos_role` FOREIGN KEY (`pos_role_id`) REFERENCES `res_role` (`role_id`),
  CONSTRAINT `fk_pos_status` FOREIGN KEY (`pos_status_id`) REFERENCES `res_status` (`stat_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `res_position` */

insert  into `res_position`(`pos_id`,`pos_name`,`pos_role_id`,`pos_status_id`) values 
(1,'test',1,1);

/*Table structure for table `res_promotion` */

DROP TABLE IF EXISTS `res_promotion`;

CREATE TABLE `res_promotion` (
  `pro_id` int(10) NOT NULL AUTO_INCREMENT,
  `pro_name` char(20) DEFAULT NULL,
  `pro_discount` int(10) DEFAULT NULL,
  `pro_start` datetime DEFAULT NULL,
  `pro_end` datetime DEFAULT NULL,
  `pro_status_id` int(10) DEFAULT NULL,
  PRIMARY KEY (`pro_id`),
  KEY `fk_pro_status` (`pro_status_id`),
  CONSTRAINT `fk_pro_status` FOREIGN KEY (`pro_status_id`) REFERENCES `res_status` (`stat_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `res_promotion` */

/*Table structure for table `res_role` */

DROP TABLE IF EXISTS `res_role`;

CREATE TABLE `res_role` (
  `role_id` int(10) NOT NULL AUTO_INCREMENT,
  `role_name` char(20) DEFAULT NULL,
  `role_front` char(20) DEFAULT NULL,
  `role_back` char(20) DEFAULT NULL,
  `role_status_id` int(10) DEFAULT NULL,
  PRIMARY KEY (`role_id`),
  KEY `fk_role_status` (`role_status_id`),
  CONSTRAINT `fk_role_status` FOREIGN KEY (`role_status_id`) REFERENCES `res_status` (`stat_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `res_role` */

insert  into `res_role`(`role_id`,`role_name`,`role_front`,`role_back`,`role_status_id`) values 
(1,'test',NULL,NULL,1);

/*Table structure for table `res_status` */

DROP TABLE IF EXISTS `res_status`;

CREATE TABLE `res_status` (
  `stat_id` int(10) NOT NULL AUTO_INCREMENT,
  `stat_name` char(20) DEFAULT NULL,
  PRIMARY KEY (`stat_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `res_status` */

insert  into `res_status`(`stat_id`,`stat_name`) values 
(1,'ใช้งาน');

/*Table structure for table `res_table` */

DROP TABLE IF EXISTS `res_table`;

CREATE TABLE `res_table` (
  `table_id` int(10) NOT NULL AUTO_INCREMENT,
  `table_number` int(10) DEFAULT NULL,
  `table_status_id` int(2) DEFAULT NULL,
  PRIMARY KEY (`table_id`),
  KEY `fk_table_status` (`table_status_id`),
  CONSTRAINT `fk_table_status` FOREIGN KEY (`table_status_id`) REFERENCES `res_status` (`stat_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `res_table` */

/*Table structure for table `res_unit` */

DROP TABLE IF EXISTS `res_unit`;

CREATE TABLE `res_unit` (
  `unit_id` int(10) NOT NULL,
  `unit_name` char(20) DEFAULT NULL,
  `unit_number` int(10) DEFAULT NULL,
  `unit_status_id` int(10) DEFAULT NULL,
  PRIMARY KEY (`unit_id`),
  KEY `fk_unit_status` (`unit_status_id`),
  CONSTRAINT `fk_unit_status` FOREIGN KEY (`unit_status_id`) REFERENCES `res_status` (`stat_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `res_unit` */

/*Table structure for table `res_unitdetail` */

DROP TABLE IF EXISTS `res_unitdetail`;

CREATE TABLE `res_unitdetail` (
  `unitdetail_id` int(10) NOT NULL AUTO_INCREMENT,
  `unitdetail_number` int(10) DEFAULT NULL,
  `unitdetail_unit_id` int(10) DEFAULT NULL,
  `unitdetail_status_id` int(2) DEFAULT NULL,
  PRIMARY KEY (`unitdetail_id`),
  KEY `fk_unitdetail_unit` (`unitdetail_unit_id`),
  KEY `fk_unitdetail_status` (`unitdetail_status_id`),
  CONSTRAINT `fk_unitdetail_status` FOREIGN KEY (`unitdetail_status_id`) REFERENCES `res_status` (`stat_id`),
  CONSTRAINT `fk_unitdetail_unit` FOREIGN KEY (`unitdetail_unit_id`) REFERENCES `res_unit` (`unit_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `res_unitdetail` */

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
