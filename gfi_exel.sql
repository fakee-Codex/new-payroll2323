-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 16, 2025 at 03:48 AM
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
  `ret` decimal(10,0) NOT NULL,
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
(81, 401, 600.00, 27000.00, 1.00, 5.00, 5.00, 3.00, 2.00, 6.00, 1.00, 1234.00, 1234.00, 43745.00, 3.00, 91.00, 273.00, 0.00, 333.00, 223, 331, 12.00, 12.00, 4057.00, 39688.00, '2025-03-14 15:23:01'),
(82, 402, 300.00, 12000.00, 1.00, 5.00, 5.00, 3.00, 2.00, 6.00, 1.00, 2100.00, 2100.00, 59111.00, 3.00, 240.00, 720.00, 0.00, 0.00, 22, 321, 12.00, 12.00, 12498.00, 46613.00, '2025-03-14 15:23:01'),
(83, 403, 400.00, 84400.00, 1.00, 5.00, 5.00, 3.00, 2.00, 6.00, 1.00, 1100.00, 1100.00, 135511.00, 3.00, 385.00, 1155.00, 0.00, 32.00, 300, 500, 12.00, 12.00, 33443.00, 102068.00, '2025-03-14 15:23:01'),
(84, 404, 433.00, 0.00, 1.00, 5.00, 5.00, 3.00, 2.00, 6.00, 1.00, 432.00, 432.00, 8788.00, 3.00, 77.00, 231.00, 0.00, 532.00, 0, 0, 12.00, 12.00, 7668.00, 1120.00, '2025-03-14 15:23:01'),
(85, 423, 232.00, 3248.00, 1.00, 5.00, 5.00, 3.00, 2.00, 6.00, 1.00, 232.00, 232.00, 19491.00, 3.00, 144.00, 432.00, 0.00, 523.00, 0, 0, 12.00, 12.00, 5960.00, 13531.00, '2025-03-14 15:23:01'),
(86, 424, 334.00, 0.00, 1.00, 5.00, 5.00, 3.00, 2.00, 6.00, 1.00, 123.00, 123.00, 18234.00, 3.00, 173.00, 519.00, 0.00, 0.00, 0, 0, 12.00, 12.00, 6163.00, 12071.00, '2025-03-14 15:23:01'),
(87, 425, 400.00, 0.00, 1.00, 5.00, 5.00, 3.00, 2.00, 6.00, 1.00, 450.00, 450.00, 5814.00, 3.00, 48.00, 144.00, 0.00, 0.00, 231, 324, 12.00, 12.00, 3015.00, 2799.00, '2025-03-14 15:23:01'),
(88, 441, 200.00, 9800.00, 1.00, 5.00, 5.00, 3.00, 2.00, 6.00, 1.00, 2300.00, 2300.00, 26311.00, 3.00, 127.00, 381.00, 0.00, 130.00, 230, 349, 12.00, 12.00, 5122.00, 21189.00, '2025-03-14 15:23:01'),
(89, 444, 325.00, 1950.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 500.00, 0.00, 10250.00, 0.00, 70.00, 0.00, 0.00, 0.00, 0, 0, 13.00, 13.00, 26.00, 10224.00, '2025-03-14 15:23:01'),
(90, 445, 350.00, 4200.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 600.00, 0.00, 14500.00, 0.00, 91.00, 0.00, 0.00, 423.00, 0, 0, 0.00, 15.00, 3821.00, 10679.00, '2025-03-14 15:23:16'),
(91, 446, 120.00, 3720.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 500.00, 0.00, 15720.00, 0.00, 106.00, 0.00, 0.00, 0.00, 0, 0, 0.00, 0.00, 3590.00, 12130.00, '2025-03-14 15:23:01'),
(92, 447, 600.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 250.00, 0.00, 15200.00, 0.00, 144.00, 0.00, 0.00, 432.00, 0, 0, 0.00, 0.00, 5494.00, 9706.00, '2025-03-14 15:23:01'),
(93, 448, 600.00, 38400.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 250.00, 0.00, 56600.00, 0.00, 173.00, 0.00, 0.00, 400.00, 530, 250, 0.00, 0.00, 7411.00, 49189.00, '2025-03-14 15:23:01'),
(94, 449, 344.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 500.00, 0.00, 14350.00, 0.00, 128.00, 0.00, 0.00, 434.00, 233, 312, 0.00, 0.00, 5485.00, 8865.00, '2025-03-14 15:23:01'),
(95, 450, 645.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 566.00, 0.00, 32000.00, 0.00, 288.00, 0.00, 0.00, 0.00, 0, 0, 0.00, 0.00, 0.00, 32000.00, '2025-03-14 15:23:01'),
(96, 451, 520.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 340.00, 0.00, 12230.00, 0.00, 108.00, 0.00, 0.00, 0.00, 0, 0, 0.00, 0.00, 0.00, 12230.00, '2025-03-14 15:23:01');

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
  `retirement` decimal(10,0) NOT NULL,
  `mp2` decimal(10,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contributions`
--

INSERT INTO `contributions` (`contributions_id`, `employee_id`, `sss_ee`, `pag_ibig_ee`, `philhealth_ee`, `sss_er`, `pag_ibig_er`, `philhealth_er`, `medical_savings`, `retirement`, `mp2`) VALUES
(18, 441, 1188, 200, 660, 528, 200, 792, 120, 130, 130),
(19, 401, 720, 200, 400, 320, 200, 480, 111, 222, 333),
(20, 445, 950, 200, 760, 380, 200, 570, 323, 425, 423),
(21, 447, 1500, 200, 1200, 900, 200, 750, 312, 345, 432),
(23, 448, 1800, 200, 1440, 1080, 200, 900, 111, 322, 400),
(25, 446, 1100, 200, 880, 660, 200, 550, 0, 0, 0),
(32, 449, 1335, 200, 1068, 801, 200, 668, 112, 323, 434),
(34, 423, 1500, 200, 1200, 900, 200, 750, 231, 233, 523),
(35, 445, 950, 200, 760, 570, 200, 475, 0, 0, 0),
(36, 447, 1500, 200, 1200, 900, 200, 750, 0, 0, 0),
(37, 425, 500, 200, 400, 300, 200, 250, 0, 0, 0),
(38, 424, 1800, 200, 1440, 1080, 200, 900, 0, 0, 0),
(40, 403, 10000, 200, 9200, 9600, 200, 2000, 32, 12, 32),
(43, 404, 2000, 200, 1840, 1920, 200, 400, 321, 230, 532),
(44, 424, 2700, 200, 810, 810, 200, 3600, 0, 0, 0),
(45, 402, 3750, 200, 1125, 1125, 200, 5000, 0, 0, 0),
(47, 444, 1095, 200, 329, 329, 200, 1460, 0, 0, 0),
(48, 444, 1095, 200, 329, 329, 200, 1460, 0, 0, 0),
(49, 444, 1095, 200, 329, 329, 200, 1460, 0, 0, 0),
(50, 444, 1095, 200, 329, 329, 200, 1460, 0, 0, 0),
(51, 444, 1095, 200, 329, 329, 200, 1460, 0, 0, 0),
(52, 444, 1095, 200, 329, 329, 200, 1460, 0, 0, 0),
(53, 444, 1095, 200, 329, 329, 200, 1460, 0, 0, 0),
(54, 444, 1095, 200, 329, 329, 200, 1460, 0, 0, 0),
(55, 444, 1095, 200, 329, 329, 200, 1460, 0, 0, 0),
(56, 444, 1095, 200, 329, 329, 200, 1460, 0, 0, 0),
(57, 444, 1095, 200, 329, 329, 200, 1460, 0, 0, 0),
(58, 444, 1095, 200, 329, 329, 200, 1460, 0, 0, 0),
(59, 444, 1095, 200, 329, 329, 200, 1460, 0, 0, 0),
(60, 444, 1095, 200, 329, 329, 200, 1460, 0, 0, 0),
(61, 444, 1095, 200, 329, 329, 200, 1460, 0, 0, 0),
(62, 444, 1095, 200, 329, 329, 200, 1460, 0, 0, 0),
(63, 444, 1095, 200, 329, 329, 200, 1460, 0, 0, 0),
(64, 444, 1095, 200, 329, 329, 200, 1460, 0, 0, 0),
(65, 444, 1095, 200, 329, 329, 200, 1460, 0, 0, 0),
(66, 444, 1095, 200, 329, 329, 200, 1460, 0, 0, 0),
(67, 444, 1095, 200, 329, 329, 200, 1460, 0, 0, 0),
(68, 444, 1095, 200, 329, 329, 200, 1460, 0, 0, 0);

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
(8, 4.50, 15.00, 20.00, 4.50);

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
(401, 'Manila', 'Bay', 'MIT', 'full-time', 'Dancer', 9500.00, 6000.00, 500, 600, 1234, 91, '2025-01-14 09:43:19'),
(402, 'Williedfg', 'Pacquiao', '', 'full-time', 'Boxer', 25000.00, 20000.00, 500, 300, 2100, 240, '2025-01-14 09:44:02'),
(403, 'genisis', 'mantilla', '', 'full-time', 'Roblox', 40000.00, 10000.00, 500, 400, 1100, 385, '2025-01-14 10:13:30'),
(404, 'John', 'Joseph', '', 'part-time', 'Monkey Elepant', 8000.00, 345.00, 500, 433, 432, 77, '2025-01-14 10:14:56'),
(423, 'MIley', 'Zyrus', '', 'full-time', 'Bao', 15000.00, 1000.00, 500, 232, 232, 144, '2025-02-01 05:07:37'),
(424, 'Mykas', 'Lugan', '', 'full-time', 'Inventories', 18000.00, 100.00, 222, 334, 123, 173, '2025-02-04 02:35:06'),
(425, 'Hazel', 'Pecodana', 'bayot', 'full-time', 'Yobmot', 5000.00, 353.00, 500, 400, 450, 48, '2025-02-07 03:22:24'),
(441, 'Christian', 'Hermonio', 'Gay', 'full-time', 'Bayot', 13200.00, 1000.00, 3000, 200, 2300, 127, '2025-02-11 08:30:36'),
(444, 'Pablo ', 'Escobar', '', 'full-time', 'Dealer', 7300.00, 1000.00, 250, 325, 500, 70, '2025-03-11 01:37:03'),
(445, 'Jake', 'Onibar', '', 'full-time', 'Gangsta', 9500.00, 800.00, 500, 350, 600, 91, '2025-03-11 01:39:13'),
(446, 'Ramoza', 'Alber', 'LPT', 'full-time', 'Technician', 11000.00, 1000.00, 400, 120, 500, 106, '2025-03-11 01:40:02'),
(447, 'Bong', 'Rangsit', '', 'full-time', 'Mechanic', 15000.00, 200.00, 350, 600, 250, 144, '2025-03-11 01:40:43'),
(448, 'Bite ', 'Queen', '', 'full-time', 'Vlogger', 18000.00, 200.00, 3000, 600, 250, 173, '2025-03-11 01:43:20'),
(449, 'Bernardito', 'Yuzon', '', 'full-time', 'Encoder', 13350.00, 1000.00, 500, 344, 500, 128, '2025-03-11 01:45:52'),
(450, 'Mark ', 'Henry', '', 'full-time', 'Wrestler ', 30000.00, 2000.00, 700, 645, 566, 288, '2025-03-11 01:47:25'),
(451, 'Ceiling', 'Fan', 'air', 'part-time', 'Circulate', 11230.00, 1000.00, 200, 520, 340, 108, '2025-03-12 14:11:12');

-- --------------------------------------------------------

--
-- Table structure for table `loans`
--

CREATE TABLE `loans` (
  `loan_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `ret` decimal(10,0) NOT NULL,
  `sss` decimal(10,0) NOT NULL,
  `hdmf_pag` decimal(10,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `loans`
--

INSERT INTO `loans` (`loan_id`, `employee_id`, `ret`, `sss`, `hdmf_pag`) VALUES
(1, 403, 500, 300, 200),
(3, 425, 324, 231, 442),
(4, 401, 331, 223, 442),
(5, 441, 349, 230, 320),
(6, 448, 250, 530, 500),
(7, 449, 312, 233, 122),
(8, 402, 321, 22, 11),
(9, 404, 0, 76, 0),
(10, 444, 0, 0, 0);

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
(19, 403, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 15.00, 12.00, 180.00, 4.00, 2.00, 8.00, 4.00, 1.00, 4.00, 0, 0, 0, 0, 0, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 10.00, 0.00, 0.00, 3.00, 4.00, 12.00, 20.00, 30.00, 3.00, 211.00),
(20, 401, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 15.00, 3.00, 45.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 0, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 10.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 45.00),
(21, 402, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 3.00, 2.00, 6.00, 2.00, 2.00, 4.00, 1.00, 1.00, 1.00, 0, 0, 0, 0, 0, 0, 4.00, 1.00, 4.00, 5.00, 1.00, 5.00, 10.00, 2.00, 20.00, 3.00, 1.00, 3.00, 2.00, 1.00, 2.00, 40.00),
(23, 441, 3, 2, 6, 2, 1, 2, 2, 2, 4, 2, 1, 2, 1, 1, 1, 1.00, 1.00, 1.00, 1.00, 1.00, 1.00, 1.00, 1.00, 1.00, 1, 1, 1, 1, 1, 1, 1.00, 1.00, 1.00, 1.00, 1.00, 1.00, 10.00, 1.00, 10.00, 4.00, 1.00, 4.00, 4.00, 20.00, 3.00, 49.00),
(24, 423, 1, 2, 2, 3, 2, 6, 3, 2, 6, 0, 0, 0, 0, 0, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 0, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 14.00),
(25, 450, 3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 0, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),
(26, 446, 3, 2, 6, 3, 5, 15, 5, 2, 10, 0, 0, 0, 0, 0, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 0, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 31.00),
(27, 445, 2, 3, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 0, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 6.00),
(35, 404, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 0, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),
(36, 444, 2, 3, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 0, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 6.00),
(37, 448, 10, 2, 20, 3, 2, 6, 3, 2, 6, 22, 1, 22, 2, 3, 6, 1.00, 3.00, 3.00, 1.00, 3.00, 3.00, 1.00, 2.00, 2.00, 3, 2, 6, 2, 3, 6, 2.00, 3.00, 6.00, 2.00, 3.00, 6.00, 2.00, 2.00, 4.00, 2.00, 3.00, 6.00, 8.00, 0.00, 30.00, 64.00);

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
-- Indexes for table `loans`
--
ALTER TABLE `loans`
  ADD PRIMARY KEY (`loan_id`),
  ADD KEY `employee_id` (`employee_id`);

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
  MODIFY `computation_id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;

--
-- AUTO_INCREMENT for table `contributions`
--
ALTER TABLE `contributions`
  MODIFY `contributions_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `contribution_percentages`
--
ALTER TABLE `contribution_percentages`
  MODIFY `percentage_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `employee_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=452;

--
-- AUTO_INCREMENT for table `loans`
--
ALTER TABLE `loans`
  MODIFY `loan_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `overload`
--
ALTER TABLE `overload`
  MODIFY `overload_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

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
