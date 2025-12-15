-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 15, 2025 at 11:32 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tiket_online`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_log`
--

CREATE TABLE `activity_log` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `aksi` varchar(50) DEFAULT NULL,
  `tabel` varchar(100) DEFAULT NULL,
  `keterangan` text,
  `ip_address` varchar(50) DEFAULT NULL,
  `waktu` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `activity_log`
--

INSERT INTO `activity_log` (`id`, `user_id`, `aksi`, `tabel`, `keterangan`, `ip_address`, `waktu`, `created_at`) VALUES
(1, 9, 'logout', 'auth', 'Petugas agussss logout', '::1', '2025-10-08 11:07:56', '2025-10-08 11:32:07'),
(2, 9, 'Verifikasi Pemesanan', NULL, 'Admin ID 9 menyetujui pemesanan dengan ID 11 (Terima).', NULL, '2025-10-09 10:52:13', '2025-10-09 10:52:13'),
(3, 9, 'Verifikasi Pemesanan', NULL, 'Admin ID 9 menyetujui pemesanan dengan ID 19 (Terima).', NULL, '2025-10-09 11:04:57', '2025-10-09 11:04:57'),
(4, 9, 'Verifikasi Pemesanan', NULL, 'Admin ID 9 menolak pemesanan dengan ID 18 (Tolak).', NULL, '2025-10-09 11:05:29', '2025-10-09 11:05:29'),
(5, 9, 'logout', 'auth', 'Petugas agussss logout', '::1', '2025-10-09 13:13:15', '2025-10-09 13:13:15'),
(6, 9, 'logout', 'auth', 'Petugas agussss logout', '::1', '2025-10-09 13:24:50', '2025-10-09 13:24:50'),
(7, 9, 'logout', 'auth', 'Petugas agussss logout', '::1', '2025-10-09 13:27:08', '2025-10-09 13:27:08'),
(8, 9, 'logout', 'auth', 'Petugas agussss logout', '::1', '2025-10-09 13:28:34', '2025-10-09 13:28:34'),
(9, 9, 'Verifikasi Pemesanan', NULL, 'Admin ID 9 menyetujui pemesanan dengan ID 5 (Terima).', NULL, '2025-10-13 00:04:39', '2025-10-13 00:04:39'),
(10, 9, 'Verifikasi Pemesanan', NULL, 'Admin ID 9 menyetujui pemesanan dengan ID 16 (Terima).', NULL, '2025-10-16 06:29:33', '2025-10-16 06:29:33'),
(11, 9, 'Verifikasi Pemesanan', NULL, 'Admin ID 9 menyetujui pemesanan dengan ID 26 (Terima).', NULL, '2025-10-16 06:29:56', '2025-10-16 06:29:56'),
(12, 10, 'Verifikasi Pemesanan', NULL, 'Admin ID 10 menyetujui pemesanan dengan ID 29 (Terima).', NULL, '2025-10-21 06:20:05', '2025-10-21 06:20:05'),
(13, 10, 'Verifikasi Pemesanan', NULL, 'Admin ID 10 menyetujui pemesanan dengan ID 30 (Terima).', NULL, '2025-10-21 06:24:53', '2025-10-21 06:24:53'),
(14, 10, 'logout', 'auth', 'Petugas budi logout', '::1', '2025-10-21 06:25:14', '2025-10-21 06:25:14'),
(15, 9, 'Verifikasi Pemesanan', NULL, 'Admin ID 9 menolak pemesanan dengan ID 32 (Tolak).', NULL, '2025-10-21 06:26:35', '2025-10-21 06:26:35'),
(16, 5, 'logout', 'auth', 'Petugas agussss logout', '::1', '2025-11-25 02:19:43', '2025-11-25 02:19:43'),
(17, 9, 'logout', 'auth', 'Petugas agussss logout', '::1', '2025-11-25 02:20:29', '2025-11-25 02:20:29');

-- --------------------------------------------------------

