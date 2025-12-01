-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 01, 2025 at 07:57 AM
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
-- Database: `expense_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_users`
--

INSERT INTO `admin_users` (`id`, `username`, `password`, `created_at`) VALUES
(2, 'admin', '$2y$12$7tFvCEWmG70.4D7EBwPsLey.zPppetoViqgTspMA7rdQU03i4ri1i', '2025-07-26 13:58:33');

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `category` varchar(50) NOT NULL,
  `expense_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `expenses`
--

INSERT INTO `expenses` (`id`, `user_id`, `amount`, `category`, `expense_date`, `created_at`) VALUES
(293, 7, 10000.00, 'Food', '2025-01-01', '2025-12-01 06:52:35'),
(294, 7, 8500.00, 'Food', '2025-02-01', '2025-12-01 06:52:52'),
(296, 7, 9500.00, 'Food', '2025-03-01', '2025-12-01 06:53:32'),
(297, 7, 10500.00, 'Food', '2025-04-01', '2025-12-01 06:54:08'),
(298, 7, 9650.00, 'Food', '2025-05-01', '2025-12-01 06:54:28'),
(299, 7, 8650.00, 'Food', '2025-06-01', '2025-12-01 06:54:42'),
(300, 7, 9200.00, 'Food', '2025-07-01', '2025-12-01 06:55:01'),
(301, 7, 8300.00, 'Food', '2025-08-01', '2025-12-01 06:55:16'),
(302, 7, 9111.00, 'Food', '2025-09-01', '2025-12-01 06:55:31'),
(303, 7, 9234.00, 'Food', '2025-10-01', '2025-12-01 06:55:55'),
(304, 7, 10111.00, 'Food', '2025-11-01', '2025-12-01 06:56:22'),
(305, 7, 7000.00, 'Food', '2025-12-01', '2025-12-01 06:56:40');

-- --------------------------------------------------------

--
-- Table structure for table `financial_tips`
--

CREATE TABLE `financial_tips` (
  `id` int(11) NOT NULL,
  `category` varchar(50) NOT NULL,
  `tip` text NOT NULL,
  `action_link` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `financial_tips`
--

INSERT INTO `financial_tips` (`id`, `category`, `tip`, `action_link`, `created_at`) VALUES
(1, 'Food', 'Cook at home more often. Meal prepping can save you significant amounts each week.', 'https://www.budgetbytes.com/', '2025-11-29 18:43:24'),
(2, 'Food', 'Use a grocery list and stick to it to avoid impulse buys.', NULL, '2025-11-29 18:43:24'),
(3, 'Food', 'Look for discounts and coupons before shopping.', NULL, '2025-11-29 18:43:24'),
(4, 'Transport', 'Consider carpooling or using public transportation to save on fuel and parking.', NULL, '2025-11-29 18:43:24'),
(5, 'Transport', 'Regular vehicle maintenance can prevent costly repairs down the line.', NULL, '2025-11-29 18:43:24'),
(6, 'Utilities', 'Switch to LED bulbs to reduce electricity consumption.', NULL, '2025-11-29 18:43:24'),
(7, 'Utilities', 'Unplug electronics when not in use to avoid phantom energy drain.', NULL, '2025-11-29 18:43:24'),
(8, 'Entertainment', 'Look for free local events or community activities.', NULL, '2025-11-29 18:43:24'),
(9, 'Entertainment', 'Review your streaming subscriptions and cancel ones you rarely use.', NULL, '2025-11-29 18:43:24'),
(10, 'Other', 'Set a budget for miscellaneous expenses and track them carefully.', NULL, '2025-11-29 18:43:24'),
(11, 'Other', 'Build an emergency fund to cover unexpected costs without borrowing.', NULL, '2025-11-29 18:43:24');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `username`, `email`, `phone_number`, `password`, `role`, `created_at`) VALUES
(7, 'tester', 'tester', 'tester@gmail.com', '9812124444', '$2y$10$rIxEueiTPskDZ9AKiKW8dOX9ozcU0khPQ0CnNjeojWoaZF.oM1A1G', 'user', '2025-12-01 06:52:07');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `financial_tips`
--
ALTER TABLE `financial_tips`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `phone_number` (`phone_number`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=306;

--
-- AUTO_INCREMENT for table `financial_tips`
--
ALTER TABLE `financial_tips`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `expenses`
--
ALTER TABLE `expenses`
  ADD CONSTRAINT `expenses_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
