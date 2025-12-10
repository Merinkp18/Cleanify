-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 10, 2025 at 11:05 AM
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
-- Database: `cleanify1`
--

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` mediumtext DEFAULT NULL,
  `contact_preference` enum('phone','email','whatsapp') DEFAULT 'whatsapp',
  `property_type` enum('apartment','house','office','other') DEFAULT 'apartment',
  `property_size_sqm` int(11) DEFAULT NULL,
  `full_address` mediumtext DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `name`, `email`, `phone`, `address`, `contact_preference`, `property_type`, `property_size_sqm`, `full_address`, `created_at`, `updated_at`) VALUES
(1, 'Gorgon Budiono', 'gorgon@gmail.com', '081234567890', 'Jl. Merdeka No. 12', 'whatsapp', 'house', 120, 'Jl. Merdeka No. 12, Jakarta', '2025-11-19 20:54:45', '2025-11-19 20:54:45'),
(2, 'Jibril Geormo', 'jibril@gmail.com', '089876543210', 'Jl. Melati No. 45', 'phone', 'apartment', 45, 'Apartemen Melati Tower A, Lt. 5', '2025-11-19 20:54:45', '2025-11-19 20:54:45'),
(3, 'Yunus Bakrie', 'yunus@gmail.com', '081223344556', 'Jl. Kenanga No. 9', 'email', 'office', 200, 'Perkantoran Kenanga Lt. 2', '2025-11-19 20:54:45', '2025-11-19 20:54:45'),
(4, 'Bahliel Kuoptore', 'bahliel@gmail.com', '082112233445', 'Jl. Sudirman No. 20', 'whatsapp', 'house', 100, 'Jl. Sudirman No. 20, Depok', '2025-11-19 20:54:45', '2025-11-19 20:54:45'),
(5, 'Linda Supratna', 'linda@gmail.com', '085123456767', 'Jl. Mawar No. 15', 'whatsapp', 'apartment', 38, 'Apartemen Mawar Tower B Lt. 10', '2025-11-19 20:54:45', '2025-12-05 06:05:42');

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_code` varchar(30) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `position` varchar(100) NOT NULL,
  `status` enum('active','inactive','on_leave') DEFAULT 'active',
  `rating` decimal(3,2) DEFAULT 0.00,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `address` mediumtext DEFAULT NULL,
  `emergency_contact_name` varchar(100) DEFAULT NULL,
  `emergency_contact_phone` varchar(20) DEFAULT NULL,
  `skills` mediumtext DEFAULT NULL,
  `total_jobs_completed` int(11) DEFAULT 0,
  `certifications` mediumtext DEFAULT NULL,
  `shift_date` date DEFAULT NULL,
  `shift_start` time DEFAULT NULL,
  `shift_end` time DEFAULT NULL,
  `shift_type` enum('morning','afternoon','evening','full') DEFAULT 'full',
  `work_status` enum('scheduled','completed','cancelled') DEFAULT 'scheduled',
  `hired_at` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `employee_code`, `name`, `position`, `status`, `rating`, `phone`, `email`, `password`, `photo`, `address`, `emergency_contact_name`, `emergency_contact_phone`, `skills`, `total_jobs_completed`, `certifications`, `shift_date`, `shift_start`, `shift_end`, `shift_type`, `work_status`, `hired_at`, `created_at`, `updated_at`) VALUES
