-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Serveur: localhost
-- Généré le : Mer 14 Mars 2012 à 08:58
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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=35 ;

-- --------------------------------------------------------

--
-- Structure de la table `profiles`
--

CREATE TABLE IF NOT EXISTS `profiles` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `GENDER` varchar(255) NOT NULL,
  `LASTNAME` varchar(255) NOT NULL,
  `FIRSTNAME` varchar(255) NOT NULL,
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
-- Structure de la table `profiles_pro`
--

CREATE TABLE IF NOT EXISTS `profiles_pro` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `COMPANY_NAME` varchar(255) NOT NULL,
  `LASTNAME` varchar(255) NOT NULL,
  `FIRSTNAME` varchar(255) NOT NULL,
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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=28 ;

-- --------------------------------------------------------

--
-- Structure de la table `user_roles`
--

CREATE TABLE IF NOT EXISTS `user_roles` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `NAME` varchar(255) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;
