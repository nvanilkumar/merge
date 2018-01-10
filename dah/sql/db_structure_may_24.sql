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
) ENGINE=InnoDB AUTO_INCREMENT=514 DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

/*Table structure for table `dah_asses_info` */

DROP TABLE IF EXISTS `dah_asses_info`;

CREATE TABLE `dah_asses_info` (
  `ainid` int(11) NOT NULL AUTO_INCREMENT,
  `tid` int(11) DEFAULT NULL,
  `totalmarks` int(11) DEFAULT NULL,
  `cutoff` int(11) DEFAULT NULL,
  PRIMARY KEY (`ainid`),
  KEY `FK_dah_asses_info` (`tid`),
  CONSTRAINT `FK_dah_asses_info` FOREIGN KEY (`tid`) REFERENCES `dah_trainings` (`tid`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

/*Table structure for table `dah_assesment_result` */

DROP TABLE IF EXISTS `dah_assesment_result`;

CREATE TABLE `dah_assesment_result` (
  `darid` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL,
  `tid` int(11) DEFAULT NULL,
  `max_marks` int(11) DEFAULT NULL,
  `correct` int(11) DEFAULT NULL,
  `unassigned` int(11) DEFAULT NULL,
  `result` enum('pass','failed') DEFAULT NULL,
  `attempt_no` int(11) DEFAULT NULL,
  `last_asses_om` bigint(11) DEFAULT NULL,
  PRIMARY KEY (`darid`),
  KEY `FK_dah_assesment_result_uid` (`uid`),
  KEY `FK_dah_assesment_result_tid` (`tid`),
  CONSTRAINT `FK_dah_assesment_result_tid` FOREIGN KEY (`tid`) REFERENCES `dah_trainings` (`tid`) ON DELETE CASCADE,
  CONSTRAINT `FK_dah_assesment_result_uid` FOREIGN KEY (`uid`) REFERENCES `dah_users` (`uid`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB AUTO_INCREMENT=60 DEFAULT CHARSET=utf8;

/*Table structure for table `dah_departments` */

DROP TABLE IF EXISTS `dah_departments`;

CREATE TABLE `dah_departments` (
  `deptid` int(11) NOT NULL AUTO_INCREMENT,
  `department` varchar(100) NOT NULL,
  `status` enum('active','inactive') NOT NULL,
  `added_on` int(11) NOT NULL,
  `updated_on` int(11) NOT NULL,
  PRIMARY KEY (`deptid`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

/*Table structure for table `dah_enquires` */

DROP TABLE IF EXISTS `dah_enquires`;

CREATE TABLE `dah_enquires` (
  `enquiry_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `message` text,
  `status` enum('read','unread') DEFAULT 'unread',
  `received_on` int(11) DEFAULT NULL,
  PRIMARY KEY (`enquiry_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

/*Table structure for table `dah_faqs` */

DROP TABLE IF EXISTS `dah_faqs`;

CREATE TABLE `dah_faqs` (
  `faqid` bigint(12) unsigned NOT NULL AUTO_INCREMENT,
  `question` text,
  `answer` text,
  `added_on` bigint(12) DEFAULT NULL,
  `updated_on` bigint(12) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  PRIMARY KEY (`faqid`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8;

/*Table structure for table `dah_members_password_reset` */

DROP TABLE IF EXISTS `dah_members_password_reset`;

CREATE TABLE `dah_members_password_reset` (
  `resetid` bigint(12) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL,
  `code` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`resetid`),
  KEY `FK_dah_password_reset` (`uid`),
  CONSTRAINT `FK_dah_members_password_reset` FOREIGN KEY (`uid`) REFERENCES `dah_users` (`uid`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

/*Table structure for table `dah_news` */

DROP TABLE IF EXISTS `dah_news`;

CREATE TABLE `dah_news` (
  `newsid` int(11) NOT NULL AUTO_INCREMENT,
  `news_title` varchar(250) DEFAULT NULL,
  `news_subtitle` varchar(250) DEFAULT NULL,
  `news_content` longtext,
  `news_image` varchar(50) DEFAULT NULL,
  `future_date` int(11) DEFAULT NULL,
  `newsletter` enum('yes','no') DEFAULT 'no',
  `news_meta_title` varchar(250) DEFAULT NULL,
  `news_meta_keyword` varchar(250) DEFAULT NULL,
  `news_meta_description` varchar(250) DEFAULT NULL,
  `added_on` int(11) DEFAULT NULL,
  `updated_on` int(11) DEFAULT NULL,
  PRIMARY KEY (`newsid`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8;

/*Table structure for table `dah_newsletter_message_queue` */

DROP TABLE IF EXISTS `dah_newsletter_message_queue`;

CREATE TABLE `dah_newsletter_message_queue` (
  `nqid` int(11) NOT NULL AUTO_INCREMENT,
  `newsid` int(11) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `status` enum('sent','pending') DEFAULT 'pending',
  `publishdate` int(11) DEFAULT NULL,
  PRIMARY KEY (`nqid`),
  KEY `FK_dah_newsletter_message_queue` (`newsid`),
  CONSTRAINT `FK_dah_newsletter_message_queue` FOREIGN KEY (`newsid`) REFERENCES `dah_news` (`newsid`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=99 DEFAULT CHARSET=utf8;

/*Table structure for table `dah_newsletter_subscribers` */

DROP TABLE IF EXISTS `dah_newsletter_subscribers`;

CREATE TABLE `dah_newsletter_subscribers` (
  `subid` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(100) DEFAULT NULL,
  `subscribed_on` int(11) DEFAULT NULL,
  `status` enum('active') DEFAULT NULL,
  PRIMARY KEY (`subid`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

/*Table structure for table `dah_password_reset` */

DROP TABLE IF EXISTS `dah_password_reset`;

CREATE TABLE `dah_password_reset` (
  `resetid` bigint(12) unsigned NOT NULL AUTO_INCREMENT,
  `adminid` bigint(12) unsigned DEFAULT NULL,
  `code` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`resetid`),
  KEY `FK_dah_password_reset` (`adminid`),
  CONSTRAINT `FK_dah_password_reset` FOREIGN KEY (`adminid`) REFERENCES `dah_admin` (`adminid`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

/*Table structure for table `dah_question_options` */

DROP TABLE IF EXISTS `dah_question_options`;

CREATE TABLE `dah_question_options` (
  `opid` int(11) NOT NULL AUTO_INCREMENT,
  `qid` int(11) DEFAULT NULL,
  `options` longtext,
  PRIMARY KEY (`opid`),
  KEY `FK_dah_question_options` (`qid`),
  CONSTRAINT `FK_dah_question_options` FOREIGN KEY (`qid`) REFERENCES `dah_training_questions` (`qid`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=164 DEFAULT CHARSET=utf8;

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
  `address` text,
  PRIMARY KEY (`settingid`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Table structure for table `dah_student_info` */

DROP TABLE IF EXISTS `dah_student_info`;

CREATE TABLE `dah_student_info` (
  `tinfoid` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL,
  `depid` int(11) DEFAULT NULL,
  `bio` text,
  `position` varchar(150) DEFAULT NULL,
  `exp` varchar(50) DEFAULT NULL,
  `qualification` varchar(150) DEFAULT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `school` varchar(250) DEFAULT NULL,
  `location` varchar(250) DEFAULT NULL,
  `updated_on` int(11) DEFAULT NULL,
  PRIMARY KEY (`tinfoid`),
  KEY `FK_dah_teachers_info_uid` (`uid`),
  KEY `FK_dah_teachers_info_dep` (`depid`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Table structure for table `dah_teachers_info` */

DROP TABLE IF EXISTS `dah_teachers_info`;

CREATE TABLE `dah_teachers_info` (
  `tinfoid` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL,
  `depid` int(11) DEFAULT NULL,
  `bio` text,
  `position` varchar(150) DEFAULT NULL,
  `exp` varchar(50) DEFAULT NULL,
  `qualification` varchar(150) DEFAULT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `school` varchar(250) DEFAULT NULL,
  `location` varchar(250) DEFAULT NULL,
  `updated_on` int(11) DEFAULT NULL,
  PRIMARY KEY (`tinfoid`),
  KEY `FK_dah_teachers_info_uid` (`uid`),
  KEY `FK_dah_teachers_info_dep` (`depid`),
  CONSTRAINT `FK_dah_teachers_info_dep` FOREIGN KEY (`depid`) REFERENCES `dah_departments` (`deptid`) ON DELETE CASCADE,
  CONSTRAINT `FK_dah_teachers_info_uid` FOREIGN KEY (`uid`) REFERENCES `dah_users` (`uid`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;

/*Table structure for table `dah_training_enrollment` */

DROP TABLE IF EXISTS `dah_training_enrollment`;

CREATE TABLE `dah_training_enrollment` (
  `enid` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL,
  `tid` int(11) DEFAULT NULL,
  `training_status` enum('incomplete','complete') DEFAULT 'incomplete',
  `certificate_status` enum('notIssued','issued','requested') DEFAULT 'notIssued',
  PRIMARY KEY (`enid`),
  KEY `FK_dah_workshop_enrollment` (`tid`),
  KEY `FK_dah_workshop_enrollment_uid` (`uid`),
  CONSTRAINT `FK_dah_training_enrollment` FOREIGN KEY (`tid`) REFERENCES `dah_trainings` (`tid`) ON DELETE CASCADE,
  CONSTRAINT `FK_dah_training_enrollment_uid` FOREIGN KEY (`uid`) REFERENCES `dah_users` (`uid`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

/*Table structure for table `dah_training_key` */

DROP TABLE IF EXISTS `dah_training_key`;

CREATE TABLE `dah_training_key` (
  `keyid` int(11) NOT NULL AUTO_INCREMENT,
  `qid` int(11) DEFAULT NULL,
  `opid` int(11) DEFAULT NULL,
  PRIMARY KEY (`keyid`),
  KEY `FK_dah_training_key_qid` (`qid`),
  KEY `FK_dah_training_key` (`opid`),
  CONSTRAINT `FK_dah_training_key` FOREIGN KEY (`opid`) REFERENCES `dah_question_options` (`opid`) ON DELETE CASCADE,
  CONSTRAINT `FK_dah_training_key_qid` FOREIGN KEY (`qid`) REFERENCES `dah_training_questions` (`qid`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

/*Table structure for table `dah_training_questions` */

DROP TABLE IF EXISTS `dah_training_questions`;

CREATE TABLE `dah_training_questions` (
  `qid` int(11) NOT NULL AUTO_INCREMENT,
  `tid` int(11) DEFAULT NULL,
  `question` longtext,
  `marks` int(11) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `qtype` enum('mcq','tf') DEFAULT NULL,
  PRIMARY KEY (`qid`),
  KEY `FK_dah_training_questions` (`tid`),
  CONSTRAINT `FK_dah_training_questions` FOREIGN KEY (`tid`) REFERENCES `dah_trainings` (`tid`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8;

/*Table structure for table `dah_training_videos` */

DROP TABLE IF EXISTS `dah_training_videos`;

CREATE TABLE `dah_training_videos` (
  `tvid` int(11) NOT NULL AUTO_INCREMENT,
  `tid` int(11) DEFAULT NULL,
  `video_title` varchar(255) DEFAULT NULL,
  `video` varchar(255) DEFAULT NULL,
  `video_url` varchar(255) DEFAULT NULL,
  `video_desc` longtext,
  `video_thumbnail` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'inactive',
  PRIMARY KEY (`tvid`),
  KEY `FK_dah_training_videos` (`tid`),
  CONSTRAINT `FK_dah_training_videos` FOREIGN KEY (`tid`) REFERENCES `dah_trainings` (`tid`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;

/*Table structure for table `dah_trainings` */

DROP TABLE IF EXISTS `dah_trainings`;

CREATE TABLE `dah_trainings` (
  `tid` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL,
  `deptid` int(11) DEFAULT NULL,
  `training_title` varchar(250) DEFAULT NULL,
  `training_description` longtext,
  `training_meta_title` varchar(250) DEFAULT NULL,
  `training_meta_keyword` varchar(250) DEFAULT NULL,
  `training_meta_description` varchar(250) DEFAULT NULL,
  `added_on` int(11) DEFAULT NULL,
  `updated_on` int(11) DEFAULT NULL,
  `public` enum('yes','no') DEFAULT 'yes',
  `assesment` enum('yes','no') DEFAULT 'no',
  `status` enum('active','inactive') DEFAULT 'inactive',
  PRIMARY KEY (`tid`),
  KEY `FK_dah_workshops` (`deptid`),
  KEY `FK_dah_trainings_uid` (`uid`),
  CONSTRAINT `FK_dah_trainings` FOREIGN KEY (`deptid`) REFERENCES `dah_departments` (`deptid`) ON DELETE CASCADE,
  CONSTRAINT `FK_dah_trainings_uid` FOREIGN KEY (`uid`) REFERENCES `dah_users` (`uid`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;

/*Table structure for table `dah_unsubscribe` */

DROP TABLE IF EXISTS `dah_unsubscribe`;

CREATE TABLE `dah_unsubscribe` (
  `unsubid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) DEFAULT NULL,
  `added_on` int(11) DEFAULT NULL,
  PRIMARY KEY (`unsubid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `dah_user_assesment_results` */

DROP TABLE IF EXISTS `dah_user_assesment_results`;

CREATE TABLE `dah_user_assesment_results` (
  `duaid` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL,
  `tid` int(11) DEFAULT NULL,
  `qid` int(11) DEFAULT NULL,
  `opid` int(11) DEFAULT NULL,
  `res` enum('correct','incorrect','unassigned') DEFAULT NULL,
  PRIMARY KEY (`duaid`),
  KEY `FK_dah_user_assesment_results_uid` (`uid`),
  KEY `FK_dah_user_assesment_results_tid` (`tid`),
  KEY `FK_dah_user_assesment_results_qid` (`qid`),
  KEY `FK_dah_user_assesment_results_opid` (`opid`),
  CONSTRAINT `FK_dah_user_assesment_results_opid` FOREIGN KEY (`opid`) REFERENCES `dah_question_options` (`opid`) ON DELETE CASCADE,
  CONSTRAINT `FK_dah_user_assesment_results_qid` FOREIGN KEY (`qid`) REFERENCES `dah_training_questions` (`qid`) ON DELETE CASCADE,
  CONSTRAINT `FK_dah_user_assesment_results_tid` FOREIGN KEY (`tid`) REFERENCES `dah_trainings` (`tid`) ON DELETE CASCADE,
  CONSTRAINT `FK_dah_user_assesment_results_uid` FOREIGN KEY (`uid`) REFERENCES `dah_users` (`uid`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

/*Table structure for table `dah_users` */

DROP TABLE IF EXISTS `dah_users`;

CREATE TABLE `dah_users` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `fname` varchar(100) DEFAULT NULL,
  `lname` varchar(100) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `salt` varchar(150) DEFAULT NULL,
  `role` varchar(50) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `verify` varchar(150) DEFAULT NULL,
  `avatar` varchar(150) DEFAULT NULL,
  `added_on` int(11) DEFAULT NULL,
  `updated_on` int(11) DEFAULT NULL,
  `last_login` int(11) DEFAULT NULL,
  `login_ip` varchar(50) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=75 DEFAULT CHARSET=utf8;

/*Table structure for table `dah_workshop_enrollment` */

DROP TABLE IF EXISTS `dah_workshop_enrollment`;

CREATE TABLE `dah_workshop_enrollment` (
  `enid` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL,
  `wid` int(11) DEFAULT NULL,
  `certificate_status` enum('issued','notIssued','requested') DEFAULT 'notIssued',
  PRIMARY KEY (`enid`),
  KEY `FK_dah_workshop_enrollment` (`wid`),
  KEY `FK_dah_workshop_enrollment_uid` (`uid`),
  CONSTRAINT `FK_dah_workshop_enrollment` FOREIGN KEY (`wid`) REFERENCES `dah_workshops` (`wid`) ON DELETE CASCADE,
  CONSTRAINT `FK_dah_workshop_enrollment_uid` FOREIGN KEY (`uid`) REFERENCES `dah_users` (`uid`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

/*Table structure for table `dah_workshops` */

DROP TABLE IF EXISTS `dah_workshops`;

CREATE TABLE `dah_workshops` (
  `wid` int(11) NOT NULL AUTO_INCREMENT,
  `deptid` int(11) DEFAULT NULL,
  `workshop_title` varchar(250) DEFAULT NULL,
  `workshop_subtitle` varchar(250) DEFAULT NULL,
  `workshop_content` longtext,
  `workshop_venue` varchar(250) DEFAULT NULL,
  `workshop_image` varchar(50) DEFAULT NULL,
  `from_date` int(11) DEFAULT NULL,
  `to_date` int(11) DEFAULT NULL,
  `workshop_meta_title` varchar(250) DEFAULT NULL,
  `workshop_meta_keyword` varchar(250) DEFAULT NULL,
  `workshop_meta_description` varchar(250) DEFAULT NULL,
  `added_on` int(11) DEFAULT NULL,
  `updated_on` int(11) DEFAULT NULL,
  PRIMARY KEY (`wid`),
  KEY `FK_dah_workshops` (`deptid`),
  CONSTRAINT `FK_dah_workshops` FOREIGN KEY (`deptid`) REFERENCES `dah_departments` (`deptid`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
