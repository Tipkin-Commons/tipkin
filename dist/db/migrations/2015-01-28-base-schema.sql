-- phpMyAdmin SQL Dump
-- version 4.0.9
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Mer 28 Janvier 2015 à 13:26
-- Version du serveur: 5.5.41-0ubuntu0.14.04.1
-- Version de PHP: 5.5.9-1ubuntu4.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `tipkin_beta`
--

-- --------------------------------------------------------

--
-- Structure de la table `addresses`
--

CREATE TABLE IF NOT EXISTS `addresses` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `TITLE` varchar(255) NOT NULL,
  `ADDRESS_1` varchar(255) NOT NULL,
  `ADDRESS_2` varchar(255) DEFAULT NULL,
  `ZIP_CODE` varchar(10) NOT NULL,
  `CITY` varchar(255) NOT NULL,
  `COUNTRY` varchar(255) NOT NULL,
  `USER_ID` int(10) unsigned NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1049 ;

-- --------------------------------------------------------

--
-- Structure de la table `alternate_currency`
--

CREATE TABLE IF NOT EXISTS `alternate_currency` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `NAME` varchar(255) NOT NULL,
  `IMAGE_URL` text NOT NULL,
  `RATE` float NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Structure de la table `alternate_currency_postal_codes`
--

CREATE TABLE IF NOT EXISTS `alternate_currency_postal_codes` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `POSTAL_CODE` varchar(6) NOT NULL,
  `ALTERNATE_CURRENCY_ID` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=43 ;

-- --------------------------------------------------------

--
-- Structure de la table `announcements`
--

CREATE TABLE IF NOT EXISTS `announcements` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `TITLE` varchar(255) NOT NULL,
  `DESCRIPTION` text,
  `PRICE_PUBLIC` float DEFAULT NULL,
  `IS_FULL_DAY_PRICE` tinyint(1) DEFAULT NULL,
  `CAUTION` float DEFAULT NULL,
  `PHOTO_MAIN` varchar(255) DEFAULT NULL,
  `PHOTO_OPTION_1` varchar(255) DEFAULT NULL,
  `PHOTO_OPTION_2` varchar(255) DEFAULT NULL,
  `TIPS` text,
  `RAW_MATERIAL` text,
  `ADDRESS_1` varchar(255) DEFAULT NULL,
  `ADDRESS_2` varchar(255) DEFAULT NULL,
  `ZIP_CODE` varchar(5) DEFAULT NULL,
  `CITY` varchar(255) DEFAULT NULL,
  `COUNTRY` varchar(255) DEFAULT NULL,
  `REGION_ID` varchar(3) DEFAULT NULL,
  `DEPARTMENT_ID` varchar(3) DEFAULT NULL,
  `PUBLICATION_DATE` date DEFAULT NULL,
  `IS_PUBLISHED` tinyint(1) DEFAULT NULL,
  `END_PUBLICATION_DATE` date DEFAULT NULL,
  `CATEGORY_ID` int(11) NOT NULL,
  `SUB_CATEGORY_ID` int(11) NOT NULL,
  `USER_ID` int(11) NOT NULL,
  `STATE_ID` int(11) NOT NULL,
  `ADMIN_COMMENT` text,
  `REF_ANNOUNCEMENT_ID` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1165 ;

-- --------------------------------------------------------

--
-- Structure de la table `announcements_pro`
--

CREATE TABLE IF NOT EXISTS `announcements_pro` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `TITLE` varchar(255) NOT NULL,
  `DESCRIPTION` text,
  `PRICE_PUBLIC` float DEFAULT NULL,
  `PHOTO_MAIN` varchar(255) DEFAULT NULL,
  `PHOTO_OPTION_1` varchar(255) DEFAULT NULL,
  `PHOTO_OPTION_2` varchar(255) DEFAULT NULL,
  `TIPS` text,
  `RAW_MATERIAL` text,
  `ADDRESS_1` varchar(255) DEFAULT NULL,
  `ADDRESS_2` varchar(255) DEFAULT NULL,
  `ZIP_CODE` varchar(5) DEFAULT NULL,
  `CITY` varchar(255) DEFAULT NULL,
  `COUNTRY` varchar(255) DEFAULT NULL,
  `REGION_ID` varchar(3) NOT NULL,
  `DEPARTMENT_ID` varchar(3) NOT NULL,
  `IS_PUBLISHED` tinyint(1) NOT NULL,
  `PUBLICATION_DATE` date DEFAULT NULL,
  `CATEGORY_ID` int(11) NOT NULL,
  `SUB_CATEGORY_ID` int(11) NOT NULL,
  `USER_ID` int(11) NOT NULL,
  `STATE_ID` int(11) NOT NULL,
  `ADMIN_COMMENT` text,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=26 ;

