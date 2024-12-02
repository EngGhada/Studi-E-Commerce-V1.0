-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 02, 2024 at 10:12 PM
-- Server version: 10.1.26-MariaDB
-- PHP Version: 7.1.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `e-commercedb`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cart_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT '1',
  `date_added` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `ID` int(11) NOT NULL,
  `Name` varchar(225) NOT NULL,
  `Description` text NOT NULL,
  `Parent` int(11) NOT NULL,
  `Ordering` int(11) DEFAULT NULL,
  `Visibility` tinyint(4) NOT NULL DEFAULT '0',
  `Allow_Comment` tinyint(4) NOT NULL DEFAULT '0',
  `Allow_Ads` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`ID`, `Name`, `Description`, `Parent`, `Ordering`, `Visibility`, `Allow_Comment`, `Allow_Ads`) VALUES
(1, 'Électronique', 'Appareils électroniques et gadgets.', 0, 1, 0, 1, 1),
(2, 'Vêtements ', 'La catégorie Vêtements propose des articles stylés et tendances, alliant confort, élégance et originalité pour toutes les occasions et pour hommes, femmes et enfants', 0, 2, 0, 1, 1),
(3, 'Maison', 'Produits pour la maison et le jardin.', 0, 3, 0, 0, 1),
(4, 'Téléphones', 'La catégorie Téléphones Mobiles offre une sélection de smartphones et de téléphones portables modernes, alliant technologie de pointe et praticité pour rester connecté à tout moment.', 0, 1, 0, 1, 1),
(9, 'Fait Main', 'La catégorie Articles faits main met en valeur des créations uniques et artisanales, réalisées avec soin et passion pour apporter une touche d\'authenticité et de charme à votre quotidien', 0, 2, 0, 0, 0),
(10, 'Ordinateurs', 'La catégorie Ordinateurs regroupe une large gamme de dispositifs informatiques modernes, performants et adaptés à tous vos besoins, qu\'ils soient personnels ou professionnels.', 0, 4, 0, 0, 0),
(13, 'Outils', 'La catégorie Outils propose une gamme complète d\'outils de qualité, adaptés aux travaux domestiques, professionnels ou de bricolage, pour vous accompagner dans tous vos projets.', 0, 6, 0, 0, 0),
(15, 'Blackberry', 'Blackberry Phones', 11, 2, 0, 0, 0),
(16, 'Marteaux', 'La sous-catégorie Marteaux propose une sélection d\'outils robustes et fiables, conçus pour répondre aux besoins des travaux manuels et professionnels.', 13, 4, 0, 1, 0),
(17, 'Boîtes', 'La sous-catégorie Boîtes rassemble des créations artisanales uniques, idéales pour le rangement ou la décoration, alliant fonctionnalité et esthétique.', 9, 3, 0, 0, 0),
(18, 'Nokia', 'Nokia Phones', 11, 3, 0, 0, 0),
(19, ' Jeux et Jouets ', '', 0, 4, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `c_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `comment_date` date NOT NULL,
  `item_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`c_id`, `comment`, `status`, `comment_date`, `item_id`, `user_id`) VALUES
