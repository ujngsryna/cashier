-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 11, 2024 at 08:24 PM
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
-- Database: `cashier_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_log`
--

CREATE TABLE `activity_log` (
  `id` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `username` varchar(255) DEFAULT NULL,
  `action` varchar(255) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `product_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activity_log`
--

INSERT INTO `activity_log` (`id`, `timestamp`, `username`, `action`, `product_id`, `product_name`) VALUES
(25, '2024-03-03 05:59:29', 'owner', 'Delete Product', 4, 'Latte'),
(26, '2024-03-03 06:17:13', 'damin', 'Add Product', 21, 'Shakeratto Arabica'),
(27, '2024-03-10 12:21:25', 'owner', 'Add User', 22, NULL),
(28, '2024-03-10 12:28:29', 'owner', 'Add User', 23, NULL),
(29, '2024-03-10 12:30:11', 'owner', 'Add User', 24, NULL),
(30, '2024-03-10 12:32:37', 'owner', 'Add User', 25, NULL),
(31, '2024-03-10 12:32:42', 'owner', 'Delete User', 25, 'Magang'),
(32, '2024-03-31 16:34:13', 'owner', 'Add User', 26, NULL),
(33, '2024-03-31 16:57:25', 'owner', 'Delete User', 27, ''),
(34, '2024-03-31 17:25:18', 'owner', 'Delete User', 26, 'md5'),
(35, '2024-03-31 17:25:36', 'owner', 'Add User', 28, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `nama_produk` varchar(45) DEFAULT NULL,
  `harga_produk` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `jumlah` int(11) DEFAULT 0,
  `kode_unik` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `nama_produk`, `harga_produk`, `created_at`, `updated_at`, `jumlah`, `kode_unik`) VALUES
(1, 'Kopi Arabica', 25000, '2024-02-23 15:11:25', '2024-03-13 08:26:37', 18, 'abcde'),
(2, 'Cappuccino', 30000, '2024-02-23 15:11:25', '2024-04-11 18:19:23', 24, 'fghij'),
(3, 'Espresso', 15000, '2024-02-23 15:11:25', '2024-03-13 10:49:34', 5, 'klmno'),
(17, 'Shakeratto Robusta', 28000, '2024-02-26 09:44:35', '2024-03-09 15:51:04', 29, '21d9bf4439'),
(21, 'Shakeratto Arabica', 28000, '2024-03-03 12:17:13', '2024-03-05 16:14:30', 39, 'c71cad2f97');

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `id_transaksi` int(11) NOT NULL,
  `tanggal_transaksi` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `uang_pelanggan` decimal(10,2) DEFAULT NULL,
  `kembalian` decimal(10,2) DEFAULT NULL,
  `total_harga` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaksi`
--

INSERT INTO `transaksi` (`id_transaksi`, `tanggal_transaksi`, `uang_pelanggan`, `kembalian`, `total_harga`) VALUES
(1, '2024-03-10 16:19:11', 50000.00, 25000.00, 25000.00),
(2, '2024-03-10 16:19:11', 50000.00, 0.00, 50000.00),
(3, '2024-03-10 16:19:11', 50000.00, 20000.00, 30000.00),
(4, '2024-03-10 16:19:11', 100000.00, 15000.00, 85000.00),
(5, '2024-03-10 16:19:11', 50000.00, 0.00, 50000.00),
(6, '2024-03-10 16:19:11', 100000.00, 50000.00, 50000.00),
(7, '2024-03-10 16:19:11', 100000.00, 50000.00, 50000.00),
(8, '2024-03-10 16:19:11', 100000.00, 45000.00, 55000.00),
(9, '2024-03-10 16:19:11', 200000.00, 75000.00, 125000.00),
(10, '2024-03-10 16:19:11', 200000.00, 95000.00, 105000.00),
(11, '2024-03-10 16:19:11', 100000.00, 0.00, 166000.00),
(12, '2024-03-10 16:19:11', 50000.00, 20000.00, 30000.00),
(13, '2024-03-10 16:19:45', 50000.00, 20000.00, 30000.00),
(14, '2024-03-10 18:06:55', 30000.00, 0.00, 30000.00),
(15, '2024-03-10 18:13:53', 500000.00, 45000.00, 455000.00),
(16, '2024-03-13 08:26:34', 100000.00, 45000.00, 55000.00),
(17, '2024-03-13 08:32:00', 50000.00, 5000.00, 45000.00),
(18, '2024-03-13 08:39:18', 200000.00, 20000.00, 180000.00),
(19, '2024-03-13 08:47:19', 200000.00, 5000.00, 195000.00),
(20, '2024-03-13 08:52:12', 200000.00, 140000.00, 60000.00),
(21, '2024-03-13 08:59:31', 500000.00, 350000.00, 150000.00),
(22, '2024-03-13 09:05:07', 100000.00, 10000.00, 90000.00),
(23, '2024-03-13 09:08:24', 500000.00, 395000.00, 105000.00),
(24, '2024-03-13 09:26:27', 200000.00, 20000.00, 180000.00),
(25, '2024-03-13 09:34:04', 100000.00, 10000.00, 90000.00),
(26, '2024-03-13 09:35:34', 100000.00, 25000.00, 75000.00),
(27, '2024-03-13 09:38:53', 100000.00, 25000.00, 75000.00),
(28, '2024-03-13 09:42:47', 100000.00, 10000.00, 90000.00),
(29, '2024-03-13 09:49:39', 100000.00, 10000.00, 90000.00),
(30, '2024-03-13 09:56:57', 100000.00, 10000.00, 90000.00),
(31, '2024-03-13 10:10:27', 500000.00, 410000.00, 90000.00),
(32, '2024-03-13 10:10:53', 100000.00, 10000.00, 90000.00),
(33, '2024-03-13 10:24:52', 50000.00, 20000.00, 30000.00),
(34, '2024-03-13 10:25:07', 200000.00, 140000.00, 60000.00),
(35, '2024-03-13 10:35:15', NULL, NULL, 15000.00),
(36, '2024-03-13 10:45:53', NULL, NULL, 30000.00),
(37, '2024-03-13 10:46:10', NULL, NULL, 15000.00),
(38, '2024-03-13 10:46:41', NULL, NULL, 15000.00),
(39, '2024-03-13 10:47:05', NULL, NULL, 30000.00),
(40, '2024-03-13 10:47:18', NULL, NULL, 30000.00),
(41, '2024-03-13 10:49:05', 30000.00, 15000.00, 15000.00),
(42, '2024-03-13 10:49:19', 100000.00, 25000.00, 75000.00),
(43, '2024-03-13 10:54:56', 500000.00, 410000.00, 90000.00),
(44, '2024-04-11 17:53:27', 10000.00, 10000.00, 0.00),
(45, '2024-04-11 18:19:23', 100000.00, 45000.00, 55000.00);

-- --------------------------------------------------------

--
-- Table structure for table `transaksi_produk`
--

CREATE TABLE `transaksi_produk` (
  `id_transaksi` int(11) DEFAULT NULL,
  `nama_produk` varchar(255) DEFAULT NULL,
  `harga_produk` decimal(10,2) DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL,
  `tanggal_transaksi` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `kode_unik` varchar(50) DEFAULT NULL,
  `total_harga` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaksi_produk`
--

INSERT INTO `transaksi_produk` (`id_transaksi`, `nama_produk`, `harga_produk`, `jumlah`, `tanggal_transaksi`, `kode_unik`, `total_harga`) VALUES
(1, 'Kopi Arabica', 25000.00, 1, '2024-03-10 16:24:17', 'abcde', 25000.00),
(2, 'Kopi Arabica', 25000.00, 2, '2024-03-10 16:24:17', 'abcde', 50000.00),
(3, 'Cappuccino', 30000.00, 1, '2024-03-10 16:24:17', 'fghij', 30000.00),
(4, 'Kopi Arabica', 25000.00, 1, '2024-03-10 16:24:17', 'abcde', 25000.00),
(4, 'Cappuccino', 30000.00, 2, '2024-03-10 16:24:17', 'fghij', 60000.00),
(5, 'Kopi Arabica', 25000.00, 2, '2024-03-10 16:24:17', 'abcde', 50000.00),
(6, 'Kopi Arabica', 25000.00, 2, '2024-03-10 16:24:17', 'abcde', 50000.00),
(7, 'Kopi Arabica', 25000.00, 2, '2024-03-10 16:24:17', 'abcde', 50000.00),
(8, 'Cappuccino', 30000.00, 1, '2024-03-10 16:24:17', 'fghij', 30000.00),
(8, 'Espresso', 25000.00, 1, '2024-03-10 16:24:17', 'klmno', 25000.00),
(9, 'Kopi Arabica', 25000.00, 5, '2024-03-10 16:24:17', 'abcde', 125000.00),
(10, 'Kopi Arabica', 25000.00, 3, '2024-03-10 16:24:17', 'abcde', 75000.00),
(10, 'Cappuccino', 30000.00, 1, '2024-03-10 16:24:17', 'fghij', 30000.00),
(11, 'Kopi Arabica', 25000.00, 1, '2024-03-10 16:24:17', 'abcde', 25000.00),
(11, 'Cappuccino', 30000.00, 2, '2024-03-10 16:24:17', 'fghij', 60000.00),
(11, 'Espresso', 25000.00, 1, '2024-03-10 16:24:17', 'klmno', 25000.00),
(11, 'Shakeratto Robusta', 28000.00, 1, '2024-03-10 16:24:17', '21d9bf4439', 28000.00),
(11, 'Shakeratto Arabica', 28000.00, 1, '2024-03-10 16:24:17', 'c71cad2f97', 28000.00),
(12, 'Cappuccino', 30000.00, 1, '2024-03-10 16:24:17', 'fghij', 30000.00),
(13, 'Espresso', 15000.00, 2, '2024-03-10 16:24:17', 'klmno', 30000.00),
(14, 'Cappuccino', 30000.00, 1, '2024-03-10 18:06:56', 'fghij', 30000.00),
(15, 'Cappuccino', 30000.00, 6, '2024-03-10 18:13:53', 'fghij', 180000.00),
(15, 'Kopi Arabica', 25000.00, 11, '2024-03-10 18:13:53', 'abcde', 275000.00),
(16, 'Cappuccino', 30000.00, 1, '2024-03-13 08:26:36', 'fghij', 30000.00),
(16, 'Kopi Arabica', 25000.00, 1, '2024-03-13 08:26:37', 'abcde', 25000.00),
(17, 'Espresso', 15000.00, 1, '2024-03-13 08:32:01', 'klmno', 15000.00),
(17, 'Cappuccino', 30000.00, 1, '2024-03-13 08:32:01', 'fghij', 30000.00),
(18, 'Espresso', 15000.00, 12, '2024-03-13 08:39:18', 'klmno', 180000.00),
(19, 'Espresso', 15000.00, 13, '2024-03-13 08:47:19', 'klmno', 195000.00),
(20, 'Espresso', 15000.00, 4, '2024-03-13 08:52:12', 'klmno', 60000.00),
(21, 'Espresso', 15000.00, 10, '2024-03-13 08:59:32', 'klmno', 150000.00),
(22, 'Espresso', 15000.00, 6, '2024-03-13 09:05:07', 'klmno', 90000.00),
(23, 'Espresso', 15000.00, 7, '2024-03-13 09:08:24', 'klmno', 105000.00),
(24, 'Espresso', 15000.00, 12, '2024-03-13 09:26:27', 'klmno', 180000.00),
(25, 'Espresso', 15000.00, 6, '2024-03-13 09:34:04', 'klmno', 90000.00),
(26, 'Espresso', 15000.00, 5, '2024-03-13 09:35:34', 'klmno', 75000.00),
(27, 'Espresso', 15000.00, 5, '2024-03-13 09:38:54', 'klmno', 75000.00),
(28, 'Espresso', 15000.00, 6, '2024-03-13 09:42:47', 'klmno', 90000.00),
(29, 'Espresso', 15000.00, 6, '2024-03-13 09:49:40', 'klmno', 90000.00),
(30, 'Espresso', 15000.00, 6, '2024-03-13 09:56:57', 'klmno', 90000.00),
(31, 'Espresso', 15000.00, 6, '2024-03-13 10:10:27', 'klmno', 90000.00),
(32, 'Espresso', 15000.00, 6, '2024-03-13 10:10:53', 'klmno', 90000.00),
(33, 'Espresso', 15000.00, 2, '2024-03-13 10:24:52', 'klmno', 30000.00),
(34, 'Espresso', 15000.00, 4, '2024-03-13 10:25:08', 'klmno', 60000.00),
(41, 'Espresso', 15000.00, 1, '2024-03-13 10:49:05', 'klmno', 15000.00),
(42, 'Espresso', 15000.00, 5, '2024-03-13 10:49:20', 'klmno', 75000.00),
(43, 'Espresso', 15000.00, 4, '2024-03-13 10:54:57', 'klmno', 60000.00),
(43, 'Cappuccino', 30000.00, 1, '2024-03-13 10:54:57', 'fghij', 30000.00),
(45, 'Kopi Arabica', 25000.00, 1, '2024-04-11 18:19:23', 'abcde', 25000.00),
(45, 'Cappuccino', 30000.00, 1, '2024-04-11 18:19:23', 'fghij', 30000.00);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(45) DEFAULT NULL,
  `password` varchar(45) DEFAULT NULL,
  `nama` varchar(45) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `level` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `nama`, `created_at`, `updated_at`, `level`) VALUES
(13, 'owner', '123123', 'daffaaptara', '2024-02-22 02:16:26', '2024-02-26 09:40:28', 'owner'),
(17, 'kasir', '123123', 'komeng', '2024-02-24 18:33:38', '2024-02-26 02:42:34', 'kasir'),
(18, 'admin', '123123', 'daffaadmin', '2024-02-26 02:32:09', '2024-03-03 12:21:56', 'admin'),
(28, 'md5', '$2y$10$UkjQlQsr6I0s.M15o.u7G.OoqoBbK/L6l06ZyH', 'md5', '2024-03-31 22:25:36', '2024-03-31 22:25:36', 'kasir');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_log`
--
ALTER TABLE `activity_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id_transaksi`);

--
-- Indexes for table `transaksi_produk`
--
ALTER TABLE `transaksi_produk`
  ADD KEY `id_transaksi` (`id_transaksi`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_log`
--
ALTER TABLE `activity_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id_transaksi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `transaksi_produk`
--
ALTER TABLE `transaksi_produk`
  ADD CONSTRAINT `transaksi_produk_ibfk_1` FOREIGN KEY (`id_transaksi`) REFERENCES `transaksi` (`id_transaksi`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
