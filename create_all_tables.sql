-- phpMyAdmin SQL Dump
-- version 4.0.10.19
-- https://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 02, 2017 at 05:43 PM
-- Server version: 5.5.31-cll
-- PHP Version: 5.3.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
-- --------------------------------------------------------

--
-- Table structure for table `ADDRESS`
--

CREATE TABLE IF NOT EXISTS `ADDRESS` (
  `AddressID` int(11) NOT NULL AUTO_INCREMENT,
  `CustomerUsername` varchar(45) NOT NULL,
  `AddressLine1` varchar(45) NOT NULL,
  `AddressLine2` varchar(45) DEFAULT NULL,
  `City` varchar(45) NOT NULL,
  `State` char(2) NOT NULL,
  `Country` varchar(45) NOT NULL DEFAULT 'USA',
  `ZIP` varchar(45) NOT NULL,
  PRIMARY KEY (`AddressID`),
  KEY `fk_ADDRESS_CUSTOMER_idx` (`CustomerUsername`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

-- --------------------------------------------------------

--
-- Table structure for table `ADMIN`
--

CREATE TABLE IF NOT EXISTS `ADMIN` (
  `CustomerUsername` varchar(45) NOT NULL,
  `Active` tinyint(1) NOT NULL,
  PRIMARY KEY (`CustomerUsername`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `CART_ITEM`
--

CREATE TABLE IF NOT EXISTS `CART_ITEM` (
  `CustomerUsername` varchar(45) NOT NULL,
  `ProductID` int(11) NOT NULL,
  `Quantity` varchar(45) NOT NULL,
  PRIMARY KEY (`ProductID`,`CustomerUsername`),
  KEY `fk_CART_ITEM_CUSTOMER1_idx` (`CustomerUsername`),
  KEY `fk_CART_ITEM_PRODUCT1_idx` (`ProductID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


--
-- Table structure for table `CATEGORY`
--

CREATE TABLE IF NOT EXISTS `CATEGORY` (
  `CategoryID` int(11) NOT NULL AUTO_INCREMENT,
  `CategoryName` varchar(155) NOT NULL,
  `ParentCategoryID` int(11) DEFAULT NULL,
  PRIMARY KEY (`CategoryID`),
  KEY `ParentID_FK_idx` (`ParentCategoryID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `CATEGORY`
--

INSERT INTO `CATEGORY` (`CategoryID`, `CategoryName`, `ParentCategoryID`) VALUES
(-1, 'ROOT', NULL),
(1, 'HOME', -1),
(2, 'MEDIA', -1),
(3, 'BOOK', 2),
(4, 'DVD', 2),
(5, 'CD', 2),
(6, 'GARDEN', 1),
(7, 'FURNITURE', 1),
(8, 'GAME', 2);

-- --------------------------------------------------------

--
-- Table structure for table `CUSTOMER`
--

CREATE TABLE IF NOT EXISTS `CUSTOMER` (
  `CustomerUsername` varchar(45) NOT NULL,
  `LastName` char(25) NOT NULL,
  `FirstName` char(25) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `encryptedPassword` varchar(60) NOT NULL,
  `Phone` char(12) DEFAULT NULL,
  `JoinDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Active` tinyint(1) NOT NULL DEFAULT '1',
  `IsPremium` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`CustomerUsername`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `INVOICE`
--

CREATE TABLE IF NOT EXISTS `INVOICE` (
  `InvoiceID` int(11) NOT NULL AUTO_INCREMENT,
  `InvoiceDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `SubTotal` decimal(9,2) NOT NULL,
  `TaxAmount` decimal(9,2) NOT NULL,
  `TotalAmount` decimal(9,2) NOT NULL,
  `CustomerUsername` varchar(45) NOT NULL,
  `AddressID` int(11) NOT NULL,
  `PaymentID` int(11) NOT NULL,
  `ShippingCost` decimal(9,2) NOT NULL,
  PRIMARY KEY (`InvoiceID`),
  KEY `fk_ORDER_CUSTOMER_idx` (`CustomerUsername`),
  KEY `fk_INVOICE_PAYMENT1_idx` (`PaymentID`),
  KEY `fk_INVOICE_ADDRESS_idx` (`AddressID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Table structure for table `LINE_ITEM`
--

CREATE TABLE IF NOT EXISTS `LINE_ITEM` (
  `InvoiceID` int(11) NOT NULL,
  `LineNumber` int(11) NOT NULL,
  `ProductID` int(11) NOT NULL,
  `Quantity` int(11) NOT NULL,
  `TotalPrice` decimal(9,2) NOT NULL,
  PRIMARY KEY (`InvoiceID`,`LineNumber`),
  KEY `fk_ORDER_DETAILS_ORDER1_idx` (`InvoiceID`),
  KEY `fk_ORDER_DETAILS_PRODUCT1_idx` (`ProductID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `LOG`
--

CREATE TABLE IF NOT EXISTS `LOG` (
  `logID` int(11) NOT NULL AUTO_INCREMENT,
  `CustomerUsername` varchar(45) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `event` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`logID`),
  KEY `fk_LOG_CUSTOMER1_idx` (`CustomerUsername`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=62 ;

-- --------------------------------------------------------

--
-- Table structure for table `PAYMENT`
--

CREATE TABLE IF NOT EXISTS `PAYMENT` (
  `PaymentID` int(11) NOT NULL AUTO_INCREMENT,
  `CustomerUsername` varchar(45) NOT NULL,
  `FirstName` varchar(45) NOT NULL,
  `LastName` varchar(45) NOT NULL,
  `AddressID` int(11) NOT NULL,
  `CardCompany` varchar(45) NOT NULL,
  `CardNumber` varchar(45) NOT NULL,
  `ExpirationMonth` int(2) NOT NULL,
  `ExpirationYear` int(4) DEFAULT NULL,
  PRIMARY KEY (`PaymentID`),
  KEY `fk_PAYMENT_CUSTOMER1_idx` (`CustomerUsername`),
  KEY `fk_PAYMENT_aDDRESS_idx` (`AddressID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

-- --------------------------------------------------------

--
-- Table structure for table `PRODUCT`
--

CREATE TABLE IF NOT EXISTS `PRODUCT` (
  `ProductID` int(11) NOT NULL AUTO_INCREMENT,
  `VendorID` int(11) NOT NULL,
  `ProductName` char(45) NOT NULL,
  `Description` varchar(2000) DEFAULT NULL,
  `UnitPrice` decimal(9,2) NOT NULL,
  `QuantityInStock` smallint(6) NOT NULL,
  `ReleaseDate` date DEFAULT NULL,
  `CategoryID` int(11) NOT NULL,
  PRIMARY KEY (`ProductID`,`VendorID`,`CategoryID`),
  KEY `fk_PRODUCT_SELLER1_idx` (`VendorID`),
  KEY `fk_PRODUCT_Category1_idx` (`CategoryID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `PRODUCT`
--

INSERT INTO `PRODUCT` (`ProductID`, `VendorID`, `ProductName`, `Description`, `UnitPrice`, `QuantityInStock`, `ReleaseDate`, `CategoryID`) VALUES
(1, 1, 'Computer Architecture', 'All information about computer architecture.', '99.78', 20, '2017-03-29', 3),
(2, 1, 'C++ Programming', 'Learn about C++.', '135.33', 6, '2017-03-29', 3),
(3, 2, 'King Bedroom Set', 'Includes bed, 2 nightstands, dresser, and mirror', '2499.95', 3, '2017-03-29', 7),
(4, 2, 'Computer Desk', 'Oak computer desk', '499.50', 5, '2017-03-29', 7),
(5, 1, 'JAVA Programming', 'Learn about JAVA programming', '149.95', 0, '2017-03-29', 3),
(6, 5, 'Legend of Zelda: Breath of the Wild', 'Forget everything you know about The Legend of Zelda games. Step into a world of discovery, exploration and adventure in The Legend of Zelda: Breath of the Wild, a boundary-breaking new game in the acclaimed series. Travel across fields, through forests and up mountain peaks as you discover what has become of the ruined kingdom of Hyrule in this stunning open-air adventure.', '59.99', 40, '2017-03-29', 8),
(7, 6, 'Dante: A Life', 'Acclaimed biog rap her R.W.B. Lewis traces the life and complex development? emotional, artistic, philosophical?of this supreme poet-historian. Here we meet the boy who first encounters the mythic Beatrice, the lyric poet obsessed with love and death, the grand master of dramatic narrative and allegory, and his monumental search for ultimate truth in The Divine Comedy. It is in this masterpiece of self-discovery and redemption that Lewis finds Dante?s own autobiography?and the sum of all his shifting passions and epiphanies.', '9.99', 3, '2017-03-29', 3),
(8, 6, 'The Divine Comedy', 'Robert Pinsky''s new verse translation of the Inferno makes it clear to the contemporary listener, as no other in English has done, why Dante is universally considered a poet of great power, intensity, and strength. This critically acclaimed translation was awarded the Los Angeles Times Book Prize for Poetry and the Harold Morton Landon Translation Award given by the Academy of American Poets. Well versed, rapid, and various in style, the Inferno is narrated by Pinsky and three other leading poets: Seamus Heaney, Frank Bidart, and Louise Gluck.', '39.99', 12, '2017-03-29', 3),
(9, 7, 'Finding Nemo', 'Nemo, a young clownfish is captured and taken to a dentist''s office aquarium. It''s up to Marlin, his father, and Dory, a friendly but forgetful regal blue tang fish, to make the epic journey to bring Nemo home from Australia''s Great Barrier Reef.', '12.99', 10, '2017-03-29', 4);

-- --------------------------------------------------------

--
-- Table structure for table `VENDOR`
--

CREATE TABLE IF NOT EXISTS `VENDOR` (
  `VendorID` int(11) NOT NULL AUTO_INCREMENT,
  `CompanyName` varchar(25) NOT NULL,
  `Website` varchar(100) DEFAULT NULL,
  `Phone` char(12) DEFAULT NULL,
  `ContactLastName` char(25) NOT NULL,
  `ContactFirstName` char(25) NOT NULL,
  `Email` varchar(100) NOT NULL,
  PRIMARY KEY (`VendorID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `VENDOR`
--

INSERT INTO `VENDOR` (`VendorID`, `CompanyName`, `Website`, `Phone`, `ContactLastName`, `ContactFirstName`, `Email`) VALUES
(1, 'Pearson', 'www.pearson.com', '555-555-1234', 'Smith', 'Tom', 'tsmith@mail.com'),
(2, 'Thomasville', 'www.thomasville.com', '555-555-7878', 'Carter', 'Thomas', 'tcarter@hotmail.com'),
(3, 'Legion Records', 'www.legion.com', '555-444-1000', 'Doe', 'John', 'jdoe@hotmail.com'),
(4, 'PIXAR', 'www.pixar.com', '555-888-2000', 'Jobs', 'Steve', 'sjobs@mail.com'),
(5, 'Nintendo', 'www.nintendo.com', '145-897-4689', 'House', 'Theresa', 'thouse@noa.com'),
(6, 'Penguin Books', 'www.penguin.com', '458-985-1648', 'Bird', 'Larry', 'lbird@penguin.com'),
(7, 'Disney', 'www.disney.com', '423-468-7264', 'May', 'Lynn', 'lynn.may@disney.com');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `ADDRESS`
--
ALTER TABLE `ADDRESS`
  ADD CONSTRAINT `fk_ADDRESS_CUSTOMER` FOREIGN KEY (`CustomerUsername`) REFERENCES `CUSTOMER` (`CustomerUsername`) ON UPDATE NO ACTION;

--
-- Constraints for table `ADMIN`
--
ALTER TABLE `ADMIN`
  ADD CONSTRAINT `fk_PREMIUM_CUSTOMER_CUSTOMER` FOREIGN KEY (`CustomerUsername`) REFERENCES `CUSTOMER` (`CustomerUsername`) ON UPDATE NO ACTION;

--
-- Constraints for table `CART_ITEM`
--
ALTER TABLE `CART_ITEM`
  ADD CONSTRAINT `fk_CART_ITEM_CUSTOMER1` FOREIGN KEY (`CustomerUsername`) REFERENCES `CUSTOMER` (`CustomerUsername`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_CART_ITEM_PRODUCT1` FOREIGN KEY (`ProductID`) REFERENCES `PRODUCT` (`ProductID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `CATEGORY`
--
ALTER TABLE `CATEGORY`
  ADD CONSTRAINT `ParentID_FK` FOREIGN KEY (`ParentCategoryID`) REFERENCES `CATEGORY` (`CategoryID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `INVOICE`
--
ALTER TABLE `INVOICE`
  ADD CONSTRAINT `fk_INVOICE_CUSTOMER` FOREIGN KEY (`CustomerUsername`) REFERENCES `CUSTOMER` (`CustomerUsername`) ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_INVOICE_PAYMENT` FOREIGN KEY (`PaymentID`) REFERENCES `PAYMENT` (`PaymentID`) ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_INVOICE_ADDRESS` FOREIGN KEY (`AddressID`) REFERENCES `ADDRESS` (`AddressID`) ON UPDATE NO ACTION;

--
-- Constraints for table `LINE_ITEM`
--
ALTER TABLE `LINE_ITEM`
  ADD CONSTRAINT `fk_LINE_ITEM_INVOICE` FOREIGN KEY (`InvoiceID`) REFERENCES `INVOICE` (`InvoiceID`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_LINE_ITEM_PRODUCT` FOREIGN KEY (`ProductID`) REFERENCES `PRODUCT` (`ProductID`) ON UPDATE NO ACTION;

--
-- Constraints for table `LOG`
--
ALTER TABLE `LOG`
  ADD CONSTRAINT `fk_LOG_CUSTOMER1` FOREIGN KEY (`CustomerUsername`) REFERENCES `CUSTOMER` (`CustomerUsername`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `PAYMENT`
--
ALTER TABLE `PAYMENT`
  ADD CONSTRAINT `fk_PAYMENT_CUSTOMER` FOREIGN KEY (`CustomerUsername`) REFERENCES `CUSTOMER` (`CustomerUsername`) ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_PAYMENT_aDDRESS` FOREIGN KEY (`AddressID`) REFERENCES `ADDRESS` (`AddressID`) ON UPDATE NO ACTION;

--
-- Constraints for table `PRODUCT`
--
ALTER TABLE `PRODUCT`
  ADD CONSTRAINT `fk_PRODUCT_VENDOR` FOREIGN KEY (`VendorID`) REFERENCES `VENDOR` (`VendorID`) ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_PRODUCT_Category1` FOREIGN KEY (`CategoryID`) REFERENCES `CATEGORY` (`CategoryID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
