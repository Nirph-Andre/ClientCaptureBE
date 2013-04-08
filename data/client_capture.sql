-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 08, 2013 at 07:59 PM
-- Server version: 5.1.63-community
-- PHP Version: 5.4.0-ZS5.6.0

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `client_capture`
--

-- --------------------------------------------------------

--
-- Table structure for table `app_audit_log`
--

DROP TABLE IF EXISTS `app_audit_log`;
CREATE TABLE IF NOT EXISTS `app_audit_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_context` varchar(30) NOT NULL,
  `customer_id` int(10) unsigned NOT NULL,
  `action` enum('Add','Update','Delete') DEFAULT NULL,
  `table_name` varchar(50) NOT NULL,
  `record_id` int(10) unsigned NOT NULL,
  `data_packet` mediumtext,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `app_link_request`
--

DROP TABLE IF EXISTS `app_link_request`;
CREATE TABLE IF NOT EXISTS `app_link_request` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `profile_id` int(10) unsigned NOT NULL,
  `device_os` varchar(100) NOT NULL,
  `device_cpu` varchar(100) NOT NULL,
  `ip_address` varchar(23) NOT NULL,
  `is_blacklisted` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `is_disabled` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `status` enum('Requested','Active','Disabled','BlackListed','Archived') NOT NULL DEFAULT 'Requested',
  `created` datetime NOT NULL,
  `updated` datetime DEFAULT NULL,
  `archived` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `asset`
--

DROP TABLE IF EXISTS `asset`;
CREATE TABLE IF NOT EXISTS `asset` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(10) unsigned NOT NULL,
  `name` varchar(250) DEFAULT NULL,
  `surname` varchar(250) DEFAULT NULL,
  `email` varchar(250) DEFAULT NULL,
  `cell` varchar(20) DEFAULT NULL,
  `created` datetime NOT NULL,
  `updated` datetime DEFAULT NULL,
  `archived` tinyint(3) unsigned DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `config`
--

DROP TABLE IF EXISTS `config`;
CREATE TABLE IF NOT EXISTS `config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `country_id` int(10) unsigned NOT NULL,
  `date_format` varchar(20) NOT NULL,
  `time_format` varchar(20) NOT NULL,
  `lib_currency_id` int(10) unsigned NOT NULL DEFAULT '1',
  `currency_prefix` varchar(5) NOT NULL,
  `vat_percentage` decimal(5,2) NOT NULL DEFAULT '0.00',
  `notification_source_email` varchar(255) NOT NULL,
  `notification_source_number` varchar(20) NOT NULL,
  `administrative_email` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `config`
--

INSERT INTO `config` (`id`, `country_id`, `date_format`, `time_format`, `lib_currency_id`, `currency_prefix`, `vat_percentage`, `notification_source_email`, `notification_source_number`, `administrative_email`) VALUES
(1, 1, 'Y-m-d', 'H:i:s', 1, 'R', '14.00', 'no-reply@nirph.com', '27844801653', 'andre@nirph.com');

-- --------------------------------------------------------

--
-- Table structure for table `contact_request`
--

DROP TABLE IF EXISTS `contact_request`;
CREATE TABLE IF NOT EXISTS `contact_request` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `person_name` varchar(100) NOT NULL,
  `trading_name` varchar(100) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `mobile` varchar(20) DEFAULT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `subject` varchar(100) DEFAULT NULL,
  `message` text,
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

DROP TABLE IF EXISTS `item`;
CREATE TABLE IF NOT EXISTS `item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime DEFAULT NULL,
  `archived` tinyint(3) unsigned DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `item`
--

