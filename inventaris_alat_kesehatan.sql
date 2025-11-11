-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 11, 2025 at 12:24 PM
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
-- Database: `inventaris_alat_kesehatan`
--

-- --------------------------------------------------------

--
-- Table structure for table `alat_kesehatan`
--

CREATE TABLE `alat_kesehatan` (
  `id` int(11) NOT NULL,
  `nama_alat` varchar(255) NOT NULL,
  `merk` varchar(100) NOT NULL,
  `nomor_seri` varchar(100) NOT NULL,
  `lokasi_alat` varchar(100) NOT NULL,
  `tanggal_masuk` date NOT NULL,
  `kondisi` enum('Baik','Rusak','Perbaikan') NOT NULL,
  `catatan` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `alat_kesehatan`
--

INSERT INTO `alat_kesehatan` (`id`, `nama_alat`, `merk`, `nomor_seri`, `lokasi_alat`, `tanggal_masuk`, `kondisi`, `catatan`, `created_at`, `updated_at`) VALUES
(1, 'EKG Machine', 'Philips', 'EKG-PH-001', 'Ruang ICU', '2024-01-15', 'Baik', 'Alat berfungsi normal', '2025-11-04 11:40:00', '2025-11-04 11:40:00'),
(2, 'Infusion Pump', 'B Braun', 'INF-BB-001', 'Ruang Rawat Inap', '2024-02-20', 'Baik', 'Siap digunakan', '2025-11-04 11:40:00', '2025-11-04 11:40:00'),
(3, 'Patient Monitor', 'GE Healthcare', 'PM-GE-001', 'Ruang Operasi', '2024-03-10', 'Perbaikan', 'Sedang dalam perbaikan sensor', '2025-11-04 11:40:00', '2025-11-04 11:40:00'),
(4, 'syringe pump', 'terumo', 'Te001', 'gB', '2025-11-04', 'Baik', '', '2025-11-04 11:41:07', '2025-11-04 11:41:07'),
(6, 'Suction Pump', 'Onemed', '1190O', 'gB', '2020-11-11', 'Perbaikan', 'Butuh Kalibrasi', '2025-11-11 11:19:05', '2025-11-11 11:19:32'),
(7, 'Suction Pump', 'Onemed', '1299O', 'gE', '2020-11-12', 'Rusak', 'Mati Total', '2025-11-11 11:20:58', '2025-11-11 11:21:29');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `alat_kesehatan`
--
ALTER TABLE `alat_kesehatan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nomor_seri` (`nomor_seri`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `alat_kesehatan`
--
ALTER TABLE `alat_kesehatan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
