-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Serveur: localhost
-- Généré le : Lun 09 Avril 2012 à 12:25
-- Version du serveur: 5.1.53
-- Version de PHP: 5.3.4

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Base de données: `tipkin`
--

--
-- Contenu de la table `announcement_states`
--

INSERT INTO `announcement_states` (`ID`, `NAME`) VALUES
(1, 'draft'),
(2, 'pending'),
(3, 'refused'),
(4, 'validated'),
(5, 'archived');

--
-- Contenu de la table `categories`
--

INSERT INTO `categories` (`ID`, `NAME`, `DESCRIPTION`, `PARENT_CATEGORY_ID`, `IS_ROOT`) VALUES
(1, 'Les Transports', '', NULL, 1),
(74, 'Les tenues', '', 9, 0),
(3, 'Maison', '', NULL, 1),
(4, 'Jardin/Brico', '', NULL, 1),
(5, 'Le coin des petits', '', NULL, 1),
(6, 'Loisirs et jeux', '', NULL, 1),
(7, 'Nos amis les bÃªtes', '', NULL, 1),
(8, 'Le dressing', '', NULL, 1),
(9, 'EvÃ¨nementiel', '', NULL, 1),
(10, 'Auto', '', 1, 0),
(11, 'Moto', '', 1, 0),
(12, 'Scooter', '', 1, 0),
(13, 'Bateau', '', 1, 0),
(14, 'Avion - Jet - HÃ©licoptÃ¨re', '', 1, 0),
(15, 'Camping-car', '', 1, 0),
(16, 'Remorque', '', 1, 0),
(17, 'Accessoires', '', 1, 0),
(19, 'ElectromÃ©nager', '', 3, 0),
(20, 'Mobilier', '', 3, 0),
(21, 'EsthÃ©tique', '', 3, 0),
(22, 'MatÃ©riel mÃ©dical', '', 3, 0),
(23, 'Photo', '', 3, 0),
(24, 'VidÃ©o - Audio', '', 3, 0),
(25, 'Console de jeux et accessoires', '', 3, 0),
(26, 'Informatique', '', 3, 0),
(28, 'Tondeuse - DÃ©broussailleuse', '', 4, 0),
(29, 'TronÃ§onneuse - Taille haie', '', 4, 0),
(30, 'Broyeur - Souffleur de feuille', '', 4, 0),
(31, 'Barbecue - Plancha', '', 4, 0),
(32, 'Outil Jardinier', '', 4, 0),
(33, 'Mobilier de jardin', '', 4, 0),
(34, 'BTP', '', 4, 0),
(35, 'Chauffage - Clim', '', 4, 0),
(36, 'Peinture et rÃ©novation', '', 4, 0),
(37, 'Nettoyage - Entretien', '', 4, 0),
(38, 'Outils de bricoleur', '', 4, 0),
(40, 'Petite puÃ©riculture', '', 5, 0),
(41, 'Poussette', '', 5, 0),
(42, 'Lit - Transat', '', 5, 0),
(43, 'Jouets', '', 5, 0),
(44, 'SiÃ¨ge auto - RÃ©hausseur', '', 5, 0),
(45, 'Parc', '', 5, 0),
(46, 'Chaise haute', '', 5, 0),
(47, 'Tout pour le bain', '', 5, 0),
(48, 'Balancelle - Trotteur', '', 5, 0),
(50, 'Tout pour camper', '', 6, 0),
(51, 'Ski et accessoires de montagne', '', 6, 0),
(52, 'Instrument de musique', '', 6, 0),
(53, 'Equitation', '', 6, 0),
(54, 'Jeux en plein air', '', 6, 0),
(55, 'Jeux de sociÃ©tÃ©', '', 6, 0),
(56, 'Le cyclisme et ses Ã©quipements', '', 6, 0),
(57, 'Chasse et pÃªche', '', 6, 0),
(58, 'Loisirs et nautiques', '', 6, 0),
(59, 'DÃ©guisement', '', 6, 0),
(61, 'La ferme', '', 7, 0),
(62, 'Les animaux de compagnie', '', 7, 0),
(63, 'Accessoires', '', 7, 0),
(65, 'Costumes Homme', '', 8, 0),
(66, 'Chaussures', '', 8, 0),
(67, 'Maroquineries et bagages', '', 8, 0),
(68, 'Accessoires', '', 8, 0),
(69, 'Bijoux et montres', '', 8, 0),
(70, 'VÃªtements femme', '', 8, 0),
(71, 'VÃªtements homme', '', 8, 0),
(72, 'VÃªtements enfants', '', 8, 0),
(75, 'Chapiteau - Tente de rÃ©ception', '', 9, 0),
(76, 'DÃ©coration', '', 9, 0),
(77, 'Art de la table - Cuisine', '', 9, 0),
(78, 'Mobilier de rÃ©ception', '', 9, 0),
(79, 'Location de salle', '', 9, 0),
(80, 'Tireuse &#224; biÃ¨re', '', 9, 0);

--
-- Contenu de la table `contact_groups`
--

INSERT INTO `contact_groups` (`ID`, `NAME`) VALUES
(1, 'Users'),
(2, 'Tippeurs'),
(3, 'Amis'),
(4, 'Famille'),
(5, 'Voisins');

