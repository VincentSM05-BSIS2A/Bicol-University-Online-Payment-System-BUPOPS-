-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 28, 2025 at 09:14 AM
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
-- Database: `bupops_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `gcash_payments`
--

CREATE TABLE `gcash_payments` (
  `id` int(11) UNSIGNED NOT NULL,
  `payment_id` int(11) UNSIGNED NOT NULL,
  `gcash_name` varchar(255) NOT NULL,
  `gcash_email` varchar(255) NOT NULL,
  `gcash_phone` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `sub_category_id` int(11) UNSIGNED DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `status` enum('pending','completed','failed') NOT NULL DEFAULT 'pending',
  `paymongo_payment_id` varchar(255) NOT NULL,
  `checkout_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `method` varchar(50) DEFAULT NULL,
  `account_number` varchar(20) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `payment_reference` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `user_id`, `sub_category_id`, `amount`, `description`, `remarks`, `status`, `paymongo_payment_id`, `checkout_url`, `created_at`, `updated_at`, `method`, `account_number`, `name`, `email`, `payment_reference`) VALUES
(45, 8, NULL, 6900.00, NULL, NULL, 'completed', '', NULL, '2025-04-07 14:34:30', '2025-04-26 11:46:15', 'gcash', '09299797138', 'chin', 'chinp23@gmail.com', ''),
(46, 106, NULL, 200.00, NULL, NULL, 'completed', '', NULL, '2025-04-07 14:34:30', '2025-04-26 06:35:57', 'gcash', '09299797138', 'chiefer', 'chiefer143@gmail.com', ''),
(47, 106, NULL, 200.00, NULL, NULL, 'pending', '', NULL, '2025-04-07 14:34:30', '2025-04-07 14:34:30', 'gcash', '09299797138', 'chiefer', 'chiefer143@gmail.com', ''),
(48, 8, NULL, 6900.00, NULL, NULL, 'completed', '', NULL, '2025-04-07 14:34:30', '2025-04-26 06:48:03', 'gcash', '09299797138', 'chin pedrano', 'chinp23@gmail.com', ''),
(49, 8, NULL, 6900.00, NULL, NULL, 'pending', '', NULL, '2025-04-07 14:34:30', '2025-04-07 14:34:30', 'gcash', '09299797138', 'chin pedrano', 'chinp23@gmail.com', ''),
(50, 8, NULL, 6900.00, NULL, NULL, 'pending', '', NULL, '2025-04-07 14:34:30', '2025-04-07 14:34:30', 'gcash', '09299797138', 'chin pedrano', 'chinp23@gmail.com', ''),
(51, 8, NULL, 6900.00, NULL, NULL, 'completed', '', NULL, '2025-04-07 14:34:30', '2025-04-18 15:24:59', 'gcash', '09299797138', 'chin pedrano', 'chinp23@gmail.com', ''),
(52, 8, NULL, 6900.00, NULL, NULL, 'pending', '', NULL, '2025-04-07 14:34:30', '2025-04-07 14:34:30', 'gcash', '09299797138', 'chin pedrano', 'chinp23@gmail.com', ''),
(53, 8, NULL, 6900.00, NULL, NULL, 'pending', '', NULL, '2025-04-07 14:34:30', '2025-04-07 14:34:30', 'gcash', '09299797138', 'chin pedrano', 'chinp23@gmail.com', ''),
(54, 8, NULL, 6900.00, NULL, NULL, 'pending', '', NULL, '2025-04-07 14:34:30', '2025-04-07 14:34:30', 'gcash', '09299797138', 'chin pedrano', 'chinp23@gmail.com', ''),
(55, 8, NULL, 6900.00, NULL, NULL, 'pending', '', NULL, '2025-04-07 14:34:30', '2025-04-07 14:34:30', 'gcash', '09299797138', 'chin pedrano', 'chinp23@gmail.com', ''),
(56, 8, NULL, 6900.00, NULL, NULL, 'pending', '', NULL, '2025-04-07 14:34:30', '2025-04-07 14:34:30', 'gcash', '09299797138', 'chin pedrano', 'chinp23@gmail.com', ''),
(57, 8, NULL, 6900.00, NULL, NULL, 'pending', '', NULL, '2025-04-07 14:34:30', '2025-04-07 14:34:30', 'gcash', '09299797138', 'chin pedrano', 'chinp23@gmail.com', ''),
(58, 8, NULL, 6900.00, NULL, NULL, 'pending', '', NULL, '2025-04-07 14:34:30', '2025-04-07 14:34:30', 'gcash', '09299797138', 'chin pedrano', 'chinp23@gmail.com', ''),
(59, 8, NULL, 6900.00, NULL, NULL, 'completed', '', NULL, '2025-04-07 14:34:30', '2025-04-26 08:13:39', 'gcash', '09299797138', 'chin pedrano', 'chinp23@gmail.com', ''),
(62, 112, NULL, 6900.00, 'gcash', 'test', 'completed', '', NULL, '2025-04-27 10:39:25', '2025-04-27 14:46:04', NULL, NULL, NULL, NULL, ''),
(63, 10, NULL, 200.00, 'gcash', 'test', 'pending', '', NULL, '2025-04-27 13:01:17', '2025-04-27 13:01:17', NULL, NULL, NULL, NULL, ''),
(65, 8, NULL, 6900.00, 'gcash', 'test', 'pending', '', NULL, '2025-04-28 01:02:30', '2025-04-28 01:02:30', NULL, NULL, NULL, NULL, '');

