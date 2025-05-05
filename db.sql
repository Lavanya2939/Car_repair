-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 04, 2025 at 11:50 PM
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
-- Database: `car_repair_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE IF NOT EXISTS `admins` (
  `admin_id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `status` enum('approved','inactive') DEFAULT 'inactive',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_primary` tinyint(1) DEFAULT 0,
  `profile_img` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`admin_id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`admin_id`, `full_name`, `email`, `password`, `status`, `created_at`, `is_primary`, `profile_img`) VALUES
(1, 'Jaswanth Kongara', 'kongarajaswanth4@gmail.com', '$2y$10$0AWpfG8PDE6BqIqkmW58se22gql5IFdxTZovbkd6ylOba4w293kQG', 'approved', '2025-05-03 20:14:51', 1, 'jaswanth.png'),
(2, 'Lavanya Mukkapati', 'lavanya@carrepair.com', 'hashed_pw2', 'approved', '2025-05-03 20:14:51', 0, 'lavanya.png'),
(3, 'Dileep Reddy', 'dileep@carrepair.com', 'hashed_pw3', 'approved', '2025-05-03 20:14:51', 0, 'dileep.png'),
(4, 'Mayur LNU', 'mayur@carrepair.com', 'hashed_pw4', 'approved', '2025-05-03 20:14:51', 0, 'mayur.png');

-- --------------------------------------------------------

--
-- Table structure for table `admin_login_logout_details`
--