-- --------------------------------------------------------

--
-- Structure de la table `announcement_prices`
--

CREATE TABLE IF NOT EXISTS `announcement_prices` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `HALF_DAY` float DEFAULT NULL,
  `DAY` float DEFAULT NULL,
  `WEEK` float DEFAULT NULL,
  `WEEK_END` float DEFAULT NULL,
  `FORTNIGHT` float DEFAULT NULL,
  `IS_ACTIVE` tinyint(1) NOT NULL,
  `ANNOUNCEMENT_ID` int(11) DEFAULT NULL,
  `CONTACT_GROUP_ID` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8551 ;

-- --------------------------------------------------------

--
-- Structure de la table `announcement_reservations`
--

CREATE TABLE IF NOT EXISTS `announcement_reservations` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `DATE` date NOT NULL,
  `DATE_END` date DEFAULT NULL,
  `DATE_OPTION` varchar(255) NOT NULL,
  `ANNOUNCEMENT_ID` int(11) NOT NULL,
  `USER_OWNER_ID` int(11) NOT NULL,
  `USER_SUBSCRIBER_ID` int(11) NOT NULL,
  `CONTACT_GROUP_ID` int(11) NOT NULL,
  `PRICE` float NOT NULL,
  `STATE_ID` int(11) NOT NULL,
  `KEY_CHECK` varchar(255) DEFAULT NULL,
  `TRANSACTION_REF` varchar(255) DEFAULT NULL,
  `CREATED_TIME` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `UPDATED_TIME` timestamp NULL DEFAULT NULL,
  `ADMIN_PROCEED` tinyint(1) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=248 ;

-- --------------------------------------------------------

--
-- Structure de la table `announcement_states`
--

CREATE TABLE IF NOT EXISTS `announcement_states` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `NAME` varchar(255) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Structure de la table `announcement_unavailabilities`
--

CREATE TABLE IF NOT EXISTS `announcement_unavailabilities` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `DATE` date NOT NULL,
  `DATE_OPTION` varchar(255) NOT NULL,
  `ANNOUNCEMENT_ID` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2465 ;

-- --------------------------------------------------------

--
-- Structure de la table `carrousels`
--

CREATE TABLE IF NOT EXISTS `carrousels` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ANNOUNCE_ID` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=126 ;

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `NAME` varchar(255) NOT NULL,
  `DESCRIPTION` varchar(255) NOT NULL,
  `PARENT_CATEGORY_ID` int(11) DEFAULT NULL,
  `IS_ROOT` tinyint(1) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=89 ;

-- --------------------------------------------------------

--
-- Structure de la table `contacts`
--

CREATE TABLE IF NOT EXISTS `contacts` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `USER_ID_1` int(11) NOT NULL,
  `USER_ID_2` int(11) NOT NULL,
  `CONTACT_GROUP_ID` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=102 ;

-- --------------------------------------------------------

--
-- Structure de la table `contact_groups`
--

CREATE TABLE IF NOT EXISTS `contact_groups` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `NAME` varchar(255) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Structure de la table `contact_requests`
--

CREATE TABLE IF NOT EXISTS `contact_requests` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `USER_ID_TO` int(11) NOT NULL,
  `USER_ID_FROM` int(11) NOT NULL,
  `CONTACT_GROUP_ID` int(11) NOT NULL,
  `TIME` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=200 ;

-- --------------------------------------------------------

--
-- Structure de la table `departments`
--

CREATE TABLE IF NOT EXISTS `departments` (
  `ID` varchar(3) NOT NULL,
  `REGION_ID` varchar(2) NOT NULL,
  `NAME` char(32) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `feedbacks`
--

CREATE TABLE IF NOT EXISTS `feedbacks` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ANNOUNCE_ID` int(11) NOT NULL,
  `USER_OWNER_ID` int(11) NOT NULL,
  `USER_SUBSCRIBER_ID` int(11) NOT NULL,
  `USER_AUTHOR_ID` int(11) NOT NULL,
  `CREATION_DATE` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `RESERVATION_ID` int(11) NOT NULL,
  `MARK` int(11) NOT NULL,
  `COMMENT` text NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=64 ;

