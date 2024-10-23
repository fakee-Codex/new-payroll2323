-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 23, 2024 at 06:54 PM
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
-- Database: `gfipayroll`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `admin_id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `role_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`admin_id`, `username`, `password`, `email`, `role_id`) VALUES
(1, 'super-admin', '$2y$10$YsDFT8paLaVha6Srn1wOX.yqa3qXkc96WOBvrBxL8gcFIw4jAXpuy', 'admin@example.com', 1),
(2, 'regular', '$2y$10$va6gvaMSmHXjFUU6ci6zb.I9Dzv9Pg/OPuJxLuEMHrc.Fa8johfOa', NULL, 2);

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `attendance_id` int(11) NOT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `check_in_time` time DEFAULT NULL,
  `check_out_time` time DEFAULT NULL,
  `total_hours` decimal(5,2) DEFAULT NULL,
  `status` varchar(10) DEFAULT NULL,
  `regular_hours` decimal(5,2) DEFAULT 0.00,
  `overtime_hours` decimal(5,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`attendance_id`, `employee_id`, `date`, `check_in_time`, `check_out_time`, `total_hours`, `status`, `regular_hours`, `overtime_hours`) VALUES
(31, 34, '2024-10-03', '08:00:00', '17:00:00', 9.00, 'Present', 8.00, 1.00),
(32, 34, '2024-10-04', '08:00:00', '17:00:00', 9.00, 'Present', 8.00, 1.00),
(33, 34, '2024-10-07', '08:00:00', '17:00:00', 9.00, 'Present', 8.00, 1.00),
(34, 34, '2024-10-08', '08:00:00', '17:00:00', 9.00, 'Present', 8.00, 1.00),
(35, 34, '2024-10-09', '08:00:00', '17:00:00', 9.00, 'Present', 8.00, 1.00),
(36, 37, '2024-10-03', '08:00:00', '17:00:00', 9.00, 'Present', 8.00, 1.00),
(37, 34, '2024-10-10', '08:00:00', '21:00:00', 13.00, 'Present', 8.00, 5.00),
(38, 38, '2024-10-04', NULL, NULL, 0.00, 'Absent', 0.00, 0.00),
(39, 38, '2024-10-04', '09:00:00', '10:00:00', 1.00, 'Present', 1.00, 0.00),
(40, 34, '2024-10-04', '12:00:00', '13:30:00', 1.50, 'Present', 1.50, 0.00),
(41, 38, '2024-10-04', '12:00:00', '13:30:00', 1.50, 'Present', 1.50, 0.00),
(42, 39, '2024-10-04', '08:00:00', '09:00:00', 1.00, 'Present', 1.00, 0.00),
(43, 34, '2024-10-04', '10:00:00', '23:00:00', 13.00, 'Present', 8.00, 5.00),
(44, 39, '2024-10-04', '11:00:00', '12:30:00', 1.50, 'Present', 1.50, 0.00),
(45, 40, '2024-10-04', '08:00:00', '10:00:00', 2.00, 'Present', 2.00, 0.00),
(46, 40, '2024-10-04', '12:30:00', '13:30:00', 1.00, 'Present', 1.00, 0.00),
(47, 41, '2024-10-04', '12:00:00', '14:00:00', 2.00, 'Present', 2.00, 0.00),
(48, 41, '2024-10-04', '15:00:00', '16:00:00', 1.00, 'Present', 1.00, 0.00),
(49, 41, '2024-10-04', '16:00:00', '17:00:00', 1.00, 'Present', 1.00, 0.00),
(50, 34, '2024-08-01', '04:00:00', '17:00:00', 13.00, 'Present', 8.00, 5.00),
(51, 34, '2024-08-01', '08:00:00', '14:00:00', 6.00, 'Present', 6.00, 0.00),
(52, 34, '2024-10-09', '08:00:00', '10:30:00', 2.50, 'Present', 2.50, 0.00),
(54, 42, '2024-10-09', '08:00:00', '10:30:00', 2.50, 'Present', 2.50, 0.00),
(55, 39, '2024-10-15', '01:12:00', '13:12:00', 12.00, 'Present', 8.00, 4.00),
(56, 34, '2024-10-16', NULL, NULL, 4.00, 'Present', 4.00, 0.00),
(57, 35, '2024-10-16', NULL, NULL, 8.00, 'Present', 8.00, 0.00),
(58, 35, '2024-10-17', NULL, NULL, 8.00, 'Present', 8.00, 0.00),
(59, 35, '2024-10-16', NULL, NULL, 52.00, 'Present', 8.00, 44.00),
(60, 35, '2024-10-23', NULL, NULL, 58.00, 'Present', 8.00, 50.00),
(61, 38, '2024-10-16', NULL, NULL, 6.00, 'Present', 6.00, 0.00),
(62, 34, '2024-10-17', NULL, NULL, 4.00, 'Present', 4.00, 0.00),
(63, 38, '2024-10-17', NULL, NULL, 6.00, 'Present', 6.00, 0.00),
(64, 38, '2024-10-21', NULL, NULL, 4.00, 'Present', 4.00, 0.00),
(65, 34, '2024-10-18', NULL, NULL, 1.00, 'Present', 1.00, 0.00),
(66, 34, '2024-10-16', NULL, NULL, 1.50, 'Present', 1.50, 0.00),
(67, 34, '2024-10-17', NULL, NULL, 6.00, 'Present', 6.00, 0.00),
(68, 34, '2024-11-05', NULL, NULL, 80.00, 'Present', 8.00, 72.00);

