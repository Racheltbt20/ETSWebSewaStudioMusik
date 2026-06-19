-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 19, 2026 at 11:49 AM
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
-- Database: `sewa_studio`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`) VALUES
(1, 'admin', '$2y$10$.7gwIRhxpr/GAzjlDlAfT.qK0oDuhhmei8sed5E8JD6k06ZSIx3Ve');

-- --------------------------------------------------------

--
-- Table structure for table `studio`
--

CREATE TABLE `studio` (
  `id` int(11) NOT NULL,
  `tipe_studio` varchar(100) NOT NULL,
  `harga` int(11) NOT NULL,
  `foto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `studio`
--

INSERT INTO `studio` (`id`, `tipe_studio`, `harga`, `foto`) VALUES
(1, 'Studio Regular', 60000, 'studio_6a32a93cbfabd.jpg'),
(2, 'Studio Premium', 100000, 'studio_6a32a9428fa55.jpg'),
(3, 'Studio VIP Recording', 150000, 'studio_6a32a94924950.jpg'),
(4, 'Studio Mini', 40000, 'studio_6a32a93636f04.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `telepon` varchar(15) NOT NULL,
  `studio_id` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL,
  `total_harga` int(11) NOT NULL,
  `status` enum('menunggu','dibayar','selesai','kedaluwarsa') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaksi`
--

INSERT INTO `transaksi` (`id`, `nama`, `telepon`, `studio_id`, `tanggal`, `jam_mulai`, `jam_selesai`, `total_harga`, `status`) VALUES
(1, 'Andi Pratama', '081234567801', 1, '2026-06-01', '08:00:00', '10:00:00', 120000, 'selesai'),
(2, 'Budi Santoso', '081234567802', 2, '2026-06-01', '09:00:00', '11:00:00', 200000, 'selesai'),
(3, 'Citra Lestari', '081234567803', 3, '2026-06-01', '13:00:00', '15:00:00', 300000, 'selesai'),
(4, 'Andi Pratama', '081234567801', 4, '2026-06-02', '08:00:00', '10:00:00', 80000, 'selesai'),
(5, 'Dewi Anggraini', '081234567804', 1, '2026-06-02', '13:00:00', '16:00:00', 180000, 'selesai'),
(6, 'Farhan Rizky', '081234567805', 2, '2026-06-02', '09:00:00', '12:00:00', 300000, 'kedaluwarsa'),
(7, 'Andi Pratama', '081234567801', 3, '2026-06-03', '08:00:00', '10:00:00', 300000, 'selesai'),
(8, 'Gita Permata', '081234567806', 4, '2026-06-03', '13:00:00', '16:00:00', 120000, 'selesai'),
(9, 'Hendra Wijaya', '081234567807', 1, '2026-06-03', '17:00:00', '19:00:00', 120000, 'selesai'),
(10, 'Andi Pratama', '081234567801', 2, '2026-06-04', '08:00:00', '10:00:00', 200000, 'selesai'),
(11, 'Indah Sari', '081234567808', 3, '2026-06-04', '13:00:00', '15:00:00', 300000, 'kedaluwarsa'),
(12, 'Joko Susilo', '081234567809', 4, '2026-06-04', '09:00:00', '11:00:00', 80000, 'selesai'),
(13, 'Andi Pratama', '081234567801', 1, '2026-06-05', '08:00:00', '11:00:00', 180000, 'selesai'),
(14, 'Kartika Dewi', '081234567810', 2, '2026-06-05', '13:00:00', '15:00:00', 200000, 'selesai'),
(15, 'Lukman Hakim', '081234567811', 3, '2026-06-05', '16:00:00', '18:00:00', 300000, 'selesai'),
(16, 'Andi Pratama', '081234567801', 4, '2026-06-06', '08:00:00', '10:00:00', 80000, 'kedaluwarsa'),
(17, 'Maya Putri', '081234567812', 1, '2026-06-06', '13:00:00', '15:00:00', 120000, 'selesai'),
(18, 'Nanda Prakoso', '081234567813', 2, '2026-06-06', '16:00:00', '19:00:00', 300000, 'selesai'),
(19, 'Andi Pratama', '081234567801', 3, '2026-06-07', '08:00:00', '10:00:00', 300000, 'selesai'),
(20, 'Olivia Maharani', '081234567814', 4, '2026-06-07', '13:00:00', '16:00:00', 120000, 'selesai'),
(21, 'Putra Ramadhan', '081234567815', 1, '2026-06-08', '08:00:00', '10:00:00', 120000, 'kedaluwarsa'),
(22, 'Andi Pratama', '081234567801', 2, '2026-06-08', '13:00:00', '15:00:00', 200000, 'selesai'),
(23, 'Qori Ahmad', '081234567816', 3, '2026-06-08', '16:00:00', '18:00:00', 300000, 'selesai'),
(24, 'Rina Amelia', '081234567817', 4, '2026-06-09', '08:00:00', '10:00:00', 80000, 'selesai'),
(25, 'Andi Pratama', '081234567801', 1, '2026-06-09', '13:00:00', '16:00:00', 180000, 'selesai'),
(26, 'Satria Nugraha', '081234567818', 2, '2026-06-09', '17:00:00', '19:00:00', 200000, 'kedaluwarsa'),
(27, 'Andi Pratama', '081234567801', 3, '2026-06-10', '08:00:00', '10:00:00', 300000, 'selesai'),
(28, 'Tika Ananda', '081234567819', 4, '2026-06-10', '13:00:00', '15:00:00', 80000, 'selesai'),
(29, 'Budi Santoso', '081234567802', 1, '2026-06-11', '08:00:00', '10:00:00', 120000, 'selesai'),
(30, 'Andi Pratama', '081234567801', 2, '2026-06-11', '13:00:00', '16:00:00', 300000, 'selesai'),
(31, 'Citra Lestari', '081234567803', 3, '2026-06-12', '08:00:00', '10:00:00', 300000, 'selesai'),
(32, 'Andi Pratama', '081234567801', 4, '2026-06-12', '13:00:00', '16:00:00', 120000, 'kedaluwarsa'),
(33, 'Dewi Anggraini', '081234567804', 1, '2026-06-13', '08:00:00', '10:00:00', 120000, 'selesai'),
(34, 'Farhan Rizky', '081234567805', 2, '2026-06-14', '09:00:00', '11:00:00', 200000, 'selesai'),
(35, 'Andi Pratama', '081234567801', 3, '2026-06-15', '13:00:00', '15:00:00', 300000, 'selesai'),
(36, 'Gita Permata', '081234567806', 4, '2026-06-16', '08:00:00', '11:00:00', 120000, 'selesai'),
(37, 'Andi Pratama', '081234567801', 1, '2026-06-18', '08:00:00', '10:00:00', 120000, 'kedaluwarsa'),
(38, 'Hendra Wijaya', '081234567807', 2, '2026-06-19', '13:00:00', '15:00:00', 200000, 'dibayar'),
(39, 'Andi Pratama', '081234567801', 3, '2026-06-20', '16:00:00', '18:00:00', 300000, 'menunggu'),
(40, 'Indah Sari', '081234567808', 4, '2026-06-21', '09:00:00', '12:00:00', 120000, 'dibayar');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `studio`
--
ALTER TABLE `studio`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_booking_studio` (`studio_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `studio`
--
ALTER TABLE `studio`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `fk_booking_studio` FOREIGN KEY (`studio_id`) REFERENCES `studio` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