INSERT INTO `item` (`id`, `name`, `created`, `updated`, `archived`) VALUES
(1, 'Item A', '2013-03-30 08:15:00', NULL, 0),
(2, 'Item B', '2013-03-30 08:15:00', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `lib_authentication_log`
--

DROP TABLE IF EXISTS `lib_authentication_log`;
CREATE TABLE IF NOT EXISTS `lib_authentication_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(50) NOT NULL,
  `profile_id` int(10) unsigned DEFAULT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `lib_notification_log`
--

DROP TABLE IF EXISTS `lib_notification_log`;
CREATE TABLE IF NOT EXISTS `lib_notification_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email_to` varchar(250) DEFAULT NULL,
  `email_subject` varchar(250) DEFAULT NULL,
  `email_body` text,
  `sms_to` varchar(20) DEFAULT NULL,
  `sms_body` text,
  `api_msg_id` varchar(32) DEFAULT NULL,
  `sms_status` varchar(50) DEFAULT NULL,
  `created` datetime NOT NULL,
  `updated` datetime DEFAULT NULL,
  `archived` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `meta_table`
--

DROP TABLE IF EXISTS `meta_table`;
CREATE TABLE IF NOT EXISTS `meta_table` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(75) NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime DEFAULT NULL,
  `hash` varchar(32) NOT NULL,
  `version` smallint(6) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`,`hash`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `meta_table`
--

INSERT INTO `meta_table` (`id`, `name`, `created`, `updated`, `hash`, `version`) VALUES
(1, 'item', '2013-04-08 00:00:00', '2013-04-08 00:00:00', '6dd983af97e1cee4f4d350d8c8a8e280', 2),
(2, 'asset', '2013-04-08 00:00:00', NULL, '00f548a4b7938d2cfe7ae9c6ec5783d6', 1),
(3, 'profile', '2013-04-08 00:00:00', '2013-04-08 00:00:00', '74f6870f6740f14743f1648e51d26b70', 2),
(4, 'contact_request', '2013-04-08 00:00:00', NULL, 'c6281d8d77e2fb13fd1b1b908e69fce7', 1),
(5, 'app_audit_log', '2013-04-08 00:00:00', NULL, 'f0ad8d2f5234d9e55ce6860f3e7d443c', 1),
(6, 'app_link_request', '2013-04-08 00:00:00', NULL, 'f43bdcb651541b245f2655558fb3d18d', 1),
(7, 'config', '2013-04-08 00:00:00', '2013-04-08 00:00:00', 'e03531a6324decfbb1141c4f2892a0c5', 2),
(8, 'lib_authentication_log', '2013-04-08 00:00:00', NULL, 'd656bfcd19186809dded6a3be9d5844e', 1),
(9, 'lib_notification_log', '2013-04-08 00:00:00', NULL, 'ed4aa2a527e0e94a55b9023ae3ef9825', 1);

-- --------------------------------------------------------

--
-- Table structure for table `profile`
--

DROP TABLE IF EXISTS `profile`;
CREATE TABLE IF NOT EXISTS `profile` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(100) NOT NULL,
  `family_name` varchar(100) NOT NULL,
  `id_number` varchar(13) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `mobile` varchar(20) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `username` varchar(40) NOT NULL,
  `password` varchar(40) NOT NULL,
  `password_salt` varchar(40) NOT NULL,
  `user_type` enum('User','Administrator') DEFAULT NULL,
  `status` enum('Active','Suspended') DEFAULT 'Active',
  `subscribe_newsletter` tinyint(3) unsigned DEFAULT '1',
  `subscribe_reminders` tinyint(3) unsigned DEFAULT '1',
  `last_login` datetime DEFAULT NULL,
  `created` datetime NOT NULL,
  `archived` tinyint(3) unsigned DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `profile`
--

INSERT INTO `profile` (`id`, `first_name`, `family_name`, `id_number`, `date_of_birth`, `mobile`, `email`, `username`, `password`, `password_salt`, `user_type`, `status`, `subscribe_newsletter`, `subscribe_reminders`, `last_login`, `created`, `archived`) VALUES
(1, 'Greggory', 'Simmons', '7711225544000', '1977-11-22', '0840840840', 'greg@simmons-cars.co.za', 'greg', '787ca4d10a31f0e66d4d792a0a8c3975dd61c697', '8deba4865385c926b093b676ee39da57853a5c22', 'User', 'Active', 1, 1, '2012-09-24 12:00:00', '2012-09-24 12:00:00', 0),
(2, 'Jack', 'Vredenweld', '7202029977333', '1972-02-02', '0820820820', 'jack.v@jackscars.com', 'jack', '787ca4d10a31f0e66d4d792a0a8c3975dd61c697', '8deba4865385c926b093b676ee39da57853a5c22', 'User', 'Active', 1, 1, '2012-09-24 12:00:00', '2012-09-24 12:00:00', 0),
(3, 'Shannon', 'Bramson', '7703155544111', '1977-03-15', '0830830830', 'shannon@simmons-cars.co.za', 'shannon', '787ca4d10a31f0e66d4d792a0a8c3975dd61c697', '8deba4865385c926b093b676ee39da57853a5c22', 'Administrator', 'Active', 1, 1, '2012-09-24 12:00:00', '2012-09-24 12:00:00', 0),
(4, 'Lilith', 'Warchild', '7703155544111', '1977-03-15', '0730730730', 'lilith@rebel-traders.co.za', 'lilith', '787ca4d10a31f0e66d4d792a0a8c3975dd61c697', '8deba4865385c926b093b676ee39da57853a5c22', 'User', 'Active', 1, 1, '2012-09-24 12:00:00', '2012-09-24 12:00:00', 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