-- --------------------------------------------------------

--
-- Table structure for table `contributions`
--

CREATE TABLE `contributions` (
  `contribution_id` int(11) NOT NULL,
  `contribution_name` varchar(255) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contributions`
--

INSERT INTO `contributions` (`contribution_id`, `contribution_name`, `amount`, `created_at`) VALUES
(1, 'SSS', 200.00, '2024-10-06 03:46:32'),
(2, 'PAG-IBIG', 250.00, '2024-10-06 03:48:05'),
(3, 'PHIC', 350.00, '2024-10-06 03:48:31');

-- --------------------------------------------------------

--
-- Table structure for table `daily_rate`
--

CREATE TABLE `daily_rate` (
  `rate_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `daily_rate` decimal(10,2) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `hourly_rate` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `daily_rate`
--

INSERT INTO `daily_rate` (`rate_id`, `employee_id`, `daily_rate`, `start_date`, `end_date`, `hourly_rate`) VALUES
(16, 34, 8.00, '2024-10-04', '2024-10-04', 1.00),
(17, 34, 976.00, '2024-10-05', '2024-10-04', 122.00),
(18, 34, 560.00, '2024-10-05', '2024-10-21', 70.00),
(19, 41, 720.00, '2024-10-06', NULL, 90.00),
(20, 42, 960.00, '2024-10-09', NULL, 120.00),
(22, 39, 712.00, '2024-10-15', NULL, 89.00),
(23, 35, 720.00, '2024-10-16', NULL, 90.00),
(24, 38, 2400.00, '2024-10-16', NULL, 300.00),
(25, 34, 7200.00, '2024-10-22', NULL, 900.00);

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `department_id` int(11) NOT NULL,
  `department_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`department_id`, `department_name`) VALUES
(54, 'IT DEPARTMENT'),
(55, 'EDUCATION'),
(56, 'ENGINEERING'),
(57, 'ADMINISTRATIVE'),
(58, 'SECURITY'),
(59, 'Registrar Office'),
(60, 'BSA/MA'),
(61, 'HS Department');

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `employee_id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `job_title_id` int(11) NOT NULL,
  `department_id` int(11) NOT NULL,
  `date_of_joining` date NOT NULL,
  `date_inactive` date DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `employment_status` varchar(50) NOT NULL DEFAULT 'full-time',
  `reason` varchar(255) DEFAULT NULL,
  `employee_id_number` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`employee_id`, `first_name`, `last_name`, `job_title_id`, `department_id`, `date_of_joining`, `date_inactive`, `status`, `employment_status`, `reason`, `employee_id_number`) VALUES
(34, 'John Laurent', 'Salazar', 18, 54, '2024-10-03', '2024-10-23', 'inactive', 'full-time', 'awol', ''),
(35, 'Michael', 'Jackson', 19, 54, '2024-10-03', NULL, 'active', 'part-time', NULL, '21'),
(36, 'Christian', 'Seguel', 20, 56, '2024-10-03', '2024-10-23', 'inactive', 'full-time', 'kawawa', '734'),
(37, 'JOSHUA', 'EMPAL', 21, 58, '2024-10-03', NULL, 'active', 'full-time', NULL, '152'),
(38, 'Gelato', 'doggo', 18, 54, '2024-10-04', NULL, 'active', 'full-time', NULL, '421'),
(39, 'Fifi', 'Lou', 20, 56, '2024-10-04', NULL, 'active', 'full-time', NULL, '5266'),
(40, 'Mark Andree', 'Siasat', 23, 60, '2024-10-04', NULL, 'active', 'full-time', NULL, '121'),
(41, 'Carlo', 'Lapura', 24, 61, '2024-10-04', NULL, 'active', 'full-time', NULL, '773'),
(42, 'CATHERINE', 'EMPAL', 25, 55, '2024-10-09', NULL, 'active', 'part-time', NULL, '826'),
(43, 'James', 'okay', 18, 54, '2024-10-22', NULL, 'active', 'full-time', NULL, '521'),
(44, 'okay', 'satanas', 18, 54, '2024-10-23', NULL, 'active', 'full-time', NULL, '666'),
(45, 'german ', 'alabe', 17, 54, '2024-10-23', NULL, 'active', 'part-time', NULL, '666');

