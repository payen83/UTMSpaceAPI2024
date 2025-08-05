-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Aug 05, 2025 at 10:30 AM
-- Server version: 5.7.39
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ispaceDB`
--

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE `news` (
  `id` int(11) NOT NULL,
  `image_url` text,
  `image_path` text,
  `title` text,
  `description` longtext,
  `location` text,
  `date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`id`, `image_url`, `image_path`, `title`, `description`, `location`, `date`) VALUES
(1, 'https://utmspace.edu.my/wp-content/uploads/2022/10/sembang-space.jpg', NULL, 'Explore endless education opportunities at our SPACE UTM Edu Fair!', 'UTMSPACE salah sebuah entiti di bawah naungan Universiti Teknologi Malaysia akan membuka pintu kepada umum bagi penganjuran SPACE UTM EDU FAIR 2024 pada bulan Jun.', 'johor bahru', '2024-05-27'),
(10, 'https://utmspace.edu.my/wp-content/uploads/2022/10/stareducationfair.jpg', NULL, 'Star Education Fair 2022', 'Choose the right institution to secure your future at the most awaited education fair, Join us this 12 & 13 November at Tropicana Gardens Mall! Meet us at booth A33! Visit the link to register! ', 'Tropicana Gardens Mall', '2024-05-06'),
(11, 'https://utmspace.edu.my/wp-content/uploads/2022/10/28oct1.jpg', NULL, 'Program Professional Certificate in Advances of Long Range Surveillance Radar', 'Program Professional Certificate in Advances of Long Range Surveillance Radar & Wireless Data Link telah pun berjalan pada 17 Oktober dan akan berlangsung sehingga 28 Oktober 2022 bertempat di UTM/UTMSPACE Johor Bahru dan TUDM Bukit Lunchu', 'TUDM Bukit Lunchu', '2024-05-22');

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `id` int(11) NOT NULL,
  `token` longtext,
  `image_url` longtext,
  `email` text NOT NULL,
  `password` longtext NOT NULL,
  `first_name` text,
  `last_name` text,
  `ic` text,
  `address` longtext,
  `position` text,
  `department` text,
  `phone_no` text,
  `birth_date` date DEFAULT NULL,
  `self_report_date` date DEFAULT NULL,
  `self_report_location` longtext,
  `self_report_letter` text,
  `self_report_document` text,
  `tentative_program` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`id`, `token`, `image_url`, `email`, `password`, `first_name`, `last_name`, `ic`, `address`, `position`, `department`, `phone_no`, `birth_date`, `self_report_date`, `self_report_location`, `self_report_letter`, `self_report_document`, `tentative_program`) VALUES
(1, '6e7f67b593261e2bbb327dc6670dcaef39a870774aada934a344584e0d70d049', NULL, 'test1@email.com', '$2y$10$Fxo9gjUVyG1mPwfpKctCKOMOqhHX1szepP8ZUDzVmYrm3K1P1elg6', 'test1', 'Tested1', NULL, NULL, 'tester1', 'Testing1', '1111111111', '1991-01-01', NULL, NULL, NULL, NULL, NULL),
(4, NULL, NULL, 'test2@email.com', '$2y$10$xV6IDx1MqrAvZhswXVZ6J.aaIRdw458TLwSaKBvdISqq9B/Pan13W', 'test2', 'Tested2', NULL, NULL, 'tester2', 'Testing2', '2222222222', '1991-02-02', NULL, NULL, NULL, NULL, NULL),
(5, NULL, NULL, 'test4@email.com', '$2y$10$bvAfCfIhjzg7tLDAPGABmO82SECRXROaKgO4m6aocMu3qU.uiz6di', 'staff5', 'Tested3', NULL, NULL, 'tester3', 'Testing3', '3333333333', '1991-03-03', NULL, NULL, NULL, NULL, NULL),
(6, NULL, NULL, 'test5@email.com', '$2y$10$TY5uQkmCfJiMzwctKSUmku04TJw3pEgUmanv4gxKUG6AAn4DkmIfi', 'test5', 'Tested5', '900102-06-5456', 'No 999 Jalan Makmur', 'tester5', 'Testing5', '3333333333', '1991-03-03', '2024-01-01', 'Taman Bunga', '/files/2024926145434-i5fh7M.pdf', '/files/2024926145434-i5fh7M.pdf', '/files/2024926145434-i5fh7M.pdf');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`(100)),
  ADD UNIQUE KEY `token` (`token`(255));

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
