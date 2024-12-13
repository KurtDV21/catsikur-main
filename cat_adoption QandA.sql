-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 11, 2024 at 05:10 AM
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
-- Table structure for table `adoption_commitment_inquiry`
--

CREATE TABLE `adoption_commitment_inquiry` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `hours_alone` varchar(255) NOT NULL,
  `sleep_location` varchar(255) NOT NULL,
  `stress_awareness` varchar(255) NOT NULL,
  `work_through_issues` varchar(255) NOT NULL,
  `spay_neuter` varchar(255) NOT NULL,
  `commitment` varchar(255) NOT NULL,
  `responsibility` varchar(255) NOT NULL,
  `truthfulness` varchar(255) NOT NULL,
  `approval` varchar(30) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `adoption_inquiry_details`
--

CREATE TABLE `adoption_inquiry_details` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `pets` varchar(255) NOT NULL,
  `spayed_neutered` varchar(255) NOT NULL,
  `status` enum('alive','dead','given','adopt','none') NOT NULL,
  `adopted_before` enum('yes','no') NOT NULL,
  `supplies` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`supplies`)),
  `approval` varchar(30) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `adoption_inquiry_details`
--

INSERT INTO `adoption_inquiry_details` (`id`, `user_id`, `post_id`, `pets`, `spayed_neutered`, `status`, `adopted_before`, `supplies`, `approval`, `created_at`) VALUES
(1, 66, 31, 'cat and dog', '2', 'alive', 'no', '[\"catfood\",\"milk\",\"grooming\"]', '', '2024-12-04 08:47:10'),
(2, 94, 31, 'wew', 'na', 'adopt', 'yes', '[\"vita\"]', '', '2024-12-09 09:51:36'),
(3, 69, 29, 'puspin', 'kapon', 'given', 'yes', '[\"milk\"]', '', '2024-12-10 12:30:03');

-- --------------------------------------------------------

--
-- Table structure for table `answers`
--

