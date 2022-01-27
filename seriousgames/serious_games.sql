-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 29, 2021 at 12:58 PM
-- Server version: 10.4.6-MariaDB
-- PHP Version: 7.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `serious_games`
--

-- --------------------------------------------------------

--
-- Table structure for table `leeruser`
--

CREATE TABLE `leeruser` (
  `leerid` int(10) NOT NULL,
  `vletter` varchar(10) NOT NULL,
  `anaam` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `leeruser`
--

INSERT INTO `leeruser` (`leerid`, `vletter`, `anaam`, `username`, `password`, `email`) VALUES
(1, 'C', 'Huang', '69helpme', '9111b0f3e334db4f883535214cb69cbcf79e4bbf', 'chen1234huang@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `level_req`
--

CREATE TABLE `level_req` (
  `req_id` int(10) NOT NULL,
  `rank` varchar(100) NOT NULL,
  `require_to_next` int(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `level_req`
--

INSERT INTO `level_req` (`req_id`, `rank`, `require_to_next`) VALUES
(1, 'Newbie', 100),
(2, 'Beginner', 1000),
(3, 'Pro', 4000),
(4, 'Master', 9001),
(5, 'Over 9000', 14000),
(6, 'Mastermind', 20000),
(7, 'Asian', 2147483647);

-- --------------------------------------------------------

--
-- Table structure for table `userlevel`
--

CREATE TABLE `userlevel` (
  `leerid` int(10) NOT NULL,
  `experience` int(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `userlevel`
--

INSERT INTO `userlevel` (`leerid`, `experience`) VALUES
(1, 8950);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `leeruser`
--
ALTER TABLE `leeruser`
  ADD PRIMARY KEY (`leerid`),
  ADD UNIQUE KEY `username` (`username`,`email`),
  ADD UNIQUE KEY `leerid` (`leerid`);

--
-- Indexes for table `level_req`
--
ALTER TABLE `level_req`
  ADD PRIMARY KEY (`req_id`),
  ADD KEY `req_id` (`req_id`);

--
-- Indexes for table `userlevel`
--
ALTER TABLE `userlevel`
  ADD PRIMARY KEY (`leerid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `leeruser`
--
ALTER TABLE `leeruser`
  MODIFY `leerid` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `level_req`
--
ALTER TABLE `level_req`
  MODIFY `req_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `userlevel`
--
ALTER TABLE `userlevel`
  MODIFY `leerid` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `userlevel`
--
ALTER TABLE `userlevel`
  ADD CONSTRAINT `userlevel_ibfk_1` FOREIGN KEY (`leerid`) REFERENCES `leeruser` (`leerid`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
