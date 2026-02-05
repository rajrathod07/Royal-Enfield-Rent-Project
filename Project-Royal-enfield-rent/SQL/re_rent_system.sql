-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 14, 2025 at 11:54 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";

CREATE DATABASE IF NOT EXISTS `re_rent_system`;
USE `re_rent_system`;

START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `re_rent_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `username`, `password`, `created_at`) VALUES
(1, 'Raj', 'admin123', '2025-08-08 09:11:44');

-- --------------------------------------------------------

--
-- Table structure for table `bikes`
--

CREATE TABLE `bikes` (
  `bike_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `engine` varchar(100) DEFAULT NULL,
  `mileage` varchar(50) DEFAULT NULL,
  `gearbox` varchar(50) DEFAULT NULL,
  `price_per_day` int(11) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `availability` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bikes`
--

INSERT INTO `bikes` (`bike_id`, `name`, `engine`, `mileage`, `gearbox`, `price_per_day`, `image`, `availability`) VALUES
(1, 'Classic 350', '349cc, Air-cooled', '35', '5-speed', 1200, 'assets/images/bikes/classic350.jpg', 1),
(2, 'Hunter 350', '349cc, Air-cooled', '36', '5-speed', 1100, 'assets/images/bikes/hunter350.jpg', 1),
(3, 'Meteor 350', '349cc, Air-oil cooled', '41', '5-speed', 1300, 'assets/images/bikes/meteor350.jpg', 1),
(4, 'Bullet 350', '349cc, Single Cylinder', '37', '5-speed', 1150, 'assets/images/bikes/bullet350.jpg', 1),
(5, 'Himalayan 450', '452cc, Liquid-cooled', '30', '6-speed', 1800, 'assets/images/bikes/himalayan450.jpg', 1),
(6, 'Scram 411', '411cc, Air-cooled', '32', '5-speed', 1500, 'assets/images/bikes/scarm411.jpg', 1),
(7, 'Interceptor 650', '648cc, Twin Cylinder', '24', '6-speed', 1900, 'assets/images/bikes/interceptor650.jpg', 1),
(8, 'Continental GT 650', '648cc, Parallel Twin', '25', '6-speed', 1950, 'assets/images/bikes/gt650.jpg', 1),
(9, 'Shotgun 650', '648cc, Twin Cylinder', '26', '6-speed', 2000, 'assets/images/bikes/shotgun650.jpg', 1),
(10, 'Super Meteor 650', '648cc, Twin Cylinder', '25', '6-speed', 2100, 'assets/images/bikes/supermeteor650.jpg', 1),
(11, 'Guerrilla 450', '452cc, Liquid-cooled', '28', '6-speed', 1750, 'assets/images/bikes/guerrilla450.jpg', 1);

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`id`, `user_id`, `comment`, `created_at`) VALUES
(1, 1, '\"if you want to change your profile picture goto Bookings and click on profile image....👻\"', '2025-09-14 09:26:02'),
(2, 1, '\"Hi Welcome To My Site Drop Your Feedback Hear....🙃\"', '2025-09-14 09:26:21');

-- --------------------------------------------------------

--
-- Table structure for table `rentals`
--

CREATE TABLE `rentals` (
  `rental_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `bike_id` int(11) NOT NULL,
  `mobile` varchar(15) DEFAULT NULL,
  `aadhaar` varchar(20) DEFAULT NULL,
  `pickup_location` varchar(255) DEFAULT NULL,
  `fuel_given` float DEFAULT 0,
  `pickup_date` date DEFAULT NULL,
  `return_date` date DEFAULT NULL,
  `total_days` int(11) DEFAULT NULL,
  `total_cost` int(11) DEFAULT NULL,
  `pre_payment_amount` decimal(10,2) DEFAULT 0.00,
  `status` varchar(20) DEFAULT NULL,
  `cancellation_requested` tinyint(1) DEFAULT 0,
  `cancellation_status` enum('Pending','Approved','Rejected') DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `payment_status` enum('Paid','Unpaid') DEFAULT 'Unpaid',
  `pickup_status` varchar(20) DEFAULT 'Not Collected',
  `is_deleted` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `profile_img` varchar(255) NOT NULL DEFAULT 'default.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `email`, `password`, `created_at`, `profile_img`) VALUES
(1, 'Rathod Raj', 'raj@gmail.com', '$2y$10$eOoz69xp6HLmny0p7J6PFeKLf46avMzTtbjHCi0qZufg.s3OAixxS', '2025-09-14 09:06:04', 'user_1.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `bikes`
--
ALTER TABLE `bikes`
  ADD PRIMARY KEY (`bike_id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `rentals`
--
ALTER TABLE `rentals`
  ADD PRIMARY KEY (`rental_id`),
  ADD KEY `fk_bike` (`bike_id`),
  ADD KEY `fk_rentals_user` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `bikes`
--
ALTER TABLE `bikes`
  MODIFY `bike_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `rentals`
--
ALTER TABLE `rentals`
  MODIFY `rental_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `rentals`
--
ALTER TABLE `rentals`
  ADD CONSTRAINT `fk_bike` FOREIGN KEY (`bike_id`) REFERENCES `bikes` (`bike_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_rentals_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
