-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 23, 2025 at 01:01 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gfi_exel`
--

-- --------------------------------------------------------

--
-- Table structure for table `computation`
--

CREATE TABLE `computation` (
  `computation_id` int(6) UNSIGNED NOT NULL,
  `employee_id` int(11) NOT NULL,
  `overload_rate` decimal(10,2) NOT NULL,
  `overload_total` decimal(10,2) NOT NULL,
  `wr_hr` decimal(10,2) NOT NULL,
  `wr_rate` decimal(10,2) NOT NULL,
  `wr_total` decimal(10,2) NOT NULL,
  `adjust_hr` decimal(10,2) NOT NULL,
  `adjust_rate` decimal(10,2) NOT NULL,
  `adjust_total` decimal(10,2) NOT NULL,
  `watch_hr` decimal(10,2) NOT NULL,
  `watch_rate` decimal(10,2) NOT NULL,
  `watch_total` decimal(10,2) NOT NULL,
  `gross_pay` decimal(10,2) NOT NULL,
  `absent_late_hr` decimal(10,2) NOT NULL,
  `absent_late_rate` decimal(10,2) NOT NULL,
  `absent_late_total` decimal(10,2) NOT NULL,
  `pagibig` decimal(10,2) NOT NULL,
  `mp2` decimal(10,2) NOT NULL,
  `sss` decimal(10,0) NOT NULL,
  `ret` decimal(10,2) NOT NULL,
  `canteen` decimal(10,2) NOT NULL,
  `others` decimal(10,2) NOT NULL,
  `total_deduction` decimal(10,2) NOT NULL,
  `net_pay` decimal(10,2) NOT NULL,
  `reg_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `computation`
--

INSERT INTO `computation` (`computation_id`, `employee_id`, `overload_rate`, `overload_total`, `wr_hr`, `wr_rate`, `wr_total`, `adjust_hr`, `adjust_rate`, `adjust_total`, `watch_hr`, `watch_rate`, `watch_total`, `gross_pay`, `absent_late_hr`, `absent_late_rate`, `absent_late_total`, `pagibig`, `mp2`, `sss`, `ret`, `canteen`, `others`, `total_deduction`, `net_pay`, `reg_date`) VALUES
(39, 401, 3545.00, 435.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 14435.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0.00, 0.00, 0.00, 0.00, 14435.00, '2025-02-23 12:00:26'),
(40, 402, 5345.00, 200.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 45200.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0.00, 0.00, 0.00, 0.00, 45200.00, '2025-02-23 12:00:26'),
(41, 403, 6423.00, 1266.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 51266.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0.00, 0.00, 0.00, 0.00, 51266.00, '2025-02-23 12:00:26'),
(42, 404, 433.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 8345.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0.00, 0.00, 0.00, 0.00, 8345.00, '2025-02-23 12:00:26'),
(43, 423, 232.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 16000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0.00, 0.00, 0.00, 0.00, 16000.00, '2025-02-23 12:00:26'),
(44, 424, 334.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 18100.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0.00, 0.00, 0.00, 0.00, 18100.00, '2025-02-23 12:00:26'),
(45, 425, 400.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 5353.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0.00, 0.00, 0.00, 0.00, 5353.00, '2025-02-23 12:00:26'),
(46, 441, 200.00, 9800.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 24000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0.00, 0.00, 0.00, 0.00, 24000.00, '2025-02-23 12:00:26'),
(47, 444, 100.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 19000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0.00, 0.00, 0.00, 0.00, 19000.00, '2025-02-23 11:59:37');

-- --------------------------------------------------------

--
-- Table structure for table `contributions`
--

