-- phpMyAdmin SQL Dump
-- version 3.2.3
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 08, 2014 at 12:30 AM
-- Server version: 5.1.40
-- PHP Version: 5.3.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `remont`
--

-- --------------------------------------------------------

--
-- Table structure for table `porjects`
--

CREATE TABLE IF NOT EXISTS `porjects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `category_id` smallint(5) unsigned NOT NULL,
  `client_id` int(11) NOT NULL,
  `status` tinyint(2) NOT NULL,
  `city_id` smallint(6) NOT NULL,
  `price` smallint(5) unsigned NOT NULL,
  `currency` int(11) NOT NULL,
  `payment_method` tinyint(1) NOT NULL,
  `deadline` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `show_comments` tinyint(1) NOT NULL,
  `enable_comments` tinyint(1) NOT NULL,
  `rate` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `porjects`
--


-- --------------------------------------------------------

--
-- Table structure for table `profiles`
--

CREATE TABLE IF NOT EXISTS `profiles` (
  `user_id` int(11) NOT NULL,
  `first_name` varchar(20) NOT NULL,
  `last_name` varchar(20) NOT NULL,
  `patronymic` varchar(20) NOT NULL,
  `gener` enum('male','female') NOT NULL,
  `birth` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `phone` varchar(13) NOT NULL,
  `city_id` int(11) NOT NULL,
  `photo` varchar(50) NOT NULL,
  `status` int(11) NOT NULL,
  `rate` int(11) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

--
-- Dumping data for table `profiles`
--


-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(20) DEFAULT NULL,
  `pass` varchar(50) NOT NULL,
  `email` varchar(40) NOT NULL,
  `reg_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_visit` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `active` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=21 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `login`, `pass`, `email`, `reg_date`, `last_visit`, `active`) VALUES
(1, 'anton', '', 'tov.ua@ukr.net', '2013-12-29 17:56:25', '2013-12-29 17:56:29', 1),
(18, NULL, 'cf30973c653e417a0b60cfe9c2f9ddc5', 'john@ukr.net', '2013-12-30 20:36:29', '0000-00-00 00:00:00', 0),
(20, NULL, '8b30bea2858345bf428111b57dbb6256', 'ato@ddb.com.ua', '2013-12-30 22:43:52', '0000-00-00 00:00:00', 0);
