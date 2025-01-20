-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Waktu pembuatan: 20 Jan 2025 pada 05.10
-- Versi server: 10.4.20-MariaDB
-- Versi PHP: 7.4.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+07:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_cashier`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `activity_log`
--

CREATE TABLE `activity_log` (
  `id` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `username` varchar(255) DEFAULT NULL,
  `action` varchar(255) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `product_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `activity_log`
--

INSERT INTO `activity_log` (`id`, `timestamp`, `username`, `action`, `product_id`, `product_name`) VALUES
(36, '2025-01-19 21:16:17', 'admin', 'Add User', 29, NULL),
(37, '2025-01-19 21:17:10', 'admin', 'Add User', 30, NULL),
(38, '2025-01-19 21:38:49', 'admin', 'Add User', 31, NULL),
(39, '2025-01-19 21:38:57', 'admin', 'Delete User', 13, 'daffaaptara'),
(40, '2025-01-19 21:39:05', 'admin', 'Delete User', 17, 'komeng'),
(41, '2025-01-19 21:40:46', 'admin', 'Delete User', 18, 'daffaadmin'),
(42, '2025-01-19 21:40:56', 'admin', 'Delete User', 28, 'md5'),
(43, '2025-01-19 21:42:03', 'admin', 'Add Product', 22, 'Cappuchino');

-- --------------------------------------------------------

--
-- Struktur dari tabel `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `nama_produk` varchar(45) DEFAULT NULL,
  `harga_produk` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `jumlah` int(11) DEFAULT 0,
  `kode_unik` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `products`
--

INSERT INTO `products` (`id`, `nama_produk`, `harga_produk`, `created_at`, `updated_at`, `jumlah`, `kode_unik`) VALUES
(22, 'Cappuchino', 25000, '2025-01-20 03:42:01', '2025-01-20 03:43:18', 28, '9264287761');

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaksi`
--

CREATE TABLE `transaksi` (
  `id_transaksi` int(11) NOT NULL,
  `tanggal_transaksi` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `uang_pelanggan` decimal(10,2) DEFAULT NULL,
  `kembalian` decimal(10,2) DEFAULT NULL,
  `total_harga` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `transaksi`
--

INSERT INTO `transaksi` (`id_transaksi`, `tanggal_transaksi`, `uang_pelanggan`, `kembalian`, `total_harga`) VALUES
(46, '2025-01-20 03:42:56', '50000.00', '25000.00', '25000.00'),
(47, '2025-01-20 03:43:16', '50000.00', '25000.00', '25000.00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaksi_produk`
--

CREATE TABLE `transaksi_produk` (
  `id_transaksi` int(11) DEFAULT NULL,
  `nama_produk` varchar(255) DEFAULT NULL,
  `harga_produk` decimal(10,2) DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL,
  `tanggal_transaksi` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `kode_unik` varchar(50) DEFAULT NULL,
  `total_harga` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `transaksi_produk`
--

INSERT INTO `transaksi_produk` (`id_transaksi`, `nama_produk`, `harga_produk`, `jumlah`, `tanggal_transaksi`, `kode_unik`, `total_harga`) VALUES
(46, 'Cappuchino', '25000.00', 1, '2025-01-20 03:42:56', '9264287761', '25000.00'),
(47, 'Cappuchino', '25000.00', 1, '2025-01-20 03:43:16', '9264287761', '25000.00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(45) DEFAULT NULL,
  `password` varchar(45) DEFAULT NULL,
  `nama` varchar(45) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `level` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `nama`, `created_at`, `updated_at`, `level`) VALUES
(29, 'owner', '123123', 'Agung (Owner)', '2025-01-20 03:16:17', '2025-01-20 03:16:17', 'owner'),
(30, 'kasir', '123123', 'Zahra (Kasir)', '2025-01-20 03:17:10', '2025-01-20 03:17:10', 'kasir'),
(31, 'admin', '123123', 'Pidi (Admin)', '2025-01-20 03:38:49', '2025-01-20 03:38:49', 'admin');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `activity_log`
--
ALTER TABLE `activity_log`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id_transaksi`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `activity_log`
--
ALTER TABLE `activity_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT untuk tabel `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id_transaksi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