-- --------------------------------------------------------

--
-- Structure de la table `feedback_requests`
--

CREATE TABLE IF NOT EXISTS `feedback_requests` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ANNOUNCE_ID` int(11) NOT NULL,
  `USER_OWNER_ID` int(11) NOT NULL,
  `USER_SUBSCRIBER_ID` int(11) NOT NULL,
  `USER_AUTHOR_ID` int(11) NOT NULL,
  `CREATION_DATE` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `RESERVATION_ID` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=97 ;

-- --------------------------------------------------------

--
-- Structure de la table `log`
--

CREATE TABLE IF NOT EXISTS `log` (
  `id` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `content` text NOT NULL,
  `script` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `moderates`
--

CREATE TABLE IF NOT EXISTS `moderates` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `USER_AUTHOR_ID` int(11) NOT NULL,
  `TYPE` int(11) NOT NULL,
  `TYPE_ID` int(11) NOT NULL,
  `MESSAGE` text NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `profiles`
--

CREATE TABLE IF NOT EXISTS `profiles` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `GENDER` varchar(255) NOT NULL,
  `LASTNAME` varchar(255) NOT NULL,
  `FIRSTNAME` varchar(255) NOT NULL,
  `DESCRIPTION` text NOT NULL,
  `PHONE` varchar(15) NOT NULL,
  `MOBILE_PHONE` varchar(15) DEFAULT NULL,
  `OFFICE_PHONE` varchar(15) DEFAULT NULL,
  `WEBSITE` varchar(255) DEFAULT NULL,
  `AVATAR` varchar(255) NOT NULL,
  `USER_ID` int(10) unsigned NOT NULL,
  `MAIN_ADDRESS_ID` int(10) unsigned NOT NULL,
  `ALTERNATE_CURRENCIES_USED` text,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=917 ;

-- --------------------------------------------------------

--
-- Structure de la table `profiles_pro`
--

CREATE TABLE IF NOT EXISTS `profiles_pro` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `COMPANY_NAME` varchar(255) NOT NULL,
  `LASTNAME` varchar(255) NOT NULL,
  `FIRSTNAME` varchar(255) NOT NULL,
  `DESCRIPTION` text NOT NULL,
  `PHONE` varchar(15) NOT NULL,
  `MOBILE_PHONE` varchar(15) DEFAULT NULL,
  `OFFICE_PHONE` varchar(15) DEFAULT NULL,
  `WEBSITE` varchar(255) DEFAULT NULL,
  `AVATAR` varchar(255) NOT NULL,
  `USER_ID` int(10) unsigned NOT NULL,
  `MAIN_ADDRESS_ID` int(10) unsigned NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

-- --------------------------------------------------------

--
-- Structure de la table `regions`
--

CREATE TABLE IF NOT EXISTS `regions` (
  `ID` varchar(2) NOT NULL,
  `NAME` varchar(255) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `USERNAME` varchar(255) NOT NULL,
  `PASSWORD` varchar(255) NOT NULL,
  `MAIL` varchar(255) NOT NULL,
  `ROLE_ID` int(10) unsigned NOT NULL,
  `IS_ACTIVE` tinyint(1) NOT NULL,
  `CREATED_TIME` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `IS_MAIL_VERIFIED` tinyint(1) NOT NULL,
  `ACTIVATION_KEY` varchar(255) NOT NULL,
  `MAILINGSTATE` tinyint(1) NOT NULL COMMENT '1= inscrit, 0 = non inscrit',
  PRIMARY KEY (`ID`),
  UNIQUE KEY `USERNAME` (`USERNAME`),
  UNIQUE KEY `MAIL` (`MAIL`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1421 ;

-- --------------------------------------------------------

--
-- Structure de la table `user_roles`
--

CREATE TABLE IF NOT EXISTS `user_roles` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `NAME` varchar(255) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Structure de la table `website_opinions`
--

CREATE TABLE IF NOT EXISTS `website_opinions` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `USERNAME` varchar(255) NOT NULL,
  `COMMENT` text NOT NULL,
  `CREATION_DATE` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `IS_PUBLISHED` tinyint(1) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
