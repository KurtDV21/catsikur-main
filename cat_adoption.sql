-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 20, 2024 at 05:10 PM
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
  `truthfulness` varchar(255) NOT NULL
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
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inquiries`
--

CREATE TABLE `inquiries` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `post_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `age` varchar(20) NOT NULL,
  `company_industry` varchar(255) NOT NULL,
  `Guardian_details` varchar(255) DEFAULT NULL,
  `Facebook` int(11) NOT NULL,
  `address` varchar(255) NOT NULL,
  `Housing` varchar(20) NOT NULL,
  `Housing_role` varchar(59) NOT NULL,
  `Household_agreement` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `household_adults` int(11) NOT NULL,
  `household_children` int(11) NOT NULL,
  `children_ages` varchar(255) NOT NULL,
  `children_experience` enum('yes','no','na') NOT NULL,
  `allergies` enum('yes','no') NOT NULL,
  `allergy_details` text DEFAULT NULL,
  `other_caregiver` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `color` varchar(255) NOT NULL,
  `picture` varchar(255) NOT NULL,
  `approval` varchar(50) NOT NULL,
  `adoption_status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `post`
--

INSERT INTO `post` (`id`, `user_id`, `cat_name`, `age`, `location`, `gender`, `color`, `picture`, `approval`, `adoption_status`) VALUES
(1, 66, 'sher', '12', 'kalokohan', 'meow', 'red', 'uploads/cats/66/sher/316237054_1284362335732679_5042494615036074117_n.jpg', 'approved', ''),
(2, 66, 'sher', '12', 'kalokohan', 'meow', 'red', 'uploads/cats/66/sher/316237054_1284362335732679_5042494615036074117_n.jpg', 'denied', ''),
(3, 66, 'kurt', 'kurt', 'kurt', 'kurt', 'kurt', 'uploads/cats/66/kurt/316237054_1284362335732679_5042494615036074117_n.jpg', 'approved', ''),
(4, 67, 'eldad', 'eldad', 'eldad', 'eldad', 'eldad', 'uploads/cats/67/eldad/316237054_1284362335732679_5042494615036074117_n.jpg', 'approved', ''),
(5, 66, 'aaron', 'aaron', 'aaron', 'aaron', 'aaron', 'uploads/cats/66/aaron/316237054_1284362335732679_5042494615036074117_n.jpg', 'approved', ''),
(6, 66, 'aaron', 'aaron', 'aaron', 'aaron', 'aaron', 'uploads/cats/66/aaron/316237054_1284362335732679_5042494615036074117_n.jpg', 'denied', ''),
(7, 66, 'bagong cat', '39', 'sa tabi lang', 'ewan ko sayo', 'asul', 'uploads/cats/66/bagong cat/Screenshot 2024-03-26 185347.png', '', ''),
(8, 66, 'jhob', 'may edad na', 'sa tabi lang', 'ewan koba', 'pula', 'uploads/cats/66/jhob/Screenshot 2024-10-17 185249.png', 'approved', ''),
(9, 69, '', 'wqeweq', 'weq', 'c', 'sda', 'uploads/cats/69//WIN_20230911_15_50_46_Pro.jpg', '', ''),
(10, 69, '', 'wqeweq', 'weq', 'c', 'sda', 'uploads/cats/69//WIN_20230911_15_50_46_Pro.jpg', '', ''),
(11, 69, 'mea', 'sd', 'loa', 'm', 'red', 'uploads/cats/69/mea/boota.png', '', ''),
(12, 69, 'red', '123', 'sd', 'c', 'color', 'uploads/cats/69/red/boota.png', '', ''),
(13, 69, 'sd', 'sd', 'sdsd', 'sd', 'sd', 'uploads/cats/69/sd/boota.png', '', ''),
(14, 69, 'we', 'qweqwe', 'qwe', 'qweqwe', 'qwe', 'uploads/cats/69/we/boota.png', '', ''),
(15, 69, 'sher', '23', 'loc', 'm', 'red', 'uploads/cats/69/sher/boota.png', '', ''),
(16, 69, 'sher', '23', 'loc', 'm', 'red', 'uploads/cats/69/sher/boota.png', '', ''),
(17, 69, 'sher', '23', 'loc', 'm', 'red', 'uploads/cats/69/sher/boota.png', '', ''),
(18, 69, 'sher', '23', 'loc', 'm', 'red', 'uploads/cats/69/sher/boota.png', '', ''),
(19, 69, 'sher', '23', 'loc', 'm', 'red', 'uploads/cats/69/sher/boota.png', '', ''),
(20, 69, 'sher', '12', '1', '2', '3', 'uploads/cats/69/sher/antispiral.jpg', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `role` varchar(50) NOT NULL,
  `name` varchar(128) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `reset_token_hash` varchar(64) DEFAULT NULL,
  `reset_token_expires_at` datetime DEFAULT NULL,
  `account_activation_hash` varchar(644) DEFAULT NULL,
  `profile_image_path` varchar(255) NOT NULL,
  `Phone_number` varchar(15) NOT NULL,
  `CIty` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `role`, `name`, `email`, `password_hash`, `reset_token_hash`, `reset_token_expires_at`, `account_activation_hash`, `profile_image_path`, `Phone_number`, `CIty`) VALUES
(26, '', 'Sean', 'sean1234@gmail.com', '$2y$10$R6bDtl8QaKDfpXe3N5gJAeDfDASfUmOKR6d3MNbc.V6PQyW9.CUCm', NULL, NULL, NULL, '', '0', '0'),
(37, '', 'liam', 'liam1234@gmail.com', '$2y$10$b2FALiGr2TASwSjgxF.q8un10p2UH7bpJcgTgnzWYu6WbpYAQAMw6', NULL, NULL, NULL, '', '0', '0'),
(38, '', 'qweqwe', 'qwe@gmail.com', '$2y$10$dC2P7.QRtsHCxnGcUx3gzuZSCSXN5v/YWi.1QC12tbtZdkGd8.GVa', NULL, NULL, NULL, '', '0', '0'),
(50, '', 'sher', 'sherwin@gmail.com', '$2y$10$hF6mUn/9sg.22zWXnsvPguNPIJ3cvPQtb65tDQBbK37ye6eDx35om', NULL, NULL, NULL, '', '0', '0'),
(52, '', 'kurt', 'kurt@gmail.com', '$2y$10$QDGGhsIilaID7BK7yGQ7CeH3FtC7KdBAA/jD27V1msvOZMphLKt7K', NULL, NULL, NULL, '', '0', '0'),
(53, '', 'marcburat', 'marc@gmail.com', '$2y$10$O7Pt//QWevlM9bwnSa9zaOPeAG2iR7uM7ivWPIyw0Q3.9VK4pA6n.', NULL, NULL, NULL, '', '0', '0'),
(54, '', '', '', '', NULL, NULL, NULL, '', '0', '0'),
(56, '', 'a', 'a@gmail.com', '$2y$10$OkGRHh7HyBCAnnZkdOzg.O4e9R7Eh0jWiARZE/Y8JKdFzjAFZaXMa', NULL, NULL, NULL, '', '', ''),
(57, '', '1', '1@gmail.com', '$2y$10$uNmKJ5au1iCImY8G5UkS7ufTUlTuW1TNkwkbqi1eQuTlTvOx9WIWW', NULL, NULL, NULL, '', '', ''),
(58, '', 'h', 'h@gmail.com', '$2y$10$3VNGtyo/aeg4zWuT5rlKC.a5/hJ6cYq4J4ZoQigf/QYKqxTfuivja', NULL, NULL, NULL, '', '', ''),
(59, '', 'wew', 'wew@gmail.com', '$2y$10$0uaN/7ONwIDy6y0cfj0D0.WwH/eYg7er.RZitKjsRJcu0xWIiRUga', NULL, NULL, NULL, '', '', ''),
(60, '', 'what', 'what@gmail.com', '$2y$10$6wojS.4bYYPXDSuqqv8QAu6RClFp3wZ..gzOo04KWYnvf6IUoBde.', NULL, NULL, NULL, '', '', ''),
(61, '', 'sher', 'sher123@gmail.com', '$2y$10$EJNtcQsnqFgaCKDWKKHtFO2iYDqQueKAzroA6O883Fv95B.nwbx72', NULL, NULL, '9899b797db8e67723f42de54cd971f6f', '', '', ''),
(62, '', 'sher', 'sher1223@gmail.com', '$2y$10$CuW2UoD9BdnTsbmBe0wWceQnXC/WAfV7vxp0MTtNCJyjd8pKNPkjK', NULL, NULL, 'fdfaff85f9928f6f08be5e79c1ce805d', '', '', ''),
(63, '', '123sherwin', '123sherwin@gmail.com', '$2y$10$UFsp28/.B/F2/F27xv1LjOCQ/amgYP/t2kIpdUyjBn9Nr9WbJyfg6', NULL, NULL, '5f6a58804b92e82a666ec1d47812b0c6', '', '', ''),
(64, '', 'sherwin', 'shersher123@gmail.com', '$2y$10$rG.3KXqTc4jwp3HJcto5Ju3D1Y0SBJjxBJD.kXqBhqkCLfoXT0SUO', NULL, NULL, '7de65c3a2bf5923d11e28e62878e11cb', 'uploads/users/sherwin/316237054_1284362335732679_5042494615036074117_n.jpg', '09123785344', 'kalokohan'),
(65, '', 'surewin', 'surewin@gmail.com', '$2y$10$nv7Srr6F6zP.Pc5gX2A8M.EnnjGadEblkZYrFDTY3vbwNsdKBlaq6', NULL, NULL, 'b45c9408944c5f2ea7499b21724fe7c1', 'uploads/users/surewin/316237054_1284362335732679_5042494615036074117_n.jpg', '09123794344', 'kalokohan'),
(66, 'admin', 'sherwin', '123@gmail.com', '$2y$10$7.E1Xyv5Zl3mEeFLmUtQcOjLJy.1Y0KaJGflYKdaDuv2K4a3WP0Lq', NULL, NULL, NULL, 'uploads/users/sherwin/suzuki.jpg', '091237834', 'kalookan'),
(67, '', 'eldad', 'eldad@gmail.com', '$2y$10$c.m.t.VF6VX7mYjjQt3XIexA5Lw/tlovw6lx1KcFvXvsrDd2h26vm', NULL, NULL, NULL, '', '13123123', 'eldad'),
(68, 'user', 'hahaha', 'hahaha@gmail.com', '$2y$10$OIDsqNfaGt1VzHcrueufkevSWSr6B7w.FnRY3ZTXT2j.c61wP5A56', NULL, NULL, '56fced4849fc5aa0fbe10286e6de9d95', '', '', ''),
(69, 'user', 'user', 'user@gmail.com', '$2y$10$ykmjhanIqmWy0utjPPzkdeqotckLOhX7dKT/2zuaHmCnarw4aHbvO', NULL, NULL, NULL, '', '123123123', 'user'),
(70, 'user', 'no', 'no70@gmail.com', '$2y$10$GdSCrQ/LYeQybeieWXdXU.sEWAHaDakRpJm6iYLSkVzw3bGuJ6JGy', NULL, NULL, 'd9ad14900a186fbf876a18712a20a3f7', '', '09232123533', 'numerouno'),
(71, 'user', 'kuer', 'shiwen@gmail.com', '$2y$10$8v7M5ZqcYc8SZvUsAwd.su7oWQG9gtaGxTIho1lQsgYbGgsfOYOFq', NULL, NULL, 'ac114a1bf2df529c5aba5e2a434f2d05', '', '0923342341', 'kokoo'),
(72, 'user', 'kurt', 'gumana@gmail.com', '$2y$10$iSFp3dYOvjrDeEthFceHKegaa1QimEj9JbJGJpz/MS9zjXR7ySUH.', NULL, NULL, NULL, 'uploads/users/kurt/316237054_1284362335732679_5042494615036074117_n.jpg', '0923123123', 'koko'),
(73, 'user', 'charles', 'charles@gmail.com', '$2y$10$RK04FzdVdi5S6ueEfPfVQOAmWxozQyzLzATnS3bgqTg81sEYyelTW', NULL, NULL, NULL, '', '1231111111', 'kalokohan'),
(74, 'user', 'marvin', 'marvin@yahoo.gmailasd', '$2y$10$mhFz7N1ikwlMcB/sPdshu.oa6ItfEzFScbbolBJtUc3c7OLzQ4RSW', NULL, NULL, 'c974543d042e8c2b2baab0af84c8cb05', '', '', ''),
(75, 'user', 'clarence', 'clarence@gmail.com', '$2y$10$zKj8WRxCf09SkOLjC2CF/eyCpHik91nPmxBSEPr6Ho4Zfiv6.QLVu', NULL, NULL, '77ba6ac3e0db2ba880d5294f51968804', '', '', ''),
(76, 'user', 'Kurt Vergara', 'kurtvergara@gmail.com', '$2y$10$UO91012XSZNmUX99ZlBRlON09vHWm.Rux49j/I7UKDD4RkXBUYe1y', NULL, NULL, 'aa265c69ef2d766de2951784a29ecb88', 'uploads/users/Kurt Vergara/gurrenlagann.jpg', '091238343', 'Caloocan'),
(80, 'admin', 'sherwin', 'shher@gmail.com', '$2y$10$/dPU9exErq9nFBGB3Ehq0u98HFNmlQAVl7nQDne6ikf7rlkY7JraG', NULL, NULL, '0ce04495d58dbb5daa715b2059feef9b', 'uploads/users/sherwin/kittan.jpg', '0988231', 'kalokohan'),
(82, 'user', 'Carlos carlos', 'carlos@gmail.com', '$2y$10$YmehrRg9Sytt4bemExavguwtjDv6/dXPerGPVm5cFXBTpIs/RlYq6', NULL, NULL, NULL, 'uploads/users/Carlos carlos/boota.png', '09123456322', 'dto lang'),
(86, 'user', 'Carlossss', 'carlosss@gmail.com', '$2y$10$O.GU9g.GcOSUq0c0taO6yu4GJU5yoI1Y2Sz8DjDULyWYXCnfPG.HK', NULL, NULL, NULL, 'uploads/users/Carlossss/boota.png', '09873432', 'mcu');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `adoption_commitment_inquiry`
--
ALTER TABLE `adoption_commitment_inquiry`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `post_id` (`post_id`);

--
-- Indexes for table `adoption_inquiry_details`
--
ALTER TABLE `adoption_inquiry_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`,`post_id`);

--
-- Indexes for table `inquiries`
--
ALTER TABLE `inquiries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`,`post_id`),
  ADD KEY `post_id` (`post_id`);

--
-- Indexes for table `pet_adoption_inquiry`
--
ALTER TABLE `pet_adoption_inquiry`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`,`post_id`);

--
-- Indexes for table `post`
--
ALTER TABLE `post`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `reset_token_hash` (`reset_token_hash`),
  ADD UNIQUE KEY `account_activation_hash` (`account_activation_hash`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inquiries`
--
ALTER TABLE `inquiries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pet_adoption_inquiry`
--
ALTER TABLE `pet_adoption_inquiry`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `post`
--
ALTER TABLE `post`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=87;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `adoption_commitment_inquiry`
--
ALTER TABLE `adoption_commitment_inquiry`
  ADD CONSTRAINT `adoption_commitment_inquiry_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `adoption_commitment_inquiry_ibfk_2` FOREIGN KEY (`post_id`) REFERENCES `post` (`id`);

--
-- Constraints for table `inquiries`
--
ALTER TABLE `inquiries`
  ADD CONSTRAINT `inquiries_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `inquiries_ibfk_2` FOREIGN KEY (`post_id`) REFERENCES `post` (`id`);

--
-- Constraints for table `post`
--
ALTER TABLE `post`
  ADD CONSTRAINT `post_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