--
-- Table structure for table `jadwal`
--

CREATE TABLE `jadwal` (
  `id_jadwal` int NOT NULL,
  `id_rute` int NOT NULL,
  `jam_berangkat` time NOT NULL,
  `jam_tiba` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `jadwal`
--

INSERT INTO `jadwal` (`id_jadwal`, `id_rute`, `jam_berangkat`, `jam_tiba`) VALUES
(12, 9, '12:00:00', '20:30:00'),
(13, 10, '11:59:00', '22:22:00'),
(15, 11, '10:04:00', '13:12:00'),
(16, 12, '14:02:00', '16:17:00');

-- --------------------------------------------------------

--
-- Table structure for table `level`
--

CREATE TABLE `level` (
  `id_level` int NOT NULL,
  `nama_level` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `level`
--

INSERT INTO `level` (`id_level`, `nama_level`) VALUES
(1, 'Admin'),
(2, 'Petugas');

-- --------------------------------------------------------

--
-- Table structure for table `pelanggan`
--

CREATE TABLE `pelanggan` (
  `id_pelanggan` int NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `alamat` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pemesanan`
--

CREATE TABLE `pemesanan` (
  `id_pemesanan` int NOT NULL,
  `kode_pemesanan` varchar(50) DEFAULT NULL,
  `tanggal_pemesanan` date NOT NULL,
  `id_pelanggan` int DEFAULT NULL,
  `kode_kursi` varchar(10) NOT NULL,
  `id_rute` int NOT NULL,
  `id_jadwal` int DEFAULT NULL,
  `tujuan` varchar(100) DEFAULT NULL,
  `tanggal_berangkat` date NOT NULL,
  `total_bayar` decimal(12,2) NOT NULL,
  `id_petugas` int DEFAULT NULL,
  `status_pemesanan` enum('pending','Terima','Tolak') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pemesanan`
--

INSERT INTO `pemesanan` (`id_pemesanan`, `kode_pemesanan`, `tanggal_pemesanan`, `id_pelanggan`, `kode_kursi`, `id_rute`, `id_jadwal`, `tujuan`, `tanggal_berangkat`, `total_bayar`, `id_petugas`, `status_pemesanan`) VALUES
(5, 'PM1759547977', '2025-10-04', 1, 'A99', 11, NULL, 'Surabaya', '2025-10-11', '350000.00', NULL, 'Terima'),
(9, 'TKT202510065581', '2025-10-06', 1, 'N/A', 10, NULL, 'Yogyakarta', '2025-10-11', '250000.00', NULL, 'Terima'),
(11, 'TKT202510066973', '2025-10-06', 1, 'N/A', 10, NULL, 'Yogyakarta', '2025-10-06', '250000.00', NULL, 'Terima'),
(16, 'TKT20251008043800145', '2025-10-08', NULL, 'KRS245', 9, 12, 'Bandung', '2025-10-08', '150000.00', NULL, 'Terima'),
(17, 'TKT20251008050750792', '2025-10-08', NULL, 'KRS575', 9, 12, 'Bandung', '2025-10-08', '150000.00', NULL, 'pending'),
(18, 'TKT20251009104610706', '2025-10-09', NULL, 'KRS535', 12, 16, 'Bali', '2025-10-09', '200000.00', NULL, 'Tolak'),
(19, 'TKT20251009104938754', '2025-10-09', NULL, 'KRS865', 11, 15, 'Surabaya', '2025-10-09', '350000.00', NULL, 'Terima'),
(20, 'TKT20251009114036314', '2025-10-09', NULL, 'KRS966', 10, 13, 'Yogyakarta', '2025-10-09', '250000.00', NULL, 'pending'),
(21, 'TKT20251009115143858', '2025-10-09', NULL, 'KRS464', 11, 15, 'Surabaya', '2025-10-09', '350000.00', NULL, 'pending'),
(22, 'TKT20251009124424588', '2025-10-09', NULL, 'KRS601', 10, 13, 'Yogyakarta', '2025-10-09', '250000.00', NULL, 'pending'),
(23, 'TKT20251009124509205', '2025-10-09', NULL, 'KRS591', 11, 15, 'Surabaya', '2025-10-09', '350000.00', NULL, 'pending'),
(24, 'TKT20251016061449400', '2025-10-16', NULL, 'KRS546', 10, 13, 'Yogyakarta', '2025-10-16', '250000.00', NULL, 'pending'),
(25, 'TKT20251016062057936', '2025-10-16', NULL, 'KRS612', 10, 13, 'Yogyakarta', '2025-10-16', '250000.00', NULL, 'pending'),
(26, 'TKT20251016062640717', '2025-10-16', NULL, 'KRS847', 10, 13, 'Yogyakarta', '2025-10-16', '250000.00', NULL, 'Terima'),
(27, 'TKT20251021020846875', '2025-10-21', NULL, 'KRS321', 10, 13, 'Yogyakarta', '2025-10-21', '250000.00', NULL, 'pending'),
(28, 'TKT20251021061835917', '2025-10-21', NULL, 'KRS252', 10, 13, 'Yogyakarta', '2025-10-21', '250000.00', NULL, 'pending'),
(29, 'TKT20251021061932163', '2025-10-21', NULL, 'KRS853', 9, 12, 'Bandung', '2025-10-21', '150000.00', NULL, 'Terima'),
(30, 'TKT20251021062409207', '2025-10-21', NULL, 'KRS385', 11, 15, 'Surabaya', '2025-10-21', '350000.00', NULL, 'Terima'),
(31, 'TKT20251021062554676', '2025-10-21', NULL, 'KRS723', 9, 12, 'Bandung', '2025-10-21', '150000.00', NULL, 'pending'),
(32, 'TKT20251021062618806', '2025-10-21', NULL, 'KRS831', 9, 12, 'Bandung', '2025-10-21', '150000.00', NULL, 'Tolak'),
(33, 'TKT20251021063050679', '2025-10-21', NULL, 'KRS576', 9, 12, 'Bandung', '2025-10-21', '150000.00', NULL, 'pending'),
(34, 'TKT20251125022102185', '2025-11-25', NULL, 'KRS633', 11, 15, 'Surabaya', '2025-11-25', '350000.00', NULL, 'pending'),
(35, 'TKT20251210034324787', '2025-12-10', NULL, 'KRS967', 10, 13, 'Yogyakarta', '2025-12-10', '250000.00', NULL, 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `penumpang`
--

CREATE TABLE `penumpang` (
  `id_penumpang` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_penumpang` varchar(100) NOT NULL,
  `alamat_penumpang` text,
  `tanggal_lahir` date DEFAULT NULL,
  `jenis_kelamin` enum('L','P') DEFAULT NULL,
  `telefone` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `penumpang`
--

INSERT INTO `penumpang` (`id_penumpang`, `username`, `password`, `nama_penumpang`, `alamat_penumpang`, `tanggal_lahir`, `jenis_kelamin`, `telefone`) VALUES
(1, 'agus', '$2y$10$2oj2coHy7yiCWdULdxuuM.sqaGhO9xcnoW2PPi1HTMQb7dJB7U41m', 'agus', 'banjar', '2025-10-18', 'L', '085803304706'),
(2, 'alysa', '$2y$10$K1g.83jo2mugOSp50R98jeJr7WBoZpPV.ND/x0reRdnodsmiv0QYy', 'alysa al zahra', 'banjar', '2025-10-29', 'P', '085803304706'),
(3, 'budi', '$2y$10$dkqDbtrg59GUv/xB1.LVQeBnUEQsyoVD2hCRjqEuQH2PFgMbinLtS', 'budiiii', 'banjar', '2025-10-08', 'L', '085803304706'),
(4, 'aguss', '$2y$10$FC6X5CjuALRbLd6DWP/mMemmoOC8D1QgqAh.D2jpB9Kkfyvm8YNkG', 'alysa al zahra', 'banjar', '2025-12-04', 'L', '085803304706');

-- --------------------------------------------------------

--
-- Table structure for table `petugas`
--

CREATE TABLE `petugas` (
  `id_petugas` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_petugas` varchar(100) NOT NULL,
  `id_level` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `petugas`
--

INSERT INTO `petugas` (`id_petugas`, `username`, `password`, `nama_petugas`, `id_level`) VALUES
(9, 'agus', '$2y$10$NhB4znq3ZwaeCF2O5cIgCuezVZ/XwWBjrONdNGU8HDsqM98UcSWMa', 'agussss', 1),
(10, 'budi', '$2y$10$YPGTwP/TcEVpT2XcQqfqUOME/IWevYlCbzvXVMwvqjyTmGeN5QoAa', 'budi', 2),
(11, 'zahra', '$2y$10$z3IOJpVi7c8.dF7xzkgIme.yIWYkIXaTmyrmD4yTwT/6v/nddrHOq', 'al', 2);

-- --------------------------------------------------------

--
-- Table structure for table `rute`
--

CREATE TABLE `rute` (
  `id_rute` int NOT NULL,
  `tujuan` varchar(100) DEFAULT NULL,
  `rute_awal` varchar(100) NOT NULL,
  `rute_ahir` varchar(100) NOT NULL,
  `harga` decimal(12,2) NOT NULL,
  `id_transportasi` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `rute`
--

INSERT INTO `rute` (`id_rute`, `tujuan`, `rute_awal`, `rute_ahir`, `harga`, `id_transportasi`) VALUES
(9, 'Bandung', 'Jakarta', 'Bandung', '150000.00', 1),
(10, 'Yogyakarta', 'Bandung', 'Yogyakarta', '250000.00', 2),
(11, 'Surabaya', 'Jakarta', 'Surabaya', '350000.00', 1),
(12, 'Bali', 'Surabaya', 'Bali', '200000.00', 3);

-- --------------------------------------------------------

--
-- Table structure for table `transportasi`
--

CREATE TABLE `transportasi` (
  `id_transportasi` int NOT NULL,
  `kode` varchar(20) NOT NULL,
  `jumlah_kursi` int NOT NULL,
  `keterangan` text,
  `id_type_transportasi` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `transportasi`
--

INSERT INTO `transportasi` (`id_transportasi`, `kode`, `jumlah_kursi`, `keterangan`, `id_type_transportasi`) VALUES
(1, 'KA001', 100, 'Kereta Ekonomi', 1),
(2, 'KA002', 80, 'Kereta Bisnis', 1),
(3, 'BUS001', 40, 'Bus Pariwisata', 2),
(4, 'Pesawat-0002', 50, 'Air Lion\r\n\r\n', 3),
(8, '000', 44, 'asd\r\n', 3);

-- --------------------------------------------------------

--
-- Table structure for table `type_transportasi`
--

CREATE TABLE `type_transportasi` (
  `id_type_transportasi` int NOT NULL,
  `nama_type` varchar(50) NOT NULL,
  `keterangan` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `type_transportasi`
--

INSERT INTO `type_transportasi` (`id_type_transportasi`, `nama_type`, `keterangan`) VALUES
(1, 'Kereta Api', 'Moda transportasi kereta api'),
(2, 'Bus', 'Moda transportasi bus'),
(3, 'Pesawat', 'Moda transportasi pesawat udara');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_log`
--
ALTER TABLE `activity_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jadwal`
--
ALTER TABLE `jadwal`
  ADD PRIMARY KEY (`id_jadwal`),
  ADD KEY `id_rute` (`id_rute`);

--
-- Indexes for table `level`
--
ALTER TABLE `level`
  ADD PRIMARY KEY (`id_level`);

--
-- Indexes for table `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`id_pelanggan`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `pemesanan`
--
ALTER TABLE `pemesanan`
  ADD PRIMARY KEY (`id_pemesanan`),
  ADD UNIQUE KEY `kode_pemesanan` (`kode_pemesanan`),
  ADD KEY `id_pelanggan` (`id_pelanggan`),
  ADD KEY `id_rute` (`id_rute`),
  ADD KEY `id_petugas` (`id_petugas`);

--
-- Indexes for table `penumpang`
--
ALTER TABLE `penumpang`
  ADD PRIMARY KEY (`id_penumpang`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `petugas`
--
ALTER TABLE `petugas`
  ADD PRIMARY KEY (`id_petugas`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `fk_petugas_level` (`id_level`);

--
-- Indexes for table `rute`
--
ALTER TABLE `rute`
  ADD PRIMARY KEY (`id_rute`),
  ADD KEY `id_transportasi` (`id_transportasi`);

--
-- Indexes for table `transportasi`
--
ALTER TABLE `transportasi`
  ADD PRIMARY KEY (`id_transportasi`),
  ADD UNIQUE KEY `kode` (`kode`),
  ADD KEY `id_type_transportasi` (`id_type_transportasi`);

--
-- Indexes for table `type_transportasi`
--
ALTER TABLE `type_transportasi`
  ADD PRIMARY KEY (`id_type_transportasi`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_log`
--
ALTER TABLE `activity_log`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `jadwal`
--
ALTER TABLE `jadwal`
  MODIFY `id_jadwal` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `level`
--
ALTER TABLE `level`
  MODIFY `id_level` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `pelanggan`
--
ALTER TABLE `pelanggan`
  MODIFY `id_pelanggan` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pemesanan`
--
ALTER TABLE `pemesanan`
  MODIFY `id_pemesanan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `penumpang`
--
ALTER TABLE `penumpang`
  MODIFY `id_penumpang` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `petugas`
--
ALTER TABLE `petugas`
  MODIFY `id_petugas` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `rute`
--
ALTER TABLE `rute`
  MODIFY `id_rute` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `transportasi`
--
ALTER TABLE `transportasi`
  MODIFY `id_transportasi` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `type_transportasi`
--
ALTER TABLE `type_transportasi`
  MODIFY `id_type_transportasi` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `jadwal`
--
ALTER TABLE `jadwal`
  ADD CONSTRAINT `jadwal_ibfk_1` FOREIGN KEY (`id_rute`) REFERENCES `rute` (`id_rute`) ON DELETE CASCADE;

--
-- Constraints for table `pemesanan`
--
ALTER TABLE `pemesanan`
  ADD CONSTRAINT `pemesanan_ibfk_1` FOREIGN KEY (`id_pelanggan`) REFERENCES `penumpang` (`id_penumpang`) ON DELETE CASCADE,
  ADD CONSTRAINT `pemesanan_ibfk_2` FOREIGN KEY (`id_rute`) REFERENCES `rute` (`id_rute`) ON DELETE CASCADE,
  ADD CONSTRAINT `pemesanan_ibfk_3` FOREIGN KEY (`id_petugas`) REFERENCES `petugas` (`id_petugas`) ON DELETE CASCADE;

--
-- Constraints for table `petugas`
--
ALTER TABLE `petugas`
  ADD CONSTRAINT `fk_petugas_level` FOREIGN KEY (`id_level`) REFERENCES `level` (`id_level`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `rute`
--
ALTER TABLE `rute`
  ADD CONSTRAINT `rute_ibfk_1` FOREIGN KEY (`id_transportasi`) REFERENCES `transportasi` (`id_transportasi`) ON DELETE CASCADE;

--
-- Constraints for table `transportasi`
--
ALTER TABLE `transportasi`
  ADD CONSTRAINT `transportasi_ibfk_1` FOREIGN KEY (`id_type_transportasi`) REFERENCES `type_transportasi` (`id_type_transportasi`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
