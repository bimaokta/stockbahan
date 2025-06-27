-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 27 Jun 2025 pada 01.22
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `stokbahan`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `hasil_peramalan`
--

CREATE TABLE `hasil_peramalan` (
  `id` int(11) NOT NULL,
  `idbarang` int(11) NOT NULL,
  `bulan_tahun` varchar(10) NOT NULL,
  `hasil` decimal(10,1) DEFAULT NULL,
  `tanggal_input` timestamp NOT NULL DEFAULT current_timestamp(),
  `satuan` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `hasil_peramalan`
--

INSERT INTO `hasil_peramalan` (`id`, `idbarang`, `bulan_tahun`, `hasil`, `tanggal_input`, `satuan`) VALUES
(89, 35, '04-2025', 15.2, '2025-04-08 14:36:45', 0),
(90, 36, '04-2025', 1.8, '2025-04-08 14:37:02', 0),
(91, 37, '04-2025', 3.1, '2025-04-08 14:37:06', 0),
(92, 35, '05-2025', 14.9, '2025-05-08 14:47:15', 0),
(93, 36, '05-2025', 2.5, '2025-05-08 14:47:22', 0),
(94, 37, '05-2025', 2.7, '2025-05-08 14:47:27', 0),
(101, 35, '07-2025', 14.3, '2025-06-11 04:42:59', 0),
(102, 36, '07-2025', 2.3, '2025-06-11 04:44:30', 0),
(103, 37, '07-2025', 2.7, '2025-06-11 04:45:39', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `keluar`
--

CREATE TABLE `keluar` (
  `idkeluar` int(11) NOT NULL,
  `idbarang` int(11) NOT NULL,
  `tanggal` date NOT NULL DEFAULT current_timestamp(),
  `satuan` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `qty` int(11) NOT NULL,
  `pegawai` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `keluar`
--

INSERT INTO `keluar` (`idkeluar`, `idbarang`, `tanggal`, `satuan`, `qty`, `pegawai`) VALUES
(145, 35, '2025-01-30', 'kg', 17, ''),
(146, 36, '2025-01-30', 'kg', 1, ''),
(147, 37, '2025-01-30', 'kg', 2, ''),
(148, 35, '2025-02-28', 'kg', 16, ''),
(149, 36, '2025-02-28', 'kg', 2, ''),
(150, 37, '2025-02-28', 'kg', 4, ''),
(151, 35, '2025-03-29', 'kg', 14, ''),
(152, 36, '2025-03-29', 'kg', 2, ''),
(153, 37, '2025-03-30', 'kg', 3, ''),
(154, 35, '2025-04-30', 'kg', 15, ''),
(155, 36, '2025-04-30', 'kg', 3, ''),
(156, 37, '2025-04-30', 'kg', 2, ''),
(157, 35, '2025-05-31', 'kg', 14, ''),
(158, 36, '2025-05-31', 'kg', 2, ''),
(159, 37, '2025-05-31', 'kg', 3, '');

-- --------------------------------------------------------

--
-- Struktur dari tabel `login`
--

CREATE TABLE `login` (
  `iduser` int(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `login`
--

INSERT INTO `login` (`iduser`, `email`, `password`) VALUES
(1, 'bima@gmail.com', '12345678'),
(2, 'owner@gmail.com', '12345678');

-- --------------------------------------------------------

--
-- Struktur dari tabel `masuk`
--

CREATE TABLE `masuk` (
  `idmasuk` int(11) NOT NULL,
  `idbarang` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `satuan` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `qty` int(11) NOT NULL,
  `pegawai` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `masuk`
--

INSERT INTO `masuk` (`idmasuk`, `idbarang`, `tanggal`, `satuan`, `qty`, `pegawai`) VALUES
(131, 35, '2025-01-02', 'kg', 22, ''),
(132, 36, '2025-01-02', 'kg', 3, ''),
(133, 37, '2025-01-02', 'kg', 5, ''),
(134, 35, '2025-02-01', 'kg', 20, ''),
(135, 36, '2025-02-01', 'kg', 4, ''),
(136, 37, '2025-02-01', 'kg', 5, ''),
(137, 35, '2025-03-01', 'kg', 17, ''),
(138, 36, '2025-03-01', 'kg', 3, ''),
(139, 37, '2025-03-01', 'kg', 5, ''),
(141, 35, '2025-04-01', 'kg', 17, ''),
(142, 36, '2025-04-02', 'kg', 4, ''),
(143, 37, '2025-04-02', 'kg', 5, ''),
(144, 35, '2025-05-03', 'kg', 23, ''),
(145, 36, '2025-05-02', 'kg', 3, ''),
(146, 37, '2025-05-02', 'kg', 5, '');

-- --------------------------------------------------------

--
-- Struktur dari tabel `stock`
--

CREATE TABLE `stock` (
  `idbarang` int(11) NOT NULL,
  `kategori` varchar(20) NOT NULL,
  `namabarang` varchar(25) NOT NULL,
  `deskripsi` varchar(25) NOT NULL,
  `stock` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `stock`
--

INSERT INTO `stock` (`idbarang`, `kategori`, `namabarang`, `deskripsi`, `stock`) VALUES
(35, 'Kopi', 'Robusta Wonosalam', 'Kg', 23),
(36, 'Kopi', 'Arabika', 'Kg', 7),
(37, 'Kopi', 'Blend', 'Kg', 11);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `hasil_peramalan`
--
ALTER TABLE `hasil_peramalan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `keluar`
--
ALTER TABLE `keluar`
  ADD PRIMARY KEY (`idkeluar`);

--
-- Indeks untuk tabel `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`iduser`);

--
-- Indeks untuk tabel `masuk`
--
ALTER TABLE `masuk`
  ADD PRIMARY KEY (`idmasuk`);

--
-- Indeks untuk tabel `stock`
--
ALTER TABLE `stock`
  ADD PRIMARY KEY (`idbarang`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `hasil_peramalan`
--
ALTER TABLE `hasil_peramalan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=104;

--
-- AUTO_INCREMENT untuk tabel `keluar`
--
ALTER TABLE `keluar`
  MODIFY `idkeluar` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=160;

--
-- AUTO_INCREMENT untuk tabel `login`
--
ALTER TABLE `login`
  MODIFY `iduser` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `masuk`
--
ALTER TABLE `masuk`
  MODIFY `idmasuk` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=147;

--
-- AUTO_INCREMENT untuk tabel `stock`
--
ALTER TABLE `stock`
  MODIFY `idbarang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