-- --------------------------------------------------------

--
-- Table structure for table `payment_categories`
--

CREATE TABLE `payment_categories` (
  `id` int(11) UNSIGNED NOT NULL,
  `category_name` varchar(255) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `required` tinyint(1) NOT NULL DEFAULT 0,
  `sub_category_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment_categories`
--

INSERT INTO `payment_categories` (`id`, `category_name`, `amount`, `required`, `sub_category_id`) VALUES
(1, 'Tuition Fee', 6900.00, 0, NULL),
(2, 'Miscellaneous Fee', 200.00, 0, NULL),
(3, 'Graduation Fee', 1500.00, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `receipts`
--

CREATE TABLE `receipts` (
  `id` int(11) UNSIGNED NOT NULL,
  `gcash_payment_id` int(11) UNSIGNED NOT NULL,
  `receipt_url` varchar(255) NOT NULL,
  `email_sent` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `payment_id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sub_categories`
--

CREATE TABLE `sub_categories` (
  `id` int(11) UNSIGNED NOT NULL,
  `category_id` int(11) UNSIGNED NOT NULL,
  `sub_category_name` varchar(255) NOT NULL,
  `amount` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sub_categories`
--

INSERT INTO `sub_categories` (`id`, `category_id`, `sub_category_name`, `amount`) VALUES
(1, 1, 'Tuition Fee', 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `transaction_history`
--

CREATE TABLE `transaction_history` (
  `id` int(11) NOT NULL,
  `payment_id` int(10) UNSIGNED NOT NULL,
  `status` enum('pending','completed','failed') NOT NULL,
  `changed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `surname` varchar(255) NOT NULL,
  `bu_email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `year_course` varchar(255) NOT NULL,
  `contact_number` varchar(20) NOT NULL,
  `role` enum('user','admin') NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `surname`, `bu_email`, `password`, `year_course`, `contact_number`, `role`) VALUES
(5, 'vince', 'macs', 'vincemacs@gmail.com', '$2y$10$QP3JNw7Ai6.49hTDYq9GCOAT3yqrUNfv9nUcyXWcd2UTFpzVanUxu', '', '', 'user'),
(6, 'chin', 'macs', 'chinmacs@123', '$2y$10$CwENE7qX0mMiZ1y19vy63ekxexRkNRu6ghLreHtyykcmkUBpuKnSy', '', '', 'user'),
(7, 'mae', 'chin', 'maechin34@gmail.com', '$2y$10$frJBWJJgwSHrGPu/7WXpAuJNfgyIYkumdkS5WGUH7lUTMFpx2tztu', '', '', 'user'),
(8, 'chris', 'pedrano', 'chinp23@gmail.com', '$2y$10$WEolwWSJyLa3jApjh6mfReYbaFDbXzjr.oBdTc44eMe9fygIHl1ne', '', '', 'user'),
(9, 'ej', 'balaguer', 'balaguerej@gmail.com', '$2y$10$qTFTuaOv8A0PkOrUe9hUquPz0WJoJZc6B55ASQsc0qJu1fy0M2ONG', '', '', 'user'),
(10, 'cjay', 'king ', 'cjking@gmail.com', '$2y$10$ANYtfb9Gl3o3kRQ89cAuveiFtDzu0gtIbhk6YVO5DF7To/ho1B1AC', '', '', 'user'),
(11, 'gwen', 'macauyam', 'gwenm@gmail.com', '$2y$10$jDM0whtMGQaClAydqDaXb.0ejE978M4MEa5fPWev7h8gMh061vMAW', '', '', 'user'),
(102, 'hak', 'dog', 'hakdog@gmail.com', '$2y$10$b.mdq/TX1w8mKoq4oT067eWHeL8pFjq05Z7PACvGCgujYL/GgCXOO', '', '', 'user'),
(103, 'Christiano', 'Ronaldo', 'CR7@gmail.com', '$2y$10$h4aMZ5qUQBep3edj3.z.BOw0WlY1DTJf0IQrDlRQsLPBA9./Z6aFG', '', '', 'user'),
(104, 'cynthia', 'macauyam', 'hyacint@gmail.com', '$2y$10$Eyczg5wCUfP.qGwFsTByKuwJkdZsQbEdF2EZsNBsN0ENH8OqnpDLq', '', '', 'user'),
(105, 'Christiano', 'Ronaldo', 'CR7thegoat@gmail.com', '$2y$10$qChJWAprX4/CTSgierL9v.tro.pMTDGAxv/f/V1Qvb4FONeD5z7D6', '', '', 'user'),
(106, 'chiefer', 'macauyam', 'chiefer143@gmail.com', '$2y$10$0RnQEsF952TpUgFVn0Hjv.OCFzBkvVPiXd9Jj0l4UIUUJl2WR3OFO', '', '', 'user'),
(107, 'vincent', 'macs', 'vinceadmin@gmail.com', '$2y$10$sLXN/P9XSxOuDVFJWcO9pOsfjPED/EKRsor9zRkR6qAw9JYm66ITm', '', '', 'user'),
(110, 'vincent', 'macauyam', 'admin@google.com', '$2y$10$hJaJhZ/.ZQHy786eDRSuxu2BXHQIDKicV/Fc5UlOz4XUpyDQDUoAS', '', '', 'admin'),
(112, 'vince', 'zoldyc', 'vince@gmail.com', '$2y$10$jLX0pw6DaxDznC3n6aa6EO1i8BGtSypbRg3XTTdY2G/4wM0k2CvpS', '', '', 'admin'),
(114, 'ej', 'balaguer', 'andoks@gmail.com', '$2y$10$W1qBS1jC/naMA22cALIWE.80vVOrG6qPWdPKYzpDZkmSwOcXI7/MC', '', '', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `gcash_payments`
--
ALTER TABLE `gcash_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payment_id` (`payment_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `payments_ibfk_2` (`sub_category_id`);

--
-- Indexes for table `payment_categories`
--
ALTER TABLE `payment_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `receipts`
--
ALTER TABLE `receipts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gcash_payment_id` (`gcash_payment_id`),
  ADD KEY `fk_receipts_payment` (`payment_id`);

--
-- Indexes for table `sub_categories`
--
ALTER TABLE `sub_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `transaction_history`
--
ALTER TABLE `transaction_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_payment_id` (`payment_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `bu_email` (`bu_email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `gcash_payments`
--
ALTER TABLE `gcash_payments`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT for table `payment_categories`
--
ALTER TABLE `payment_categories`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `receipts`
--
ALTER TABLE `receipts`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `sub_categories`
--
ALTER TABLE `sub_categories`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `transaction_history`
--
ALTER TABLE `transaction_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=115;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `gcash_payments`
--
ALTER TABLE `gcash_payments`
  ADD CONSTRAINT `gcash_payments_ibfk_1` FOREIGN KEY (`payment_id`) REFERENCES `payments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payments_ibfk_2` FOREIGN KEY (`sub_category_id`) REFERENCES `sub_categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `receipts`
--
ALTER TABLE `receipts`
  ADD CONSTRAINT `fk_receipts_payment` FOREIGN KEY (`payment_id`) REFERENCES `payments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `receipts_ibfk_1` FOREIGN KEY (`gcash_payment_id`) REFERENCES `gcash_payments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sub_categories`
--
ALTER TABLE `sub_categories`
  ADD CONSTRAINT `sub_categories_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `payment_categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transaction_history`
--
ALTER TABLE `transaction_history`
  ADD CONSTRAINT `fk_payment_id` FOREIGN KEY (`payment_id`) REFERENCES `payments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transaction_history_ibfk_1` FOREIGN KEY (`payment_id`) REFERENCES `payments` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
