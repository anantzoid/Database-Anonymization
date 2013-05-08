-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 27, 2013 at 09:00 PM
-- Server version: 5.5.16
-- PHP Version: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `nb`
--

-- --------------------------------------------------------

--
-- Table structure for table `table1`
--

CREATE TABLE IF NOT EXISTS `table1` (
  `sl` int(4) NOT NULL AUTO_INCREMENT,
  `Education` varchar(20) NOT NULL,
  `Sex` varchar(1) NOT NULL,
  `Work` int(3) NOT NULL,
  `Disease` varchar(20) NOT NULL,
  `Salary` int(6) NOT NULL,
  PRIMARY KEY (`sl`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=20 ;

--
-- Dumping data for table `table1`
--

INSERT INTO `table1` (`sl`, `Education`, `Sex`, `Work`, `Disease`, `Salary`) VALUES
(1, '9th', 'M', 32, 'Bronchitis', 3000),
(2, '9th', 'M', 30, 'Cholera', 1000),
(3, '9th', 'M', 33, 'Flu', 2000),
(4, '10th', 'F', 35, 'Bronchitis', 2000),
(5, '10th', 'F', 36, 'Cholera', 3000),
(6, '11th', 'M', 37, 'Flu', 3000),
(7, '12th', 'M', 38, 'Cholera', 3000),
(8, '12th', 'F', 38, 'Flu', 3000),
(9, '11th', 'M', 37, 'Bronchitis', 1000),
(10, 'Masters', 'M', 41, 'Cholera', 1000),
(11, 'Bachelors', 'F', 39, 'Bronchitis', 2000),
(12, 'Masters', 'M', 42, 'Flu', 1000),
(13, 'Masters', 'M', 44, 'Flu', 2000),
(14, 'Bachelors', 'F', 38, 'Bronchitis', 1000),
(15, 'Doctorate', 'F', 44, 'Cholera', 2000),
(16, 'Masters', 'F', 40, 'Flu', 1000),
(17, 'Doctorate', 'F', 44, 'Bronchitis', 1000),
(18, 'Doctorate', 'F', 45, 'Cholera', 3000),
(19, 'Doctorate', 'F', 44, 'Cholera', 2000);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
