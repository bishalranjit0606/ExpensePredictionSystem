-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jul 26, 2025 at 04:07 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

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
(2, 'admin', '$2y$10$uGjx9HsTrKB4TIOtKzA4B.qYAwqcD.qFjBsRpp07Assgy9XI94dvG', '2025-07-26 13:58:33');

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
(198, 3, 2500.50, 'Food', '2024-01-05', '2025-07-26 13:48:05'),
(199, 3, 1200.75, 'Transport', '2024-01-07', '2025-07-26 13:48:05'),
(200, 3, 3500.00, 'Utilities', '2024-01-10', '2025-07-26 13:48:05'),
(201, 3, 800.25, 'Entertainment', '2024-01-12', '2025-07-26 13:48:05'),
(202, 3, 1500.00, 'Other', '2024-01-15', '2025-07-26 13:48:05'),
(203, 3, 2700.30, 'Food', '2024-02-03', '2025-07-26 13:48:05'),
(204, 3, 1300.60, 'Transport', '2024-02-06', '2025-07-26 13:48:05'),
(205, 3, 3600.80, 'Utilities', '2024-02-09', '2025-07-26 13:48:05'),
(206, 3, 900.45, 'Entertainment', '2024-02-11', '2025-07-26 13:48:05'),
(207, 3, 1600.20, 'Other', '2024-02-14', '2025-07-26 13:48:05'),
(208, 3, 2600.10, 'Food', '2024-03-04', '2025-07-26 13:48:05'),
(209, 3, 1400.90, 'Transport', '2024-03-07', '2025-07-26 13:48:05'),
(210, 3, 3700.15, 'Utilities', '2024-03-10', '2025-07-26 13:48:05'),
(211, 3, 850.30, 'Entertainment', '2024-03-13', '2025-07-26 13:48:05'),
(212, 3, 1700.50, 'Other', '2024-03-16', '2025-07-26 13:48:05'),
(213, 3, 2800.70, 'Food', '2024-04-02', '2025-07-26 13:48:05'),
(214, 3, 1250.25, 'Transport', '2024-04-05', '2025-07-26 13:48:05'),
(215, 3, 3550.40, 'Utilities', '2024-04-08', '2025-07-26 13:48:05'),
(216, 3, 950.60, 'Entertainment', '2024-04-11', '2025-07-26 13:48:05'),
(217, 3, 1550.80, 'Other', '2024-04-14', '2025-07-26 13:48:05'),
(218, 3, 2900.90, 'Food', '2024-05-03', '2025-07-26 13:48:05'),
(219, 3, 1350.15, 'Transport', '2024-05-06', '2025-07-26 13:48:05'),
(220, 3, 3650.20, 'Utilities', '2024-05-09', '2025-07-26 13:48:05'),
(221, 3, 875.75, 'Entertainment', '2024-05-12', '2025-07-26 13:48:05'),
(222, 3, 1650.30, 'Other', '2024-05-15', '2025-07-26 13:48:05'),
(223, 3, 3000.40, 'Food', '2024-06-02', '2025-07-26 13:48:05'),
(224, 3, 1450.50, 'Transport', '2024-06-05', '2025-07-26 13:48:05'),
(225, 3, 3750.60, 'Utilities', '2024-06-08', '2025-07-26 13:48:05'),
(226, 3, 925.80, 'Entertainment', '2024-06-11', '2025-07-26 13:48:05'),
(227, 3, 1750.10, 'Other', '2024-06-14', '2025-07-26 13:48:05'),
(228, 3, 3100.20, 'Food', '2024-07-03', '2025-07-26 13:48:05'),
(229, 3, 1550.70, 'Transport', '2024-07-06', '2025-07-26 13:48:05'),
(230, 3, 3850.90, 'Utilities', '2024-07-09', '2025-07-26 13:48:05'),
(231, 3, 975.25, 'Entertainment', '2024-07-12', '2025-07-26 13:48:05'),
(232, 3, 1850.40, 'Other', '2024-07-15', '2025-07-26 13:48:05'),
(233, 3, 3200.60, 'Food', '2024-08-02', '2025-07-26 13:48:05'),
(234, 3, 1650.80, 'Transport', '2024-08-05', '2025-07-26 13:48:05'),
(235, 3, 3950.15, 'Utilities', '2024-08-08', '2025-07-26 13:48:05'),
(236, 3, 1025.30, 'Entertainment', '2024-08-11', '2025-07-26 13:48:05'),
(237, 3, 1950.50, 'Other', '2024-08-14', '2025-07-26 13:48:05'),
(238, 3, 3300.70, 'Food', '2024-09-03', '2025-07-26 13:48:05'),
(239, 3, 1750.90, 'Transport', '2024-09-06', '2025-07-26 13:48:05'),
(240, 3, 4050.20, 'Utilities', '2024-09-09', '2025-07-26 13:48:05'),
(241, 3, 1075.60, 'Entertainment', '2024-09-12', '2025-07-26 13:48:05'),
(242, 3, 2050.80, 'Other', '2024-09-15', '2025-07-26 13:48:05'),
(243, 3, 3400.10, 'Food', '2024-10-02', '2025-07-26 13:48:05'),
(244, 3, 1850.25, 'Transport', '2024-10-05', '2025-07-26 13:48:05'),
(245, 3, 4150.40, 'Utilities', '2024-10-08', '2025-07-26 13:48:05'),
(246, 3, 1125.75, 'Entertainment', '2024-10-11', '2025-07-26 13:48:05'),
(247, 3, 2150.90, 'Other', '2024-10-14', '2025-07-26 13:48:05'),
(248, 3, 3500.30, 'Food', '2024-11-03', '2025-07-26 13:48:05'),
(249, 3, 1950.15, 'Transport', '2024-11-06', '2025-07-26 13:48:05'),
(250, 3, 4250.60, 'Utilities', '2024-11-09', '2025-07-26 13:48:05'),
(251, 3, 1175.80, 'Entertainment', '2024-11-12', '2025-07-26 13:48:05'),
(252, 3, 2250.20, 'Other', '2024-11-15', '2025-07-26 13:48:05'),
(253, 3, 3600.40, 'Food', '2024-12-02', '2025-07-26 13:48:05'),
(254, 3, 2050.50, 'Transport', '2024-12-05', '2025-07-26 13:48:05'),
(255, 3, 4350.70, 'Utilities', '2024-12-08', '2025-07-26 13:48:05'),
(256, 3, 1225.90, 'Entertainment', '2024-12-11', '2025-07-26 13:48:05'),
(257, 3, 2350.10, 'Other', '2024-12-14', '2025-07-26 13:48:05'),
(258, 3, 3700.60, 'Food', '2025-01-03', '2025-07-26 13:48:05'),
(259, 3, 2150.80, 'Transport', '2025-01-06', '2025-07-26 13:48:05'),
(260, 3, 4450.15, 'Utilities', '2025-01-09', '2025-07-26 13:48:05'),
(261, 3, 1275.25, 'Entertainment', '2025-01-12', '2025-07-26 13:48:05'),
(262, 3, 2450.40, 'Other', '2025-01-15', '2025-07-26 13:48:05'),
(263, 3, 3800.70, 'Food', '2025-02-02', '2025-07-26 13:48:05'),
(264, 3, 2250.90, 'Transport', '2025-02-05', '2025-07-26 13:48:05'),
(265, 3, 4550.20, 'Utilities', '2025-02-08', '2025-07-26 13:48:05'),
(266, 3, 1325.60, 'Entertainment', '2025-02-11', '2025-07-26 13:48:05'),
(267, 3, 2550.80, 'Other', '2025-02-14', '2025-07-26 13:48:05'),
(268, 3, 3900.10, 'Food', '2025-03-03', '2025-07-26 13:48:05'),
(269, 3, 2350.25, 'Transport', '2025-03-06', '2025-07-26 13:48:05'),
(270, 3, 4650.40, 'Utilities', '2025-03-09', '2025-07-26 13:48:05'),
(271, 3, 1375.75, 'Entertainment', '2025-03-12', '2025-07-26 13:48:05'),
(272, 3, 2650.90, 'Other', '2025-03-15', '2025-07-26 13:48:05'),
(273, 3, 4000.30, 'Food', '2025-04-02', '2025-07-26 13:48:05'),
(274, 3, 2450.15, 'Transport', '2025-04-05', '2025-07-26 13:48:05'),
(275, 3, 4750.60, 'Utilities', '2025-04-08', '2025-07-26 13:48:05'),
(276, 3, 1425.80, 'Entertainment', '2025-04-11', '2025-07-26 13:48:05'),
(277, 3, 2750.20, 'Other', '2025-04-14', '2025-07-26 13:48:05'),
(278, 3, 4100.40, 'Food', '2025-05-03', '2025-07-26 13:48:05'),
(279, 3, 2550.50, 'Transport', '2025-05-06', '2025-07-26 13:48:05'),
(280, 3, 4850.70, 'Utilities', '2025-05-09', '2025-07-26 13:48:05'),
(281, 3, 1475.90, 'Entertainment', '2025-05-12', '2025-07-26 13:48:05'),
(282, 3, 2850.10, 'Other', '2025-05-15', '2025-07-26 13:48:05'),
(283, 3, 4200.60, 'Food', '2025-06-02', '2025-07-26 13:48:05'),
(284, 3, 2650.80, 'Transport', '2025-06-05', '2025-07-26 13:48:05'),
(285, 3, 4950.15, 'Utilities', '2025-06-08', '2025-07-26 13:48:05'),
(286, 3, 1525.25, 'Entertainment', '2025-06-11', '2025-07-26 13:48:05'),
(287, 3, 2950.40, 'Other', '2025-06-14', '2025-07-26 13:48:05'),
(288, 3, 4300.70, 'Food', '2025-07-03', '2025-07-26 13:48:05'),
(289, 3, 2750.90, 'Transport', '2025-07-06', '2025-07-26 13:48:05'),
(290, 3, 5050.20, 'Utilities', '2025-07-09', '2025-07-26 13:48:05'),
(291, 3, 1575.60, 'Entertainment', '2025-07-12', '2025-07-26 13:48:05'),
(292, 3, 3050.80, 'Other', '2025-07-15', '2025-07-26 13:48:05');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `created_at`) VALUES
(1, 'admin', '$2y$10$J8vXz5kX1Qz7Y6mZ3pW4Ne4QzX9k2j5m6n7p8r9t0u1v2w3x4y5z', 'admin', '2025-07-26 13:23:00'),
(2, 'user1', '$2y$10$J8vXz5kX1Qz7Y6mZ3pW4Ne4QzX9k2j5m6n7p8r9t0u1v2w3x4y5z', 'user', '2025-07-26 13:23:00'),
(3, 'tester', '$2y$10$0VmW4CvZU4igv8anbFCbUu3HXozBGN4ymZ1BwsFjrTWAi1kHU5i.q', 'user', '2025-07-26 13:26:12');

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
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=293;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
