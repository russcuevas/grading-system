-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 23, 2025 at 05:56 AM
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
(16, 'Grade 11', 'Heritage', 'TOURISM', NULL),
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
(9, 'Oral Communication', 'Grade 11', '1st semester', 'ABM'),
(10, 'Komunikasyon at Pananaliksik sa Wika at Kulturang Pilipino', 'Grade 11', '1st semester', 'ABM'),
(11, 'General Mathematics', 'Grade 11', '1st semester', 'ABM'),
(12, 'Earth and Life Science/Earth Science', 'Grade 11', '1st semester', 'ABM'),
(13, 'Understanding Culture, Society and Politics', 'Grade 11', '1st semester', 'ABM'),
(14, 'Physical Education and Health 1', 'Grade 11', '1st semester', 'ABM'),
(15, 'Personal Development/Pansariling Kaunlaran', 'Grade 11', '1st semester', 'ABM'),
(16, 'Principles of Marketing', 'Grade 11', '1st semester', 'ABM'),
(17, 'Reading and Writing', 'Grade 11', '2nd semester', 'ABM'),
(18, 'Pagbasa at Pagsusuri ng Ibat-ibang Teksto Tungo sa Pananaliksik', 'Grade 11', '2nd semester', 'ABM'),
(19, '21st Century Literature from the Philippines and the World', 'Grade 11', '2nd semester', 'ABM'),
(20, 'Statistics and Probability', 'Grade 11', '2nd semester', 'ABM'),
(21, 'Physical Education and Health 2', 'Grade 11', '2nd semester', 'ABM'),
(22, 'Practical Research 1', 'Grade 11', '2nd semester', 'ABM'),
(23, 'Principles of Marketing', 'Grade 11', '2nd semester', 'ABM'),
(24, 'Oral Communication', 'Grade 11', '1st semester', 'HE'),
(25, 'Komunikasyon at Pananaliksik sa Wika at Kulturang Pilipino', 'Grade 11', '1st semester', 'HE'),
(26, 'General Mathematics', 'Grade 11', '1st semester', 'HE'),
(27, 'Earth and Life Science/Earth Science', 'Grade 11', '1st semester', 'HE'),
(28, 'Understanding Culture, Society and Politics', 'Grade 11', '1st semester', 'HE'),
(29, 'Physical Education and Health 1', 'Grade 11', '1st semester', 'HE'),
(30, 'Personal Development/Pansariling Kaunlaran', 'Grade 11', '1st semester', 'HE'),
(31, 'FBS 1', 'Grade 11', '1st semester', 'HE'),
(32, 'FBS 2', 'Grade 11', '1st semester', 'HE'),
(33, 'Reading and Writing', 'Grade 11', '2nd semester', 'HE'),
(34, 'Pagbasa at Pagsusuri ng Ibat-ibang Teksto Tungo sa Pananaliksik', 'Grade 11', '2nd semester', 'HE'),
(35, '21st Century Literature from the Philippines and the World', 'Grade 11', '2nd semester', 'HE'),
(36, 'Statistics and Probability', 'Grade 11', '2nd semester', 'HE'),
(37, 'Physical Education and Health 2', 'Grade 11', '2nd semester', 'HE'),
(38, 'Practical Research 1', 'Grade 11', '2nd semester', 'HE'),
(39, 'BPP 1', 'Grade 11', '2nd semester', 'HE'),
(40, 'BPP 2', 'Grade 11', '2nd semester', 'HE'),
(41, 'Oral Communication', 'Grade 11', '1st semester', 'HUMSS'),
(42, 'Komunikasyon at Pananaliksik sa Wika at Kulturang Pilipino', 'Grade 11', '1st semester', 'HUMSS'),
(43, 'General Mathematics', 'Grade 11', '1st semester', 'HUMSS'),
(44, 'Earth and Life Science/Earth Science', 'Grade 11', '1st semester', 'HUMSS'),
(45, 'Understanding Culture, Society and Politics', 'Grade 11', '1st semester', 'HUMSS'),
(46, 'Physical Education and Health 1', 'Grade 11', '1st semester', 'HUMSS'),
(47, 'Personal Development/Pansariling Kaunlaran', 'Grade 11', '1st semester', 'HUMSS'),
(48, 'DIASS', 'Grade 11', '1st semester', 'HUMSS'),
(49, 'Reading and Writing', 'Grade 11', '2nd semester', 'HUMSS'),
(50, 'Pagbasa at Pagsusuri ng Ibat-ibang Teksto Tungo sa Pananaliksik', 'Grade 11', '2nd semester', 'HUMSS'),
(51, '21st Century Literature from the Philippines and the World', 'Grade 11', '2nd semester', 'HUMSS'),
(52, 'Statistics and Probability', 'Grade 11', '2nd semester', 'HUMSS'),
(53, 'Physical Education and Health 2', 'Grade 11', '2nd semester', 'HUMSS'),
(54, 'Practical Research 1', 'Grade 11', '2nd semester', 'HUMSS'),
(55, 'POLGOV', 'Grade 11', '2nd semester', 'HUMSS'),
(56, 'DIASS', 'Grade 11', '2nd semester', 'HUMSS'),
(57, 'Oral Communication', 'Grade 11', '1st semester', 'TOURISM'),
(58, 'Komunikasyon at Pananaliksik sa Wika at Kulturang Pilipino', 'Grade 11', '1st semester', 'TOURISM'),
(59, 'General Mathematics', 'Grade 11', '1st semester', 'TOURISM'),
(60, 'Earth and Life Science/Earth Science', 'Grade 11', '1st semester', 'TOURISM'),
(61, 'Understanding Culture, Society and Politics', 'Grade 11', '1st semester', 'TOURISM'),
(62, 'Physical Education and Health 1', 'Grade 11', '1st semester', 'TOURISM'),
(63, 'Personal Development/Pansariling Kaunlaran', 'Grade 11', '1st semester', 'TOURISM'),
(64, 'Tourism promotion', 'Grade 11', '1st semester', 'TOURISM'),
(65, 'Reading and Writing', 'Grade 11', '2nd semester', 'TOURISM'),
(66, 'Pagbasa at Pagsusuri ng Ibat-ibang Teksto Tungo sa Pananaliksik', 'Grade 11', '2nd semester', 'TOURISM'),
(67, '21st Century Literature from the Philippines and the World', 'Grade 11', '2nd semester', 'TOURISM'),
(68, 'Statistics and Probability', 'Grade 11', '2nd semester', 'TOURISM'),
(69, 'Physical Education and Health 2', 'Grade 11', '2nd semester', 'TOURISM'),
(70, 'Practical Research 1', 'Grade 11', '2nd semester', 'TOURISM'),
(71, 'Tourism promotion 2', 'Grade 11', '2nd semester', 'TOURISM'),
(72, 'Oral Communication', 'Grade 11', '1st semester', 'ICT'),
(73, 'Komunikasyon at Pananaliksik sa Wika at Kulturang Pilipino', 'Grade 11', '1st semester', 'ICT'),
(74, 'General Mathematics', 'Grade 11', '1st semester', 'ICT'),
(75, 'Earth and Life Science/Earth Science', 'Grade 11', '1st semester', 'ICT'),
(76, 'Understanding Culture, Society and Politics', 'Grade 11', '1st semester', 'ICT'),
(77, 'Physical Education and Health 1', 'Grade 11', '1st semester', 'ICT'),
(78, 'Personal Development/Pansariling Kaunlaran', 'Grade 11', '1st semester', 'ICT'),
(79, 'Computer Programming 1', 'Grade 11', '1st semester', 'ICT'),
(80, 'Computer Programming 2', 'Grade 11', '1st semester', 'ICT'),
(81, 'Reading and Writing', 'Grade 11', '2nd semester', 'ICT'),
(82, 'Pagbasa at Pagsusuri ng Ibat-ibang Teksto Tungo sa Pananaliksik', 'Grade 11', '2nd semester', 'ICT'),
(83, '21st Century Literature from the Philippines and the World', 'Grade 11', '2nd semester', 'ICT'),
(84, 'Statistics and Probability', 'Grade 11', '2nd semester', 'ICT'),
(85, 'Physical Education and Health 2', 'Grade 11', '2nd semester', 'ICT'),
(86, 'Practical Research 1', 'Grade 11', '2nd semester', 'ICT'),
(87, 'Computer Programming 3', 'Grade 11', '2nd semester', 'ICT'),
(88, 'Computer Programming 4', 'Grade 11', '2nd semester', 'ICT'),
(89, 'Oral Communication', 'Grade 11', '1st semester', 'STEM'),
(90, 'Komunikasyon at Pananaliksik sa Wika at Kulturang Pilipino', 'Grade 11', '1st semester', 'STEM'),
(91, 'General Mathematics', 'Grade 11', '1st semester', 'STEM'),
(92, 'Earth and Life Science/Earth Science', 'Grade 11', '1st semester', 'STEM'),
(93, 'Understanding Culture, Society and Politics', 'Grade 11', '1st semester', 'STEM'),
(94, 'Physical Education and Health 1', 'Grade 11', '1st semester', 'STEM'),
(95, 'Personal Development/Pansariling Kaunlaran', 'Grade 11', '1st semester', 'STEM'),
(96, 'Pre calculus', 'Grade 11', '1st semester', 'STEM'),
(97, 'Basic cal', 'Grade 11', '1st semester', 'STEM'),
(98, 'Reading and Writing', 'Grade 11', '2nd semester', 'STEM'),
(99, 'Pagbasa at Pagsusuri ng Ibat-ibang Teksto Tungo sa Pananaliksik', 'Grade 11', '2nd semester', 'STEM'),
(100, '21st Century Literature from the Philippines and the World', 'Grade 11', '2nd semester', 'STEM'),
(101, 'Statistics and Probability', 'Grade 11', '2nd semester', 'STEM'),
(102, 'Physical Education and Health 2', 'Grade 11', '2nd semester', 'STEM'),
(103, 'Practical Research 1', 'Grade 11', '2nd semester', 'STEM'),
(104, 'Gen chem 1', 'Grade 11', '2nd semester', 'STEM'),
(105, 'Gen chem 2', 'Grade 11', '2nd semester', 'STEM'),
(106, 'Oral Communication', 'Grade 11', '1st semester', 'GAS'),
(107, 'Komunikasyon at Pananaliksik sa Wika at Kulturang Pilipino', 'Grade 11', '1st semester', 'GAS'),
(108, 'General Mathematics', 'Grade 11', '1st semester', 'GAS'),
(109, 'Earth and Life Science/Earth Science', 'Grade 11', '1st semester', 'GAS'),
(110, 'Understanding Culture, Society and Politics', 'Grade 11', '1st semester', 'GAS'),
(111, 'Physical Education and Health 1', 'Grade 11', '1st semester', 'GAS'),
(112, 'Personal Development/Pansariling Kaunlaran', 'Grade 11', '1st semester', 'GAS'),
(113, 'Pre calculus', 'Grade 11', '1st semester', 'GAS'),
(114, 'Organizational Management', 'Grade 11', '1st semester', 'GAS'),
(115, 'Reading and Writing', 'Grade 11', '2nd semester', 'GAS'),
(116, 'Pagbasa at Pagsusuri ng Ibat-ibang Teksto Tungo sa Pananaliksik', 'Grade 11', '2nd semester', 'GAS'),
(117, '21st Century Literature from the Philippines and the World', 'Grade 11', '2nd semester', 'GAS'),
(118, 'Statistics and Probability', 'Grade 11', '2nd semester', 'GAS'),
(119, 'Physical Education and Health 2', 'Grade 11', '2nd semester', 'GAS'),
(120, 'Practical Research 1', 'Grade 11', '2nd semester', 'GAS'),
(121, 'Polgov', 'Grade 11', '2nd semester', 'GAS'),
(122, 'DIASS', 'Grade 11', '2nd semester', 'GAS'),
(123, 'Introduction to the Philosophy of the Human Person', 'Grade 12', '1st semester', 'STEM'),
(124, 'Physical Science', 'Grade 12', '1st semester', 'STEM'),
(125, 'Physical Education and Health 3', 'Grade 12', '1st semester', 'STEM'),
(126, 'Gen bio 1', 'Grade 12', '1st semester', 'STEM'),
(127, 'Gen bio 2', 'Grade 12', '1st semester', 'STEM'),
(128, 'Contemporary Philippines Arts from the Regions', 'Grade 12', '2nd semester', 'STEM'),
(129, 'Physical Education and Health 4', 'Grade 12', '2nd semester', 'STEM'),
(130, 'Media and Information Literacy', 'Grade 12', '2nd semester', 'STEM'),
(131, 'Gen physics 1', 'Grade 12', '2nd semester', 'STEM'),
(132, 'Gen physics 2', 'Grade 12', '2nd semester', 'STEM'),
(133, 'Introduction to the Philosophy of the Human Person', 'Grade 12', '1st semester', 'ABM'),
(134, 'Physical Science', 'Grade 12', '1st semester', 'ABM'),
(135, 'Physical Education and Health 3', 'Grade 12', '1st semester', 'ABM'),
(136, 'Fundamentals of Accountancy Business Management', 'Grade 12', '1st semester', 'ABM'),
(137, 'Business Finance', 'Grade 12', '1st semester', 'ABM'),
(138, 'Contemporary Philippines Arts from the Regions', 'Grade 12', '2nd semester', 'ABM'),
(139, 'Physical Education and Health 4', 'Grade 12', '2nd semester', 'ABM'),
(140, 'Media and Information Literacy', 'Grade 12', '2nd semester', 'ABM'),
(141, 'Business Ethics', 'Grade 12', '2nd semester', 'ABM'),
(142, 'Applied Economics', 'Grade 12', '2nd semester', 'ABM'),
(143, 'Introduction to the Philosophy of the Human Person', 'Grade 12', '1st semester', 'HE'),
(144, 'Physical Science', 'Grade 12', '1st semester', 'HE'),
(145, 'Physical Education and Health 3', 'Grade 12', '1st semester', 'HE'),
(146, 'Food Bevarage Service 1', 'Grade 12', '1st semester', 'HE'),
(147, 'Food Bevarage Service 2', 'Grade 12', '1st semester', 'HE'),
(148, 'Contemporary Philippines Arts from the Regions', 'Grade 12', '2nd semester', 'HE'),
(149, 'Physical Education and Health 4', 'Grade 12', '2nd semester', 'HE'),
(150, 'Media and Information Literacy', 'Grade 12', '2nd semester', 'HE'),
(151, 'BPP 1', 'Grade 12', '2nd semester', 'HE'),
(152, 'BPP 2', 'Grade 12', '2nd semester', 'HE'),
(153, 'Introduction to the Philosophy of the Human Person', 'Grade 12', '1st semester', 'GAS'),
(154, 'Physical Science', 'Grade 12', '1st semester', 'GAS'),
(155, 'Physical Education and Health 3', 'Grade 12', '1st semester', 'GAS'),
(156, 'English for Academic and Professional Purposes ', 'Grade 12', '1st semester', 'GAS'),
(157, 'Disaster Readiness and Risk Reduction', 'Grade 12', '1st semester', 'GAS'),
(158, 'Contemporary Philippines Arts from the Regions', 'Grade 12', '2nd semester', 'GAS'),
(159, 'Physical Education and Health 4', 'Grade 12', '2nd semester', 'GAS'),
(160, 'Media and Information Literacy', 'Grade 12', '2nd semester', 'GAS'),
(161, 'Gen physics 1', 'Grade 12', '2nd semester', 'GAS'),
(162, 'Applied Economics', 'Grade 12', '2nd semester', 'GAS'),
(163, 'Introduction to the Philosophy of the Human Person', 'Grade 12', '1st semester', 'ICT'),
(164, 'Physical Science', 'Grade 12', '1st semester', 'ICT'),
(165, 'Physical Education and Health 3', 'Grade 12', '1st semester', 'ICT'),
(166, 'English for Academic and Professional Purposes', 'Grade 12', '1st semester', 'ICT'),
(167, 'Practical Research 2', 'Grade 12', '1st semester', 'ICT'),
(168, 'Pagsulat sa Filipino sa Larangan (Tekbok)', 'Grade 12', '1st semester', 'ICT'),
(169, 'Entrepreneurship', 'Grade 12', '1st semester', 'ICT'),
(170, 'Animation 1', 'Grade 12', '1st semester', 'ICT'),
(171, 'Animation 2', 'Grade 12', '1st semester', 'ICT'),
(172, 'Contemporary Philippines Arts from the Regions', 'Grade 12', '2nd semester', 'ICT'),
(173, 'Physical Education and Health 4', 'Grade 12', '2nd semester', 'ICT'),
(174, 'Media and Information Literacy', 'Grade 12', '2nd semester', 'ICT'),
(175, 'Inquiries, Investigations and Immersion', 'Grade 12', '2nd semester', 'ICT'),
(176, 'Animation 3', 'Grade 12', '2nd semester', 'ICT'),
(177, 'Animation 4', 'Grade 12', '2nd semester', 'ICT'),
(178, 'Work Immersion', 'Grade 12', '2nd semester', 'ICT'),
(179, 'Introduction to the Philosophy of the Human Person', 'Grade 12', '1st semester', 'HUMSS'),
(180, 'Physical Science', 'Grade 12', '1st semester', 'HUMSS'),
(181, 'Physical Education and Health 3', 'Grade 12', '1st semester', 'HUMSS'),
(182, 'English for Academic and Professional Purposes ', 'Grade 12', '1st semester', 'HUMSS'),
(183, 'Community Engagement and Solidarity', 'Grade 12', '1st semester', 'HUMSS'),
(184, 'Contemporary Philippines Arts from the Regions', 'Grade 12', '2nd semester', 'HUMSS'),
(185, 'Physical Education and Health 4', 'Grade 12', '2nd semester', 'HUMSS'),
(186, 'Media and Information Literacy', 'Grade 12', '2nd semester', 'HUMSS'),
(187, 'Creative Writing Non Fiction', 'Grade 12', '2nd semester', 'HUMSS'),
(188, 'Trend Network and Thinking', 'Grade 12', '2nd semester', 'HUMSS');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `tbl_sections`
--
ALTER TABLE `tbl_sections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `tbl_students`
--
ALTER TABLE `tbl_students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `tbl_subjects`
--
ALTER TABLE `tbl_subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=189;

--
-- AUTO_INCREMENT for table `tbl_teachers`
--
ALTER TABLE `tbl_teachers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

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
