-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Serveur: localhost
-- Généré le : Lun 09 Avril 2012 à 12:24
-- Version du serveur: 5.1.53
-- Version de PHP: 5.3.4

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Base de données: `tipkin`
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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=40 ;

-- --------------------------------------------------------

--
-- Structure de la table `announcements`
--

CREATE TABLE IF NOT EXISTS `announcements` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `TITLE` varchar(255) NOT NULL,
  `DESCRIPTION` text,
  `PRICE_PUBLIC` float DEFAULT NULL,
  `IS_FULL_DAY_PRICE` tinyint(1) NOT NULL,
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
  `REGION_ID` varchar(3) NOT NULL,
  `DEPARTMENT_ID` varchar(3) NOT NULL,
  `PUBLICATION_DATE` date DEFAULT NULL,
  `IS_PUBLISHED` tinyint(1) NOT NULL,
  `END_PUBLICATION_DATE` date NOT NULL,
  `CATEGORY_ID` int(11) NOT NULL,
  `SUB_CATEGORY_ID` int(11) NOT NULL,
  `USER_ID` int(11) NOT NULL,
  `STATE_ID` int(11) NOT NULL,
  `ADMIN_COMMENT` text,
  `REF_ANNOUNCEMENT_ID` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16 ;

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Structure de la table `announcement_prices`
--

CREATE TABLE IF NOT EXISTS `announcement_prices` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `HALF_DAY` float DEFAULT NULL,
  `DAY` float DEFAULT NULL,
  `WEEK` float DEFAULT NULL,
  `FORTNIGHT` float DEFAULT NULL,
  `IS_ACTIVE` tinyint(1) NOT NULL,
  `ANNOUNCEMENT_ID` int(11) DEFAULT NULL,
  `CONTACT_GROUP_ID` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=77 ;

-- --------------------------------------------------------

--
-- Structure de la table `announcement_reservations`
--

CREATE TABLE IF NOT EXISTS `announcement_reservations` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `DATE` date NOT NULL,
  `DATE_OPTION` varchar(255) NOT NULL,
  `ANNOUNCEMENT_ID` int(11) NOT NULL,
  `USER_OWNER_ID` int(11) NOT NULL,
  `USER_SUBSCRIBER_ID` int(11) NOT NULL,
  `CONTACT_GROUP_ID` int(11) NOT NULL,
  `PRICE` float NOT NULL,
  `STATE_ID` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- Structure de la table `announcement_states`
--

CREATE TABLE IF NOT EXISTS `announcement_states` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `NAME` varchar(255) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16 ;

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=198 ;

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=81 ;

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Structure de la table `contact_groups`
--

CREATE TABLE IF NOT EXISTS `contact_groups` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `NAME` varchar(255) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=46 ;

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

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
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

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
  `IS_MAIL_VERIFIED` tinyint(1) NOT NULL,
  `ACTIVATION_KEY` varchar(255) NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `USERNAME` (`USERNAME`),
  UNIQUE KEY `MAIL` (`MAIL`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=29 ;

-- --------------------------------------------------------

--
-- Structure de la table `user_roles`
--

CREATE TABLE IF NOT EXISTS `user_roles` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `NAME` varchar(255) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;
