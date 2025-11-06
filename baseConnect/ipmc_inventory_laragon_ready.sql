CREATE DATABASE IF NOT EXISTS `ipmc_inventory` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `ipmc_inventory`;

-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 01, 2025 at 01:26 AM
-- Server version: 10.4.16-MariaDB
-- PHP Version: 7.4.12

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
-- Table structure for table `brand`
--

CREATE TABLE `brand` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `brand_name` varchar(100) NOT NULL,
  `date_added` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `brand`
--

INSERT INTO `brand` (`id`, `brand_name`, `date_added`) VALUES
(1, 'Lenovo ', '2025-10-27'),
(2, 'HP', '2025-10-28'),
(3, 'Dell', '2025-10-29');

-- --------------------------------------------------------

--
-- Table structure for table `computers`
--

CREATE TABLE `computers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `computer_name` varchar(100) NOT NULL,
  `brand` int(11) NOT NULL,
  `serial_number` varchar(100) NOT NULL,
  `memory_size` varchar(50) NOT NULL,
  `hard_drive_size` varchar(100) NOT NULL,
  `lab` int(11) NOT NULL,
  `monitor_name` varchar(100) NOT NULL,
  `size` varchar(20) NOT NULL,
  `monitor_serial` varchar(100) NOT NULL,
  `date_added` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `computers`
--

INSERT INTO `computers` (`id`, `computer_name`, `brand`, `serial_number`, `memory_size`, `hard_drive_size`, `lab`, `monitor_name`, `size`, `monitor_serial`, `date_added`) VALUES
(10, 'i7 10Gen system unit', 2, '111222333000', '12', '256', 1, 'MonitorOne', '19inches', '000111222333', '2025-10-30');

-- --------------------------------------------------------

--
-- Table structure for table `course`
--

CREATE TABLE `course` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `course_name` varchar(100) NOT NULL,
  `createdby` int(11) NOT NULL,
  `datecreated` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `course`
--

INSERT INTO `course` (`id`, `course_name`, `createdby`, `datecreated`) VALUES
(4, 'Database', 7, '2025-10-28'),
(6, 'Software Engineering', 7, '2025-10-28'),
(8, 'Graphics & Web', 7, '2025-10-29'),
(11, 'IT @ Workplace', 7, '2025-10-29'),
(12, 'Graphics & Web Design', 7, '2025-10-29'),
(14, 'Software', 7, '2025-10-29'),
(15, 'System Eng', 7, '2025-10-30'),
(16, 'Hardware', 7, '2025-10-30');

-- --------------------------------------------------------

--
-- Table structure for table `examination`
--

CREATE TABLE `examination` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `examination_date` date NOT NULL,
  `batch_time` varchar(100) NOT NULL,
  ``session`` varchar(100) NOT NULL,
  `course_id` int(11) NOT NULL,
  `date_booked` date NOT NULL,
  `start_time` varchar(20) NOT NULL,
  `module_id` int(11) NOT NULL,
  `instructor_id` int(11) NOT NULL,
  `batch_semester` varchar(100) NOT NULL,
  `status` enum('approve','pending','cancelled') NOT NULL DEFAULT 'pending' COMMENT '1-approved\r\n2-pending\r\n3-cancelled',
  `date_added` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `examination`
--

INSERT INTO `examination` (`id`, `examination_date`, `batch_time`, ``session``, `course_id`, `date_booked`, `start_time`, `module_id`, `instructor_id`, `batch_semester`, `status`, `date_added`) VALUES
(12, '2025-11-07', '1pm - 3pm', 'Weekday', 6, '2025-10-30', '13:00', 1, 7, 'Sem-2', 'cancelled', '2025-10-30'),
(13, '2025-11-03', '11am - 1pm', 'Weekday', 8, '2025-10-30', '11:00', 8, 7, 'Sem-2', 'approve', '2025-10-30'),
(14, '2025-11-05', '3pm - 5pm', 'Weekday', 6, '2025-10-31', '15:00', 2, 7, 'Sem-1', 'pending', '2025-10-31');

-- --------------------------------------------------------

--
-- Table structure for table `instructors`
--

CREATE TABLE `instructors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `lab_id` int(11) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `course_id` int(11) NOT NULL,
  `date_added` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `instructors`
--

INSERT INTO `instructors` (`id`, `first_name`, `last_name`, `lab_id`, `phone`, `email`, `course_id`, `date_added`) VALUES
(7, 'Unicom', 'Center', 4, '0240526430', 'mckenzieraney43@gmail.com', 8, '2025-10-29'),
(13, 'Agyei', 'Emmanuel', 3, '0260613128', 'smgee43@gmail.com', 11, '2025-10-30'),
(15, 'Daniel', 'Darko', 4, '0283819314', 'info@luxuryshippinghome.com', 16, '2025-10-31');

-- --------------------------------------------------------

--
-- Table structure for table `issues`
--

CREATE TABLE `issues` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `computer` int(11) NOT NULL,
  `issue_type` varchar(100) NOT NULL,
  `lab` int(11) DEFAULT NULL,
  `issue_date` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `issue_description` varchar(500) NOT NULL,
  `date_added` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `lab`
--

