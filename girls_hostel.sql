-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 26, 2024 at 07:10 PM
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
-- Database: `girls_hostel`
--

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`id`, `title`, `description`, `created_at`) VALUES
(3, 'Papers', '7 August', '2024-08-01 09:44:09');

-- --------------------------------------------------------

--
-- Table structure for table `applications`
--

CREATE TABLE `applications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `student_id` varchar(50) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `agree_count` int(11) DEFAULT 0,
  `disagree_count` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `applications`
--

INSERT INTO `applications` (`id`, `user_id`, `name`, `student_id`, `subject`, `message`, `submitted_at`, `agree_count`, `disagree_count`) VALUES
(1, 5, 'Ali Raza', '3141', 'This is test', 'Test', '2024-07-17 13:25:00', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `application_votes`
--

CREATE TABLE `application_votes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `application_id` int(11) NOT NULL,
  `vote` enum('agree','disagree') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `application_votes`
--

INSERT INTO `application_votes` (`id`, `user_id`, `application_id`, `vote`) VALUES
(3, 7, 1, 'agree'),
(4, 8, 1, 'disagree');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `venue` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `title`, `date`, `time`, `venue`, `created_at`) VALUES
(1, 'new event', '2024-07-31', '09:01:00', 'RCM', '2024-07-17 13:01:24');

-- --------------------------------------------------------

--
-- Table structure for table `registrations`
--

CREATE TABLE `registrations` (
  `id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `reg_number` varchar(255) NOT NULL,
  `admission_year` varchar(4) NOT NULL,
  `department` varchar(255) NOT NULL,
  `semester` int(11) NOT NULL,
  `password` varchar(255) NOT NULL,
  `joined_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `registrations`
--

INSERT INTO `registrations` (`id`, `full_name`, `username`, `reg_number`, `admission_year`, `department`, `semester`, `password`, `joined_at`) VALUES
(5, 'Ali Raza', 'aliraza', '3141', '2024', 'Computer Science', 6, '$2y$10$BC.tpJKILvbOVpS2Sglwm.TRXMcz7bNOxvzPvdWnZVEb1fiPZK7pe', '2024-07-17 12:37:52'),
(6, 'Syed Ali Raza Kazmi', 'aliraza07', 'F21-KUM-BCS-0448', '2021', 'Computer Science', 6, '$2y$10$kydS3B1uX0R.FIHO1.I6nuDpGqkJUsCa6xDc2BqC5vDw4WsDFAQl2', '2024-07-17 16:11:10'),
(7, 'warda', 'hareem', '9202', '2024', 'Computer Science', 6, '$2y$10$tp1RrIlCN9MbVM/VasQ9QOnUWtz3naRqj3Q.nC2A5pofJD.3Xlm1q', '2024-07-22 08:53:47'),
(8, 'mishal', 'mishalgul', '5555', '2021', 'Computer Science', 6, '$2y$10$qRN5gCBdNq0e1ShJaHzMkupbqqFVc3uunvWZVaH6GRvqz5zxNF1Iy', '2024-08-01 09:42:02');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `applications`
--
ALTER TABLE `applications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `application_votes`
--
ALTER TABLE `application_votes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_vote` (`user_id`,`application_id`),
  ADD KEY `application_id` (`application_id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `registrations`
--
ALTER TABLE `registrations`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `applications`
--
ALTER TABLE `applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `application_votes`
--
ALTER TABLE `application_votes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `registrations`
--
ALTER TABLE `registrations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `applications`
--
ALTER TABLE `applications`
  ADD CONSTRAINT `applications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `registrations` (`id`);

--
-- Constraints for table `application_votes`
--
ALTER TABLE `application_votes`
  ADD CONSTRAINT `application_votes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `registrations` (`id`),
  ADD CONSTRAINT `application_votes_ibfk_2` FOREIGN KEY (`application_id`) REFERENCES `applications` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
