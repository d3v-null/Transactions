-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 08, 2013 at 09:00 AM
-- Server version: 5.5.24-log
-- PHP Version: 5.4.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `test`
--

-- --------------------------------------------------------

--
-- Table structure for table `history`
--

CREATE TABLE IF NOT EXISTS `history` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `TransactionID` int(11) NOT NULL,
  `Description` varchar(255) NOT NULL,
  `Comment` varchar(600) DEFAULT NULL,
  `RecordedDate` datetime NOT NULL,
  `TransactionDate` datetime DEFAULT NULL,
  `PaymentDate` datetime DEFAULT NULL,
  `RecordedPersonID` int(11) NOT NULL,
  `ResponsibleParty` varchar(255) DEFAULT NULL,
  `AssociatedParty` varchar(255) DEFAULT NULL,
  `Amount` int(11) NOT NULL,
  `Inflow` tinyint(1) NOT NULL,
  `StatusID` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=39 ;

--
-- Dumping data for table `history`
--

INSERT INTO `history` (`ID`, `TransactionID`, `Description`, `Comment`, `RecordedDate`, `TransactionDate`, `PaymentDate`, `RecordedPersonID`, `ResponsibleParty`, `AssociatedParty`, `Amount`, `Inflow`, `StatusID`) VALUES
(1, 1, 'Bought 50 gold pencils', 'They looked so purdy and I couldn''t resist! :}', '0000-00-00 00:00:00', '2012-12-12 00:00:00', '2012-12-12 00:00:00', 0, 'Bob the Builder', 'Someone', 1, 0, 1),
(2, 0, 'Sold a meatball', 'He knew it''d be tasty! "I''ll love it", he said.', '0000-00-00 00:00:00', '0001-01-01 00:00:00', '2012-12-12 00:00:00', 0, 'Bob the Builder', 'Wendy the Other Person', 124, 0, 1),
(32, 1, 'id is 1, and complete', 'They looked so purdy and I couldn''t resist! :}', '2013-10-03 22:24:52', '2012-12-12 00:00:00', '2012-12-12 00:00:00', 0, 'Bob the Builder', 'Someone', 1, 0, 2),
(34, 1, 'id is 1, and complete', 'They looked so purdy and I couldn''t resist! :}', '2013-10-03 22:26:06', '2012-12-12 00:00:00', '2012-12-12 00:00:00', 0, 'Bob the Builder', 'Someone', 1, 1, 2),
(35, 3, 'Bought 50 gold pencils id3', 'They looked so purdy and I couldn''t resist! :}', '2013-10-03 22:26:49', '2012-12-12 00:00:00', '2012-12-12 00:00:00', 0, 'Bob the Builder', 'Someone', 1, 0, 1),
(36, 1, 'Bought 50 gold pencils', 'They looked so purdy and I couldn''t resist! :}', '2013-10-03 22:28:37', '2012-12-12 00:00:00', '2012-12-12 00:00:00', 0, 'Bob the Builder', 'Someone', 1, 0, 2),
(37, 1, 'Bought 50 gold pencils. Updated!!', 'They looked so purdy and I couldn''t resist! :}', '2013-10-07 10:58:48', '2012-12-12 00:00:00', '2012-12-12 00:00:00', 0, 'Bob the Builder', 'Someone', 1, 0, 1),
(38, 1, 'Bought 50 gold pencils', 'They looked so purdy and I couldn''t resist! :}', '2013-10-07 14:12:19', '2012-12-12 00:00:00', '2012-12-12 00:00:00', 0, 'Bob the Builder', 'Someone', 19898, 0, 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
