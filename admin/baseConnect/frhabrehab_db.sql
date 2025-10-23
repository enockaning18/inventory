-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 17, 2025 at 03:27 PM
-- Server version: 10.4.16-MariaDB
-- PHP Version: 7.4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `frhabrehab_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `contact`
--

CREATE TABLE `contact` (
  `form_id` int(11) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `mobile` varchar(20) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `contact`
--

INSERT INTO `contact` (`form_id`, `firstname`, `lastname`, `email`, `mobile`, `subject`, `message`) VALUES
(30, '', '', 'mckenzieraney43@gmail.com', '0240526430', 'CHARGE INCLUSION', 'Message Test');

-- --------------------------------------------------------

--
-- Table structure for table `donationfrm`
--

CREATE TABLE `donationfrm` (
  `donation_id` int(11) NOT NULL,
  `donor_firstname` varchar(100) NOT NULL,
  `donor_lastname` varchar(100) NOT NULL,
  `donor_phone` varchar(20) DEFAULT NULL,
  `donation_type` enum('cash','goods','service','other') NOT NULL,
  `item_donated` varchar(255) DEFAULT NULL,
  `donation_date` date NOT NULL,
  `datetimestamp` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `news_stats`
--

CREATE TABLE `news_stats` (
  `id` int(11) NOT NULL,
  `news_id` int(11) NOT NULL,
  `views` int(11) DEFAULT 0,
  `likes` int(11) DEFAULT 0,
  `date_time` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `news_stats`
--

INSERT INTO `news_stats` (`id`, `news_id`, `views`, `likes`, `date_time`) VALUES
(1, 1, 56, 18, '2025-10-17 00:22:46'),
(2, 2, 14, 0, '2025-10-17 01:16:27'),
(3, 3, 14, 0, '2025-10-17 01:16:27');

-- --------------------------------------------------------

--
-- Table structure for table `sponsorfrm`
--

CREATE TABLE `sponsorfrm` (
  `sponsor_id` int(11) NOT NULL,
  `organization_name` varchar(255) NOT NULL,
  `contact_person` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `sponsorship_type` enum('Financial','Material','Service','Other') DEFAULT NULL,
  `sponsorship_item` varchar(255) DEFAULT NULL,
  `sponsorship_description` text DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `datetimestamp` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `sponsorfrm`
--

INSERT INTO `sponsorfrm` (`sponsor_id`, `organization_name`, `contact_person`, `email`, `phone`, `address`, `sponsorship_type`, `sponsorship_item`, `sponsorship_description`, `start_date`, `end_date`, `datetimestamp`) VALUES
(1, 'Okyere Enock', 'Okyere Enock', 'admin@mail.com', '0556061647', 'Accra-Ghana', 'Material', 'Food   ', '  Tasty Tom', '2025-10-11', '2025-10-19', '2025-10-02 11:16:43'),
(6, 'Luxury Shipping Container Home', 'Daniel Darko', 'info@luxuryshippinghome.com', '0260613128', 'Sunyani-Berlin top, Ghana', 'Material', 'Shoes and Bags', '  Testing', '2025-10-02', '0000-00-00', '2025-10-02 23:43:24');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `firstname` varchar(60) NOT NULL,
  `lastname` varchar(60) NOT NULL,
  `email` varchar(100) NOT NULL,
  `userid` varchar(50) NOT NULL,
  `userkey` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstname`, `lastname`, `email`, `userid`, `userkey`, `created_at`) VALUES
(30, 'Unicom', 'Center', 'admin@mail.com', 'admin', '90b9aa7e25f80cf4f64e990b78a9fc5ebd6cecad', '2025-10-06 01:35:19');

-- --------------------------------------------------------

--
-- Table structure for table `visitor_info`
--

CREATE TABLE `visitor_info` (
  `visitor_id` int(11) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `user_agent` text NOT NULL,
  `operating_system` varchar(100) DEFAULT NULL,
  `browser_name` varchar(100) DEFAULT NULL,
  `browser_version` varchar(50) DEFAULT NULL,
  `visit_date` datetime NOT NULL DEFAULT current_timestamp(),
  `country` varchar(100) DEFAULT NULL,
  `datetimestamp` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `voluntaryfrm`
--

CREATE TABLE `voluntaryfrm` (
  `voluntary_id` int(11) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `gender` enum('Male','Female','Other') DEFAULT NULL,
  `educational_background` varchar(100) DEFAULT NULL,
  `nationality` varchar(50) DEFAULT NULL,
  `days_available` varchar(100) DEFAULT NULL,
  `additional_notes` text DEFAULT NULL,
  `datetimestamp` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `contact`
--
ALTER TABLE `contact`
  ADD PRIMARY KEY (`form_id`);

--
-- Indexes for table `donationfrm`
--
ALTER TABLE `donationfrm`
  ADD PRIMARY KEY (`donation_id`);

--
-- Indexes for table `news_stats`
--
ALTER TABLE `news_stats`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sponsorfrm`
--
ALTER TABLE `sponsorfrm`
  ADD PRIMARY KEY (`sponsor_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `firstname` (`firstname`),
  ADD UNIQUE KEY `lastname` (`lastname`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`userid`);

--
-- Indexes for table `visitor_info`
--
ALTER TABLE `visitor_info`
  ADD PRIMARY KEY (`visitor_id`);

--
-- Indexes for table `voluntaryfrm`
--
ALTER TABLE `voluntaryfrm`
  ADD PRIMARY KEY (`voluntary_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `contact`
--
ALTER TABLE `contact`
  MODIFY `form_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `donationfrm`
--
ALTER TABLE `donationfrm`
  MODIFY `donation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `news_stats`
--
ALTER TABLE `news_stats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `sponsorfrm`
--
ALTER TABLE `sponsorfrm`
  MODIFY `sponsor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `visitor_info`
--
ALTER TABLE `visitor_info`
  MODIFY `visitor_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `voluntaryfrm`
--
ALTER TABLE `voluntaryfrm`
  MODIFY `voluntary_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
