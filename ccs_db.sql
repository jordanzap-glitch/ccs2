-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 17, 2024 at 04:43 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ccs_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_capstone`
--

CREATE TABLE `tbl_capstone` (
  `id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `abstract` longtext NOT NULL,
  `a1_sname` varchar(150) NOT NULL,
  `a1_fname` varchar(150) NOT NULL,
  `a1_mname` varchar(150) NOT NULL,
  `a1_role` varchar(150) NOT NULL,
  `adviser` varchar(150) NOT NULL,
  `submit_date` date NOT NULL,
  `poster_path` varchar(300) NOT NULL,
  `imrad_path` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_capstone`
--

INSERT INTO `tbl_capstone` (`id`, `title`, `abstract`, `a1_sname`, `a1_fname`, `a1_mname`, `a1_role`, `adviser`, `submit_date`, `poster_path`, `imrad_path`) VALUES
(20, 'aa', 'aa', 'aa', 'a', 'a', 'aa', 'a', '2024-12-18', 'poster/alondra.jpg', 'imrad/2020_09_08 4_49 pm Office Lens.pdf'),
(21, 'bb', 'bb', 'bb', 'bb', 'bb', 'bb', 'bb', '2024-12-28', 'poster/appointment.jpg', 'imrad/2021_09_16 12_42 pm Office Lens.pdf'),
(22, 'cc', 'cc', 'cc', 'cc', 'cc', 'cc', 'cc', '2025-01-01', 'poster/market.jpg', 'imrad/2021_09_16 12_42 pm Office Lens.pdf'),
(23, 'vv', 'vv', 'vv', 'vv', 'vv', 'vv', 'vv', '2024-12-12', 'poster/financial.png', 'imrad/2024_01_15 3_36 PM Office Lens.pdf'),
(24, 'ff', 'ff', 'ff', 'ff', 'ff', 'ff', 'ff', '2025-01-01', 'poster/toda.jpg', 'imrad/2024_01_15 3_36 PM Office Lens.pdf');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_capstone`
--
ALTER TABLE `tbl_capstone`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_capstone`
--
ALTER TABLE `tbl_capstone`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
