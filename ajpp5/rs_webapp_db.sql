-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 25, 2024 at 01:25 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- Database: `rs_webapp_db`

-- --------------------------------------------------------

-- Table structure for table `users`
CREATE TABLE `users` (
  `id` int AUTO_INCREMENT PRIMARY KEY NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table `users`
INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone`, `created_at`) VALUES
(1, 'Admin User', 'admin@domain.com', '$2y$10$gB0bNfwQHDBgF1uCkA11B.b2fllvxSkXlVUk48S9M58YkiZpCCUye', '123456789', '2024-09-24 10:15:45'),
(2, 'John Doe', 'john.doe@example.com', 'password_hash', '987654321', '2024-09-24 11:20:00');

-- --------------------------------------------------------

-- Table structure for table `properties`
CREATE TABLE `properties` (
  `id` int AUTO_INCREMENT PRIMARY KEY NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text,
  `image` text,
  `price` decimal(10,2) NOT NULL,
  `property_type` varchar(50) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `beds` int DEFAULT NULL,
  `baths` float DEFAULT NULL,
  `features` text,
  `label` varchar(100) DEFAULT NULL,
  `area_sqft` float DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `floors` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table `properties`
INSERT INTO `properties` (`id`, `title`, `description`, `image`, `price`, `property_type`, `city`, `beds`, `baths`, `features`, `label`, `area_sqft`, `created_at`, `floors`) VALUES
(31, 'Modern Apartment', 'Test', 'uploads/66f3fff891d62_moreproperty1.png', 15000000.00, 'Apartment', 'Makati City', 2, 1, 'Smart System, Gym Access, Wifi Ready', 'New Listing', 1000, '2024-09-25 12:20:08', NULL),
(32, 'Luxury Villa', 'Test Villa', 'uploads/66f3fff891d62_villa.png', 25000000.00, 'Villa', 'Tagaytay', 4, 3, 'Pool, Garden, WiFi', 'Featured', 2000, '2024-09-25 12:20:08', NULL);

-- --------------------------------------------------------

-- Table structure for table `reservations`
CREATE TABLE `reservations` (
  `id` int AUTO_INCREMENT PRIMARY KEY NOT NULL,
  `property_id` int,
  `user_id` int,
  `status` varchar(20) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `check_in_date` date NOT NULL,
  `check_out_date` date NOT NULL,
  `guests` int NOT NULL,
  `message` text,
  FOREIGN KEY (property_id) REFERENCES properties(id),
  FOREIGN KEY (user_id) REFERENCES users(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table `reservations`
INSERT INTO `reservations` (`id`, `property_id`, `user_id`, `status`, `created_at`, `name`, `email`, `phone`, `check_in_date`, `check_out_date`, `guests`, `message`) VALUES
(1, 31, 1, 'pending', '2024-09-24 12:58:52', 'MARK RYAN HOLMINA', 'markholmina@gmail.com', '09491417161', '2024-09-28', '2024-09-28', 1, 'Reserve Exquisite Mansion');

-- --------------------------------------------------------

-- Table structure for table `appointments`
CREATE TABLE `appointments` (
  `id` int AUTO_INCREMENT PRIMARY KEY NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `appointment_date` datetime NOT NULL,
  `notes` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table `appointments`
INSERT INTO `appointments` (`id`, `name`, `email`, `phone`, `appointment_date`, `notes`, `created_at`) VALUES
(1, 'Jam A', 'test@email.com', '12345678', '2024-09-24 10:00:00', 'Test', '2024-09-23 14:10:02'),
(2, 'Alexandria Holmina', 'test1@gmail.com', '123456789', '2024-09-30 11:00:00', 'Test', '2024-09-25 12:11:16');

-- --------------------------------------------------------

-- Table structure for table `contact_submissions`
CREATE TABLE `contact_submissions` (
  `id` int AUTO_INCREMENT PRIMARY KEY NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `message` text NOT NULL,
  `submitted_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table `contact_submissions`
INSERT INTO `contact_submissions` (`id`, `name`, `email`, `phone`, `message`, `submitted_at`) VALUES
(1, 'Alexa H', 'test@email.com', '1234567889', 'Test email', '2024-09-23 12:58:56'),
(2, 'Alexa H', 'test2@email.com', '12345678', 'Test 2', '2024-09-23 18:15:16'),
(3, 'Jam', 'test4@gmail.com', '123456789', 'Test Contact', '2024-09-25 12:34:33');

-- --------------------------------------------------------

-- Table structure for table `exclusive_properties`
CREATE TABLE `exclusive_properties` (
  `id` int AUTO_INCREMENT PRIMARY KEY NOT NULL,
  `property_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

-- Table structure for table `purchase_agreements`
CREATE TABLE `purchase_agreements` (
  `id` int AUTO_INCREMENT PRIMARY KEY NOT NULL,
  `property_id` int DEFAULT NULL,
  `buyer_name` varchar(255) NOT NULL,
  `buyer_email` varchar(255) NOT NULL,
  `buyer_phone` varchar(255) NOT NULL,
  `purchase_price` decimal(10,2) NOT NULL,
  `closing_date` date NOT NULL,
  `contingencies` text,
  `status` varchar(20) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table `purchase_agreements`
INSERT INTO `purchase_agreements` (`id`, `property_id`, `buyer_name`, `buyer_email`, `buyer_phone`, `purchase_price`, `closing_date`, `contingencies`, `status`, `created_at`) VALUES
(1, 31, 'MARK HOLMINA', 'markholmina@email.com', '0949471578', 15000000.00, '2024-10-15', 'Bank Loan Approval', 'pending', '2024-09-25 12:11:00');

-- --------------------------------------------------------

-- Table structure for table `inquiries`
CREATE TABLE `inquiries` (
  `id` int AUTO_INCREMENT PRIMARY KEY NOT NULL,
  `property_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `inquiry_message` text NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (property_id) REFERENCES properties(id),
  FOREIGN KEY (user_id) REFERENCES users(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table `inquiries`
INSERT INTO `inquiries` (`id`, `property_id`, `user_id`, `inquiry_message`, `created_at`) VALUES
(1, 31, 1, 'Is the property still available?', '2024-09-25 12:12:01'),
(2, 32, 2, 'Can I schedule a visit?', '2024-09-25 12:15:00');

-- --------------------------------------------------------

-- Table structure for table `favorited_properties`
CREATE TABLE `favorited_properties` (
  `id` int AUTO_INCREMENT PRIMARY KEY NOT NULL,
  `user_id` int DEFAULT NULL,
  `property_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id),
  FOREIGN KEY (property_id) REFERENCES properties(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table `favorited_properties`
INSERT INTO `favorited_properties` (`id`, `user_id`, `property_id`, `created_at`) VALUES
(1, 1, 31, '2024-09-25 12:20:20'),
(2, 2, 32, '2024-09-25 12:25:25');

COMMIT;
