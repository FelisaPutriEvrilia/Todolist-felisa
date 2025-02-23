-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 21, 2025 at 01:53 PM
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
-- Database: `db_todolistfelis`
--

-- --------------------------------------------------------

--
-- Table structure for table `subtasks`
--

CREATE TABLE `subtasks` (
  `id` int(11) NOT NULL,
  `task_id` int(11) NOT NULL,
  `subtask` varchar(255) NOT NULL,
  `status` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `priority` int(11) DEFAULT 2,
  `deadline` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subtasks`
--

INSERT INTO `subtasks` (`id`, `task_id`, `subtask`, `status`, `created_at`, `updated_at`, `priority`, `deadline`) VALUES
(12, 4, 'Transalasi', 1, '2025-02-12 03:32:43', '2025-02-21 10:59:52', 2, NULL),
(13, 4, 'Transalasi', 0, '2025-02-12 03:32:46', '2025-02-12 03:32:46', 2, NULL),
(14, 4, 'Transalasi', 0, '2025-02-12 03:33:40', '2025-02-12 03:33:40', 2, NULL),
(15, 4, 'Transalasi', 0, '2025-02-12 03:33:46', '2025-02-12 03:33:46', 2, NULL),
(16, 4, 'Transalasi', 0, '2025-02-12 03:34:30', '2025-02-12 03:34:30', 2, NULL),
(20, 3, 'Cerpen', 0, '2025-02-12 03:46:26', '2025-02-12 03:46:26', 2, NULL),
(21, 3, 'Transalasi', 0, '2025-02-12 04:08:25', '2025-02-12 04:08:25', 2, NULL),
(22, 3, 'Perkalian', 0, '2025-02-12 04:10:57', '2025-02-12 04:10:57', 2, NULL),
(23, 3, 'Perkalian', 0, '2025-02-12 04:11:01', '2025-02-12 04:11:01', 2, NULL),
(24, 3, 'Perkalian', 0, '2025-02-12 04:11:12', '2025-02-12 04:11:12', 2, NULL),
(25, 3, 'Perkalian', 0, '2025-02-12 04:13:46', '2025-02-12 04:13:46', 2, NULL),
(26, 3, 'Transalasi', 0, '2025-02-12 04:18:44', '2025-02-12 04:18:44', 3, '2025-02-13'),
(29, 5, 'Cerpen', 0, '2025-02-19 07:57:21', '2025-02-19 07:57:21', 2, NULL),
(51, 1, 'dialog', 1, '2025-02-21 08:57:46', '2025-02-21 10:17:23', 1, '2025-03-07'),
(58, 4, 'felisa meencuci piring', 0, '2025-02-21 10:51:53', '2025-02-21 10:51:53', 3, '2025-02-22'),
(61, 1, 'Perkalian', 0, '2025-02-21 11:25:30', '2025-02-21 11:25:30', 2, '2025-02-22'),
(62, 27, 'dialog', 1, '2025-02-21 12:30:24', '2025-02-21 12:30:26', 1, '2025-02-24');

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `task` varchar(255) NOT NULL,
  `status` tinyint(1) DEFAULT 0,
  `deadline` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id`, `user_id`, `task`, `status`, `deadline`, `created_at`, `updated_at`) VALUES
(1, 1, 'jepang', 0, '2025-02-19', '2025-02-11 04:50:05', '2025-02-11 04:50:05'),
(3, 1, 'inggris', 0, '2025-02-13', '2025-02-11 04:50:39', '2025-02-11 04:50:39'),
(4, 1, 'IPS', 0, '2025-02-19', '2025-02-11 04:50:53', '2025-02-11 04:50:53'),
(5, 1, 'B.Indonesia', 0, '2025-02-12', '2025-02-12 03:17:52', '2025-02-12 03:17:52'),
(7, 1, 'IPA', 0, '2025-02-20', '2025-02-20 01:38:32', '2025-02-20 01:38:32'),
(8, 1, 'B.Korea', 0, NULL, '2025-02-20 01:51:58', '2025-02-20 01:51:58'),
(27, 2, 'B.Korea', 0, NULL, '2025-02-21 12:13:01', '2025-02-21 12:13:01'),
(28, 2, 'jepang', 0, NULL, '2025-02-21 12:30:08', '2025-02-21 12:30:08');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `created_at`, `updated_at`) VALUES
(1, 'felisa putri', 'felisa15@gmail.com', '$2y$10$3l8y0hiiL5ENO35N953Q4uZIY0MiPPJUiffQ4HqV8vFF4Yf1eCnm.', '2025-02-11 04:49:39', '2025-02-11 04:49:39'),
(2, 'felisaputri', 'felisa123@gmail.com', '$2y$10$u4WvWyvRi5GQDd5SCwHAXuJMOm4XGYKaZodBTSEZPFvb9yCTHluE6', '2025-02-21 12:12:44', '2025-02-21 12:12:44');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `subtasks`
--
ALTER TABLE `subtasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `task_id` (`task_id`);

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
-- AUTO_INCREMENT for table `subtasks`
--
ALTER TABLE `subtasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `subtasks`
--
ALTER TABLE `subtasks`
  ADD CONSTRAINT `subtasks_ibfk_1` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `tasks_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
