-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 22, 2025 at 03:08 AM
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
-- Database: `todolist_app`
--

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('pending','completed') DEFAULT 'pending',
  `deadline` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_late` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id`, `user_id`, `title`, `description`, `status`, `deadline`, `created_at`, `is_late`) VALUES
(4, 2, 'MAKAN', 'nasi', 'pending', '2025-05-08 08:09:00', '2025-05-08 00:07:19', 0),
(7, 1, 'makna', 'makan', 'pending', '2025-05-10 00:00:00', '2025-05-08 00:41:17', 0),
(8, 1, 'makan', 'tidur', 'completed', '2025-05-05 00:00:00', '2025-05-08 00:51:56', 0),
(9, 3, 'makan', 'makan', 'pending', '2025-05-17 00:00:00', '2025-05-08 00:55:14', 0),
(10, 3, 'anang', 'pembuatan gelang ocean', 'pending', '2025-05-09 00:00:00', '2025-05-08 00:57:57', 0),
(11, 4, 'makna', 'makna', 'pending', '2025-05-19 08:03:00', '2025-05-16 01:29:21', 0),
(12, 4, 'makan', 'tidur', 'completed', '2025-05-16 10:30:00', '2025-05-16 02:20:21', 0),
(14, 4, 'tidur', 'bubuk', 'completed', '2025-05-18 20:33:00', '2025-05-18 12:32:45', 0),
(15, 4, 'minum', 'makan', 'pending', '2025-05-18 20:39:00', '2025-05-18 12:38:31', 0),
(16, 4, 'maakna', 'makan', 'pending', '2025-05-20 07:52:00', '2025-05-19 23:50:42', 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `profile_picture`, `created_at`) VALUES
(1, 'admin', 'putra123@gmail.com', '$2y$10$MHMdJrklVkSO6wMAnxikv.pzEv736rbn.GAVnR8RCRNKh0nlvo6DK', '682616add8b8c.jpg', '2025-05-06 00:01:48'),
(2, 'ALEXNXX', 'christianbernandesbenu@gmail.com', '$2y$10$.7q6d.cTaktFylM/ahC3Q.CfWI2S3IkxjXnasDHi9TJJYqScNn9gu', '681bf6ebc2c53.jpeg', '2025-05-08 00:06:35'),
(3, 'MADURAKERAS', 'intotobe173@gmail.com', '$2y$10$NHXeW8whCGnU49erqezX.eqeLH8TM1G0nD6yh5/Px7s.AIfQH.kDK', NULL, '2025-05-08 00:54:47'),
(4, 'alek ganteng', 'shadowgaming635@gmail.com', '$2y$10$VANFi26Wd497Yk4FZFFhvuzunLTaNV1VIzYmn0bofW5MOVEzjQr2m', '682bc46236c25.jpg', '2025-05-15 12:23:44');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `tasks_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