CREATE TABLE `answers` (
  `id` int(11) NOT NULL,
  `question_id` int(11) DEFAULT NULL,
  `answer_text` varchar(255) DEFAULT NULL,
  `is_other` tinyint(1) DEFAULT 0,
  `input_type` enum('radio','checkbox','text','dropdown') DEFAULT 'radio'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chats`
--

CREATE TABLE `chats` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `inquirer_id` int(11) NOT NULL,
  `author_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chats`
--

INSERT INTO `chats` (`id`, `post_id`, `inquirer_id`, `author_id`, `created_at`) VALUES
(70, 31, 66, 109, '2024-12-04 08:54:10'),
(71, 29, 69, 106, '2024-12-10 12:32:55');

-- --------------------------------------------------------

--
-- Table structure for table `chat_messages`
--

CREATE TABLE `chat_messages` (
  `id` int(11) NOT NULL,
  `chat_id` int(11) DEFAULT NULL,
  `sender_id` int(11) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chat_messages`
--

INSERT INTO `chat_messages` (`id`, `chat_id`, `sender_id`, `message`, `created_at`) VALUES
(127, 70, 109, 'hii po', '2024-12-06 09:43:33'),
(128, 70, 66, 'oi', '2024-12-06 09:45:21'),
(129, 70, 109, 'hindi dba', '2024-12-06 10:02:40'),
(130, 70, 66, 'oo', '2024-12-06 10:02:52'),
(131, 70, 66, 'ewan ko sayo', '2024-12-10 07:21:04'),
(132, 70, 66, 'hi', '2024-12-10 07:22:21'),
(133, 70, 66, 'hi', '2024-12-10 07:23:00'),
(134, 70, 66, 'hi', '2024-12-10 07:24:07'),
(135, 71, 106, 'hello', '2024-12-10 12:37:37'),
(136, 71, 69, 'hello din po', '2024-12-10 12:39:43');

-- --------------------------------------------------------

--
-- Table structure for table `inquiries`
--

CREATE TABLE `inquiries` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `age` varchar(20) NOT NULL,
  `company_industry` varchar(255) NOT NULL,
  `Guardian_details` varchar(255) DEFAULT NULL,
  `Facebook` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `Housing` varchar(20) NOT NULL,
  `Housing_role` varchar(59) NOT NULL,
  `Household_agreement` varchar(10) NOT NULL,
  `approval` varchar(30) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(25) NOT NULL,
  `modal_see` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inquiries`
--

INSERT INTO `inquiries` (`id`, `user_id`, `post_id`, `name`, `age`, `company_industry`, `Guardian_details`, `Facebook`, `address`, `Housing`, `Housing_role`, `Household_agreement`, `approval`, `created_at`, `status`, `modal_see`) VALUES
(3, 66, 31, 'Kurt vergara', '21', 'Student - College', '', 'https://www.facebook.com/sherw1n', '411-D caloocan City', 'House', 'own', 'Yes', '', '2024-12-04 08:47:10', 'approved', 0),
(4, 94, 31, 'sherwin coletmodres', '12', 'Student - High School/Senior Highschool', 'EDINDJF', 'https://www.facebook.com/sherw1n', 'sa caloocan lang', 'Apartment', 'own', 'Yes', '', '2024-12-09 09:51:36', '', 0),
(5, 69, 29, 'she oc', '24', 'Homestay/HouseWife/HouseHusband', 'Guardian', 'facebook', 'caloocan', 'Other: puilubi', 'own', 'Yes', '', '2024-12-10 12:30:03', 'approved', 0);

-- --------------------------------------------------------

--
-- Table structure for table `pet_adoption_inquiry`
--

CREATE TABLE `pet_adoption_inquiry` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `caregiver` varchar(255) NOT NULL,
  `landlord_permission` varchar(255) NOT NULL,
  `restrictions` varchar(255) NOT NULL,
  `household_adults` varchar(11) NOT NULL,
  `household_children` varchar(11) NOT NULL,
  `children_ages` varchar(255) NOT NULL,
  `children_experience` enum('yes','no','na') NOT NULL,
  `allergies` enum('yes','no') NOT NULL,
  `allergy_details` text DEFAULT NULL,
  `approval` varchar(30) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pet_adoption_inquiry`
--

INSERT INTO `pet_adoption_inquiry` (`id`, `user_id`, `post_id`, `caregiver`, `landlord_permission`, `restrictions`, `household_adults`, `household_children`, `children_ages`, `children_experience`, `allergies`, `allergy_details`, `approval`, `created_at`) VALUES
(3, 66, 31, 'me', 'na', 'none', '4', '1', '6', 'yes', 'no', '', '', '2024-12-04 08:47:10'),
(4, 94, 31, 'parents', 'na', 'number', '44', '33', '33', 'no', 'no', '', '', '2024-12-09 09:51:36'),
(5, 69, 29, 'me', 'yes', 'size', '4', '4', '4', 'yes', 'yes', 'yes', '', '2024-12-10 12:30:03');

-- --------------------------------------------------------

--
-- Table structure for table `post`
--

CREATE TABLE `post` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `cat_name` varchar(255) NOT NULL,
  `age` varchar(50) NOT NULL,
  `location` varchar(50) NOT NULL,
  `gender` varchar(50) NOT NULL,
  `color` varchar(30) NOT NULL,
  `Description` longtext NOT NULL,
  `picture` varchar(255) NOT NULL,
  `sample_pictures` text NOT NULL,
  `approval` varchar(50) NOT NULL,
  `post_status` varchar(255) NOT NULL,
  `post_type` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `post`
--

INSERT INTO `post` (`id`, `user_id`, `cat_name`, `age`, `location`, `gender`, `color`, `Description`, `picture`, `sample_pictures`, `approval`, `post_status`, `post_type`, `created_at`) VALUES
(29, 106, 'kurt', '12 months', 'sdf', 'Female', 'Grey', 'JIJI LANG', 'uploads/cats/106_kurt/kurt/profile_20130630-stray-kitten-carousel.jpg', '[\"uploads\\/cats\\/106_kurt\\/kurt\\/sample_0_1ff822190343ca87a1d7b65732519bcf.jpg\",\"uploads\\/cats\\/106_kurt\\/kurt\\/sample_1_Adopt-Lot-.jpg\",\"uploads\\/cats\\/106_kurt\\/kurt\\/sample_2_kitten.jpg\"]', 'approved', '', 'Adoption', '2024-12-04 03:09:50'),
(30, 109, 'haru', '2 months', 'Caloocan', 'Male', 'Brown', 'He needs some help...', 'uploads/cats/109_Sherwin/haru/profile_cat5.jpg', '[\"uploads\\/cats\\/109_Sherwin\\/haru\\/sample_0_msedge.exe\"]', 'approved', '', 'Rescued', '2024-12-04 08:25:36'),
(31, 109, 'haru', '1 months', 'Caloocan', 'Male', 'White', 'He needs some help...', 'uploads/cats/109_Sherwin/haru/profile_cat4.jpg', '[\"uploads\\/cats\\/109_Sherwin\\/haru\\/sample_0_cat5.jpg\"]', 'approved', '', 'Adoption', '2024-12-04 08:26:40'),
(32, 94, 'mingming', '18 months', 'caloocan', 'Female', 'Black', 'ming ming sa tabi', 'uploads/cats/94_jsiajdisid/mingming/profile_1ff822190343ca87a1d7b65732519bcf.jpg', '[\"uploads\\/cats\\/94_jsiajdisid\\/mingming\\/sample_0_20130630-stray-kitten-carousel.jpg\",\"uploads\\/cats\\/94_jsiajdisid\\/mingming\\/sample_1_Adopt-Lot-.jpg\",\"uploads\\/cats\\/94_jsiajdisid\\/mingming\\/sample_2_kitten.jpg\"]', 'approved', '', 'Rescued', '2024-12-09 07:24:05'),
(33, 106, 'Haru', '2 months', 'Caloocan', 'Male', 'White', 'Cute Cat', 'uploads/cats/106_kurt/Haru/profile_1ff822190343ca87a1d7b65732519bcf.jpg', '[\"uploads\\/cats\\/106_kurt\\/Haru\\/sample_0_20130630-stray-kitten-carousel.jpg\",\"uploads\\/cats\\/106_kurt\\/Haru\\/sample_1_Adopt-Lot-.jpg\",\"uploads\\/cats\\/106_kurt\\/Haru\\/sample_2_i-helped-a-stray-cat-in-paros-greece-get-help-and-medical-v0-hhk09e8px3ed1.webp\"]', 'denied', '', 'Rescue', '2024-12-10 12:41:11');

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
(7, 'Guardian Name - Relationship - Contact Number: (FOR 18 YRS OLD & BELOW ONLY) Example: Melinda Reyes - Mother - 0917522634', 'text', 0, NULL),
(8, 'Your Facebook profile link:', 'text', 0, NULL),
(9, 'Do you live in a:', 'radio', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `role` varchar(50) NOT NULL,
  `name` varchar(128) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `reset_token_hash` varchar(64) DEFAULT NULL,
  `reset_token_expires_at` datetime DEFAULT NULL,
  `account_activation_hash` varchar(644) DEFAULT NULL,
  `profile_image_path` varchar(255) NOT NULL,
  `Phone_number` varchar(15) NOT NULL,
  `CIty` varchar(255) NOT NULL,
  `is_restricted` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `role`, `name`, `first_name`, `last_name`, `email`, `password_hash`, `reset_token_hash`, `reset_token_expires_at`, `account_activation_hash`, `profile_image_path`, `Phone_number`, `CIty`, `is_restricted`) VALUES
(26, '', 'Sean', '', '', 'sean1234@gmail.com', '$2y$10$R6bDtl8QaKDfpXe3N5gJAeDfDASfUmOKR6d3MNbc.V6PQyW9.CUCm', NULL, NULL, NULL, '', '0', '0', ''),
(37, '', 'liam', '', '', 'liam1234@gmail.com', '$2y$10$b2FALiGr2TASwSjgxF.q8un10p2UH7bpJcgTgnzWYu6WbpYAQAMw6', NULL, NULL, NULL, '', '0', '0', ''),
(38, '', 'qweqwe', '', '', 'qwe@gmail.com', '$2y$10$dC2P7.QRtsHCxnGcUx3gzuZSCSXN5v/YWi.1QC12tbtZdkGd8.GVa', NULL, NULL, NULL, '', '0', '0', ''),
(50, '', 'sher', '', '', 'sherwin@gmail.com', '$2y$10$hF6mUn/9sg.22zWXnsvPguNPIJ3cvPQtb65tDQBbK37ye6eDx35om', NULL, NULL, NULL, '', '0', '0', ''),
(52, '', 'kurt', '', '', 'kurt@gmail.com', '$2y$10$QDGGhsIilaID7BK7yGQ7CeH3FtC7KdBAA/jD27V1msvOZMphLKt7K', NULL, NULL, NULL, '', '0', '0', ''),
(53, '', 'marcburat', '', '', 'marc@gmail.com', '$2y$10$O7Pt//QWevlM9bwnSa9zaOPeAG2iR7uM7ivWPIyw0Q3.9VK4pA6n.', NULL, NULL, NULL, '', '0', '0', ''),
(54, '', '', '', '', '', '', NULL, NULL, NULL, '', '0', '0', ''),
(56, '', 'a', '', '', 'a@gmail.com', '$2y$10$OkGRHh7HyBCAnnZkdOzg.O4e9R7Eh0jWiARZE/Y8JKdFzjAFZaXMa', NULL, NULL, NULL, '', '', '', ''),
(57, '', '1', '', '', '1@gmail.com', '$2y$10$uNmKJ5au1iCImY8G5UkS7ufTUlTuW1TNkwkbqi1eQuTlTvOx9WIWW', NULL, NULL, NULL, '', '', '', ''),
(58, '', 'h', '', '', 'h@gmail.com', '$2y$10$3VNGtyo/aeg4zWuT5rlKC.a5/hJ6cYq4J4ZoQigf/QYKqxTfuivja', NULL, NULL, NULL, '', '', '', ''),
(59, '', 'wew', '', '', 'wew@gmail.com', '$2y$10$0uaN/7ONwIDy6y0cfj0D0.WwH/eYg7er.RZitKjsRJcu0xWIiRUga', NULL, NULL, NULL, '', '', '', ''),
(60, '', 'what', '', '', 'what@gmail.com', '$2y$10$6wojS.4bYYPXDSuqqv8QAu6RClFp3wZ..gzOo04KWYnvf6IUoBde.', NULL, NULL, NULL, '', '', '', ''),
(61, '', 'sher', '', '', 'sher123@gmail.com', '$2y$10$EJNtcQsnqFgaCKDWKKHtFO2iYDqQueKAzroA6O883Fv95B.nwbx72', NULL, NULL, '9899b797db8e67723f42de54cd971f6f', '', '', '', ''),
(62, '', 'sher', '', '', 'sher1223@gmail.com', '$2y$10$CuW2UoD9BdnTsbmBe0wWceQnXC/WAfV7vxp0MTtNCJyjd8pKNPkjK', NULL, NULL, 'fdfaff85f9928f6f08be5e79c1ce805d', '', '', '', ''),
(63, '', '123sherwin', '', '', '123sherwin@gmail.com', '$2y$10$UFsp28/.B/F2/F27xv1LjOCQ/amgYP/t2kIpdUyjBn9Nr9WbJyfg6', NULL, NULL, '5f6a58804b92e82a666ec1d47812b0c6', '', '', '', ''),
(64, '', 'sherwin', '', '', 'shersher123@gmail.com', '$2y$10$rG.3KXqTc4jwp3HJcto5Ju3D1Y0SBJjxBJD.kXqBhqkCLfoXT0SUO', NULL, NULL, '7de65c3a2bf5923d11e28e62878e11cb', 'uploads/users/sherwin/316237054_1284362335732679_5042494615036074117_n.jpg', '09123785344', 'kalokohan', ''),
(65, '', 'surewin', '', '', 'surewin@gmail.com', '$2y$10$nv7Srr6F6zP.Pc5gX2A8M.EnnjGadEblkZYrFDTY3vbwNsdKBlaq6', NULL, NULL, 'b45c9408944c5f2ea7499b21724fe7c1', 'uploads/users/surewin/316237054_1284362335732679_5042494615036074117_n.jpg', '09123794344', 'kalokohan', ''),
(66, 'admin', 'lewayne', 'sherwin', 'cole', '123@gmail.com', '$2y$10$7.E1Xyv5Zl3mEeFLmUtQcOjLJy.1Y0KaJGflYKdaDuv2K4a3WP0Lq', NULL, NULL, NULL, 'uploads/users/sherwin/suzuki.jpg', '091237834', 'kalookan', ''),
(67, '', 'eldad', '', '', 'eldad@gmail.com', '$2y$10$c.m.t.VF6VX7mYjjQt3XIexA5Lw/tlovw6lx1KcFvXvsrDd2h26vm', NULL, NULL, NULL, '', '13123123', 'eldad', ''),
(68, 'user', 'hahaha', '', '', 'hahaha@gmail.com', '$2y$10$OIDsqNfaGt1VzHcrueufkevSWSr6B7w.FnRY3ZTXT2j.c61wP5A56', NULL, NULL, '56fced4849fc5aa0fbe10286e6de9d95', '', '', '', ''),
(69, 'user', 'user', 'she', 'oc', 'user@gmail.com', '$2y$10$ykmjhanIqmWy0utjPPzkdeqotckLOhX7dKT/2zuaHmCnarw4aHbvO', NULL, NULL, NULL, 'uploads/users/splashscreen.jpg', '123123123', 'user', ''),
(70, 'user', 'no', '', '', 'no70@gmail.com', '$2y$10$GdSCrQ/LYeQybeieWXdXU.sEWAHaDakRpJm6iYLSkVzw3bGuJ6JGy', NULL, NULL, 'd9ad14900a186fbf876a18712a20a3f7', '', '09232123533', 'numerouno', ''),
(71, 'user', 'kuer', '', '', 'shiwen@gmail.com', '$2y$10$8v7M5ZqcYc8SZvUsAwd.su7oWQG9gtaGxTIho1lQsgYbGgsfOYOFq', NULL, NULL, 'ac114a1bf2df529c5aba5e2a434f2d05', '', '0923342341', 'kokoo', ''),
(72, 'user', 'kurt', '', '', 'gumana@gmail.com', '$2y$10$iSFp3dYOvjrDeEthFceHKegaa1QimEj9JbJGJpz/MS9zjXR7ySUH.', NULL, NULL, NULL, 'uploads/users/kurt/316237054_1284362335732679_5042494615036074117_n.jpg', '0923123123', 'koko', ''),
(73, 'user', 'charles', '', '', 'charles@gmail.com', '$2y$10$RK04FzdVdi5S6ueEfPfVQOAmWxozQyzLzATnS3bgqTg81sEYyelTW', NULL, NULL, NULL, '', '1231111111', 'kalokohan', ''),
(74, 'user', 'marvin', '', '', 'marvin@yahoo.gmailasd', '$2y$10$mhFz7N1ikwlMcB/sPdshu.oa6ItfEzFScbbolBJtUc3c7OLzQ4RSW', NULL, NULL, 'c974543d042e8c2b2baab0af84c8cb05', '', '', '', ''),
(75, 'user', 'clarence', '', '', 'clarence@gmail.com', '$2y$10$zKj8WRxCf09SkOLjC2CF/eyCpHik91nPmxBSEPr6Ho4Zfiv6.QLVu', NULL, NULL, '77ba6ac3e0db2ba880d5294f51968804', '', '', '', ''),
(76, 'user', 'Kurt Vergara', '', '', 'kurtvergara@gmail.com', '$2y$10$UO91012XSZNmUX99ZlBRlON09vHWm.Rux49j/I7UKDD4RkXBUYe1y', NULL, NULL, 'aa265c69ef2d766de2951784a29ecb88', 'uploads/users/Kurt Vergara/gurrenlagann.jpg', '091238343', 'Caloocan', ''),
(80, 'admin', 'sherwin', '', '', 'shher@gmail.com', '$2y$10$/dPU9exErq9nFBGB3Ehq0u98HFNmlQAVl7nQDne6ikf7rlkY7JraG', NULL, NULL, '0ce04495d58dbb5daa715b2059feef9b', 'uploads/users/sherwin/kittan.jpg', '0988231', 'kalokohan', ''),
(82, 'user', 'Carlos carlos', '', '', 'carlos@gmail.com', '$2y$10$YmehrRg9Sytt4bemExavguwtjDv6/dXPerGPVm5cFXBTpIs/RlYq6', NULL, NULL, NULL, 'uploads/users/Carlos carlos/boota.png', '09123456322', 'dto lang', ''),
(86, 'user', 'Carlossss', '', '', 'carlosss@gmail.com', '$2y$10$O.GU9g.GcOSUq0c0taO6yu4GJU5yoI1Y2Sz8DjDULyWYXCnfPG.HK', NULL, NULL, NULL, 'uploads/users/Carlossss/boota.png', '09873432', 'mcu', ''),
(90, 'user', 'kurt32', '', '', 'kurt123@gmail.com', '$2y$10$3NmpIrtLCouk6JWwkwytWeuf0c0iATFbPHVP1eYlHhxcgMHUj6MmG', NULL, NULL, '840eef0176c949c3b0580c322b0df35c', '', '', '', ''),
(93, 'user', 'iooew', '', '', 'iooow@gmail.com', '$2y$10$BS0q.V9cZ.SrMeThjam7oewDz05IJpKg.lZLtT6fo6Dt/GqCFIsWe', NULL, NULL, 'aab9c777ffc4ec26c3b8fb5599facdda', '', '', '', ''),
(94, 'user', 'Sherwin', 'sherwin', 'coletmodres', 'shersherwe@gmail.com', '$2y$10$uwz9Vi0Rk3hxNABgigE3l.O1vKhGviQ/QFQTLZS6pJY1n8E4XEtfu', NULL, NULL, NULL, 'uploads/users/kamina.jpg', '+630989787878', 'valenzuela', ''),
(95, 'user', 'sean', 'bon', 'gwapings', 'bonbon@gmail.com', '$2y$10$dr2wj7xV3tnJS15FO/.xEeEKHnWzbRyxJuHNAO0FDjYF2BT8EoyfS', NULL, NULL, '425e0b37fbd04c81e4a787cd498cfddb', '', '+630989787878', 'Biringan City', ''),
(99, 'user', 'kurt', '', '', 'kurt1223@gmail.com', '$2y$10$WjDq6NIGkF9b4Q7ALhUvUOVhGo0uEGnTWg8Qt.hWd/pjpitbwv7mu', NULL, NULL, 'd9e1841e3bd54ac9f2999c4dbce9dc05', '', '', '', ''),
(100, 'user', 'sdsd', '', '', 'kurtsd@gmail.com', '$2y$10$aIweRMqOf6kCD6Vr/H6fzumoekbJCRQe.PZkhpAToS3Qa8gSWXChS', NULL, NULL, '005962f5528768c6435990cbd32d154f', '', '', '', ''),
(101, 'user', 'sher', '', '', 'sherwayne@gmail.com', '$2y$10$4U9w6emW4WCPmnO0L7URXOV5lp92Ab5l/MhsEcI2ZcuHAln6b7Dxy', NULL, NULL, '4d4ceb5d45535605bc6a868cb1ad2030', '', '', '', ''),
(103, 'user', 'kurt', '', '', 'kurtvergaras@gmail.com', '$2y$10$f0yqQKz0Oj3jsU.DY3KTHub0FYAanHlbtKcK1wDaWuqUqVVw6tB66', NULL, NULL, '0277a069889ea29419096c034751b16f', '', '', '', ''),
(106, 'user', 'kurt', 'kurt', 'vergara', '123123kurt@gmail.com', '$2y$10$CbVenY39dGSsKf1uxb9DmOqn3vmioUHulbJ0ax.2WZ0fb84W.WZxC', NULL, NULL, NULL, 'uploads/users//boota.png', '+630934323444', 'caloocan', ''),
(107, 'user', 'kurt21', 'kurt', 'vergara', 'vergarakurt1@gmail.com', '$2y$10$VbpBM/5zlSGLYE4bBH//CucBmnT.1F1MxRVrf/z5HfNqmiH5LyjGm', NULL, NULL, NULL, 'uploads/users//316237054_1284362335732679_5042494615036074117_n.jpg', '+639123221233', 'caloocan', ''),
(108, 'admin', 'kurt', 'kurt', 'colendres', 'kurtadmin@gmail.com', '$2y$10$GOVZZYS5aIG/pmaOnEqOG.9KmCK8oVhoIy6d4dz/SjsBLq7eOaNy.', NULL, NULL, NULL, 'uploads/users//316237054_1284362335732679_5042494615036074117_n.jpg', '+630992233232', 'Caloocan', ''),
(109, 'user', 'Sherwin', 'Sherwin', 'Colendres', 'sherwin15551@gmail.com', '$2y$10$q74abk2kLfIpV9IimLy5/uPoRneEMb9oLR9c7cVzmHVuO5TJdXfVa', NULL, NULL, NULL, 'uploads/users//profile.jpg', '+639311710936', 'Caloocan', ''),
(110, 'user', 'sher', '', '', '1223@gmail.com', '$2y$10$11dgI4w7og9dAsMFhjEvNO6YDssJk6W/0SQTH0y0qLoHD4s/sjhl.', NULL, NULL, '0f0b118d7204e62dbb9b2d89af546714', '', '', '', ''),
(112, 'user', 'sher', '', '', 'sherweinnew@gmail.com', '$2y$10$gKMgrwnS6ouq/OLNJP/y6epYBzDc6WAwi.aGM.vhryD5SoqnC7hla', NULL, NULL, '26879627f047f4df26eceef77462bd87', '', '', '', ''),
(113, 'user', 'x', '', '', 'x@gmail.com', '$2y$10$NDvv1IIXhqJyi3dVi6pOcuBmAyWEWYOuopeB9zV4j7fHwhtsOnPuS', NULL, NULL, 'cc5ac6da1720583f782e32eaa91103e6', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `user_logins`
--

CREATE TABLE `user_logins` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `login_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_logins`
--

INSERT INTO `user_logins` (`id`, `user_id`, `login_time`) VALUES
(1, 69, '2024-11-25 17:37:19'),
(2, 69, '2024-11-25 17:37:45'),
(3, 94, '2024-11-27 09:55:45'),
(4, 94, '2024-11-27 10:48:12'),
(5, 69, '2024-11-27 10:57:10'),
(6, 94, '2024-11-27 11:02:34'),
(7, 69, '2024-11-27 11:46:32'),
(8, 69, '2024-11-27 11:50:20'),
(9, 69, '2024-11-27 12:02:11'),
(10, 94, '2024-11-27 12:13:55'),
(11, 69, '2024-11-27 12:35:25'),
(12, 94, '2024-11-28 03:41:25'),
(13, 94, '2024-11-28 07:38:12'),
(14, 94, '2024-11-29 02:06:42'),
(15, 94, '2024-11-29 08:59:30'),
(16, 94, '2024-11-30 04:02:43'),
(17, 94, '2024-11-30 07:08:08'),
(18, 94, '2024-12-01 06:42:05'),
(19, 94, '2024-12-02 01:20:26'),
(20, 94, '2024-12-02 02:08:54'),
(21, 94, '2024-12-02 05:46:40'),
(22, 94, '2024-12-02 06:05:21'),
(23, 69, '2024-12-02 06:11:19'),
(24, 69, '2024-12-03 04:52:14'),
(25, 69, '2024-12-03 11:53:27'),
(26, 69, '2024-12-03 12:29:08'),
(27, 94, '2024-12-03 12:29:53'),
(28, 69, '2024-12-03 17:02:13'),
(29, 94, '2024-12-03 17:06:56'),
(30, 94, '2024-12-03 17:28:08'),
(31, 94, '2024-12-03 17:29:27'),
(32, 69, '2024-12-04 02:04:56'),
(33, 69, '2024-12-04 02:06:33'),
(34, 106, '2024-12-04 02:46:04'),
(35, 106, '2024-12-04 02:51:04'),
(36, 107, '2024-12-04 04:41:32'),
(37, 69, '2024-12-04 04:53:33'),
(38, 69, '2024-12-04 05:39:28'),
(39, 69, '2024-12-04 05:42:11'),
(40, 69, '2024-12-04 05:50:52'),
(41, 69, '2024-12-04 05:52:19'),
(42, 69, '2024-12-04 05:53:23'),
(43, 69, '2024-12-04 05:54:13'),
(44, 69, '2024-12-04 05:59:27'),
(45, 69, '2024-12-04 06:45:20'),
(46, 69, '2024-12-04 07:49:19'),
(47, 69, '2024-12-04 07:51:59'),
(48, 69, '2024-12-04 08:02:34'),
(49, 69, '2024-12-04 08:03:37'),
(50, 69, '2024-12-04 08:07:42'),
(51, 109, '2024-12-04 08:19:20'),
(52, 109, '2024-12-04 08:20:26'),
(53, 94, '2024-12-05 10:48:52'),
(54, 94, '2024-12-06 08:00:46'),
(55, 69, '2024-12-06 08:35:03'),
(56, 94, '2024-12-06 08:56:55'),
(57, 94, '2024-12-06 09:03:36'),
(58, 69, '2024-12-06 09:23:25'),
(59, 94, '2024-12-06 09:41:17'),
(60, 109, '2024-12-06 09:43:20');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `adoption_commitment_inquiry`
--
ALTER TABLE `adoption_commitment_inquiry`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `adoption_inquiry_details`
--
ALTER TABLE `adoption_inquiry_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `answers`
--
ALTER TABLE `answers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `question_id` (`question_id`);

--
-- Indexes for table `chats`
--
ALTER TABLE `chats`
  ADD PRIMARY KEY (`id`),
  ADD KEY `chats_ibfk_1` (`inquirer_id`),
  ADD KEY `chats_ibfk_2` (`author_id`),
  ADD KEY `chats_ibfk_3` (`post_id`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`),
  ADD KEY `chat_id` (`chat_id`),
  ADD KEY `sender_id` (`sender_id`);

--
-- Indexes for table `inquiries`
--
ALTER TABLE `inquiries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pet_adoption_inquiry`
--
ALTER TABLE `pet_adoption_inquiry`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `post`
--
ALTER TABLE `post`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `reset_token_hash` (`reset_token_hash`),
  ADD UNIQUE KEY `account_activation_hash` (`account_activation_hash`);

--
-- Indexes for table `user_logins`
--
ALTER TABLE `user_logins`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `adoption_commitment_inquiry`
--
ALTER TABLE `adoption_commitment_inquiry`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `adoption_inquiry_details`
--
ALTER TABLE `adoption_inquiry_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `answers`
--
ALTER TABLE `answers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `chats`
--
ALTER TABLE `chats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT for table `chat_messages`
--
ALTER TABLE `chat_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=137;

--
-- AUTO_INCREMENT for table `inquiries`
--
ALTER TABLE `inquiries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `pet_adoption_inquiry`
--
ALTER TABLE `pet_adoption_inquiry`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `post`
--
ALTER TABLE `post`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=114;

--
-- AUTO_INCREMENT for table `user_logins`
--
ALTER TABLE `user_logins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `answers`
--
ALTER TABLE `answers`
  ADD CONSTRAINT `answers_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `chats`
--
ALTER TABLE `chats`
  ADD CONSTRAINT `chats_ibfk_1` FOREIGN KEY (`inquirer_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `chats_ibfk_2` FOREIGN KEY (`author_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `chats_ibfk_3` FOREIGN KEY (`post_id`) REFERENCES `post` (`id`);

--
-- Constraints for table `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD CONSTRAINT `chat_messages_ibfk_1` FOREIGN KEY (`chat_id`) REFERENCES `chats` (`id`),
  ADD CONSTRAINT `chat_messages_ibfk_2` FOREIGN KEY (`sender_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `post`
--
ALTER TABLE `post`
  ADD CONSTRAINT `post_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `user_logins`
--
ALTER TABLE `user_logins`
  ADD CONSTRAINT `user_logins_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
