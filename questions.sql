-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 11, 2024 at 10:29 AM
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
-- Database: `cat_adoption`
--

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `id` int(11) NOT NULL,
  `question` text NOT NULL,
  `type` enum('text','radio','checkbox','dropdown') NOT NULL,
  `is_optional` tinyint(1) NOT NULL DEFAULT 0,
  `additional_info` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`id`, `question`, `type`, `is_optional`, `additional_info`) VALUES
(1, 'First Name', 'text', 0, NULL),
(2, 'Last Name', 'text', 0, NULL),
(3, 'Email Address', 'text', 0, NULL),
(4, 'Phone Number', 'text', 0, NULL),
(5, 'Address', 'text', 0, NULL),
(6, 'Age', 'text', 0, NULL),
(7, 'Industry of the Company You are working for:', 'dropdown', 0, 'Select your company'),
(8, 'Guardian Name - Relationship - Contact Number: (FOR 18 YRS OLD & BELOW ONLY) Example: Melinda Reyes - Mother - 0917522634', 'text', 0, NULL),
(9, 'Your Facebook profile link:', 'text', 0, NULL),
(10, 'Do you live in a:', 'radio', 0, NULL),
(11, 'Do you own the house you are currently residing in or are you a tenant renting the house?', 'radio', 0, ''),
(12, 'Are all members of your household in agreement with this adoption?', 'radio', 0, '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
