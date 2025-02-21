-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 21, 2025 at 05:32 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gradingsystem`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_grades`
--

CREATE TABLE `tbl_grades` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `semester` varchar(255) NOT NULL,
  `final_grade` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_sections`
--

CREATE TABLE `tbl_sections` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `section` varchar(10) NOT NULL,
  `strand` varchar(50) NOT NULL,
  `adviser_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_sections`
--

INSERT INTO `tbl_sections` (`id`, `name`, `section`, `strand`, `adviser_id`) VALUES
(8, 'Grade 11', 'Ramsay', 'HE', NULL),
(9, 'Grade 11', 'Weber', 'HUMSS', NULL),
(10, 'Grade 11', 'Marx', 'HUMSS', NULL),
(11, 'Grade 11', 'Jobs', 'ICT', NULL),
(12, 'Grade 11', 'Euclid', 'STEM', NULL),
(13, 'Grade 11', 'Curie', 'STEM', NULL),
(14, 'Grade 11', 'Darwin', 'GAS', NULL),
(15, 'Grade 11', 'Pacioli', 'ABM', NULL),
(16, 'Grade 11', 'Heritage', 'Tourism', NULL),
(17, 'Grade 12', 'Thompson', 'ICT', NULL),
(18, 'Grade 12', 'Liskov', 'ICT', NULL),
(19, 'Grade 12', 'Ayala', 'ABM', NULL),
(20, 'Grade 12', 'Aristotle', 'HUMSS', NULL),
(21, 'Grade 12', 'Durkheim', 'HUMSS', NULL),
(22, 'Grade 12', 'Ducasse', 'HE', NULL),
(23, 'Grade 12', 'Lawson', 'HE', NULL),
(24, 'Grade 12', 'Einstein', 'STEM', NULL),
(25, 'Grade 12', 'Descartes', 'GAS', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_students`
--

CREATE TABLE `tbl_students` (
  `id` int(11) NOT NULL,
  `student_id` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `section_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_subjects`
--

CREATE TABLE `tbl_subjects` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `grade_level` varchar(10) NOT NULL,
  `semester` varchar(255) NOT NULL,
  `strand` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_subjects`
--

INSERT INTO `tbl_subjects` (`id`, `name`, `grade_level`, `semester`, `strand`) VALUES
(1, 'Oral Communication', 'Grade 11', '1st semester', 'HE'),
(2, 'General Mathematics', 'Grade 11', '1st semester', 'STEM'),
(3, 'Business Ethics', 'Grade 11', '1st semester', 'ABM'),
(4, 'Humanities', 'Grade 11', '2nd semester', 'HUMSS'),
(5, 'Physical Science', 'Grade 11', '2nd semester', 'STEM'),
(6, 'General Mathematics', 'Grade 11', '1st semester', 'HUMSS');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_teachers`
--

CREATE TABLE `tbl_teachers` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_grades`
--
ALTER TABLE `tbl_grades`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `subject_id` (`subject_id`);

--
-- Indexes for table `tbl_sections`
--
ALTER TABLE `tbl_sections`
  ADD PRIMARY KEY (`id`),
  ADD KEY `adviser_id` (`adviser_id`);

--
-- Indexes for table `tbl_students`
--
ALTER TABLE `tbl_students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `student_id` (`student_id`),
  ADD KEY `section_id` (`section_id`);

--
-- Indexes for table `tbl_subjects`
--
ALTER TABLE `tbl_subjects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_teachers`
--
ALTER TABLE `tbl_teachers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_grades`
--
ALTER TABLE `tbl_grades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `tbl_sections`
--
ALTER TABLE `tbl_sections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `tbl_students`
--
ALTER TABLE `tbl_students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tbl_subjects`
--
ALTER TABLE `tbl_subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tbl_teachers`
--
ALTER TABLE `tbl_teachers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_grades`
--
ALTER TABLE `tbl_grades`
  ADD CONSTRAINT `tbl_grades_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `tbl_students` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tbl_grades_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `tbl_subjects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tbl_sections`
--
ALTER TABLE `tbl_sections`
  ADD CONSTRAINT `tbl_sections_ibfk_1` FOREIGN KEY (`adviser_id`) REFERENCES `tbl_teachers` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `tbl_students`
--
ALTER TABLE `tbl_students`
  ADD CONSTRAINT `tbl_students_ibfk_1` FOREIGN KEY (`section_id`) REFERENCES `tbl_sections` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
