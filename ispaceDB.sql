-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Jun 26, 2024 at 12:57 AM
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
  `title` text,
  `description` longtext,
  `location` text,
  `date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`id`, `image_url`, `title`, `description`, `location`, `date`) VALUES
(1, 'https://utmspace.edu.my/wp-content/uploads/2022/10/sembang-space.jpg', 'Explore endless education opportunities at our SPACE UTM Edu Fair!', 'UTMSPACE salah sebuah entiti di bawah naungan Universiti Teknologi Malaysia akan membuka pintu kepada umum bagi penganjuran SPACE UTM EDU FAIR 2024 pada bulan Jun.', 'johor bahru', '2024-05-27'),
(10, 'https://utmspace.edu.my/wp-content/uploads/2022/10/stareducationfair.jpg', 'Star Education Fair 2022', 'Choose the right institution to secure your future at the most awaited education fair, Join us this 12 & 13 November at Tropicana Gardens Mall! Meet us at booth A33! Visit the link to register! ', 'Tropicana Gardens Mall', '2024-05-06'),
(11, 'https://utmspace.edu.my/wp-content/uploads/2022/10/28oct1.jpg', 'Program Professional Certificate in Advances of Long Range Surveillance Radar', 'Program Professional Certificate in Advances of Long Range Surveillance Radar & Wireless Data Link telah pun berjalan pada 17 Oktober dan akan berlangsung sehingga 28 Oktober 2022 bertempat di UTM/UTMSPACE Johor Bahru dan TUDM Bukit Lunchu', 'TUDM Bukit Lunchu', '2024-05-22');

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
  `position` text,
  `department` text,
  `phone_no` text,
  `birth_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`id`, `token`, `image_url`, `email`, `password`, `first_name`, `last_name`, `position`, `department`, `phone_no`, `birth_date`) VALUES
(1, '$2y$10$3ChlGEbHuJEM7RUhv9.LnOKwTbRHZNlbWjFQfU1OCZe7VhUusAzOS', NULL, 'test1@email.com', '$2y$10$Fxo9gjUVyG1mPwfpKctCKOMOqhHX1szepP8ZUDzVmYrm3K1P1elg6', 'test1', 'Tested1', 'tester1', 'Testing1', '1111111111', '1991-01-01');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
