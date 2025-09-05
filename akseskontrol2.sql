-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 05, 2025 at 07:35 PM
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
-- Database: `akseskontrol2`
--

-- --------------------------------------------------------

--
-- Table structure for table `cards`
--

CREATE TABLE `cards` (
  `uid` varchar(32) NOT NULL,
  `name` varchar(100) NOT NULL,
  `division` varchar(100) NOT NULL,
  `mask` tinyint(3) UNSIGNED NOT NULL,
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cards`
--

INSERT INTO `cards` (`uid`, `name`, `division`, `mask`, `updated_at`) VALUES
('5BF4B012', 'Romadhon', 'IT-Spport', 128, '2025-09-06 00:15:54');

-- --------------------------------------------------------

--
-- Table structure for table `device`
--

CREATE TABLE `device` (
  `id` int(11) NOT NULL,
  `NAMA` varchar(100) NOT NULL,
  `APIKEY` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `device`
--

INSERT INTO `device` (`id`, `NAMA`, `APIKEY`, `created_at`) VALUES
(1, 'ESP32_Lantai1', 'mysecret123', '2025-09-05 12:49:06'),
(2, 'ESP32_Lantai2', 'APIKEY-789012-FGHIJ', '2025-09-05 12:49:06'),
(3, 'ESP32_ServerRoom', 'APIKEY-345678-KLMNO', '2025-09-05 12:49:06');

-- --------------------------------------------------------

--
-- Table structure for table `division`
--

CREATE TABLE `division` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `division`
--

INSERT INTO `division` (`id`, `nama`, `created_at`) VALUES
(6, 'IT-Division', '2025-09-05 15:54:56');

-- --------------------------------------------------------

--
-- Table structure for table `rfid_logs`
--

CREATE TABLE `rfid_logs` (
  `id` int(11) NOT NULL,
  `uid` varchar(32) NOT NULL,
  `action` varchar(20) NOT NULL,
  `relays` varchar(50) DEFAULT '-',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rfid_logs`
--

INSERT INTO `rfid_logs` (`id`, `uid`, `action`, `relays`, `created_at`) VALUES
(284, '5BF4B012', 'DENIED', '-', '2025-09-05 08:29:30'),
(285, '5BF4B012', 'DENIED', '-', '2025-09-05 08:48:37'),
(286, '5BF4B012', 'GRANTED', 'LT8', '2025-09-05 08:50:00'),
(287, '5BF4B012', 'REMOVE', '-', '2025-09-05 09:03:40'),
(288, '5BF4B012', 'GRANTED', 'LT8', '2025-09-05 09:13:09'),
(289, '5BF4B012', 'GRANTED', 'LT8', '2025-09-05 09:13:39'),
(290, '5BF4B012', 'GRANTED', 'LT7,LT8', '2025-09-05 09:21:01'),
(291, '5BF4B012', 'GRANTED', 'LT8', '2025-09-05 09:25:17'),
(292, '5BF4B012', 'GRANTED', 'LT8', '2025-09-05 09:28:34'),
(293, '5BF4B012', 'GRANTED', 'LT8', '2025-09-05 09:30:18'),
(294, '5BF4B012', 'GRANTED', 'LT7,LT8', '2025-09-05 09:30:42'),
(295, 'dd', 'ADD', '192', '2025-09-05 09:31:38'),
(296, '5BF4B012', 'DELETED', '192', '2025-09-05 09:35:16'),
(297, '5BF4B012', 'DELETED', '128', '2025-09-05 09:40:47'),
(298, '5BF4B012', 'GRANTED', 'LT7,LT8', '2025-09-05 09:42:33'),
(299, '5BF4B012', 'GRANTED', 'LT8', '2025-09-05 09:48:28'),
(300, 'dd', 'ADD', '128', '2025-09-05 09:50:07'),
(301, 'dd', 'DELETED', '128', '2025-09-05 09:50:24'),
(302, '5BF4B012', 'GRANTED', 'LT7,LT8', '2025-09-05 10:11:48'),
(303, '5BF4B012', 'GRANTED', 'LT7,LT8', '2025-09-05 10:41:21'),
(304, '5BF4B012', 'GRANTED', 'LT6,LT7,LT8', '2025-09-05 10:42:57'),
(305, 'aa', 'DELETED', '128', '2025-09-05 10:43:43'),
(306, '5BF4B012', 'GRANTED', 'LT6,LT7,LT8', '2025-09-05 10:49:15'),
(307, '5BF4B012', 'GRANTED', 'LT6,LT7,LT8', '2025-09-05 15:10:34'),
(308, 'sadsad', 'DELETED', '128', '2025-09-05 15:41:38'),
(309, '5BF4B012', 'DELETED', '224', '2025-09-05 16:01:35'),
(310, '5BF4B012', 'GRANTED', 'LT6,LT7,LT8', '2025-09-05 16:01:39'),
(311, '5BF4B012', 'GRANTED', 'LT6,LT7,LT8', '2025-09-05 16:01:45'),
(312, '5BF4B012', 'DENIED', '-', '2025-09-05 16:01:54'),
(313, '5BF4B012', 'DENIED', '-', '2025-09-05 16:01:58'),
(314, '5BF4B012', 'DENIED', '-', '2025-09-05 16:02:15'),
(315, '5BF4B012', 'DENIED', '-', '2025-09-05 17:15:16');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cards`
--
ALTER TABLE `cards`
  ADD PRIMARY KEY (`uid`);

--
-- Indexes for table `device`
--
ALTER TABLE `device`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `division`
--
ALTER TABLE `division`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rfid_logs`
--
ALTER TABLE `rfid_logs`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `device`
--
ALTER TABLE `device`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `division`
--
ALTER TABLE `division`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `rfid_logs`
--
ALTER TABLE `rfid_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=316;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
