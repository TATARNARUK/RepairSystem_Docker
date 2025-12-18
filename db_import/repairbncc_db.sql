-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 10, 2025 at 10:17 AM
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
-- Database: `repairbncc_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `category_repair`
--

CREATE TABLE `category_repair` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category_repair`
--

INSERT INTO `category_repair` (`id`, `name`) VALUES
(1, 'ไฟฟ้า'),
(2, 'ปะปา'),
(3, 'อุปกรณ์อิเล็กทรอนิกส์');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_member`
--

CREATE TABLE `tbl_member` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(50) NOT NULL,
  `m_level` enum('admin','staff','member') NOT NULL DEFAULT 'member',
  `dataCreate` timestamp NOT NULL DEFAULT current_timestamp(),
  `title_name` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `surname` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tbl_member`
--

INSERT INTO `tbl_member` (`id`, `username`, `password`, `m_level`, `dataCreate`, `title_name`, `name`, `surname`) VALUES
(3, 'tar@gmail.com', '2b7092c4009757432fe1db6221b3731a89673218', 'admin', '2025-02-21 08:53:37', 'นาย', 'admingg', 'eiei'),
(9, 'ratchanon@gmail.com', '2115835f5b17481db85b2490c9e78c2528ecd1a2', 'member', '2025-02-25 03:03:41', 'นาย', 'c', 'c'),
(11, 'kittikun@gmail.com', '53372dc8ed050df64a1d78639e359595744c681f', 'member', '2025-02-26 10:02:43', 'นาย', 'd', 'd');

-- --------------------------------------------------------

--
-- Table structure for table `tdl_form_repair`
--

CREATE TABLE `tdl_form_repair` (
  `id` int(11) NOT NULL,
  `repair_status` varchar(50) NOT NULL,
  `member_id` int(11) NOT NULL,
  `phone_number` varchar(12) DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `repair_equipment` varchar(255) DEFAULT NULL,
  `location` varchar(255) NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status_updated_by` int(11) DEFAULT NULL COMMENT 'id ของผู้แก้ไข (admin)',
  `status_updated_at` datetime DEFAULT NULL COMMENT 'วันเวลาที่แก้ไขสถานะ'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tdl_form_repair`
--

INSERT INTO `tdl_form_repair` (`id`, `repair_status`, `member_id`, `phone_number`, `category`, `description`, `repair_equipment`, `location`, `image_path`, `created_at`, `status_updated_by`, `status_updated_at`) VALUES
(2, 'ไม่สามารถดำเนินการได้', 3, '0610895230', 'ปะปา', 'ะด่ปพะด่ปั่', 'คอมพิวเตอร์', 'ตึก9ชั้น7ห้อง9705', 'image/1740914115_Screenshot 2025-03-02 164757.png', '2025-03-02 11:15:15', NULL, NULL),
(3, 'รอดำเนินการ', 9, '0927569314', 'อุปการณ์อิเล็กทรอนิกส์', 'คอมเสีย', 'คอมพิวเตอร์', 'ตึก9ชั้น7ห้อง9704', 'image/1740914225_Screenshot 2025-02-27 150255.png', '2025-03-02 11:17:05', NULL, NULL),
(4, 'สำเร็จเสร็จสิ้น', 11, '10510156', 'ปะปา', 'รนบทืรบงืทนาบ', 'คอมพิวเตอร์', 'ทืรบทืรนบืรนบ', 'image/1740920049_1.png', '2025-03-02 12:54:09', NULL, NULL),
(5, 'กำลังดำเนินการแก้ไข', 3, '0927569314', 'ไฟฟ้า', 'ดับไม่ติด', 'หลอดไฟ', 'ตึฏตชั้นึห้อง9703', 'image/1760003373_Screenshot 2025-10-09 150212.png', '2025-10-09 09:49:33', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category_repair`
--
ALTER TABLE `category_repair`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_member`
--
ALTER TABLE `tbl_member`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `tdl_form_repair`
--
ALTER TABLE `tdl_form_repair`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category_repair`
--
ALTER TABLE `category_repair`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_member`
--
ALTER TABLE `tbl_member`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tdl_form_repair`
--
ALTER TABLE `tdl_form_repair`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
