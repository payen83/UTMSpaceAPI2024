-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Oct 08, 2024 at 10:19 AM
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
(1, 'https://news.utmspace.edu.my/wp-content/uploads/2023/12/CMUMC1-2.jpg', 'Explore endless education opportunities at our SPACE UTM Edu Fair!', 'UTMSPACE salah sebuah entiti di bawah naungan Universiti Teknologi Malaysia akan membuka pintu kepada umum bagi penganjuran SPACE UTM EDU FAIR 2024 pada bulan Jun.', 'johor bahru', '2024-05-27'),
(61, 'https://news.utmspace.edu.my/wp-content/uploads/2024/05/AKKP.jpg', 'PENGAUDITAN ANUGERAH KESELAMATAN & KESIHATAN PEKERJAAN(AKKP) 2023 UTMSPACE', 'Johor Bahru, 13 Mei 2024 : Pihak OSHE UTM telah menjalankan sesi pengauditan AKKP 2023 terhadap UTMSPACE bagi kategori Kluster, UTM Johor. Seramai 8 orang auditor yang diketuai oleh Ts. Dr. Hadafi Fitri bin Mohd Latip manakala UTMSPACE diwakili oleh Ahli JKKP UTMSPACE Johor Bahru yang diketuai oleh Ts. Nik Maria bin Nik Mahamood.  Berikut adalah maklumat pengauditan.', 'Media Centre', '2024-10-01'),
(62, 'https://news.utmspace.edu.my/wp-content/uploads/2024/02/mou-msnj-dan-utmspace2.jpg', 'UTMSPACE dan MSNJ Memperkasa Pendidikan Tinggi Dalam Bidang Sukan Negeri Johor.', '13 Feb. 2024, Johor Bahru – Memorandum Persefahaman (MoU) dimeterai antara UTMSPACE dan Majlis Sukan Negeri Johor (MSNJ) yang telah ditandatangani oleh Prof Ts. Dr Nazri bin Ali, Ketua Pegawai Eksekutif dan Pengarah Urusan UTMSPACE | Dekan SPACE UTM dan Ybrs. Ts Mohd Ekmaluddin bin Ishak, Pengarah MSNJ. Sesi menandatangani MoU tersebut disaksikan oleh Menteri Besar Johor, Yang Amat Berhormat Dato’ Onn Hafiz bin Ghani pada Majlis Countdown SUKAM Johor “SUKMA 2024: The Next Chapter” di Pusat Akuatik Majlis Bandaraya Johor Bahru, Arena Larkin.', 'UTMSpace JB', '2024-02-24'),
(63, 'https://news.utmspace.edu.my/wp-content/uploads/2024/02/WhatsApp-Image-2024-02-13-at-2.57.26-PM1-2048x1367.jpeg', 'COURTESY VISIT FROM UNIVERSITY GEOMATIKA MALAYSIA', '13 Februari 2024 – A delegation from University Geomatika Malaysia (UGM) headed by its Vice Chancellor, Prof Sr. Dr. Mohd Zulkifli bin Mohd Yunos visited UTMSPACE . CEO and Managing Director of UTMSPACE, Prof Dr Nazri bin Ali, and UTMSPACE management welcomed the visit. Among matters discussed are collaboration in offering professional development programs and franchising UTM programs. UTMSPACE is interested to explore UGM TVET programs especially in hospitality and lifestyle.', 'UTMSpace Kuala Lumpur', '2024-03-07');

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
(1, '', 'https://picsum.photos/id/2/200/300', 'test1@email.com', '$2y$10$Fxo9gjUVyG1mPwfpKctCKOMOqhHX1szepP8ZUDzVmYrm3K1P1elg6', 'Ahmad', 'Khairul', '881220-06-5457', 'No 93, Apartment Rose, Jalan Kuala Langat, 55670, Selangor.', 'HR manager', 'Human Resources', '0123235567', '1991-01-01', NULL, NULL, NULL, NULL, NULL),
(4, NULL, 'https://picsum.photos/id/11/200/300', 'test2@email.com', '$2y$10$xV6IDx1MqrAvZhswXVZ6J.aaIRdw458TLwSaKBvdISqq9B/Pan13W', 'Nurul Huda', 'Abidin', '900323-01-6788', 'No 123 Kampung Gambut, Jalan Kota Lama, 23500, Bukit Senapang, Pahang.', 'Accountant', 'Accounting', '0113447890', '1991-02-02', NULL, NULL, NULL, NULL, NULL),
(5, NULL, 'https://picsum.photos/id/20/200/300', 'test4@email.com', '$2y$10$bvAfCfIhjzg7tLDAPGABmO82SECRXROaKgO4m6aocMu3qU.uiz6di', 'Mohamad Jainuddin', 'Wan Hisyam', '870928-07-4569', 'No 9878 Kampung Parit Baru, Jalan Bukit Bukau, 67000, Kota Tinggi, Terengganu.', 'Sales Assistant', 'Sales', '0167884521', '1991-03-03', NULL, NULL, NULL, NULL, NULL),
(6, NULL, 'https://picsum.photos/id/29/200/300', 'test5@email.com', '$2y$10$TY5uQkmCfJiMzwctKSUmku04TJw3pEgUmanv4gxKUG6AAn4DkmIfi', 'Abdul Halim', 'Samsudin', '900102-06-5456', 'No 999 Jalan Makmur, Bangi Lama, 34770, Pulau Pinang.', 'Sales Manager', 'Sales', '0125567878', '1991-03-03', NULL, NULL, NULL, NULL, NULL),
(7, NULL, 'https://picsum.photos/id/58/200/300', 'halimah@gmail.com', '$2y$10$6ZJE1Y71WWk3DSCQGREOTux4ryx2HjFtBMfCbnRKrwg.VFmmMdn/u', 'Noor Halimah', 'Abu Bakar', '900102-06-5456', 'No 999 Jalan Makmur, Kota Lama, 44500, Johor.', 'HR', 'Human Resource', '0192239403', '1991-03-03', NULL, NULL, NULL, NULL, NULL);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