(1, 'Produit de très bonne qualité.', 1, '2024-11-09', 1, 1),
(3, 'Pas satisfait de la qualité.', 0, '2024-11-11', 3, 3),
(4, 'Le produit correspond à la description.', 1, '2024-11-12', 4, 1),
(8, 'a good quality with a good price', 0, '2024-06-15', 12, 7),
(20, 'Excellente qualité sonore, parfait pour mes podcasts !', 1, '2024-10-24', 13, 8),
(63, 'very beautiful article ,well made and designed.', 0, '2024-10-25', 17, 4),
(67, 'it\'s a good item ,the price  is good also ,happy to buy and deal with you ', 0, '2024-10-26', 12, 4);

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `Item_ID` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Description` text NOT NULL,
  `Price` varchar(255) NOT NULL,
  `Add_Date` date NOT NULL,
  `Country_Made` varchar(255) NOT NULL,
  `Image` varchar(255) NOT NULL,
  `Status` varchar(255) CHARACTER SET latin1 NOT NULL,
  `Rating` smallint(6) NOT NULL,
  `Approve` tinyint(4) NOT NULL DEFAULT '0',
  `Cat_ID` int(11) NOT NULL,
  `Member_ID` int(11) NOT NULL,
  `Tags` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`Item_ID`, `Name`, `Description`, `Price`, `Add_Date`, `Country_Made`, `Image`, `Status`, `Rating`, `Approve`, `Cat_ID`, `Member_ID`, `Tags`) VALUES
(1, 'Téléviseur Samsung', 'Téléviseur haute définition avec écran plat.', '500', '2024-11-04', 'France', '54640_Téléviseur-Samsung.png', '1', 4, 1, 1, 1, 'HD, TV, Samsung'),
(3, 'Canapé 3 places', 'Canapé confortable pour le salon.', '300', '2024-11-06', 'Italie', '38836_Canapé-3-places.jpg', '2', 4, 1, 3, 3, 'Salon,Canapé,Maison'),
(4, 'iPhone 13 Pro Max', 'Le iPhone 13 Pro Max offre une expérience exceptionnelle avec son écran Super Retina XDR, son système de triple caméra avancé, et ses performances inégalées grâce à la puce A15 Bionic. Design élégant, autonomie impressionnante et capacités 5G pour rester connecté partout.', '1200', '2024-11-07', 'États-Unis', '65511_iPhone-13-pro-Max.jpg', '4', 5, 1, 4, 4, 'Apple,iPhone,Téléphone'),
(12, 'Haut-parleur Bluetooth', 'Un haut-parleur Bluetooth portable avec un son puissant et clair, idéal pour écouter de la musique à la maison ou en extérieur. Design élégant et autonomie prolongée.', '30', '2024-10-17', 'USA', '14487_Haut-parleur Bluetooth.jpg', '2', 0, 1, 10, 7, 'Électronique,Haut-parleur,Bluetooth,Musique,Portable'),
(13, 'Yeti Blue Mic', 'Un microphone de haute qualité parfait pour les podcasts, le streaming et l\\\'enregistrement.', '25', '2024-10-17', 'États-Unis', '16317_Yeti-Blue-Mic.png', '3', 0, 1, 1, 8, 'Audio, Microphone, Électronique, Enregistrement'),
(14, 'souris d\'ordinateur', 'Une souris ergonomique et réactive, idéale pour le travail et les jeux. Dotée d\'une connectivité sans fil ou filaire, d\'une précision exceptionnelle, et d\'un design confortable pour une utilisation prolongée.', '130', '2024-10-17', 'USA', '25999_souris-ordinateur.jpg', '4', 0, 1, 1, 7, 'Électronique,Souris,Ergonomique,Sans Fil,Précision,Confort,Jeux'),
(17, 'Boîte artisanale', 'Une boîte fabriquée à la main avec des matériaux naturels, idéale pour ranger vos bijoux, souvenirs ou petits objets. Allie élégance et qualité artisanale.', '25', '2024-06-30', 'France', '4789_Boîte-artisanale.jpg', '3', 0, 1, 17, 10, 'Fait Main,Boîte,Artisanat,Rangement.'),
(28, 'Diablo |||', 'Un jeu de rôle épique et captivant où les joueurs affrontent des forces démoniaques dans un univers sombre et fantastique.', '30', '2024-10-28', 'États-Unis', '3091_Diablo.jpg', '1', 0, 1, 19, 43, 'RPG,Online,Game'),
(31, 'Assassin\'s Creed', 'Un jeu d\'action-aventure immersif où les joueurs incarnent des assassins dans des périodes historiques fascinantes pour affronter des templiers et défendre la liberté.', '25', '2024-11-04', 'France', '90506_Assassin-Creed.png', '2', 0, 1, 19, 4, 'Jeux, Action, Aventure, Assassin, Ubisoft'),
(32, 'Cadre en bois', 'Un cadre en bois élégant fabriqué à la main, parfait pour encadrer vos photos ou œuvres d\\\'art avec une touche artisanale unique.', '7', '2024-11-08', 'India', '16114_cadre-bois.jpg', '1', 0, 0, 17, 78, 'photo,cadre,bois'),
(33, 'Légendes d\'Avaloria', 'Explorez un RPG immersif où vous incarnez un héros légendaire chargé de sauver un royaume magique des forces obscures.', '50', '2024-11-23', 'France', '24783_Légendes-Avaloria.png', '1', 0, 0, 19, 10, 'RPG, jeu vidéo, aventure, fantasy, PC, console, héros, royaume magique, Made in France'),
(85, 'Téléviseur LG', 'Téléviseur haute résolution avec écran OLED.', '800', '2024-11-08', 'Corée du Sud', '20159_Téléviseur-LG.png', '1', 5, 1, 1, 2, ' Téléviseur, OLED'),
(86, 'Smartphone Samsung Galaxy', 'Smartphone avec écran AMOLED et triple caméra.', '900', '2024-11-08', 'Corée du Sud', '69236_Smartphone-Samsung-Galaxy.png', '3', 5, 1, 4, 2, 'Téléphone,Électronique,Samsung'),
(87, 'Casque Bose', 'Casque audio sans fil avec réduction de bruit.', '250', '2024-11-08', 'États-Unis', '93345_Casque-Bose.png', '3', 4, 1, 1, 3, 'Audio, Casque, Bose'),
(88, 'Tablette Apple iPad', 'Tablette avec écran Retina et processeur A14.', '600', '2024-11-08', 'États-Unis', '87000_Tablette-Apple-iPad.png', '2', 5, 1, 1, 43, 'Électronique, Apple, iPad'),
(89, 'Appareil photo Nikon', 'Appareil photo reflex avec objectif 24-70mm.', '1200', '2024-11-08', 'Japon', '66546_AppareilphotoNikon.png', '1', 5, 1, 1, 78, 'Photo, Nikon, Reflex'),
(100, 'T-shirt Nike', 'T-shirt en coton de haute qualité.', '30', '2024-11-08', 'Chine', '80260_T-shirt-Nike.png', '1', 5, 1, 2, 4, 'Mode, T-shirt, Nike'),
(101, 'Veste Zara', 'Veste élégante pour hommes.', '100', '2024-11-08', 'Espagne', '79518_Veste-Zara.png', '2', 4, 1, 2, 5, 'Mode, Veste, Zara'),
(102, 'Jean Levi\'s', 'Jean classique coupe droite.', '80', '2024-11-08', 'États-Unis', '20374_Jean-Levis.png', '3', 5, 1, 2, 6, 'Mode, Jean, Levi\'s'),
(103, 'Chaussures Adidas', 'Chaussures de sport confortables.', '120', '2024-11-08', 'Allemagne', '41369_Chaussures-Adidas.png', '4', 5, 1, 2, 8, 'Chaussures, Sport, Adidas'),
(104, 'Robe H&M', 'Robe élégante pour les occasions spéciales.', '90', '2024-11-08', 'Suède', '58527_Robe H&M.png', '1', 4, 1, 2, 7, 'Mode, Robe, H&M'),
(120, 'Canapé d\'angle', 'Canapé d\'angle confortable pour le salon.', '700', '2024-11-08', 'Italie', '80581_canape.jpg', '2', 5, 1, 3, 8, 'Maison, Salon, Canapé'),
(121, 'Table en bois', 'Table en bois massif avec un design moderne.', '400', '2024-11-08', 'France', '89501_Table.png', '1', 5, 1, 3, 44, 'Maison, Table, Bois'),
(122, 'Lampe IKEA', 'Lampe moderne avec lumière LED.', '50', '2024-11-08', 'Suède', '49803_lampIKEA.png', '4', 5, 1, 3, 5, 'Maison, Lampe, IKEA'),
(123, 'Rideaux en soie', 'Rideaux élégants fabriqués en soie.', '100', '2024-11-08', 'Inde', '18899_curtain.png', '2', 4, 1, 3, 73, 'Maison, Rideaux, Soie'),
(124, 'Coussin décoratif', 'Coussin coloré pour décorer votre canapé.', '20', '2024-11-08', 'Maroc', '36293_pillow.jpg', '2', 5, 1, 3, 6, 'Maison, Coussin, Décor'),
(125, 'iPhone 14 Pro', 'Dernier modèle d\'iPhone avec caméra avancée.', '1500', '2024-11-08', 'États-Unis', '52988_png-transparent-iphone-14-pro.png', '3', 5, 1, 4, 3, 'Téléphone, iPhone, Apple'),
(126, 'Samsung Galaxy Fold', 'Téléphone pliable avec grand écran AMOLED.', '2000', '2024-11-08', 'Corée du Sud', '20343_SamsungGalaxyFold.jpg', '2', 5, 1, 4, 2, 'Téléphone, Samsung, Pliable'),
(127, 'Huawei Mate 50', 'Téléphone Android avec fonctionnalités premium.', '800', '2024-11-08', 'Chine', '45376_HuaweiMate.jpg', '1', 5, 1, 4, 3, 'Téléphone, Huawei, Android'),
(128, 'Xiaomi Mi 13', 'Téléphone avec excellent rapport qualité-prix.', '500', '2024-11-08', 'Chine', '94918_xiamo.jpg', '2', 5, 1, 4, 4, 'Téléphone, Xiaomi, Mi'),
(129, 'Nokia 3310', 'Téléphone classique avec clavier numérique.', '50', '2024-11-08', 'Finlande', '31075_nokia-3310-nokia.png', '3', 4, 1, 4, 5, 'Téléphone, Nokia, Classique'),
(140, 'Bougie artisanale', 'Bougie fabriquée à la main avec des matériaux naturels.', '20', '2024-11-08', 'France', '88489_bougie.jpg', '1', 5, 1, 9, 6, 'Fait Main, Bougie, Artisanat'),
(141, 'Bracelet en perles', 'Bracelet unique fabriqué à la main avec des perles.', '40', '2024-11-08', 'Maroc', '39796_bracelet.png', '4', 5, 1, 9, 7, 'Bijoux, Fait Main, Perles'),
(142, 'Écharpe tricotée', 'Écharpe chaude faite à la main en laine.', '50', '2024-11-08', 'Italie', '73485_scarf-scarves.png', '2', 5, 1, 9, 8, 'Fait Main, Tricot, Écharpe'),
(143, 'Sac en cuir', 'Sac élégant fabriqué en cuir naturel.', '120', '2024-11-08', 'Espagne', '59698_sac.png', '3', 5, 1, 9, 10, 'Fait Main, Sac, Cuir'),
(144, 'Tableau artistique', 'Peinture unique réalisée par un artiste local.', '300', '2024-11-08', 'France', '3618_clipart-painting.png', '4', 5, 1, 9, 74, 'Art, Tableau, Fait Main'),
(155, 'Marteau Stanley', 'Marteau robuste en acier avec manche ergonomique.', '20', '2024-11-03', 'États-Unis', '50934_marteau.png', '1', 5, 1, 16, 52, 'Outils, Marteau, Stanley'),
(156, 'Tournevis Philips', 'Tournevis polyvalent de haute qualité.', '10', '2024-10-08', 'Allemagne', '44617_Tournevis-Philips.png', '2', 5, 1, 13, 8, 'Outils, Tournevis, Philips'),
(157, 'Scie à main Bosch', 'Scie à main efficace pour le bois.', '30', '2024-06-09', 'Allemagne', '19595_scie-main-bosch.jpg', '3', 5, 1, 13, 45, 'Outils, Scie, Bosch'),
(158, 'Perceuse Makita', 'Perceuse électrique avec plusieurs embouts.', '120', '2024-11-08', 'Japon', '46792_perceuse.jpg', '4', 5, 1, 13, 43, 'Outils, Perceuse, Makita'),
(159, 'Pince universelle', 'Pince robuste et multifonctionnelle.', '15', '2024-11-08', 'France', '11677_pince.png', '1', 5, 1, 13, 5, 'Outils, Pince, Universelle'),
(160, 'Puzzle 1000 pièces', 'Puzzle éducatif et divertissant pour toute la famille.', '25', '2024-11-08', 'Allemagne', '71115_puzzle.jpg', '1', 5, 1, 19, 2, 'Jeux, Puzzle, Éducatif'),
(161, 'Jeu d\'échecs', 'Jeu d\'échecs en bois pour amateurs et professionnels.', '50', '2024-11-08', 'France', '74406_jeu-echelle.jpg', '2', 5, 1, 19, 3, 'Jeux, Échecs, Bois'),
(162, 'Poupée Barbie', 'Poupée classique avec accessoires.', '30', '2024-11-08', 'États-Unis', '61694_barbie.png', '3', 5, 1, 19, 4, 'Jeux, Poupée, Barbie'),
(163, 'Jeu de société Monopoly', 'Version classique du célèbre jeu Monopoly.', '40', '2024-11-08', 'États-Unis', '55906_Monpoly.jpg', '4', 5, 1, 19, 4, 'Jeux, Société, Monopoly'),
(164, 'Voiture télécommandée', 'Voiture télécommandée rapide et amusante.', '60', '2024-11-08', 'Chine', '80402_voiture-tele.png', '1', 5, 1, 19, 7, 'Jeux, Voiture, Télécommandée'),
(165, 'Ordinateur Portable HP', 'Un ordinateur portable puissant avec processeur Intel Core i7 et écran 15 pouces.', '1200', '2024-11-08', 'États-Unis', '39554_Ordinateur-Portable-HP.jpg', '1', 5, 1, 10, 6, 'Ordinateur, Portable, HP, Intel'),
(166, 'MacBook Pro', 'Un ordinateur portable haut de gamme avec puce Apple M1 et écran Retina.', '2500', '2024-11-08', 'États-Unis', '87482_MacBook-Pro.png', '2', 5, 1, 10, 7, 'Ordinateur, Apple, MacBook, Retina'),
(167, 'Dell Inspiron', 'Ordinateur portable fiable avec écran Full HD et Windows 11.', '900', '2024-11-08', 'États-Unis', '15321_Dell-Inspiron.png', '3', 5, 1, 10, 5, 'Ordinateur, Portable, Dell, Windows'),
(168, 'Lenovo ThinkPad', 'Ordinateur portable professionnel avec clavier ergonomique.', '1100', '2024-11-08', 'Chine', '314_Lenovo-ThinkPad.png', '4', 5, 1, 10, 8, 'Ordinateur, Lenovo, Professionnel, Ergonomique'),
(169, 'Asus ROG Gaming', 'Ordinateur portable conçu pour les jeux avec GPU NVIDIA GeForce RTX.', '2000', '2024-11-08', 'Taïwan', '69192_Asus-ROG-Gaming.png', '4', 5, 1, 10, 8, 'Ordinateur, Gaming, Asus, NVIDIA');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `UserID` int(11) NOT NULL COMMENT 'To identify user',
  `UserName` varchar(225) NOT NULL COMMENT 'user name to login',
  `Password` varchar(225) NOT NULL COMMENT 'password to login',
  `Email` varchar(225) NOT NULL,
  `FullName` varchar(225) NOT NULL,
  `GroupID` int(11) NOT NULL DEFAULT '0' COMMENT 'identify user group',
  `TrustStatus` int(11) NOT NULL DEFAULT '0' COMMENT 'seller rank',
  `RegStatus` int(11) NOT NULL DEFAULT '0' COMMENT 'user approval ',
  `Date` date NOT NULL,
  `Avatar` varchar(255) NOT NULL,
  `rememberToken` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`UserID`, `UserName`, `Password`, `Email`, `FullName`, `GroupID`, `TrustStatus`, `RegStatus`, `Date`, `Avatar`, `rememberToken`) VALUES
(1, 'Jean', '$2y$10$vEiMWiv6pCPOE4C10BfMguEkexStFXtD5MjhXdE.Udx.HJWXqlT8.', 'jean.dupont@example.com', 'Jean Dupont', 0, 1, 1, '2024-11-01', '', ''),
(2, 'Marie', '$2y$10$ZhgrGWZdtnj559GvLu8N2.PMD5omVFcLo4ccA371V40WeSPjrJiqm', 'marie.curie@example.com', 'Marie Curie', 0, 0, 1, '2024-11-02', '', ''),
(3, 'Paul', '$2y$10$sX5XZgo9xw/MiayC4ve7nuBR5JsZd3yDs2YcHdyIr/8GPqJ9/ebF6', 'paul.durand@example.com', 'Paul Durand', 0, 1, 1, '2024-11-03', '', ''),
(4, 'Yasin', '$2y$10$B/znh1LXbX.xQNpnN3XPHeN/QG8wTsRxcJ/fT842n1y9nIadNfaFG', 'Yasin@yahoo.cm', 'Yasin Mostafa', 1, 0, 1, '2024-09-28', '95727_male-avatar.png', ''),
(5, 'Gamila', '$2y$10$P9xMyZ01/KAaIQL8gcbsqOW5DUQ99BiWzXX44Rdybfr7Bn26iC72y', 'gamila@gmail.com', 'Gamila Awad Dawoud ', 0, 0, 0, '2024-09-28', '', ''),
(6, 'Sarah', '$2y$10$sAKz9uI9u14xTc5AVDnhW.cMDnatmnfNn7N3ObF8.nKjfCXN4ccee', 'Sarah@gmail.com', 'Sarah Fawzi', 1, 0, 1, '2024-10-15', '', ''),
(7, 'Maiar', '$2y$10$jGbX/YYIzxS01TfcvAdOxOTUmNMT8BSl6eQVAx6fxFzZ16yG7NxPm', 'Maiar@gmail.com', 'Maiar Tolan', 0, 0, 0, '2024-10-15', '72831_beautiful-blonde-woman-avatar.jpg', '34338579c71fd706d28560b453faefb8'),
(8, 'shaheen', '$2y$10$qfB6X4zMFKdn618vgixgbO4pNkPkYV2nXW2EKGiOCh3e.M5irE9MO', 'shaheen@gmail.com', 'Shaheen Wael', 0, 0, 1, '2024-10-15', '', ''),
(10, 'Ayaat', '$2y$10$z2m.C8KsDNn9U6ymBbHla.z5m3a6K2qCnrsqKUBt/iopZvgl8xx5C', 'ayaat@gmail.com', 'Ayaat Ahmed', 0, 0, 1, '2024-10-21', '', 'dac1c7d035e8a62a566882a08f455d86'),
(43, 'Gamal', '$2y$10$z4OmJyxhZk42DFMa.YxEker6aDaiGnR2Dnmq9DDjXZPk.0RQjYwa2', 'Gamal@gmail.com', 'Gamal@yahoo.com', 0, 0, 1, '2024-10-29', '96521_male-avatar.png', ''),
(44, 'Gigi', '$2y$10$TwDaaZcXVBD8N8tA3RQEx.g8Bu9OeneKk7lUOIWBzYYKgXlTcAUJu', 'Gigi@gmail.com', 'Gigi Hassan', 0, 0, 1, '2024-10-29', '30372_beautiful-blonde-woman-avatar.jpg', ''),
(45, 'Gimi', '$2y$10$WN.kKFS0BeNgPNRJNFxEvO4QHIRfoYNEgJi0bnV6irRpgPJPslkS2', 'Gimi@yahoo.com', 'Gimi Karter', 0, 0, 1, '2024-10-29', '', ''),
(52, 'zizi', '$2y$10$9BnddvXxFblk216ZDp4ONuk15TT7pX9LjMV99/DeerlK7GhIvE8r2', 'zizi@123.com', 'Zizi Hassan', 0, 0, 1, '2024-10-29', '', ''),
(72, 'Yaso', '$2y$10$evXf4epJA4b52zfHxqfgx.frYw20znyfFy3YhUa1208FCMuwTSf7e', 'Yaso@gmail.com', 'Yaso Nader', 0, 0, 1, '2024-10-29', '', ''),
(73, 'zinab', '$2y$10$9cbUpKCgIhMcxsfcZYvxseV6u7kK3CwvHvcSuhNrP3ZVkeZppuCsy', 'zinab@gmail.com', 'zinab Naser', 0, 0, 1, '2024-10-29', '8688_beautiful-blonde-woman-avatar.jpg', ''),
(74, 'Jasmine', '$2y$10$1plLcEx/tv9Dt6BFa6rHw.OeusRunkCQBgOXsF7FtTL4VfPyokKd6', 'Yasmine@gmail.com', 'Yasmine Zakaria', 0, 0, 1, '2024-10-31', '13065_beautiful-blonde-woman-avatar.jpg', ''),
(78, 'Adam', '$2y$10$tB1nBs9lbtfOOZoXKPqwceWk1F44nHK4v9oI566SvVAvxNc.g6Eg.', 'Adam@gmal.com', 'Adam Zain', 1, 0, 0, '2024-11-07', '', '58f52379f8a3e49792e1f84796a2b427'),
(80, 'Samy', '$2y$10$QlZwr4cMDSsj9psaaeL8EOltY1Swn1Oc.L1Xst0kWu4J8Wc2woXZS', 'sam@gmail.com', 'Sam Shaeen', 0, 0, 0, '2024-11-22', '', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD UNIQUE KEY `unique_cart_item` (`user_id`,`item_id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `Name` (`Name`) USING BTREE;

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`c_id`),
  ADD KEY `comment_user` (`user_id`),
  ADD KEY `comment_item` (`item_id`) USING BTREE;

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`Item_ID`),
  ADD KEY `member_1` (`Member_ID`),
  ADD KEY `cat_1` (`Cat_ID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UserID`),
  ADD UNIQUE KEY `UserName` (`UserName`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `c_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;
--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `Item_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=170;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'To identify user', AUTO_INCREMENT=81;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`UserID`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `items` (`Item_ID`);

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comment_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `items_comment` FOREIGN KEY (`item_id`) REFERENCES `items` (`Item_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `cat_1` FOREIGN KEY (`Cat_ID`) REFERENCES `categories` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `member_1` FOREIGN KEY (`Member_ID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