CREATE TABLE `lab` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lab_name` varchar(100) NOT NULL,
  `course_id` int(11) NOT NULL,
  `number_computers` varchar(100) NOT NULL,
  `date_added` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `lab`
--

INSERT INTO `lab` (`id`, `lab_name`, `course_id`, `number_computers`, `date_added`) VALUES
(1, 'Lab2', 4, '10', '2025-10-27'),
(3, 'Lab4', 6, '30', '2025-10-29'),
(4, 'Lab3', 8, '25', '2025-10-29'),
(7, 'Lab5', 16, '18', '2025-10-30');

-- --------------------------------------------------------

--
-- Table structure for table `module`
--

CREATE TABLE `module` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `semester` varchar(20) NOT NULL,
  `course_id` int(11) NOT NULL,
  `date_created` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `module`
--

INSERT INTO `module` (`id`, `name`, `semester`, `course_id`, `date_created`) VALUES
(1, 'C#.NET', 'Sem-2', 6, '2025-10-29'),
(2, 'Programming Methods', 'Sem-1', 6, '2025-10-29'),
(3, 'Core Java', 'Sem-1', 6, '2025-10-29'),
(5, 'Adobe Premier', 'Sem-2', 12, '2025-10-29'),
(8, 'System Analysis & Design', 'Sem-2', 8, '2025-10-29');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(100) NOT NULL,
  `user_type` varchar(30) NOT NULL COMMENT '1-admin\r\n2-instructor\r\n3-student',
  `instructor_id` int(11) NOT NULL,
  `user_key` varchar(255) NOT NULL,
  `defaultkey` varchar(50) NOT NULL,
  `date_created` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `user_type`, `instructor_id`, `user_key`, `defaultkey`, `date_created`) VALUES
(7, 'admin@email.com', 'admin', 7, '$2y$10$vXv/lTUj2NrqNax.oElRUugEUsNxgaOCwymyw6CCZ3pGIfDUexUO.', '', '2025-10-30'),
(31, 'smgee43@gmail.com', 'instructor', 13, '$2y$10$bdjh/1xfvb8jqvZyKEdnseKQ5/mPZRcGHrvlqd2nvG/.16uPTIiwm', 'Instructor2025!', '2025-10-31');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `brand`
--
ALTER TABLE `brand`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `computers`
--
ALTER TABLE `computers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `brand_id` (`brand`),
  ADD KEY `lab_id` (`lab`);

--
-- Indexes for table `course`
--
ALTER TABLE `course`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `course_name` (`course_name`),
  ADD KEY `createdby_fk` (`createdby`);

--
-- Indexes for table `examination`
--
ALTER TABLE `examination`
  ADD PRIMARY KEY (`id`),
  ADD KEY `courseid_fk` (`course_id`),
  ADD KEY `moduleid_fk` (`module_id`),
  ADD KEY `instructorid_fk` (`instructor_id`);

--
-- Indexes for table `instructors`
--
ALTER TABLE `instructors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `phone` (`phone`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `fk_lab_id` (`lab_id`),
  ADD KEY `fk_course_id` (`course_id`);

--
-- Indexes for table `issues`
--
ALTER TABLE `issues`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fklab_id` (`lab`),
  ADD KEY `computer_id` (`computer`);

--
-- Indexes for table `lab`
--
ALTER TABLE `lab`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_course_id` (`course_id`) USING BTREE;

--
-- Indexes for table `module`
--
ALTER TABLE `module`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `course_fk_id` (`course_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `instructor_id_fk` (`instructor_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `brand`
--
ALTER TABLE `brand`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `computers`
--
ALTER TABLE `computers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `course`
--
ALTER TABLE `course`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `examination`
--
ALTER TABLE `examination`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `instructors`
--
ALTER TABLE `instructors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `issues`
--
ALTER TABLE `issues`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `lab`
--
ALTER TABLE `lab`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `module`
--
ALTER TABLE `module`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `computers`
--
ALTER TABLE `computers`
  ADD CONSTRAINT `brand_id` FOREIGN KEY (`brand`) REFERENCES `brand` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `lab_id` FOREIGN KEY (`lab`) REFERENCES `lab` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `course`
--
ALTER TABLE `course`
  ADD CONSTRAINT `createdby_fk` FOREIGN KEY (`createdby`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `examination`
--
ALTER TABLE `examination`
  ADD CONSTRAINT `courseid_fk` FOREIGN KEY (`course_id`) REFERENCES `course` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `instructorid_fk` FOREIGN KEY (`instructor_id`) REFERENCES `instructors` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `moduleid_fk` FOREIGN KEY (`module_id`) REFERENCES `module` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `instructors`
--
ALTER TABLE `instructors`
  ADD CONSTRAINT `fk_course_id` FOREIGN KEY (`course_id`) REFERENCES `course` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_lab_id` FOREIGN KEY (`lab_id`) REFERENCES `lab` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `issues`
--
ALTER TABLE `issues`
  ADD CONSTRAINT `computer_id` FOREIGN KEY (`computer`) REFERENCES `computers` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fklab_id` FOREIGN KEY (`lab`) REFERENCES `lab` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `lab`
--
ALTER TABLE `lab`
  ADD CONSTRAINT `fk_courseid` FOREIGN KEY (`course_id`) REFERENCES `course` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `module`
--
ALTER TABLE `module`
  ADD CONSTRAINT `course_fk_id` FOREIGN KEY (`course_id`) REFERENCES `course` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `instructor_id_fk` FOREIGN KEY (`instructor_id`) REFERENCES `instructors` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