CREATE TABLE IF NOT EXISTS `admin_login_logout_details` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) DEFAULT NULL,
  `login_time` datetime DEFAULT NULL,
  `logout_time` datetime DEFAULT NULL,
  PRIMARY KEY (`log_id`),
  KEY `admin_id` (`admin_id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_login_logout_details`
--

INSERT INTO `admin_login_logout_details` (`log_id`, `admin_id`, `login_time`, `logout_time`) VALUES
(1, 1, '2025-05-03 16:14:51', '2025-05-03 16:14:51'),
(2, 2, '2025-05-03 16:14:51', '2025-05-03 16:14:51'),
(3, 3, '2025-05-03 16:14:51', '2025-05-03 16:14:51'),
(4, 4, '2025-05-03 16:14:51', '2025-05-03 16:14:51'),
(5, 1, '2025-05-03 16:16:56', '2025-05-03 16:16:56'),
(6, 2, '2025-05-03 16:16:56', '2025-05-03 16:16:56'),
(7, 3, '2025-05-03 16:16:56', '2025-05-03 16:16:56'),
(8, 4, '2025-05-03 16:16:56', '2025-05-03 16:16:56'),
(9, 1, '2025-05-03 16:17:31', '2025-05-03 16:17:31'),
(10, 2, '2025-05-03 16:17:31', '2025-05-03 16:17:31'),
(11, 3, '2025-05-03 16:17:31', '2025-05-03 16:17:31'),
(12, 4, '2025-05-03 16:17:31', '2025-05-03 16:17:31'),
(13, 1, '2025-05-03 16:17:50', '2025-05-03 16:17:50'),
(14, 2, '2025-05-03 16:17:50', '2025-05-03 16:17:50'),
(15, 3, '2025-05-03 16:17:50', '2025-05-03 16:17:50'),
(16, 4, '2025-05-03 16:17:50', '2025-05-03 16:17:50'),
(17, 1, '2025-05-03 22:53:50', NULL),
(18, 1, '2025-05-03 22:54:37', NULL),
(19, 1, '2025-05-04 00:13:11', NULL),
(20, 1, '2025-05-04 16:26:18', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `admin_requests`
--

CREATE TABLE IF NOT EXISTS `admin_requests` (
  `request_id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `requested_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`request_id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_requests`
--

INSERT INTO `admin_requests` (`request_id`, `full_name`, `email`, `password`, `requested_at`) VALUES
(1, 'Jaswanth Kongara', 'jaswanth.request@carrepair.com', 'pass123', '2025-05-03 20:14:52'),
(2, 'Lavanya Mukkapati', 'lavanya.request@carrepair.com', 'pass123', '2025-05-03 20:14:52'),
(5, 'Jaswanth Kongara', 'jaswanth.request@carrepair.com', 'pass123', '2025-05-03 20:16:56'),
(6, 'Lavanya Mukkapati', 'lavanya.request@carrepair.com', 'pass123', '2025-05-03 20:16:56'),
(7, 'Dileep Reddy', 'dileep.request@carrepair.com', 'pass123', '2025-05-03 20:16:56'),
(8, 'Mayur LNU', 'mayur.request@carrepair.com', 'pass123', '2025-05-03 20:16:56'),
(9, 'Jaswanth Kongara', 'jaswanth.request@carrepair.com', 'pass123', '2025-05-03 20:17:31'),
(10, 'Lavanya Mukkapati', 'lavanya.request@carrepair.com', 'pass123', '2025-05-03 20:17:31'),
(11, 'Dileep Reddy', 'dileep.request@carrepair.com', 'pass123', '2025-05-03 20:17:31'),
(12, 'Mayur LNU', 'mayur.request@carrepair.com', 'pass123', '2025-05-03 20:17:31'),
(13, 'Jaswanth Kongara', 'jaswanth.request@carrepair.com', 'pass123', '2025-05-03 20:17:50'),
(14, 'Lavanya Mukkapati', 'lavanya.request@carrepair.com', 'pass123', '2025-05-03 20:17:50'),
(15, 'Dileep Reddy', 'dileep.request@carrepair.com', 'pass123', '2025-05-03 20:17:50'),
(16, 'Mayur LNU', 'mayur.request@carrepair.com', 'pass123', '2025-05-03 20:17:50'),
(17, 'Jaswanth Kongara', 'kongarajaswanth4@gmail.com', '$2y$10$0AWpfG8PDE6BqIqkmW58se22gql5IFdxTZovbkd6ylOba4w293kQG', '2025-05-03 20:49:53'),
(18, 'Jaswanth Kongara', 'kongarajaswanth4@gmail.com', '$2y$10$zcfv4U6rUeYjs91a62iqTuG/6DlHUt6/l3oO4g6NChhAyfeb7QFEW', '2025-05-03 20:51:19');

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE IF NOT EXISTS `appointments` (
  `appointment_id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) DEFAULT NULL,
  `vehicle_id` int(11) DEFAULT NULL,
  `mechanic_id` int(11) DEFAULT NULL,
  `service_date` date DEFAULT NULL,
  `service_time` time DEFAULT NULL,
  `issue_description` text DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`appointment_id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`appointment_id`, `customer_id`, `vehicle_id`, `mechanic_id`, `service_date`, `service_time`, `issue_description`, `status`, `created_at`) VALUES
(1, 1, 1, 1, '2025-04-01', '10:00:00', 'Oil change', 'pending', '2025-05-03 20:14:52'),
(2, 1, 2, 2, '2025-04-02', '11:00:00', 'Brake issue', 'approved', '2025-05-03 20:14:52'),
(3, 2, 3, 3, '2025-04-03', '12:00:00', 'Tire replacement', 'completed', '2025-05-03 20:14:52'),
(4, 3, 4, 4, '2025-04-04', '13:00:00', 'AC check', 'pending', '2025-05-03 20:14:52'),
(5, 1, 1, 1, '2025-04-01', '10:00:00', 'Oil change', 'pending', '2025-05-03 20:16:56'),
(6, 1, 2, 2, '2025-04-02', '11:00:00', 'Brake issue', 'approved', '2025-05-03 20:16:56'),
(7, 2, 3, 3, '2025-04-03', '12:00:00', 'Tire replacement', 'completed', '2025-05-03 20:16:56'),
(8, 3, 4, 4, '2025-04-04', '13:00:00', 'AC check', 'pending', '2025-05-03 20:16:56'),
(9, 1, 1, 1, '2025-04-01', '10:00:00', 'Oil change', 'pending', '2025-05-03 20:17:31'),
(10, 1, 2, 2, '2025-04-02', '11:00:00', 'Brake issue', 'approved', '2025-05-03 20:17:31'),
(11, 2, 3, 3, '2025-04-03', '12:00:00', 'Tire replacement', 'completed', '2025-05-03 20:17:31'),
(12, 3, 4, 4, '2025-04-04', '13:00:00', 'AC check', 'pending', '2025-05-03 20:17:31'),
(13, 1, 1, 1, '2025-04-01', '10:00:00', 'Oil change', 'pending', '2025-05-03 20:17:50'),
(14, 1, 2, 2, '2025-04-02', '11:00:00', 'Brake issue', 'approved', '2025-05-03 20:17:50'),
(15, 2, 3, 3, '2025-04-03', '12:00:00', 'Tire replacement', 'completed', '2025-05-03 20:17:50'),
(16, 3, 4, 4, '2025-04-04', '13:00:00', 'AC check', 'pending', '2025-05-03 20:17:50'),
(17, 9, 2, 1, '2034-02-03', '13:12:00', 'car full check', 'completed', '2025-05-03 21:08:59'),
(18, 9, 2, 3, '2025-05-31', '00:00:00', 'wheels', 'completed', '2025-05-04 16:49:08');

-- --------------------------------------------------------

--
-- Table structure for table `assigned_items`
--

CREATE TABLE IF NOT EXISTS `assigned_items` (
  `assignment_id` int(11) NOT NULL AUTO_INCREMENT,
  `mechanic_id` int(11) DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `assigned_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`assignment_id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `assigned_items`
--

INSERT INTO `assigned_items` (`assignment_id`, `mechanic_id`, `customer_id`, `item_id`, `quantity`, `assigned_at`) VALUES
(1, 1, 1, 1, 2, '2025-05-03 20:14:52'),
(2, 2, 2, 2, 1, '2025-05-03 20:14:52'),
(3, 3, 3, 3, 5, '2025-05-03 20:14:52'),
(4, 4, 4, 4, 3, '2025-05-03 20:14:52'),
(5, 1, 1, 1, 2, '2025-05-03 20:16:56'),
(6, 2, 2, 2, 1, '2025-05-03 20:16:56'),
(7, 3, 3, 3, 5, '2025-05-03 20:16:56'),
(8, 4, 4, 4, 3, '2025-05-03 20:16:56'),
(9, 1, 1, 1, 2, '2025-05-03 20:17:31'),
(10, 2, 2, 2, 1, '2025-05-03 20:17:31'),
(11, 3, 3, 3, 5, '2025-05-03 20:17:31'),
(12, 4, 4, 4, 3, '2025-05-03 20:17:31'),
(13, 1, 1, 1, 2, '2025-05-03 20:17:50'),
(14, 2, 2, 2, 1, '2025-05-03 20:17:50'),
(15, 3, 3, 3, 5, '2025-05-03 20:17:50'),
(16, 4, 4, 4, 3, '2025-05-03 20:17:50');

-- --------------------------------------------------------

--
-- Table structure for table `billing`
--

CREATE TABLE IF NOT EXISTS `billing` (
  `billing_id` int(11) NOT NULL AUTO_INCREMENT,
  `service_id` int(11) DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `repair_cost` decimal(10,2) DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `payment_id` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`billing_id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `billing`
--

INSERT INTO `billing` (`billing_id`, `service_id`, `customer_id`, `total_amount`, `status`, `created_at`, `repair_cost`, `payment_method`, `payment_id`) VALUES
(1, 1, 1, 500.00, 'paid', NULL, 450.00, 'Credit Card', 'PAY001'),
(2, 2, 2, 600.00, 'pending', NULL, 560.00, 'Cash', 'PAY002'),
(3, 3, 3, 300.00, 'paid', NULL, 280.00, 'UPI', 'PAY003'),
(4, 4, 4, 700.00, 'pending', NULL, 650.00, 'Debit Card', 'PAY004'),
(5, 1, 1, 500.00, 'paid', NULL, 450.00, 'Credit Card', 'PAY001'),
(6, 2, 2, 600.00, 'pending', NULL, 560.00, 'Cash', 'PAY002'),
(7, 3, 3, 300.00, 'paid', NULL, 280.00, 'UPI', 'PAY003'),
(8, 4, 4, 700.00, 'pending', NULL, 650.00, 'Debit Card', 'PAY004'),
(9, 1, 1, 500.00, 'paid', NULL, 450.00, 'Credit Card', 'PAY001'),
(10, 2, 2, 600.00, 'pending', NULL, 560.00, 'Cash', 'PAY002'),
(11, 3, 3, 300.00, 'paid', NULL, 280.00, 'UPI', 'PAY003'),
(12, 4, 4, 700.00, 'pending', NULL, 650.00, 'Debit Card', 'PAY004'),
(13, 1, 1, 500.00, 'paid', NULL, 450.00, 'Credit Card', 'PAY001'),
(14, 2, 2, 600.00, 'pending', NULL, 560.00, 'Cash', 'PAY002'),
(15, 3, 3, 300.00, 'paid', NULL, 280.00, 'UPI', 'PAY003'),
(16, 4, 4, 700.00, 'pending', NULL, 650.00, 'Debit Card', 'PAY004'),
(17, 17, 9, 15705.87, 'paid', NULL, 12344.00, 'UPI', 'PAY6817cd3ae3dee');

-- --------------------------------------------------------

--
-- Table structure for table `billing_items`
--

CREATE TABLE IF NOT EXISTS `billing_items` (
  `billing_item_id` int(11) NOT NULL AUTO_INCREMENT,
  `billing_id` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `cost` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`billing_item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE IF NOT EXISTS `customers` (
  `customer_id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `is_verified` tinyint(4) DEFAULT 0,
  `dob` date DEFAULT NULL,
  `address` text DEFAULT NULL,
  `license_number` varchar(50) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`customer_id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`customer_id`, `first_name`, `last_name`, `email`, `password`, `is_verified`, `dob`, `address`, `license_number`, `phone`, `profile_image`) VALUES
(1, 'Jaswanth', 'Kongara', 'jaswanth@gmail.com', 'pass1', 1, '1998-06-15', 'Address 1', 'LIC123', '1234567890', 'jaswanth.png'),
(2, 'Lavanya', 'Mukkapati', 'lavanya@gmail.com', 'pass2', 1, '1999-08-20', 'Address 2', 'LIC124', '1234567891', 'lavanya.png'),
(3, 'Dileep', 'Reddy', 'dileep@gmail.com', 'pass3', 1, '1997-04-10', 'Address 3', 'LIC125', '1234567892', 'dileep.png'),
(4, 'Mayur', 'LNU', 'mayur@gmail.com', 'pass4', 1, '1996-02-05', 'Address 4', 'LIC126', '1234567893', 'mayur.png'),
(9, 'Kongara', 'Jaswanth', 'kongarajaswanth4@gmail.com', '$2y$10$o.ZaGZEviJ1zj2t6w9p0L.E274DR35GPRTFjBHghCItAr7gPZr1d2', 1, '2002-03-31', '', '', '', NULL),
(10, 'lavanya', 'Mukkapati', 'lmukkapati@albany.edu', '$2y$10$pppW1NjOmID2lN9DB7r5vOS3HTM99NUtHVerVYaO3Xo2G3DtETfCu', 1, '0000-00-00', '', '', '', '1746328746_WhatsApp Image 2025-04-12 at 9.38.01 PM.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `customer_login_logout_details`
--

CREATE TABLE IF NOT EXISTS `customer_login_logout_details` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) DEFAULT NULL,
  `login_time` datetime DEFAULT NULL,
  `logout_time` datetime DEFAULT NULL,
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer_login_logout_details`
--

INSERT INTO `customer_login_logout_details` (`log_id`, `customer_id`, `login_time`, `logout_time`) VALUES
(1, 1, '2025-05-03 16:16:56', '2025-05-03 16:16:56'),
(2, 2, '2025-05-03 16:16:56', '2025-05-03 16:16:56'),
(3, 3, '2025-05-03 16:16:56', '2025-05-03 16:16:56'),
(4, 4, '2025-05-03 16:16:56', '2025-05-03 16:16:56'),
(5, 1, '2025-05-03 16:17:50', '2025-05-03 16:17:50'),
(6, 2, '2025-05-03 16:17:50', '2025-05-03 16:17:50'),
(7, 3, '2025-05-03 16:17:50', '2025-05-03 16:17:50'),
(8, 4, '2025-05-03 16:17:50', '2025-05-03 16:17:50'),
(9, 9, '2025-05-03 22:23:08', '2025-05-03 22:49:28'),
(10, 9, '2025-05-03 23:07:30', '2025-05-04 05:15:48'),
(11, 9, '2025-05-04 00:13:27', '2025-05-04 05:15:48'),
(12, 10, '2025-05-04 05:17:41', '2025-05-04 05:20:01'),
(13, 9, '2025-05-04 05:20:35', NULL),
(14, 9, '2025-05-04 05:32:13', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `customer_otps`
--

CREATE TABLE IF NOT EXISTS `customer_otps` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(100) DEFAULT NULL,
  `otp_code` varchar(10) DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer_otps`
--

INSERT INTO `customer_otps` (`id`, `email`, `otp_code`, `expires_at`) VALUES
(1, 'jaswanth@gmail.com', '123456', '2025-05-03 16:21:56'),
(2, 'lavanya@gmail.com', '654321', '2025-05-03 16:21:56'),
(3, 'dileep@gmail.com', '789012', '2025-05-03 16:21:56'),
(4, 'mayur@gmail.com', '210987', '2025-05-03 16:21:56'),
(5, 'jaswanth@gmail.com', '123456', '2025-05-03 16:22:50'),
(6, 'lavanya@gmail.com', '654321', '2025-05-03 16:22:50'),
(7, 'dileep@gmail.com', '789012', '2025-05-03 16:22:50'),
(8, 'mayur@gmail.com', '210987', '2025-05-03 16:22:50');

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE IF NOT EXISTS `inventory` (
  `item_id` int(11) NOT NULL AUTO_INCREMENT,
  `item_name` varchar(100) DEFAULT NULL,
  `item_type` varchar(50) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `unit_price` decimal(10,2) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`item_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`item_id`, `item_name`, `item_type`, `image`, `quantity`, `unit_price`, `status`, `created_at`) VALUES
(1, 'Oil Filter', 'Engine', 'oil_filter.png', 50, 20.00, 'available', '2025-05-03 20:16:56'),
(2, 'Brake Pads', 'Brake', 'brake_pads.png', 30, 45.00, 'available', '2025-05-03 20:16:56'),
(3, 'Tires', 'Wheel', 'tires.png', 20, 100.00, 'available', '2025-05-03 20:16:56'),
(4, 'Coolant', 'Fluid', 'coolant.png', 60, 15.00, 'available', '2025-05-03 20:16:56'),
(5, 'Oil Filter', 'Engine', 'oil_filter.png', 50, 20.00, 'available', '2025-05-03 20:17:50'),
(6, 'Brake Pads', 'Brake', 'brake_pads.png', 30, 45.00, 'available', '2025-05-03 20:17:50'),
(7, 'Tires', 'Wheel', 'tires.png', 20, 100.00, 'available', '2025-05-03 20:17:50'),
(8, 'Coolant', 'Fluid', 'coolant.png', 60, 15.00, 'available', '2025-05-03 20:17:50');

-- --------------------------------------------------------

--
-- Table structure for table `mechanics`
--

CREATE TABLE IF NOT EXISTS `mechanics` (
  `mechanic_id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `specialty` varchar(100) DEFAULT NULL,
  `rating` int(11) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`mechanic_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mechanics`
--

INSERT INTO `mechanics` (`mechanic_id`, `full_name`, `email`, `phone`, `specialty`, `rating`, `status`, `created_at`, `first_name`, `last_name`) VALUES
(1, 'Jaswanth Kongara', 'mech1@gmail.com', '1234567890', 'All', 5, 'active', '2025-05-03 20:16:56', 'Jaswanth', 'Kongara'),
(2, 'Lavanya Mukkapati', 'mech2@gmail.com', '1234567891', 'Brakes', 4, 'active', '2025-05-03 20:16:56', 'Lavanya', 'Mukkapati'),
(3, 'Dileep Reddy', 'mech3@gmail.com', '1234567892', 'Wheels', 5, 'active', '2025-05-03 20:16:56', 'Dileep', 'Reddy'),
(4, 'Mayur LNU', 'mech4@gmail.com', '1234567893', 'Engine', 5, 'active', '2025-05-03 20:16:56', 'Mayur', 'LNU'),
(5, 'Jaswanth Kongara', 'mech1@gmail.com', '1234567890', 'All', 5, 'active', '2025-05-03 20:17:50', 'Jaswanth', 'Kongara'),
(6, 'Lavanya Mukkapati', 'mech2@gmail.com', '1234567891', 'Brakes', 4, 'active', '2025-05-03 20:17:50', 'Lavanya', 'Mukkapati'),
(7, 'Dileep Reddy', 'mech3@gmail.com', '1234567892', 'Wheels', 5, 'active', '2025-05-03 20:17:50', 'Dileep', 'Reddy'),
(8, 'Mayur LNU', 'mech4@gmail.com', '1234567893', 'Engine', 5, 'active', '2025-05-03 20:17:50', 'Mayur', 'LNU');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE IF NOT EXISTS `notifications` (
  `notification_id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`notification_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`notification_id`, `customer_id`, `message`, `status`, `created_at`) VALUES
(1, 1, 'Service booked successfully', 'read', '2025-05-03 20:16:56'),
(2, 2, 'Mechanic assigned', 'unread', '2025-05-03 20:16:56'),
(3, 3, 'Payment reminder', 'read', '2025-05-03 20:16:56'),
(4, 4, 'Bill generated', 'unread', '2025-05-03 20:16:56'),
(5, 1, 'Service booked successfully', 'read', '2025-05-03 20:17:50'),
(6, 2, 'Mechanic assigned', 'unread', '2025-05-03 20:17:50'),
(7, 3, 'Payment reminder', 'read', '2025-05-03 20:17:50'),
(8, 4, 'Bill generated', 'unread', '2025-05-03 20:17:50');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE IF NOT EXISTS `payments` (
  `payment_id` int(11) NOT NULL AUTO_INCREMENT,
  `billing_id` int(11) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `method` varchar(50) DEFAULT NULL,
  `cardholder_name` varchar(100) DEFAULT NULL,
  `masked_card_number` varchar(20) DEFAULT NULL,
  `paid_at` datetime DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`payment_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`payment_id`, `billing_id`, `amount`, `method`, `cardholder_name`, `masked_card_number`, `paid_at`, `status`) VALUES
(1, 1, 500.00, 'Credit Card', NULL, NULL, NULL, 'paid'),
(2, 2, 600.00, 'Cash', NULL, NULL, NULL, 'pending'),
(3, 3, 300.00, 'UPI', NULL, NULL, NULL, 'paid'),
(4, 4, 700.00, 'Debit Card', NULL, NULL, NULL, 'pending'),
(5, 1, 500.00, 'Credit Card', NULL, NULL, NULL, 'paid'),
(6, 2, 600.00, 'Cash', NULL, NULL, NULL, 'pending'),
(7, 3, 300.00, 'UPI', NULL, NULL, NULL, 'paid'),
(8, 4, 700.00, 'Debit Card', NULL, NULL, NULL, 'pending'),
(9, 17, 15705.87, 'Cash', '', '', '2025-05-03 17:56:37', 'paid'),
(10, 17, 15705.87, 'Debit Card', 'jaswanth', '***************2345', '2025-05-04 16:17:00', 'paid'),
(11, 17, 15705.87, 'Debit Card', 'jaswanth', '***************2345', '2025-05-04 16:22:56', 'paid'),
(12, 17, 15705.87, 'UPI', '', '', '2025-05-04 16:25:30', 'paid');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE IF NOT EXISTS `services` (
  `service_id` int(11) NOT NULL AUTO_INCREMENT,
  `appointment_id` int(11) DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `mechanic_id` int(11) DEFAULT NULL,
  `vehicle_id` int(11) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `description` text DEFAULT NULL,
  PRIMARY KEY (`service_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`service_id`, `appointment_id`, `customer_id`, `mechanic_id`, `vehicle_id`, `status`, `last_update`, `updated_at`, `description`) VALUES
(1, 1, 1, 1, 1, 'completed', NULL, NULL, 'Oil changed'),
(2, 2, 2, 2, 2, 'pending', NULL, NULL, 'Brakes inspected'),
(3, 3, 3, 3, 3, 'completed', NULL, NULL, 'Tires replaced'),
(4, 4, 4, 4, 4, 'pending', NULL, NULL, 'Coolant added'),
(5, 1, 1, 1, 1, 'completed', NULL, NULL, 'Oil changed'),
(6, 2, 2, 2, 2, 'pending', NULL, NULL, 'Brakes inspected'),
(7, 3, 3, 3, 3, 'completed', NULL, NULL, 'Tires replaced'),
(8, 4, 4, 4, 4, 'pending', NULL, NULL, 'Coolant added');

-- --------------------------------------------------------

--
-- Table structure for table `service_ratings`
--

CREATE TABLE IF NOT EXISTS `service_ratings` (
  `rating_id` int(11) NOT NULL AUTO_INCREMENT,
  `service_id` int(11) DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `mechanic_id` int(11) DEFAULT NULL,
  `rating` int(11) DEFAULT NULL,
  `feedback` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`rating_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `service_ratings`
--

INSERT INTO `service_ratings` (`rating_id`, `service_id`, `customer_id`, `mechanic_id`, `rating`, `feedback`, `created_at`) VALUES
(1, 1, 1, 1, 5, 'Excellent service', NULL),
(2, 2, 2, 2, 4, 'Good work', NULL),
(3, 3, 3, 3, 5, 'Very satisfied', NULL),
(4, 4, 4, 4, 3, 'Average', NULL),
(5, 1, 1, 1, 5, 'Excellent service', NULL),
(6, 2, 2, 2, 4, 'Good work', NULL),
(7, 3, 3, 3, 5, 'Very satisfied', NULL),
(8, 4, 4, 4, 3, 'Average', NULL),
(9, 17, 9, 1, 5, 'good', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `support_tickets`
--

CREATE TABLE IF NOT EXISTS `support_tickets` (
  `ticket_id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `admin_reply` text DEFAULT NULL,
  `status` varchar(20) DEFAULT 'open',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`ticket_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `support_tickets`
--

INSERT INTO `support_tickets` (`ticket_id`, `customer_id`, `subject`, `message`, `admin_reply`, `status`, `created_at`) VALUES
(1, 1, 'Delay in service', 'My service is delayed', NULL, 'open', '2025-05-04 14:49:50'),
(2, 2, 'Payment issue', 'Payment not reflecting', NULL, 'closed', '2025-05-04 14:49:50'),
(3, 3, 'Mechanic not assigned', 'Still waiting for assignment', NULL, 'closed', '2025-05-04 14:49:50'),
(4, 4, 'Bill mismatch', 'Incorrect bill amount', NULL, 'resolved', '2025-05-04 14:49:50'),
(5, 1, 'Delay in service', 'My service is delayed', NULL, 'closed', '2025-05-04 14:49:50'),
(6, 2, 'Payment issue', 'Payment not reflecting', NULL, 'open', '2025-05-04 14:49:50'),
(7, 3, 'Mechanic not assigned', 'Still waiting for assignment', NULL, 'closed', '2025-05-04 14:49:50'),
(8, 4, 'Bill mismatch', 'Incorrect bill amount', NULL, 'resolved', '2025-05-04 14:49:50'),
(9, 9, 'respecgt', 'has jude', NULL, 'open', '2025-05-04 14:49:50'),
(10, 9, 'no proper oil filters', 'need to change', NULL, NULL, '2025-05-04 14:49:50'),
(11, 9, 'no proper oil filters', 'not', NULL, NULL, '2025-05-04 14:49:50'),
(12, 9, 'oil', 'change', NULL, 'open', '2025-05-04 14:52:13'),
(13, 9, 'respecgt', 'helo', NULL, 'open', '2025-05-04 15:03:22'),
(14, 9, 'respecgt', 'gg', NULL, 'open', '2025-05-04 15:08:23');

-- --------------------------------------------------------

--
-- Table structure for table `vehicles`
--

CREATE TABLE IF NOT EXISTS `vehicles` (
  `vehicle_id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) DEFAULT NULL,
  `vehicle_model` varchar(100) DEFAULT NULL,
  `vehicle_number` varchar(50) DEFAULT NULL,
  `year` int(11) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `number_plate` varchar(50) NOT NULL,
  `make` varchar(100) DEFAULT NULL,
  `model` varchar(100) DEFAULT NULL,
  `body_style` varchar(100) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`vehicle_id`),
  UNIQUE KEY `number_plate` (`number_plate`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vehicles`
--

INSERT INTO `vehicles` (`vehicle_id`, `customer_id`, `vehicle_model`, `vehicle_number`, `year`, `image`, `number_plate`, `make`, `model`, `body_style`, `image_path`) VALUES
(2, 9, NULL, NULL, 2026, NULL, 'JWEHU328', 'toyata', 'carmy', 'sedan', '../uploads/vehicles/1746306482_updated_assignment.png');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin_login_logout_details`
--
ALTER TABLE `admin_login_logout_details`
  ADD CONSTRAINT `admin_login_logout_details_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`admin_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