(1, 'EMP-001', 'Enjel Saputra', 'Regular Cleaner', 'active', 4.60, '081234567890', 'enjel.cleanify@mail.com', NULL, '104a081f3cce40eb82c6ea1c6137282d.jpeg', 'Jl. Melati No. 10, Bandung', 'Merin Julianto', '081200000111', 'Sapu & Pel, Lap Debu, Kaca Kecil', 0, 'Basic Cleaning Certification', '2025-01-20', '08:00:00', '12:00:00', 'morning', 'scheduled', '2024-05-12', '2025-11-19 19:51:56', '2025-12-09 04:50:34'),
(2, 'EMP-002', 'Redup Mayern', 'Deep Clean Specialist', 'active', 4.80, '081298765432', 'redup.cleanify@mail.com', NULL, 'cd6cc5092b1bcb98a8ab199433eb058e.jpg', 'Jl. Sukajadi No. 50, Bandung', 'Budi Saputra', '081299998888', 'Deep Clean, Vacuum, Kitchen & Bathroom Expert', 0, 'Deep Clean Pro Level 2', '2025-01-20', '13:00:00', '17:00:00', 'afternoon', 'scheduled', '2023-11-02', '2025-11-19 19:51:56', '2025-12-09 04:50:06'),
(3, 'EMP-003', 'Amita Bacan', 'Premium Senior Staff', 'on_leave', 4.90, '081277744410', 'amita.cleanify@mail.com', NULL, '6a9768b46b3291be53680c5cfce770e8.png', 'Jl. Dago Pakar No. 11, Bandung', 'Ardi Susanto', '081311112222', 'Detailing, Aromatherapy Setup, VIP Handling, Eco Cleaning', 2, 'Premium Service Certification', '2025-01-25', '07:00:00', '15:00:00', 'full', 'scheduled', '2022-09-18', '2025-11-19 19:51:56', '2025-12-09 04:29:32'),
(4, NULL, 'Chocavala Kharista', 'Regular Cleaner', 'active', 3.00, '081277744455', 'merin@gmail.com', NULL, 'pekerja/asset/uploads/314f63fa1c417a5d1239f509d5f2c483.jpg', 'Jl Telaga Permata 42, Dki Jakarta', 'Ardi Susanto', '081311113333', 'Menyapu,membersihkan permukaan lantai atau bagian dari bangunan dari debu, sampah, dan sebagainya.', 0, '', NULL, NULL, NULL, 'full', 'scheduled', NULL, '2025-12-05 02:15:33', '2025-12-09 04:50:59'),
(7, NULL, 'Linda Supratna', 'Regular Cleaner', 'active', 4.00, '085123456789', 'admin1@gmail.com', NULL, '6a0a4d1f90ffca3a38668e7403deb8bd.png', 'cirebon timur', 'Budi Saputra', '081200000111', 'gapunya skill', 0, '', NULL, NULL, NULL, 'full', 'scheduled', NULL, '2025-12-09 04:52:52', '2025-12-09 04:53:44'),
(8, NULL, 'Redup', 'Regular Cleaner', 'active', 5.00, '081277744410', 'redup7@gmail.com', NULL, 'emp_9c0d36cb3d0e53fcb672ea40.png', 'bengkulu', 'Ardi Susanto', '081299998888', 'useless', 0, '', NULL, NULL, NULL, 'full', 'scheduled', NULL, '2025-12-09 06:01:16', '2025-12-09 06:01:16');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_code` varchar(30) DEFAULT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `service_id` bigint(20) UNSIGNED NOT NULL,
  `order_date` datetime DEFAULT current_timestamp(),
  `scheduled_date` date DEFAULT NULL,
  `scheduled_start_time` time DEFAULT NULL,
  `scheduled_end_time` time DEFAULT NULL,
  `total_cost` decimal(12,2) NOT NULL,
  `status` enum('baru','dikonfirmasi','dalam_proses','selesai','dibatalkan') DEFAULT 'baru',
  `payment_proof` varchar(255) DEFAULT NULL,
  `payment_confirmed_at` datetime DEFAULT NULL,
  `start_time` datetime DEFAULT NULL,
  `finish_time` datetime DEFAULT NULL,
  `customer_rating` int(11) DEFAULT NULL,
  `customer_feedback` text DEFAULT NULL,
  `cancellation_reason` text DEFAULT NULL,
  `cancelled_by` varchar(100) DEFAULT NULL,
  `cancelled_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `order_code`, `customer_id`, `service_id`, `order_date`, `scheduled_date`, `scheduled_start_time`, `scheduled_end_time`, `total_cost`, `status`, `payment_proof`, `payment_confirmed_at`, `start_time`, `finish_time`, `customer_rating`, `customer_feedback`, `cancellation_reason`, `cancelled_by`, `cancelled_at`, `created_at`, `updated_at`) VALUES
