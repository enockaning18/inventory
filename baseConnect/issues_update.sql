-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 03, 2025 at 12:44 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ipmc_inventory`
--

-- --------------------------------------------------------

--
-- Table structure for table `issues`
--

CREATE TABLE `issues` (
  `id` int(11) NOT NULL,
  `system` int(11) DEFAULT NULL,
  `monitor` int(11) DEFAULT NULL,
  `serial_number` varchar(100) NOT NULL,
  `issue_type` varchar(100) NOT NULL,
  `resolved_type` varchar(50) DEFAULT NULL,
  `lab` int(11) DEFAULT NULL,
  `issue_status` varchar(100) NOT NULL,
  `issue_date` date NOT NULL DEFAULT current_timestamp(),
  `issue_description` varchar(500) NOT NULL,
  `sent_to_accra` tinyint(1) NOT NULL,
  `date_added` datetime NOT NULL DEFAULT current_timestamp(),
  `date_returned` date DEFAULT current_timestamp(),
  `device_category` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `issues`
--
ALTER TABLE `issues`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fklab_id` (`lab`),
  ADD KEY `computer_id` (`system`),
  ADD KEY `fk_monitor_id` (`monitor`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `issues`
--
ALTER TABLE `issues`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `issues`
--
ALTER TABLE `issues`
  ADD CONSTRAINT `fk_monitor_id` FOREIGN KEY (`monitor`) REFERENCES `monitor` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_system_id` FOREIGN KEY (`system`) REFERENCES `system` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fklab_id` FOREIGN KEY (`lab`) REFERENCES `lab` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
