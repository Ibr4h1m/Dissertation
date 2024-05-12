-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 12, 2024 at 10:50 PM
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
-- Database: `autocab`
--

-- --------------------------------------------------------

--
-- Table structure for table `customer_complaints`
--

CREATE TABLE `customer_complaints` (
  `complaint_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `phone_number` varchar(255) NOT NULL,
  `driver_name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `action` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `drivers`
--

CREATE TABLE `drivers` (
  `driver_id` int(11) NOT NULL,
  `date_started` date NOT NULL,
  `referral_id` varchar(255) DEFAULT NULL,
  `status` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `drivers`
--

INSERT INTO `drivers` (`driver_id`, `date_started`, `referral_id`, `status`) VALUES
(1096, '2024-02-06', '0', 0),
(1147, '2024-02-13', '0', 0),
(1225, '2024-01-08', '0', 1),
(1226, '2024-01-08', '0', 1),
(1228, '2024-02-02', '0', 0),
(1231, '2024-01-22', '1139', 1),
(1232, '2024-01-23', '2098', 0),
(1233, '2024-01-25', '1144', 1),
(1234, '2024-01-26', '0', 0),
(1237, '2024-02-17', '0', 0),
(1238, '2024-02-17', '0', 0),
(1240, '2024-02-20', '0', 0),
(1243, '2024-02-22', '0', 1),
(1249, '1212-12-12', '1212', 0),
(2052, '2024-02-19', '0', 0),
(2065, '2024-01-15', '0', 1),
(2091, '2024-01-25', '0', 1),
(2122, '2024-01-08', '0', 1),
(2128, '2024-01-28', '0', 0),
(2136, '2024-02-06', '2073', 0),
(2151, '2024-02-21', '0', 0),
(2160, '2024-02-12', '0', 0),
(2164, '2024-02-22', '0', 0),
(2178, '2024-01-12', '0', 1),
(2192, '2024-01-23', '0', 1),
(2204, '2024-02-21', '0', 0),
(2209, '2024-02-21', '0', 0),
(2216, '2024-02-21', '0', 0),
(2217, '2024-02-22', '0', 0),
(6969, '1212-12-12', '122', 0),
(8888, '2023-12-12', '12', 0),
(9898, '2024-05-02', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `drivers_logs`
--

CREATE TABLE `drivers_logs` (
  `log_id` int(11) NOT NULL,
  `log_date` date NOT NULL,
  `log_time` time NOT NULL,
  `username` varchar(255) NOT NULL,
  `action_taken` varchar(255) NOT NULL,
  `driver_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `drivers_logs`
--

INSERT INTO `drivers_logs` (`log_id`, `log_date`, `log_time`, `username`, `action_taken`, `driver_id`) VALUES
(19, '2024-02-14', '14:48:25', 'Ibrahim', 'Added', 1231),
(20, '2024-02-14', '14:49:01', 'Ibrahim', 'Added', 1225),
(21, '2024-02-14', '14:49:42', 'Ibrahim', 'Added', 2122),
(22, '2024-02-14', '14:50:06', 'Ibrahim', 'Added', 2178),
(23, '2024-02-14', '14:50:26', 'Ibrahim', 'Added', 2128),
(24, '2024-02-14', '14:51:11', 'Ibrahim', 'Added', 2192),
(25, '2024-02-14', '14:52:06', 'Ibrahim', 'Added', 1232),
(26, '2024-02-14', '14:52:31', 'Ibrahim', 'Added', 2065),
(27, '2024-02-14', '14:52:57', 'Ibrahim', 'Added', 2091),
(28, '2024-02-14', '14:53:41', 'Ibrahim', 'Added', 1051),
(29, '2024-02-14', '14:54:14', 'Ibrahim', 'Added', 1233),
(30, '2024-02-14', '14:54:52', 'Ibrahim', 'Added', 1234),
(31, '2024-02-14', '14:55:15', 'Ibrahim', 'Added', 1235),
(32, '2024-02-14', '14:55:51', 'Ibrahim', 'Added', 2088),
(33, '2024-02-14', '14:56:13', 'Ibrahim', 'Added', 2136),
(34, '2024-02-14', '14:56:41', 'Ibrahim', 'Added', 1226),
(35, '2024-02-14', '14:57:04', 'Ibrahim', 'Added', 1096),
(36, '2024-02-14', '14:57:22', 'Ibrahim', 'Added', 1147),
(37, '2024-02-14', '15:04:06', 'Ibrahim', 'Paid', 1225),
(38, '2024-02-14', '15:28:18', 'Ibrahim', 'Paid', 2122),
(39, '2024-02-14', '15:28:29', 'Ibrahim', 'Paid', 2178),
(40, '2024-02-14', '15:28:53', 'Ibrahim', 'Paid', 1226),
(41, '2024-02-14', '15:41:18', 'Jang', 'Paid', 2065),
(42, '2024-02-14', '15:45:25', 'Jang', 'Added', 2184),
(43, '2024-02-14', '15:45:58', 'Jang', 'Deleted', 2184),
(44, '2024-02-17', '12:25:53', 'Jang', 'Added', 2160),
(45, '2024-02-19', '11:12:15', 'Ibby', 'Paid', 2088),
(46, '2024-02-19', '15:23:59', 'Jang', 'Added', 2052),
(47, '2024-02-19', '15:24:40', 'Jang', 'Deleted', 2052),
(48, '2024-02-20', '09:44:13', 'Jang', 'Added', 1228),
(49, '2024-02-20', '09:54:18', 'Jang', 'Paid', 1231),
(50, '2024-02-20', '11:14:49', 'Jang', 'Added', 2052),
(51, '2024-02-20', '11:33:29', 'Jang', 'Added', 1239),
(52, '2024-02-21', '15:10:40', 'ibby', 'Deleted', 1239),
(53, '2024-02-21', '15:17:10', 'ibby', 'Added', 1237),
(54, '2024-02-21', '15:17:43', 'ibby', 'Added', 1238),
(55, '2024-02-22', '12:30:29', 'Jang', 'Added', 2204),
(56, '2024-02-22', '12:30:49', 'Jang', 'Added', 2209),
(57, '2024-02-22', '12:31:10', 'Jang', 'Added', 2216),
(58, '2024-02-22', '12:31:50', 'Jang', 'Added', 2151),
(59, '2024-02-22', '13:40:07', 'Jang', 'Added', 1243),
(60, '2024-02-22', '16:38:26', 'ibby', 'Added', 2164),
(61, '2024-02-22', '16:38:34', 'ibby', 'Added', 2217),
(62, '2024-02-23', '11:09:00', 'Ibby', 'Added', 1240),
(63, '2024-02-26', '10:00:16', 'Jang', 'Added', 2172),
(64, '2024-02-26', '11:22:27', 'Jang', 'Paid', 1051),
(65, '2024-02-26', '12:33:05', 'Jang', 'Paid', 2192),
(66, '2024-02-26', '12:36:38', 'Jang', 'Paid', 1233),
(67, '2024-02-26', '12:40:12', 'Jang', 'Paid', 2091),
(68, '2024-02-26', '12:41:54', 'Jang', 'Deleted', 1235),
(69, '2024-02-28', '12:33:52', 'Ibby', 'Added', 1244),
(70, '2024-02-28', '12:34:01', 'Ibby', 'Added', 1245),
(71, '2024-02-29', '09:33:51', 'Ibby', 'Added', 1249),
(76, '2024-03-01', '20:23:01', 'Ibby', 'Deleted', 1244),
(77, '2024-03-01', '20:23:04', 'Ibby', 'Deleted', 1245),
(78, '2024-03-01', '20:23:10', 'Ibby', 'Deleted', 1051),
(79, '2024-03-01', '20:23:17', 'Ibby', 'Deleted', 1249),
(80, '2024-03-01', '20:23:23', 'Ibby', 'Added', 1249),
(81, '2024-03-01', '20:34:06', 'Ibby', 'Added', 6969),
(82, '2024-03-01', '20:36:16', 'Ibby', 'Deleted', 2172),
(83, '2024-03-01', '20:36:25', 'Ibby', 'Added', 1111),
(84, '2024-03-01', '20:36:30', 'Ibby', 'Paid', 1111),
(85, '2024-03-01', '20:36:35', 'Ibby', 'Deleted', 1111),
(86, '2024-03-01', '20:36:42', 'Ibby', 'Added', 1111),
(87, '2024-03-01', '20:36:47', 'Ibby', 'Paid', 1111),
(88, '2024-03-01', '20:37:49', 'Ibby', 'Deleted', 1111),
(89, '2024-03-01', '20:46:52', 'Ibby', 'Added', 8888),
(90, '2024-03-01', '20:46:59', 'Ibby', 'Paid', 8888),
(91, '2024-03-01', '20:47:05', 'Ibby', 'Deleted', 2088),
(92, '2024-03-01', '20:47:26', 'Ibby', 'Deleted', 8888),
(93, '2024-03-01', '20:47:34', 'Ibby', 'Added', 8888),
(94, '2024-05-08', '17:06:32', 'Ibby', 'Paid', 1243),
(95, '2024-05-08', '17:07:02', 'Ibby', 'Added', 9898);

-- --------------------------------------------------------

--
-- Table structure for table `lost_property`
--

CREATE TABLE `lost_property` (
  `id` int(11) NOT NULL,
  `operator_name` varchar(255) NOT NULL,
  `job_id` int(11) NOT NULL,
  `driver_callsign` varchar(255) NOT NULL,
  `pickup_date` date NOT NULL,
  `pickup_time` time NOT NULL,
  `drop_off_time` time NOT NULL,
  `property_description` text DEFAULT NULL,
  `item_colour` varchar(255) DEFAULT NULL,
  `status` enum('returned','pending') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lost_property_archive`
--

CREATE TABLE `lost_property_archive` (
  `id` int(11) NOT NULL,
  `operator_name` varchar(255) NOT NULL,
  `job_id` int(11) NOT NULL,
  `driver_callsign` varchar(255) NOT NULL,
  `pickup_date` date NOT NULL,
  `pickup_time` time NOT NULL,
  `drop_off_time` time NOT NULL,
  `property_description` text DEFAULT NULL,
  `item_colour` varchar(255) DEFAULT NULL,
  `status` enum('returned','pending') DEFAULT 'pending',
  `archived_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lost_property_archive`
--

INSERT INTO `lost_property_archive` (`id`, `operator_name`, `job_id`, `driver_callsign`, `pickup_date`, `pickup_time`, `drop_off_time`, `property_description`, `item_colour`, `status`, `archived_at`) VALUES
(32, 'Jang', 1234, '2184', '1010-10-10', '10:10:00', '10:10:00', 'phone', 'black', 'returned', '2024-02-14 15:47:36'),
(33, 'Ibrahim', 5000, '123', '1212-12-12', '12:12:00', '12:12:00', 'Hello', 'Black', 'pending', '2024-02-14 15:49:45'),
(34, 'waqas', 2345, '2345', '0000-00-00', '23:23:00', '23:23:00', 'iphone', 'black', 'pending', '2024-02-20 11:34:52'),
(35, 'mobeen', 123, '1233', '2021-02-10', '12:12:00', '12:12:00', 'iphone', 'black', 'returned', '2024-02-26 10:26:41'),
(36, 'fareed', 123321, '1231', '2121-12-12', '21:12:00', '12:12:00', 'iphone', 'black', 'returned', '2024-02-26 13:18:53'),
(37, 'mohsin', 12341, '1234', '2020-10-10', '20:20:00', '20:20:00', 'iphone 14', 'black', 'returned', '2024-02-26 16:06:16'),
(38, 'Ibby', 12, '12', '1212-12-12', '12:12:00', '12:12:00', '1212', '12', 'pending', '2024-03-05 14:10:23');

-- --------------------------------------------------------

--
-- Table structure for table `lost_property_logs`
--

CREATE TABLE `lost_property_logs` (
  `log_id` int(11) NOT NULL,
  `log_date` date NOT NULL,
  `log_time` time NOT NULL,
  `username` varchar(255) NOT NULL,
  `action_taken` enum('added','edited','deleted') NOT NULL,
  `record_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lost_property_logs`
--

INSERT INTO `lost_property_logs` (`log_id`, `log_date`, `log_time`, `username`, `action_taken`, `record_id`) VALUES
(20, '2024-02-14', '15:47:12', 'Jang', 'added', 32),
(21, '2024-02-14', '15:47:55', 'Jang', 'edited', 32),
(22, '2024-02-14', '15:48:07', 'Jang', 'deleted', 32),
(23, '2024-02-14', '15:50:05', 'Ibrahim', 'added', 33),
(24, '2024-02-14', '15:50:16', 'Ibrahim', 'deleted', 33),
(25, '2024-02-20', '11:34:48', 'waqas', 'added', 34),
(26, '2024-02-20', '11:35:34', 'Ibby', 'deleted', 34),
(27, '2024-02-26', '10:22:18', 'mobeen', 'added', 35),
(28, '2024-02-26', '10:22:28', 'mobeen', 'edited', 35),
(29, '2024-02-26', '10:27:21', 'mobeen', 'deleted', 35),
(30, '2024-02-26', '10:33:16', 'fareed', 'added', 36),
(31, '2024-02-26', '10:33:34', 'fareed', 'edited', 36),
(32, '2024-02-26', '13:19:33', 'Ibby', 'deleted', 36),
(33, '2024-02-26', '16:05:46', 'mohsin', 'added', 37),
(34, '2024-02-26', '16:06:15', 'mobeen', 'edited', 37),
(35, '2024-02-26', '16:06:56', 'mobeen', 'deleted', 37),
(36, '2024-03-05', '15:10:12', 'Ibby', 'added', 38),
(37, '2024-03-05', '15:10:23', 'Ibby', 'deleted', 38);

-- --------------------------------------------------------

--
-- Table structure for table `new_drivers_archive`
--

CREATE TABLE `new_drivers_archive` (
  `driver_id` int(11) NOT NULL,
  `date_started` date NOT NULL,
  `referral_id` varchar(255) DEFAULT NULL,
  `status` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `new_drivers_archive`
--

INSERT INTO `new_drivers_archive` (`driver_id`, `date_started`, `referral_id`, `status`) VALUES
(1051, '2024-01-25', '0', 1),
(1111, '1212-12-12', '', 1),
(1235, '2024-01-26', '0', 0),
(1239, '2024-02-20', '1141', 0),
(1244, '2024-02-28', '0', 0),
(1245, '2024-02-28', '0', 0),
(1249, '2024-02-28', '1172', 0),
(2052, '2024-02-19', '', 0),
(2172, '2024-02-26', '', 0),
(6969, '2002-01-01', '01', 0),
(1111, '1212-12-12', '11212', 1),
(2088, '2024-01-19', '2184', 1),
(8888, '2023-12-12', '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `shifts`
--

CREATE TABLE `shifts` (
  `shift_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `clock_in` datetime NOT NULL,
  `clock_out` datetime DEFAULT NULL,
  `total_hours` decimal(5,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shifts`
--

INSERT INTO `shifts` (`shift_id`, `username`, `clock_in`, `clock_out`, `total_hours`) VALUES
(152, 'Lisa', '2024-04-04 12:00:00', '2024-04-04 23:00:00', 11.00),
(155, 'lisa', '2024-04-19 13:22:21', '2024-04-19 13:22:33', 0.00),
(157, 'lisa', '2024-05-08 16:10:00', '2024-05-08 16:10:08', 0.00),
(158, 'staff', '2024-05-12 21:49:26', '2024-05-12 21:49:28', 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `Rank` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `Rank`) VALUES
(31, 'admin', '$2y$10$MTkwsfu9NmPg4tp0/pGy8u1HRCB/iODDOcen4rPfghncsa0515aDa', 2),
(32, 'staff', '$2y$10$eZSIpGBOV2Fuf2eG3n8wa.8dtV5ipWn376WtxvtInEXC27rCI8VLy', 1);

-- --------------------------------------------------------

--
-- Table structure for table `vehicles`
--

CREATE TABLE `vehicles` (
  `id` int(11) NOT NULL,
  `registration` varchar(255) NOT NULL,
  `mot_expiry` date NOT NULL,
  `council_name` enum('SOT','Wolverhampton','Ashfield','Newcastle') NOT NULL,
  `plate_expiry` date NOT NULL,
  `interim_expiry` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vehicles`
--

INSERT INTO `vehicles` (`id`, `registration`, `mot_expiry`, `council_name`, `plate_expiry`, `interim_expiry`) VALUES
(21, 'BL71AAA', '2025-04-01', 'SOT', '2024-04-01', NULL),
(22, 'FS12 AQW', '2025-12-12', 'SOT', '2026-01-01', '2200-02-20');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customer_complaints`
--
ALTER TABLE `customer_complaints`
  ADD PRIMARY KEY (`complaint_id`);

--
-- Indexes for table `drivers_logs`
--
ALTER TABLE `drivers_logs`
  ADD PRIMARY KEY (`log_id`);

--
-- Indexes for table `lost_property`
--
ALTER TABLE `lost_property`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lost_property_archive`
--
ALTER TABLE `lost_property_archive`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lost_property_logs`
--
ALTER TABLE `lost_property_logs`
  ADD PRIMARY KEY (`log_id`);

--
-- Indexes for table `shifts`
--
ALTER TABLE `shifts`
  ADD PRIMARY KEY (`shift_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customer_complaints`
--
ALTER TABLE `customer_complaints`
  MODIFY `complaint_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `drivers_logs`
--
ALTER TABLE `drivers_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=96;

--
-- AUTO_INCREMENT for table `lost_property`
--
ALTER TABLE `lost_property`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `lost_property_archive`
--
ALTER TABLE `lost_property_archive`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `lost_property_logs`
--
ALTER TABLE `lost_property_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `shifts`
--
ALTER TABLE `shifts`
  MODIFY `shift_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=159;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `vehicles`
--
ALTER TABLE `vehicles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
