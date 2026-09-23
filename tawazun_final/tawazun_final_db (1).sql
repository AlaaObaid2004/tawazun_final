-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 23, 2026 at 02:22 AM
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
-- Database: `tawazun_final_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_login`
--

CREATE TABLE `admin_login` (
  `id` int(11) NOT NULL,
  `email` text DEFAULT NULL,
  `password` text DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_login`
--

INSERT INTO `admin_login` (`id`, `email`, `password`, `name`) VALUES
(1, 'a@gmail.com', '123', 'المشرف '),
(2, 'ae@gmail.com', '123', 'ايه');

-- --------------------------------------------------------

--
-- Table structure for table `doctor_login`
--

CREATE TABLE `doctor_login` (
  `id` int(11) NOT NULL,
  `email` text DEFAULT NULL,
  `password` int(255) DEFAULT NULL,
  `name` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctor_login`
--

INSERT INTO `doctor_login` (`id`, `email`, `password`, `name`) VALUES
(1, 'd@gmail.com', 1234, 'الدكتور '),
(2, 'm@gmail.com', 1234, 'محمد');

-- --------------------------------------------------------

--
-- Table structure for table `psychological_requests`
--

CREATE TABLE `psychological_requests` (
  `id` int(11) NOT NULL,
  `student_name` varchar(150) NOT NULL,
  `problem_type` varchar(100) NOT NULL,
  `priority_level` enum('عالية','متوسطة','منخفضة') NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `is_anonymous` tinyint(1) DEFAULT 0,
  `status` enum('جديد','قيد المراجعة','مكتملة') DEFAULT 'جديد',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `psychological_requests`
--

INSERT INTO `psychological_requests` (`id`, `student_name`, `problem_type`, `priority_level`, `title`, `description`, `is_anonymous`, `status`, `created_at`) VALUES
(13, 'الطالب', 'ضغوط الدراسة', 'عالية', '00000000000000000000000000000', '1111111111111111111111111111111111111111111111111', 0, 'قيد المراجعة', '2026-06-22 02:56:30'),
(20, 'الطالب', 'ضغوط الدراسة', 'عالية', '00000', '00000000000000000000000000000', 0, 'جديد', '2026-06-22 23:57:55'),
(21, 'الطالب', 'صعوبة في التركيز', 'منخفضة', '1111111', '1111111111111111111111111111', 1, 'جديد', '2026-06-22 23:58:29'),
(22, 'امير غاندي الفرا', 'العزلة الاجتماعية', 'عالية', 'بسم', 'بسم الله الرحمن الرحيم', 0, 'جديد', '2026-06-23 00:01:06'),
(23, 'يارا', 'قلق', 'عالية', 'يارا', 'يارا يارا يارا', 0, 'جديد', '2026-06-23 00:16:04'),
(24, 'يارا', 'ضغوط الدراسة', 'منخفضة', 'يارا1', 'يارا 1 يارا 1يارا 1', 1, 'جديد', '2026-06-23 00:16:43'),
(25, 'امير غاندي الفرا', 'العزلة الاجتماعية', 'متوسطة', 'امير امير امير', 'الفراالفرا الفرا', 0, 'جديد', '2026-06-23 00:18:57');

-- --------------------------------------------------------

--
-- Table structure for table `register`
--

CREATE TABLE `register` (
  `name` text DEFAULT NULL,
  `email` text DEFAULT NULL,
  `phone` int(10) DEFAULT NULL,
  `University` int(8) DEFAULT NULL,
  `password` text DEFAULT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `register`
--

INSERT INTO `register` (`name`, `email`, `phone`, `University`, `password`, `id`) VALUES
('الطالب', 's@gmail.com', 599833981, 12345678, '12345678', 2),
('يارا', 'e@gmail.com', 599999999, 12345678, '12345678', 4),
('امير غاندي الفرا', 'a421@gmail.gom', 595614106, 20221469, '123456789', 7);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_login`
--
ALTER TABLE `admin_login`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `doctor_login`
--
ALTER TABLE `doctor_login`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `psychological_requests`
--
ALTER TABLE `psychological_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `register`
--
ALTER TABLE `register`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_login`
--
ALTER TABLE `admin_login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `doctor_login`
--
ALTER TABLE `doctor_login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `psychological_requests`
--
ALTER TABLE `psychological_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `register`
--
ALTER TABLE `register`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