(17, 'ORD-20250101-001', 1, 1, '2025-01-01 10:20:00', NULL, NULL, NULL, 75000.00, 'baru', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-19 21:00:58', '2025-11-19 21:00:58'),
(18, 'ORD-20250102-002', 2, 2, '2025-01-02 08:45:00', '2025-01-05', '09:00:00', '12:00:00', 150000.00, 'dikonfirmasi', 'Bukti transfer #A73819', '2025-01-02 09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-19 21:00:58', '2025-11-19 21:00:58'),
(19, 'ORD-20250103-003', 3, 1, '2025-01-03 13:00:00', '2025-01-06', '14:00:00', '15:00:00', 75000.00, 'dalam_proses', 'DP 50% diterima', '2025-01-03 13:10:00', '2025-01-06 14:00:00', NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-19 21:00:58', '2025-11-19 21:00:58'),
(20, 'ORD-20250104-004', 1, 3, '2025-02-04 09:00:00', '2025-01-04', '10:00:00', '13:00:00', 250000.00, 'selesai', 'transfer', '2025-01-04 09:30:00', '2025-01-04 10:00:00', '2025-01-04 13:00:00', 5, 'Customer sangat puas', NULL, NULL, NULL, '2025-11-19 21:00:58', '2025-12-09 07:06:07'),
(21, 'ORD-20250105-005', 4, 1, '2025-01-05 11:20:00', '2025-01-07', '08:00:00', '09:30:00', 75000.00, 'dibatalkan', NULL, NULL, NULL, NULL, NULL, NULL, 'Customer membatalkan karena hujan', 'customer', '2025-01-05 12:00:00', '2025-11-19 21:00:58', '2025-11-19 21:00:58'),
(22, 'ORD-20250106-006', 5, 2, '2025-02-28 16:10:00', NULL, NULL, NULL, 150000.00, 'baru', 'transfer', '2025-02-28 14:02:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-19 21:00:58', '2025-12-09 07:03:16'),
(23, 'ORD-20250107-007', 3, 3, '2025-12-09 07:50:00', '2025-01-07', '08:00:00', '12:00:00', 250000.00, 'dalam_proses', 'transfer', '2025-01-07 07:55:00', '2025-01-07 08:00:00', NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-19 21:00:58', '2025-12-09 07:07:30'),
(24, 'ORD-20250108-008', 2, 1, '2025-12-09 14:30:00', '2025-01-08', '15:00:00', '16:00:00', 75000.00, 'selesai', 'transfer', '2025-01-08 14:45:00', '2025-01-08 15:00:00', '2025-01-08 16:00:00', 4, 'Bersih dan rapi', NULL, NULL, NULL, '2025-11-19 21:00:58', '2025-12-09 06:58:08');

-- --------------------------------------------------------

--
-- Table structure for table `schedules`
--

CREATE TABLE `schedules` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED DEFAULT NULL,
  `schedule_date` date NOT NULL,
  `time_slot_start` time NOT NULL,
  `time_slot_end` time NOT NULL,
  `status` enum('scheduled','in_progress','completed','cancelled') DEFAULT 'scheduled',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `schedules`
--

INSERT INTO `schedules` (`id`, `employee_id`, `order_id`, `schedule_date`, `time_slot_start`, `time_slot_end`, `status`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, '2025-01-21', '08:00:00', '11:00:00', 'scheduled', 'Cleaning apartemen kecil', '2025-11-19 20:05:21', '2025-11-19 20:05:21'),
(2, 2, NULL, '2025-01-21', '13:00:00', '17:00:00', 'scheduled', 'Deep clean kitchen & bathroom', '2025-11-19 20:05:21', '2025-11-19 20:05:21'),
(3, 3, NULL, '2025-01-21', '08:00:00', '16:00:00', 'scheduled', 'Premium cleaning VIP customer', '2025-11-19 20:05:21', '2025-11-19 20:05:21');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `category` enum('regular','deep_clean','premium') NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `duration_minutes` int(11) NOT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `short_description` varchar(255) DEFAULT NULL,
  `full_description` mediumtext DEFAULT NULL,
  `features` mediumtext DEFAULT NULL,
  `not_included` mediumtext DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `name`, `category`, `price`, `duration_minutes`, `status`, `short_description`, `full_description`, `features`, `not_included`, `created_at`, `updated_at`) VALUES
(1, 'Regular Cleaning', 'regular', 50000.00, 45, 'active', 'Pembersihan standar untuk kamar kecil', 'Termasuk sapu, pel, lap debu, membersihkan kaca kecil, dan merapikan barang', 'Sapu dan pel, Lap debu, Bersih kaca kecil, Desinfektan ringan', 'Tidak termasuk mencuci karpet, tidak termasuk deep clean', '2025-11-19 19:31:54', '2025-11-19 19:31:54'),
(2, 'Deep Cleaning', 'deep_clean', 120000.00, 120, 'active', 'Pembersihan mendalam untuk seluruh ruangan', 'Termasuk pembersihan detail, mengangkat furnitur ringan, desinfeksi kuat, pembersihan sudut-sudut sulit', 'Alat khusus, Chemical khusus, Pembersihan mendalam', 'Tidak termasuk perbaikan furnitur atau bongkar besar', '2025-11-19 19:32:48', '2025-11-19 19:32:48'),
(3, 'Premium Cleaning', 'premium', 200000.00, 300, 'active', 'Layanan premium dengan hasil maksimal', 'Layanan bersih premium dengan fokus pada detail dan hasil maksimal. Meliputi pembersihan lantai, debu, kaca, serta area yang sering terlewat untuk tampilan ruangan lebih segar', 'sapu dan pel lantai seluruh area, lap debu permukaan furnitur yang mudah dijangkau, pembersihan kaca bagian dalam yang terjangkau, pembersihan kamar mandi standar (kloset, wastafel, lantai, cermin), pembersihan dapur ringan seperti countertop, kompor bagian atas, dan area sink, serta buang sampah (kantong sampah disediakan pelanggan)', 'Layanan ini tidak mencakup cuci/setrika pakaian, cuci piring menumpuk, pembersihan mendalam untuk kerak/jamur/noda membandel, pembersihan bagian dalam kulkas/oven/microwave, cuci sofa/kasur/karpet dengan mesin, pembersihan area luar rumah atau area tinggi (plafon), serta mengangkat atau memindahkan barang berat', '2025-11-19 19:33:55', '2025-12-10 09:51:11');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin','cleaner') NOT NULL DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `email`, `password`, `role`, `created_at`, `updated_at`) VALUES
(1, 'redup', 'redup', 'refiansyah415@gmail.com', '$2y$10$5NvubLDnCPQe3W7HO/kxm.UxH6IoDuYt/84zMxub1M4KbUVLwPAxm', 'user', '2025-11-06 02:25:50', '2025-11-06 02:25:50'),
(3, '', 'wonder of u', 'redup47@gmail.com', '$2y$10$x8KsP4n794bfAjJdSE3kduDZTpTBpjWh/uvLS8M00LbPZeV3MlAK2', 'user', '2025-11-19 17:33:29', '2025-11-19 17:33:29'),
(10, '', 'mk', 'mk@gmail.com', '$2y$10$RDrHZzFzrfMWdKz.EyNzhOAcITCeF5gTjl70hqShLZQzRgdmQh/Ia', 'user', '2025-11-11 01:31:08', '2025-11-11 01:31:08'),
(11, '', 'kharista', 'kharista@gmail.com', '$2y$10$B/ioL2m18KPFPaGUdMjVc.QyIhtJbGwJwyZwKz2k65/EIGA0yWe5.', 'user', '2025-11-11 01:32:33', '2025-11-11 01:32:33'),
(12, 'Merin Kharista Putri', 'merinkp', 'merin@gmail.com', '$2y$10$B/ioL2m18KPFPaGUdMjVc.QyIhtJbGwJwyZwKz2k65/EIGA0yWe5.', 'cleaner', '2025-11-11 01:50:11', '2025-11-11 01:50:11'),
(20, '', 'admin10', 'admin@gmail.com', '$2y$10$OMIVr96ZBhpcMc4I9oOo5uDO1eRgyxp43P9wnIwPpllXHbhITxwfu', 'admin', '2025-11-25 08:52:24', '2025-11-25 09:03:42'),
(21, '', 'refin', 'refinganteng123@gmail.com', '$2y$10$G6RaUGQ22jAN2qXepfC6OOThy/7O/cOJgYf6f7yXPXshnn8u/pvv6', 'user', '2025-11-25 08:55:11', '2025-11-25 08:55:11'),
(23, NULL, 'adminR', 'admin1@gmail.com', '$2y$10$yBgMshVjGmFid.LNVzxED.0lmWIJWqprsuJGw1vCold9FmbDlywp6', 'admin', '2025-12-01 14:57:12', '2025-12-01 15:05:12'),
(24, NULL, 'gorr', 'revinansyah3@gmail.com', '$2y$10$FiSvI4CEJtznRNYE.jBwAO8TgkYmfQ88p4XtMXAta04eWofhhk2oS', 'user', '2025-12-02 06:14:26', '2025-12-02 06:14:26'),
(25, NULL, 'kuuu', 'redup7@gmail.com', '$2y$10$mcGhKxXQyjmb9N.HoDaiau1at5mRQIiIII2NSdhsmfbJn2Oeqes6W', 'user', '2025-12-02 06:22:02', '2025-12-02 06:22:02');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `employee_code` (`employee_code`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `service_id` (`service_id`);

--
-- Indexes for table `schedules`
--
ALTER TABLE `schedules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_id` (`employee_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `email_2` (`email`),
  ADD UNIQUE KEY `username_2` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `schedules`
--
ALTER TABLE `schedules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`);

--
-- Constraints for table `schedules`
--
ALTER TABLE `schedules`
  ADD CONSTRAINT `schedules_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`),
  ADD CONSTRAINT `schedules_ibfk_2` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