CREATE TABLE `contributions` (
  `contributions_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `sss_ee` decimal(11,0) NOT NULL,
  `pag_ibig_ee` decimal(11,0) NOT NULL,
  `philhealth_ee` decimal(11,0) NOT NULL,
  `sss_er` decimal(11,0) NOT NULL,
  `pag_ibig_er` decimal(11,0) NOT NULL,
  `philhealth_er` decimal(11,0) NOT NULL,
  `medical_savings` decimal(10,0) NOT NULL,
  `retirement` decimal(10,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contribution_percentages`
--

CREATE TABLE `contribution_percentages` (
  `percentage_id` int(11) NOT NULL,
  `sss_er_percentage` decimal(5,2) NOT NULL,
  `sss_ee_percentage` decimal(5,2) NOT NULL,
  `philhealth_er_percentage` decimal(5,2) NOT NULL,
  `philhealth_ee_percentage` decimal(5,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contribution_percentages`
--

INSERT INTO `contribution_percentages` (`percentage_id`, `sss_er_percentage`, `sss_ee_percentage`, `philhealth_er_percentage`, `philhealth_ee_percentage`) VALUES
(8, 4.00, 9.00, 6.00, 5.00);

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `employee_id` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `suffix_title` varchar(50) NOT NULL,
  `employee_type` enum('full-time','part-time') NOT NULL,
  `classification` varchar(255) NOT NULL,
  `basic_salary` decimal(10,2) NOT NULL,
  `honorarium` decimal(10,2) DEFAULT 0.00,
  `incentives` decimal(10,0) NOT NULL,
  `overload_rate` decimal(10,0) NOT NULL,
  `watch_reward` decimal(10,0) NOT NULL,
  `absent_lateRate` decimal(10,0) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`employee_id`, `first_name`, `last_name`, `suffix_title`, `employee_type`, `classification`, `basic_salary`, `honorarium`, `incentives`, `overload_rate`, `watch_reward`, `absent_lateRate`, `created_at`) VALUES
(401, 'Manila', 'Bay', 'MIT', 'full-time', 'Dancer', 8000.00, 6000.00, 500, 3545, 1234, 77, '2025-01-14 09:43:19'),
(402, 'Williedfg', 'Pacquiao', '', 'full-time', 'Boxer', 25000.00, 20000.00, 500, 5345, 3342, 240, '2025-01-14 09:44:02'),
(403, 'genisis', 'mantilla', '', 'full-time', 'Roblox', 40000.00, 10000.00, 500, 6423, 6454, 385, '2025-01-14 10:13:30'),
(404, 'John', 'Joseph', '', 'part-time', 'Monkey Elepant', 8000.00, 345.00, 500, 433, 432, 77, '2025-01-14 10:14:56'),
(423, 'MIley', 'Zyrus', '', 'full-time', 'Bao', 15000.00, 1000.00, 500, 232, 232, 144, '2025-02-01 05:07:37'),
(424, 'Mykas', 'Lugan', '', 'full-time', 'Inventories', 18000.00, 100.00, 222, 334, 123, 173, '2025-02-04 02:35:06'),
(425, 'Hazel', 'Pecodana', 'bayot', 'full-time', 'Yobmot', 5000.00, 353.00, 500, 400, 450, 48, '2025-02-07 03:22:24'),
(441, 'Christian', 'Hermonio', 'Gay', 'full-time', 'Bayot', 13200.00, 1000.00, 3000, 200, 2300, 127, '2025-02-11 08:30:36'),
(444, 'kliunsa', 'latkliunsa', 's', 'full-time', 'asd', 18000.00, 1000.00, 100, 100, 100, 173, '2025-02-23 10:27:22');

-- --------------------------------------------------------

--
-- Table structure for table `overload`
--

CREATE TABLE `overload` (
  `overload_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `mwf_days` decimal(10,0) NOT NULL,
  `mwf_hrs` decimal(10,0) NOT NULL,
  `mwf_total` decimal(10,0) NOT NULL,
  `tth_days` decimal(10,0) NOT NULL,
  `tth_hrs` decimal(10,0) NOT NULL,
  `tth_total` decimal(10,0) NOT NULL,
  `ss_days` decimal(10,0) NOT NULL,
  `ss_hrs` decimal(10,0) NOT NULL,
  `ss_total` decimal(10,0) NOT NULL,
  `monday_days` decimal(10,0) NOT NULL,
  `monday_hrs` decimal(10,0) NOT NULL,
  `monday_total` decimal(10,0) NOT NULL,
  `tuesday_days` decimal(10,0) NOT NULL,
  `tuesday_hrs` decimal(10,0) NOT NULL,
  `tuesday_total` decimal(10,0) NOT NULL,
  `wednesday_days` decimal(10,2) DEFAULT 0.00,
  `wednesday_hrs` decimal(10,2) DEFAULT 0.00,
  `wednesday_total` decimal(10,2) DEFAULT 0.00,
  `thursday_days` decimal(10,2) DEFAULT 0.00,
  `thursday_hrs` decimal(10,2) DEFAULT 0.00,
  `thursday_total` decimal(10,2) DEFAULT 0.00,
  `friday_days` decimal(10,2) DEFAULT 0.00,
  `friday_hrs` decimal(10,2) DEFAULT 0.00,
  `friday_total` decimal(10,2) DEFAULT 0.00,
  `saturday_days` decimal(10,0) NOT NULL,
  `saturday_hrs` decimal(10,0) NOT NULL,
  `saturday_total` decimal(10,0) NOT NULL,
  `sunday_days` decimal(10,0) NOT NULL,
  `sunday_hrs` decimal(10,0) NOT NULL,
  `sunday_total` decimal(10,0) NOT NULL,
  `mtth_days` decimal(10,2) DEFAULT 0.00,
  `mtth_hrs` decimal(10,2) DEFAULT 0.00,
  `mtth_total` decimal(10,2) DEFAULT 0.00,
  `mtwf_days` decimal(10,2) DEFAULT 0.00,
  `mtwf_hrs` decimal(10,2) DEFAULT 0.00,
  `mtwf_total` decimal(10,2) DEFAULT 0.00,
  `twthf_days` decimal(10,2) DEFAULT 0.00,
  `twthf_hrs` decimal(10,2) DEFAULT 0.00,
  `twthf_total` decimal(10,2) DEFAULT 0.00,
  `mw_days` decimal(10,2) DEFAULT 0.00,
  `mw_hrs` decimal(10,2) DEFAULT 0.00,
  `mw_total` decimal(10,2) DEFAULT 0.00,
  `less_lateOL` decimal(10,2) DEFAULT 0.00,
  `additional` decimal(10,2) DEFAULT 0.00,
  `adjustment_less` decimal(10,2) DEFAULT 0.00,
  `grand_total` decimal(11,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `overload`
--

INSERT INTO `overload` (`overload_id`, `employee_id`, `mwf_days`, `mwf_hrs`, `mwf_total`, `tth_days`, `tth_hrs`, `tth_total`, `ss_days`, `ss_hrs`, `ss_total`, `monday_days`, `monday_hrs`, `monday_total`, `tuesday_days`, `tuesday_hrs`, `tuesday_total`, `wednesday_days`, `wednesday_hrs`, `wednesday_total`, `thursday_days`, `thursday_hrs`, `thursday_total`, `friday_days`, `friday_hrs`, `friday_total`, `saturday_days`, `saturday_hrs`, `saturday_total`, `sunday_days`, `sunday_hrs`, `sunday_total`, `mtth_days`, `mtth_hrs`, `mtth_total`, `mtwf_days`, `mtwf_hrs`, `mtwf_total`, `twthf_days`, `twthf_hrs`, `twthf_total`, `mw_days`, `mw_hrs`, `mw_total`, `less_lateOL`, `additional`, `adjustment_less`, `grand_total`) VALUES
(18, 401, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 15.00, 5.00, 75.00, 4.00, 4.00, 16.00, 3.00, 4.00, 12.00, 0, 0, 0, 0, 0, 0, 2.00, 5.00, 10.00, 0.00, 0.00, 0.00, 10.00, 0.00, 0.00, 0.00, 0.00, 0.00, 12.00, 3.00, 4.00, 100.00),
(19, 403, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 15.00, 12.00, 180.00, 4.00, 2.00, 8.00, 4.00, 1.00, 4.00, 0, 0, 0, 0, 0, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 10.00, 0.00, 0.00, 3.00, 4.00, 12.00, 20.00, 30.00, 3.00, 211.00),
(20, 401, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 15.00, 3.00, 45.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 0, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 10.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 45.00),
(21, 402, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 3.00, 2.00, 6.00, 2.00, 2.00, 4.00, 1.00, 1.00, 1.00, 0, 0, 0, 0, 0, 0, 4.00, 1.00, 4.00, 5.00, 1.00, 5.00, 10.00, 2.00, 20.00, 3.00, 1.00, 3.00, 2.00, 1.00, 2.00, 40.00),
(23, 441, 3, 2, 6, 2, 1, 2, 2, 2, 4, 2, 1, 2, 1, 1, 1, 1.00, 1.00, 1.00, 1.00, 1.00, 1.00, 1.00, 1.00, 1.00, 1, 1, 1, 1, 1, 1, 1.00, 1.00, 1.00, 1.00, 1.00, 1.00, 10.00, 1.00, 10.00, 4.00, 1.00, 4.00, 4.00, 20.00, 3.00, 49.00);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `email` varchar(250) NOT NULL,
  `password` varchar(250) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `created_at`) VALUES
(1, 'admin', 'admin@gmail.com', '$2y$10$6lRgFyZ0YpMCGLG2HCw8C.YyjSN9k4m370O7Ofvn8nhKheqHCQnG2', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `computation`
--
ALTER TABLE `computation`
  ADD PRIMARY KEY (`computation_id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `contributions`
--
ALTER TABLE `contributions`
  ADD PRIMARY KEY (`contributions_id`),
  ADD KEY `fk_contributions_employee_id` (`employee_id`);

--
-- Indexes for table `contribution_percentages`
--
ALTER TABLE `contribution_percentages`
  ADD PRIMARY KEY (`percentage_id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`employee_id`);

--
-- Indexes for table `overload`
--
ALTER TABLE `overload`
  ADD PRIMARY KEY (`overload_id`),
  ADD KEY `fk_overload_employee_id` (`employee_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `computation`
--
ALTER TABLE `computation`
  MODIFY `computation_id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `contributions`
--
ALTER TABLE `contributions`
  MODIFY `contributions_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `contribution_percentages`
--
ALTER TABLE `contribution_percentages`
  MODIFY `percentage_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `employee_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=445;

--
-- AUTO_INCREMENT for table `overload`
--
ALTER TABLE `overload`
  MODIFY `overload_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `contributions`
--
ALTER TABLE `contributions`
  ADD CONSTRAINT `fk_contributions_employee_id` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_employee_id` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`) ON DELETE CASCADE;

--
-- Constraints for table `overload`
--
ALTER TABLE `overload`
  ADD CONSTRAINT `fk_overload_employee_id` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
