/*
SQLyog Enterprise - MySQL GUI v7.02 
MySQL - 5.6.24 : Database - dah
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

CREATE DATABASE /*!32312 IF NOT EXISTS*/`dah` /*!40100 DEFAULT CHARACTER SET utf8 */;

/*Table structure for table `dah_activity_log` */

DROP TABLE IF EXISTS `dah_activity_log`;

CREATE TABLE `dah_activity_log` (
  `logid` int(11) NOT NULL AUTO_INCREMENT,
  `adminid` bigint(11) unsigned NOT NULL,
  `message` text,
  `logged_on` int(11) DEFAULT NULL,
  PRIMARY KEY (`logid`,`adminid`),
  KEY `adminid` (`adminid`),
  CONSTRAINT `dah_activity_log_ibfk_1` FOREIGN KEY (`adminid`) REFERENCES `dah_admin` (`adminid`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=270 DEFAULT CHARSET=utf8;

/*Table structure for table `dah_admin` */

DROP TABLE IF EXISTS `dah_admin`;

CREATE TABLE `dah_admin` (
  `adminid` bigint(12) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(50) DEFAULT NULL,
  `email` varchar(200) DEFAULT NULL,
  `password` varchar(150) DEFAULT NULL,
  `salt` varchar(150) DEFAULT NULL,
  `name` varchar(150) DEFAULT NULL,
  `lastlogin` bigint(12) DEFAULT NULL,
  `lastlogin_ip` varchar(30) DEFAULT NULL,
  `created_on` bigint(12) DEFAULT NULL,
  `updated_on` bigint(12) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `default_admin` enum('yes','no') DEFAULT 'no',
  PRIMARY KEY (`adminid`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

/*Table structure for table `dah_content_pages` */

DROP TABLE IF EXISTS `dah_content_pages`;

CREATE TABLE `dah_content_pages` (
  `pageid` int(11) NOT NULL AUTO_INCREMENT,
  `page_name` varchar(100) DEFAULT NULL,
  `page_title` varchar(250) DEFAULT NULL,
  `page_url` varchar(100) DEFAULT NULL,
  `page_subtitle` varchar(250) DEFAULT NULL,
  `page_content` longtext,
  `page_image` varchar(50) DEFAULT NULL,
  `page_meta_title` varchar(250) DEFAULT NULL,
  `page_meta_keyword` varchar(250) DEFAULT NULL,
  `page_meta_description` varchar(250) DEFAULT NULL,
  `added_on` int(11) DEFAULT NULL,
  `updated_on` int(11) DEFAULT NULL,
  PRIMARY KEY (`pageid`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

/*Table structure for table `dah_departments` */

DROP TABLE IF EXISTS `dah_departments`;

CREATE TABLE `dah_departments` (
  `deptid` int(11) NOT NULL AUTO_INCREMENT,
  `department` varchar(100) NOT NULL,
  `status` enum('active','inactive') NOT NULL,
  `added_on` int(11) NOT NULL,
  `updated_on` int(11) NOT NULL,
  PRIMARY KEY (`deptid`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8;

/*Table structure for table `dah_password_reset` */

DROP TABLE IF EXISTS `dah_password_reset`;

CREATE TABLE `dah_password_reset` (
  `resetid` bigint(12) unsigned NOT NULL AUTO_INCREMENT,
  `adminid` bigint(12) unsigned DEFAULT NULL,
  `code` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`resetid`),
  KEY `FK_dah_password_reset` (`adminid`),
  CONSTRAINT `FK_dah_password_reset` FOREIGN KEY (`adminid`) REFERENCES `dah_admin` (`adminid`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

/*Table structure for table `dah_settings` */

DROP TABLE IF EXISTS `dah_settings`;

CREATE TABLE `dah_settings` (
  `settingid` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `linkedin` varchar(255) DEFAULT NULL,
  `twitter` varchar(255) DEFAULT NULL,
  `facebook` varchar(255) DEFAULT NULL,
  `google_plus` varchar(255) DEFAULT NULL,
  `update_on` int(11) DEFAULT NULL,
  PRIMARY KEY (`settingid`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Table structure for table `dah_unsubscribe` */

DROP TABLE IF EXISTS `dah_unsubscribe`;

CREATE TABLE `dah_unsubscribe` (
  `unsubid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) DEFAULT NULL,
  `added_on` int(11) DEFAULT NULL,
  PRIMARY KEY (`unsubid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `dah_users` */

DROP TABLE IF EXISTS `dah_users`;

CREATE TABLE `dah_users` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `roleid` int(11) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `added_on` int(11) DEFAULT NULL,
  `updated_on` int(11) DEFAULT NULL,
  `logged_on` int(11) DEFAULT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
