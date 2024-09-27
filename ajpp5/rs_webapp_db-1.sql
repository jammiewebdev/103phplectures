-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 26, 2024 at 04:00 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rs_webapp_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `appointment_date` datetime NOT NULL,
  `notes` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `name`, `email`, `phone`, `appointment_date`, `notes`, `created_at`) VALUES
(1, 'Jam A', 'test@email.com', '12345678', '2024-09-24 10:00:00', 'Test', '2024-09-23 14:10:02'),
(2, 'Alexandria Holmina', 'test1@gmail.com', '123456789', '2024-09-30 11:00:00', 'Test', '2024-09-25 12:11:16');

-- --------------------------------------------------------

--
-- Table structure for table `contact_submissions`
--

CREATE TABLE `contact_submissions` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `message` text NOT NULL,
  `submitted_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `contact_submissions`
--

INSERT INTO `contact_submissions` (`id`, `name`, `email`, `phone`, `message`, `submitted_at`) VALUES
(1, 'Alexa H', 'test@email.com', '1234567889', 'Test email', '2024-09-23 12:58:56'),
(2, 'Alexa H', 'test2@email.com', '12345678', 'Test 2', '2024-09-23 18:15:16'),
(3, 'Jam', 'test4@gmail.com', '123456789', 'Test Contact ', '2024-09-25 12:34:33');

-- --------------------------------------------------------

--
-- Table structure for table `exclusive_properties`
--

CREATE TABLE `exclusive_properties` (
  `id` int NOT NULL,
  `property_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `properties`
--

CREATE TABLE `properties` (
  `id` int NOT NULL,
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `properties`
--

INSERT INTO `properties` (`id`, `title`, `description`, `image`, `price`, `property_type`, `city`, `beds`, `baths`, `features`, `label`, `area_sqft`, `created_at`, `floors`) VALUES
(31, 'Modern Apartment', 'Test', 'uploads/66f3fff891d62_moreproperty1.png', '15000000.00', 'Apartment', 'Makati City', 2, 1, 'Smart System, Gym Access, Wifi Ready', 'New Listing', 1000, '2024-09-25 12:20:08', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `purchase_agreements`
--

CREATE TABLE `purchase_agreements` (
  `id` int NOT NULL,
  `property_id` int DEFAULT NULL,
  `buyer_name` varchar(255) NOT NULL,
  `buyer_email` varchar(255) NOT NULL,
  `buyer_phone` varchar(255) NOT NULL,
  `purchase_price` decimal(10,2) NOT NULL,
  `closing_date` date NOT NULL,
  `contingencies` text,
  `status` varchar(20) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `purchase_agreements`
--

INSERT INTO `purchase_agreements` (`id`, `property_id`, `buyer_name`, `buyer_email`, `buyer_phone`, `purchase_price`, `closing_date`, `contingencies`, `status`, `created_at`) VALUES
(17, NULL, 'Jam A', 'test2@mail.com', '12345678', '800000.00', '2024-10-02', 'Submit Purchase Agreement for Contemporary Condo Suite', 'pending', '2024-09-25 20:15:05');

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `id` int NOT NULL,
  `property_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `check_in_date` date NOT NULL,
  `check_out_date` date NOT NULL,
  `guests` int NOT NULL,
  `message` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `reservations`
--

INSERT INTO `reservations` (`id`, `property_id`, `user_id`, `status`, `created_at`, `name`, `email`, `phone`, `check_in_date`, `check_out_date`, `guests`, `message`) VALUES
(1, 3, NULL, 'pending', '2024-09-24 12:58:52', 'MARK RYAN HOLMINA', 'markholmina@gmail.com', '09491417161', '2024-09-28', '2024-09-28', 1, 'Reserve Exquisite Mansion\r\n'),
(2, 6, NULL, 'pending', '2024-09-24 13:00:49', 'MARK RYAN HOLMINA', 'markholmina@gmail.com', '09491417161', '2024-09-28', '2024-09-28', 1, 'Reserve Chic City Apartment\r\n'),
(3, 9, NULL, 'pending', '2024-09-24 13:01:27', 'MARK RYAN HOLMINA', 'test3@gmail.com', '09491417161', '2024-09-28', '2024-09-28', 3, 'Reserve Serene Garden Estate\r\n'),
(4, 11, NULL, 'pending', '2024-09-24 13:02:46', 'MARK RYAN HOLMINA', 'markholmina@gmail.com', '09491417161', '2024-09-28', '2024-09-28', 2, '​ \r\nReserve Serene Garden Estate'),
(5, 21, NULL, 'pending', '2024-09-24 13:07:08', 'MARK RYAN HOLMINA', 'markholmina@gmail.com', '09491417161', '2024-09-28', '2024-09-19', 2, 'Reserve Sophisticated House\r\n'),
(6, 3, NULL, 'pending', '2024-09-24 13:09:06', 'MARK RYAN HOLMINA', 'markholmina@gmail.com', '09491417161', '2024-09-28', '2024-09-29', 2, 'Reserve Exquisite Mansion\r\n'),
(7, 31, NULL, 'pending', '2024-09-25 20:21:12', 'Alexandria Holmina', 'test1@gmail.com', '123456789', '2024-09-28', '2024-09-28', 2, 'Test');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact_submissions`
--
ALTER TABLE `contact_submissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `exclusive_properties`
--
ALTER TABLE `exclusive_properties`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `property_id` (`property_id`);

--
-- Indexes for table `properties`
--
ALTER TABLE `properties`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchase_agreements`
--
ALTER TABLE `purchase_agreements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `property_id` (`property_id`);

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `property_id` (`property_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `contact_submissions`
--
ALTER TABLE `contact_submissions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `exclusive_properties`
--
ALTER TABLE `exclusive_properties`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `properties`
--
ALTER TABLE `properties`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `purchase_agreements`
--
ALTER TABLE `purchase_agreements`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `exclusive_properties`
--
ALTER TABLE `exclusive_properties`
  ADD CONSTRAINT `exclusive_properties_ibfk_1` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`);

--
-- Constraints for table `purchase_agreements`
--
ALTER TABLE `purchase_agreements`
  ADD CONSTRAINT `purchase_agreements_ibfk_1` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`);

--
-- Constraints for table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`),
  ADD CONSTRAINT `reservations_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