-- --------------------------------------------------------

--
-- Table structure for table `fs_15th_pay`
--

CREATE TABLE `fs_15th_pay` (
  `fs_15th_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fs_15th_pay`
--

INSERT INTO `fs_15th_pay` (`fs_15th_id`, `employee_id`, `amount`, `date`) VALUES
(1, 34, 800.00, '2024-10-16');

-- --------------------------------------------------------

--
-- Table structure for table `job_titles`
--

CREATE TABLE `job_titles` (
  `job_title_id` int(11) NOT NULL,
  `job_title` varchar(100) NOT NULL,
  `department_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `job_titles`
--

INSERT INTO `job_titles` (`job_title_id`, `job_title`, `department_id`) VALUES
(17, 'IT HEAD', 54),
(18, 'PROGRAMMER', 54),
(19, 'DEAN', 54),
(20, 'DEAN', 56),
(21, 'HEAD', 58),
(22, 'DEAN', 55),
(23, 'Revalida In-charge/Faculty', 60),
(24, 'HS Adviser', 61),
(25, 'TEACHER', 55);

-- --------------------------------------------------------

--
-- Table structure for table `loans`
--

CREATE TABLE `loans` (
  `loan_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `loan_amount` decimal(10,2) NOT NULL,
  `loan_terms` int(11) NOT NULL,
  `remaining_balance` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `loan_description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `loans`
--

INSERT INTO `loans` (`loan_id`, `employee_id`, `loan_amount`, `loan_terms`, `remaining_balance`, `created_at`, `loan_description`) VALUES
(7, 34, 6000.00, 6, 0.00, '2024-10-21 15:42:25', 'SSS'),
(8, 34, 10000.00, 12, 0.00, '2024-10-21 15:53:57', 'PAG IBIG LOAN'),
(9, 34, 100000.00, 2, 0.00, '2024-10-21 15:59:04', 'something');

-- --------------------------------------------------------

--
-- Table structure for table `other_deductions`
--

CREATE TABLE `other_deductions` (
  `deduction_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `medical_savings` decimal(10,2) NOT NULL,
  `canteen` decimal(10,2) NOT NULL,
  `absence_late` decimal(10,2) NOT NULL,
  `total_deduction` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('unpaid','paid') DEFAULT 'unpaid'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `other_deductions`
--

INSERT INTO `other_deductions` (`deduction_id`, `employee_id`, `medical_savings`, `canteen`, `absence_late`, `total_deduction`, `created_at`, `status`) VALUES
(4, 34, 123.00, 123.00, 123.00, 369.00, '2024-10-14 16:59:43', 'paid'),
(5, 34, 5125.00, 5215.00, 5215.00, 15555.00, '2024-10-14 17:00:07', 'paid'),
(6, 34, 70.00, 70.00, 70.00, 210.00, '2024-10-14 17:06:15', 'paid'),
(7, 39, 666.00, 643.00, 62.00, 1371.00, '2024-10-14 17:11:39', 'paid'),
(8, 34, 600.00, 150.00, 666.00, 1416.00, '2024-10-16 13:26:41', 'paid');

-- --------------------------------------------------------

--
-- Table structure for table `overload_pay`
--

CREATE TABLE `overload_pay` (
  `overload_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `date` date NOT NULL,
  `start_date` date NOT NULL DEFAULT '2024-01-01',
  `end_date` date NOT NULL DEFAULT '2024-12-31'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `overload_pay`
--

INSERT INTO `overload_pay` (`overload_id`, `employee_id`, `amount`, `date`, `start_date`, `end_date`) VALUES
(44, 34, 20.00, '0000-00-00', '2024-10-24', '2024-10-03'),
(45, 35, 20.00, '0000-00-00', '2024-10-24', '2024-10-03'),
(46, 36, 20.00, '0000-00-00', '2024-10-24', '2024-10-03'),
(48, 37, 20.00, '0000-00-00', '2024-10-24', '2024-10-03'),
(49, 38, 20.00, '0000-00-00', '2024-10-24', '2024-10-03'),
(51, 41, 20.00, '0000-00-00', '2024-10-24', '2024-10-03'),
(52, 42, 20.00, '0000-00-00', '2024-10-24', '2024-10-03');

-- --------------------------------------------------------

--
-- Table structure for table `payroll_history`
--

CREATE TABLE `payroll_history` (
  `id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `date_generated` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payroll_history`
--

INSERT INTO `payroll_history` (`id`, `start_date`, `end_date`, `date_generated`) VALUES
(11, '2024-10-16', '2024-10-31', '2024-10-22 23:40:30');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `role_id` int(11) NOT NULL,
  `role_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`role_id`, `role_name`) VALUES
(1, 'super admin'),
(2, 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `watch_pay`
--

CREATE TABLE `watch_pay` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `date_added` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `watch_pay`
--

INSERT INTO `watch_pay` (`id`, `employee_id`, `amount`, `date_added`) VALUES
(1, 34, 300.00, '2024-10-16');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`admin_id`),
  ADD KEY `fk_role` (`role_id`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`attendance_id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `contributions`
--
ALTER TABLE `contributions`
  ADD PRIMARY KEY (`contribution_id`);

--
-- Indexes for table `daily_rate`
--
ALTER TABLE `daily_rate`
  ADD PRIMARY KEY (`rate_id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`department_id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`employee_id`),
  ADD KEY `job_title_id` (`job_title_id`),
  ADD KEY `department_id` (`department_id`);

--
-- Indexes for table `fs_15th_pay`
--
ALTER TABLE `fs_15th_pay`
  ADD PRIMARY KEY (`fs_15th_id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `job_titles`
--
ALTER TABLE `job_titles`
  ADD PRIMARY KEY (`job_title_id`),
  ADD KEY `fk_department` (`department_id`);

--
-- Indexes for table `loans`
--
ALTER TABLE `loans`
  ADD PRIMARY KEY (`loan_id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `other_deductions`
--
ALTER TABLE `other_deductions`
  ADD PRIMARY KEY (`deduction_id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `overload_pay`
--
ALTER TABLE `overload_pay`
  ADD PRIMARY KEY (`overload_id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `payroll_history`
--
ALTER TABLE `payroll_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`role_id`);

--
-- Indexes for table `watch_pay`
--
ALTER TABLE `watch_pay`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `attendance_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `contributions`
--
ALTER TABLE `contributions`
  MODIFY `contribution_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `daily_rate`
--
ALTER TABLE `daily_rate`
  MODIFY `rate_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `department_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `employee_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `fs_15th_pay`
--
ALTER TABLE `fs_15th_pay`
  MODIFY `fs_15th_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `job_titles`
--
ALTER TABLE `job_titles`
  MODIFY `job_title_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `loans`
--
ALTER TABLE `loans`
  MODIFY `loan_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `other_deductions`
--
ALTER TABLE `other_deductions`
  MODIFY `deduction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `overload_pay`
--
ALTER TABLE `overload_pay`
  MODIFY `overload_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `payroll_history`
--
ALTER TABLE `payroll_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `watch_pay`
--
ALTER TABLE `watch_pay`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admins`
--
ALTER TABLE `admins`
  ADD CONSTRAINT `fk_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`);

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`);

--
-- Constraints for table `daily_rate`
--
ALTER TABLE `daily_rate`
  ADD CONSTRAINT `daily_rate_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`) ON DELETE CASCADE;

--
-- Constraints for table `employees`
--
ALTER TABLE `employees`
  ADD CONSTRAINT `employees_ibfk_1` FOREIGN KEY (`job_title_id`) REFERENCES `job_titles` (`job_title_id`),
  ADD CONSTRAINT `employees_ibfk_2` FOREIGN KEY (`department_id`) REFERENCES `departments` (`department_id`);

--
-- Constraints for table `fs_15th_pay`
--
ALTER TABLE `fs_15th_pay`
  ADD CONSTRAINT `fs_15th_pay_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`);

--
-- Constraints for table `job_titles`
--
ALTER TABLE `job_titles`
  ADD CONSTRAINT `fk_department` FOREIGN KEY (`department_id`) REFERENCES `departments` (`department_id`);

--
-- Constraints for table `loans`
--
ALTER TABLE `loans`
  ADD CONSTRAINT `loans_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`) ON DELETE CASCADE;

--
-- Constraints for table `other_deductions`
--
ALTER TABLE `other_deductions`
  ADD CONSTRAINT `other_deductions_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`) ON DELETE CASCADE;

--
-- Constraints for table `overload_pay`
--
ALTER TABLE `overload_pay`
  ADD CONSTRAINT `overload_pay_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`);

--
-- Constraints for table `watch_pay`
--
ALTER TABLE `watch_pay`
  ADD CONSTRAINT `watch_pay_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