--
-- Contenu de la table `departments`
--

INSERT INTO `departments` (`ID`, `REGION_ID`, `NAME`) VALUES
('01', '22', 'Ain'),
('02', '20', 'Aisne'),
('03', '3', 'Allier'),
('04', '18', 'Alpes de haute provence'),
('05', '18', 'Hautes alpes'),
('06', '18', 'Alpes maritimes'),
('07', '22', 'Ardèche'),
('08', '8', 'Ardennes'),
('09', '16', 'Ariège'),
('10', '8', 'Aube'),
('11', '13', 'Aude'),
('12', '16', 'Aveyron'),
('13', '18', 'Bouches du rhône'),
('14', '4', 'Calvados'),
('15', '3', 'Cantal'),
('16', '21', 'Charente'),
('17', '21', 'Charente maritime'),
('18', '7', 'Cher'),
('19', '14', 'Corrèze'),
('20a', '9', 'Corse du Sud'),
('20b', '9', 'Haute Corse'),
('21', '5', 'Côte d''or'),
('22', '6', 'Côtes d''Armor'),
('23', '14', 'Creuse'),
('24', '2', 'Dordogne'),
('25', '10', 'Doubs'),
('26', '22', 'Drôme'),
('27', '11', 'Eure'),
('28', '7', 'Eure et Loir'),
('29', '6', 'Finistère'),
('30', '13', 'Gard'),
('31', '16', 'Haute garonne'),
('32', '16', 'Gers'),
('33', '2', 'Gironde'),
('34', '13', 'Hérault'),
('35', '6', 'Ile et Vilaine'),
('36', '7', 'Indre'),
('37', '7', 'Indre et Loire'),
('38', '22', 'Isère'),
('39', '10', 'Jura'),
('40', '2', 'Landes'),
('41', '7', 'Loir et Cher'),
('42', '22', 'Loire'),
('43', '3', 'Haute loire'),
('44', '19', 'Loire Atlantique'),
('45', '7', 'Loiret'),
('46', '16', 'Lot'),
('47', '2', 'Lot et Garonne'),
('48', '13', 'Lozère'),
('49', '19', 'Maine et Loire'),
('50', '4', 'Manche'),
('51', '8', 'Marne'),
('52', '8', 'Haute Marne'),
('53', '19', 'Mayenne'),
('54', '15', 'Meurthe et Moselle'),
('55', '15', 'Meuse'),
('56', '6', 'Morbihan'),
('57', '15', 'Moselle'),
('58', '5', 'Nièvre'),
('59', '17', 'Nord'),
('60', '20', 'Oise'),
('61', '4', 'Orne'),
('62', '17', 'Pas de Calais'),
('63', '3', 'Puy de Dôme'),
('64', '2', 'Pyrénées Atlantiques'),
('65', '16', 'Hautes Pyrénées'),
('66', '13', 'Pyrénées Orientales'),
('67', '1', 'Bas Rhin'),
('68', '1', 'Haut Rhin'),
('69', '22', 'Rhône'),
('70', '10', 'Haute Saône'),
('71', '5', 'Saône et Loire'),
('72', '19', 'Sarthe'),
('73', '22', 'Savoie'),
('74', '22', 'Haute Savoie'),
('75', '12', 'Paris'),
('76', '11', 'Seine Maritime'),
('77', '12', 'Seine et Marne'),
('78', '12', 'Yvelines'),
('79', '21', 'Deux Sèvres'),
('80', '20', 'Somme'),
('81', '16', 'Tarn'),
('82', '16', 'Tarn et Garonne'),
('83', '18', 'Var'),
('84', '18', 'Vaucluse'),
('85', '19', 'Vendée'),
('86', '21', 'Vienne'),
('87', '14', 'Haute Vienne'),
('88', '15', 'Vosge'),
('89', '5', 'Yonne'),
('90', '10', 'Territoire de Belfort'),
('91', '12', 'Essonne'),
('92', '12', 'Haut de seine'),
('93', '12', 'Seine Saint Denis'),
('94', '12', 'Val de Marne'),
('95', '12', 'Val d''Oise');

--
-- Contenu de la table `regions`
--

INSERT INTO `regions` (`ID`, `NAME`) VALUES
('1', 'Alsace'),
('2', 'Aquitaine'),
('3', 'Auvergne'),
('4', 'Basse Normandie'),
('5', 'Bourgogne'),
('6', 'Bretagne'),
('7', 'Centre'),
('8', 'Champagne Ardenne'),
('9', 'Corse'),
('10', 'Franche Comte'),
('11', 'Haute Normandie'),
('12', 'Ile de France'),
('13', 'Languedoc Roussillon'),
('14', 'Limousin'),
('15', 'Lorraine'),
('16', 'Midi-Pyrénées'),
('17', 'Nord Pas de Calais'),
('18', 'Provence Alpes Côte d''Azur'),
('19', 'Pays de la Loire'),
('20', 'Picardie'),
('21', 'Poitou Charente'),
('22', 'Rhone Alpes');

--
-- Contenu de la table `user_roles`
--

INSERT INTO `user_roles` (`ID`, `NAME`) VALUES
(4, 'Administrateur'),
(3, 'Membre Pro'),
(2, 'Membre'),
(1, 'Visiteur'),
(5, 'SuperAdmin');
