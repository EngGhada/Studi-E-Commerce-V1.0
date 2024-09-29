-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 29, 2024 at 02:23 PM
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
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `ID` int(11) NOT NULL,
  `Name` varchar(225) NOT NULL,
  `Description` text NOT NULL,
  `Ordering` int(11) DEFAULT NULL,
  `Visibility` tinyint(4) NOT NULL DEFAULT '0',
  `Allow_Comment` tinyint(4) NOT NULL DEFAULT '0',
  `Allow_Ads` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`ID`, `Name`, `Description`, `Ordering`, `Visibility`, `Allow_Comment`, `Allow_Ads`) VALUES
(1, 'Mobile', '', 2, 0, 0, 1),
(2, 'PC', '', NULL, 0, 1, 0),
(3, 'Electronics', '', 0, 1, 0, 0),
(4, 'Toys', 'This is toys for Kids', 10, 0, 1, 0),
(5, 'Private Category', 'This is a private Category', 3, 1, 1, 1);

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
  `Cat_ID` int(11) NOT NULL,
  `Member_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
  `Date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`UserID`, `UserName`, `Password`, `Email`, `FullName`, `GroupID`, `TrustStatus`, `RegStatus`, `Date`) VALUES
(24, 'Alaa', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'alaa@gmail.com', 'Alaa Shaheen', 0, 0, 1, '2024-09-28'),
(25, 'Nader', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'Nader@gmail.com', 'Nader Awad', 0, 0, 0, '2024-09-28'),
(26, 'Zahra', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'zahra@gmail.com', 'Zahra Amed', 0, 0, 0, '2024-09-28'),
(27, 'Ghada', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'ghada@gmail.com', 'Ghada Mohamed', 0, 0, 0, '2024-09-28'),
(28, 'Yasin', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'Yasin@yahoo.cm', 'Yasin Mostafa', 1, 0, 1, '2024-09-28'),
(29, 'Gamila', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'gamila@gmail.com', 'Gamila Awad Dawoud', 1, 0, 1, '2024-09-28'),
(30, 'Sara', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'sara@gmail.com', 'Sara Hossam', 0, 0, 0, '2024-09-28'),
(31, 'Maher', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'Maher@gmail.com', 'Maher Abullah', 0, 0, 1, '2024-09-28');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `Name` (`Name`) USING BTREE;

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`Item_ID`),
  ADD UNIQUE KEY `Name` (`Name`),
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
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `Item_ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'To identify user', AUTO_INCREMENT=32;
--
-- Constraints for dumped tables
--

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
