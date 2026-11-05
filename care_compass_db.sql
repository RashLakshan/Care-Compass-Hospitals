-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Feb 26, 2025 at 08:08 AM
-- Server version: 9.1.0
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `care_compass_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

DROP TABLE IF EXISTS `appointments`;
CREATE TABLE IF NOT EXISTS `appointments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `patient_id` int DEFAULT NULL,
  `doctor_id` int DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `status` enum('pending','confirmed','cancelled') DEFAULT 'pending',
  `service_id` int DEFAULT NULL,
  `appointment_date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `patient_id` (`patient_id`),
  KEY `service_id` (`service_id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `patient_id`, `doctor_id`, `date`, `status`, `service_id`, `appointment_date`) VALUES
(4, 4, 5, NULL, 'pending', 5, '2025-02-27 14:00:00'),
(5, 4, 4, NULL, 'pending', 2, '2025-02-28 11:47:00'),
(7, 11, 6, NULL, 'pending', 2, '2025-02-27 11:59:00');

-- --------------------------------------------------------

--
-- Table structure for table `bill_payments`
--

DROP TABLE IF EXISTS `bill_payments`;
CREATE TABLE IF NOT EXISTS `bill_payments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `appointment_id` int NOT NULL,
  `patient_id` int NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_date` datetime NOT NULL,
  `payment_method` enum('cash','card','online') NOT NULL,
  `status` enum('pending','paid','overdue') DEFAULT 'pending',
  PRIMARY KEY (`id`),
  KEY `appointment_id` (`appointment_id`),
  KEY `patient_id` (`patient_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bill_payments`
--

INSERT INTO `bill_payments` (`id`, `appointment_id`, `patient_id`, `amount`, `payment_date`, `payment_method`, `status`) VALUES
(1, 3, 4, 5000.00, '2025-02-27 03:19:00', 'cash', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `contact_queries`
--

DROP TABLE IF EXISTS `contact_queries`;
CREATE TABLE IF NOT EXISTS `contact_queries` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `status` enum('pending','resolved') DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `doctors`
--

DROP TABLE IF EXISTS `doctors`;
CREATE TABLE IF NOT EXISTS `doctors` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `specialty` varchar(100) NOT NULL,
  `location` varchar(50) NOT NULL,
  `contact` varchar(50) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctors`
--

INSERT INTO `doctors` (`id`, `name`, `specialty`, `location`, `contact`, `photo`) VALUES
(1, 'Dr. Kaveesha ', 'Cardiology', 'Colombo', 'kaveesha@ccompass.lk', '1.jpg'),
(2, 'Dr. Janani', 'Pediatrics', 'Kurunegala', 'janani@ccompass.lk', '2.jpg'),
(3, 'Dr. Pandula ', 'Neurology', 'Colombo', 'pandula@ccompass.lk', '3.jpg'),
(4, 'Dr. Nimali ', 'General Medicine', 'Kandy', 'nimali@ccompass.lk', '4.jpg'),
(5, 'Dr. Tharanga', 'Neurology', 'Colombo', 'chandra@ccompass.lk', '5.jpg'),
(6, 'Dr. Lakshman', 'General Medicine', 'Kandy', 'laksh@ccompass.lk', '6.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

DROP TABLE IF EXISTS `feedback`;
CREATE TABLE IF NOT EXISTS `feedback` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `role` varchar(20) NOT NULL,
  `feedback_text` text NOT NULL,
  `rating` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`id`, `user_id`, `role`, `feedback_text`, `rating`, `created_at`) VALUES
(1, 4, 'patient', 'Good Serveice', 5, '2025-02-26 06:41:23'),
(2, 11, 'patient', 'Fast and Kinly service', 4, '2025-02-26 06:54:21');

-- --------------------------------------------------------

--
-- Table structure for table `medical_records`
--

DROP TABLE IF EXISTS `medical_records`;
CREATE TABLE IF NOT EXISTS `medical_records` (
  `id` int NOT NULL AUTO_INCREMENT,
  `patient_id` int NOT NULL,
  `doctor_id` int DEFAULT NULL,
  `service_id` int DEFAULT NULL,
  `diagnosis` text,
  `treatment` text,
  `date_added` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `patient_id` (`patient_id`),
  KEY `doctor_id` (`doctor_id`),
  KEY `service_id` (`service_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `medical_records`
--

INSERT INTO `medical_records` (`id`, `patient_id`, `doctor_id`, `service_id`, `diagnosis`, `treatment`, `date_added`) VALUES
(1, 4, 1, NULL, 'Stress , Dehydration', 'Rest in quient, Paracetamal', '2025-02-24 21:55:17');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

DROP TABLE IF EXISTS `services`;
CREATE TABLE IF NOT EXISTS `services` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text,
  `category` enum('treatment','laboratory','emergency') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `name`, `description`, `category`) VALUES
(1, 'Western Medicine', 'Expert care in allopathic treatments for various conditions.', 'treatment'),
(2, 'Blood Tests', 'Comprehensive blood test for diagnosis and monitoring', 'laboratory'),
(3, 'Emergency Care', '24/7 care for accidents, injuries, and critical conditions.', 'emergency'),
(4, 'Ayurvedic Therapy', 'Traditional healing methods for wellness and chronic conditions.', 'treatment'),
(5, 'MRI Scan', 'Advanced imaging for detailed diagnostics of internal structures.', 'laboratory'),
(6, 'Heart Attack Response', 'Immediate care and treatment for cardiac emergencies.', 'emergency');

-- --------------------------------------------------------

--
-- Table structure for table `service_queries`
--

DROP TABLE IF EXISTS `service_queries`;
CREATE TABLE IF NOT EXISTS `service_queries` (
  `id` int NOT NULL AUTO_INCREMENT,
  `patient_name` varchar(100) NOT NULL,
  `query` text NOT NULL,
  `service_id` int DEFAULT NULL,
  `status` enum('pending','resolved') DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `service_id` (`service_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','staff','patient') DEFAULT 'patient',
  `email` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `mobile` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `email`, `name`, `mobile`) VALUES
(4, 'rash', '$2y$10$HcZGFkpB26h4rLjuLlLHVuXdwOQn2Pf/g796VHLEoEBPd14zdcZP6', 'patient', 'rashinthalakshan@gmail.com', 'Rashintha Lakshan Tharanga', '+94769459575'),
(8, 'admin', '$2y$10$Ua4PUS7nngxEPkyAowcVz.XBR439ivr64HFBmROHu917E3URGPS8O', 'admin', 'admin@carecompass.lk', 'Admin User', '+94798765432'),
(10, 'hasith', '$2y$10$IiMMcUhjSKUGYr9uJxcObu..vEtG2OEKvLq1zOI2IQ1JcIgOqRP6q', 'staff', 'rash@gmail.com', 'Hasith Methsara', '+94788888723'),
(11, 'Pramu', '$2y$10$2BvQuCk5Ls1Cx7Jx.yi6JeqHrtLrY5k7kC2nckCP78Eo./WfBGipa', 'patient', 'pramu@gmail.com', 'Pramuditha', '+94788888888');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
