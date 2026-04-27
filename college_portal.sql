-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 27, 2026 at 05:00 PM
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
-- Database: `college_portal`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`) VALUES
(3, 'admin', '$2y$10$vSmz5B1V7wfSWyJR341L/eqYhaRWqVA70lf6NNr6568uBoucGNVWa');

-- --------------------------------------------------------

--
-- Table structure for table `admissions`
--

CREATE TABLE `admissions` (
  `id` int(11) NOT NULL,
  `application_no` varchar(20) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `parent_name` varchar(100) DEFAULT NULL,
  `department` varchar(50) DEFAULT NULL,
  `course_type` varchar(10) DEFAULT NULL,
  `community` varchar(20) DEFAULT NULL,
  `tenth_mark` varchar(10) DEFAULT NULL,
  `twelfth_mark` varchar(10) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `tenth_file` varchar(255) DEFAULT NULL,
  `twelfth_file` varchar(255) DEFAULT NULL,
  `community_file` varchar(255) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `message` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admissions`
--

INSERT INTO `admissions` (`id`, `application_no`, `name`, `dob`, `gender`, `phone`, `email`, `address`, `parent_name`, `department`, `course_type`, `community`, `tenth_mark`, `twelfth_mark`, `photo`, `tenth_file`, `twelfth_file`, `community_file`, `status`, `created_at`, `message`) VALUES
(12, 'APP26023', 'Sathish M', '2222-02-12', 'Male', '1234567', 'sathish@gmail.com', 'sdfgh', 'xcv', 'B.Sc Physics', 'UG', 'BC', '333', '444', '1773944536_WIN_20260108_17_36_32_Pro.jpg', '1773944536_Screenshot 2026-03-04 201700.png', '1773944536_Screenshot 2026-02-03 182919.png', '1773944536_Screenshot 2026-03-04 201700.png', 'pending', '2026-03-19 18:22:16', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `id` int(11) NOT NULL,
  `title` varchar(200) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`id`, `title`, `message`, `created_at`) VALUES
(5, 'leave announcement for student', '25.11.2026', '2026-02-28 14:42:30');

-- --------------------------------------------------------

--
-- Table structure for table `approve_students`
--

CREATE TABLE `approve_students` (
  `id` int(11) NOT NULL,
  `roll_no` varchar(50) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `department` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `photo` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `subject` varchar(100) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`id`, `student_id`, `staff_id`, `subject`, `date`, `status`) VALUES
(13, 3, 4, 'Java', '2026-03-03', 'Present'),
(20, 6, 8, 'C++', '2026-03-08', 'Absent'),
(21, 5, 8, 'C++', '2026-03-08', 'Absent');

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` int(11) NOT NULL,
  `dept_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `dept_name`) VALUES
(1, 'B.Sc Computer Science'),
(2, 'BCA'),
(3, 'B.Com'),
(4, 'BBA'),
(5, 'B.Sc Mathematics'),
(6, 'B.Sc Physics'),
(7, 'BA.Tamil'),
(8, 'BA.English');

-- --------------------------------------------------------

--
-- Table structure for table `exam_schedule`
--

CREATE TABLE `exam_schedule` (
  `id` int(11) NOT NULL,
  `department` varchar(100) DEFAULT NULL,
  `subject` varchar(100) DEFAULT NULL,
  `exam_date` date DEFAULT NULL,
  `exam_time` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `exam_schedule`
--

INSERT INTO `exam_schedule` (`id`, `department`, `subject`, `exam_date`, `exam_time`) VALUES
(1, 'BCA', 'Python', '2026-12-29', '10.00 AM-1.00 PM'),
(2, 'BCA', 'C++', '2026-11-12', '10.00 AM-1.00 PM'),
(3, 'BCA', 'Java', '2026-11-13', '10.00 AM-1.00 PM'),
(4, 'BCA', 'Lab', '2026-11-14', '10.00 AM-1.00 PM'),
(5, 'B.Sc Mathematics', 'Arranging maths', '2026-11-11', '10.00 AM-1.00 PM'),
(6, 'B.Sc Mathematics', 'Java Lab', '2026-11-12', '10.00 AM-1.00 PM'),
(7, 'B.Sc Mathematics', 'Maths Tricks', '2026-01-13', '10.00 AM-1.00 PM'),
(8, 'B.Sc Mathematics', 'operations', '2026-10-11', '10.00 AM-1.00 PM'),
(9, 'B.Sc Mathematics', 'R Program', '2026-11-16', '10.00 AM-1.00 PM'),
(10, 'B.Sc Mathematics', 'Statistic', '2026-11-17', '10.00 AM-1.00 PM');

-- --------------------------------------------------------

--
-- Table structure for table `fees`
--

CREATE TABLE `fees` (
  `id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `total_fees` int(11) DEFAULT NULL,
  `paid_fees` int(11) DEFAULT NULL,
  `due_fees` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fees`
--

INSERT INTO `fees` (`id`, `student_id`, `total_fees`, `paid_fees`, `due_fees`) VALUES
(26, 3, 1000, 1000, 0),
(27, 9, 1000, 0, 1000),
(28, 13, 2000, 0, 2000),
(29, 6, 20000, 20000, 0),
(30, 5, 10, 0, 10);

-- --------------------------------------------------------

--
-- Table structure for table `halltickets`
--

CREATE TABLE `halltickets` (
  `id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `exam_name` varchar(150) DEFAULT NULL,
  `exam_date` date DEFAULT NULL,
  `hall_no` varchar(50) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_cleared` tinyint(1) DEFAULT 0,
  `roll_no` varchar(50) DEFAULT NULL,
  `department` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `halltickets`
--

INSERT INTO `halltickets` (`id`, `student_id`, `exam_name`, `exam_date`, `hall_no`, `file_path`, `created_at`, `is_cleared`, `roll_no`, `department`) VALUES
(1, 5, NULL, NULL, NULL, 'hallticket_23UCA144.pdf', '2026-03-11 16:10:13', 0, '23UCA144', 'BCA'),
(2, 6, NULL, NULL, NULL, 'hallticket_23UCA143.pdf', '2026-03-11 16:10:15', 0, '23UCA143', 'BCA'),
(3, 7, NULL, NULL, NULL, 'hallticket_23UBCOM120.pdf', '2026-03-11 16:10:16', 0, '23UBCOM120', 'B.Com'),
(4, 8, NULL, NULL, NULL, 'hallticket_23UBCOM121.pdf', '2026-03-11 16:10:18', 0, '23UBCOM121', 'B.Com'),
(5, 9, NULL, NULL, NULL, 'hallticket_23UCS101.pdf', '2026-03-11 16:10:20', 0, '23UCS101', 'B.Sc Computer Science'),
(6, 10, NULL, NULL, NULL, 'hallticket_23UCS102.pdf', '2026-03-11 16:10:21', 0, '23UCS102', 'B.Sc Computer Science'),
(7, 11, NULL, NULL, NULL, 'hallticket_23UMA110.pdf', '2026-03-11 16:10:22', 0, '23UMA110', 'B.Sc Mathematics'),
(8, 12, NULL, NULL, NULL, 'hallticket_23UMA111.pdf', '2026-03-11 16:10:24', 0, '23UMA111', 'B.Sc Mathematics'),
(9, 13, NULL, NULL, NULL, 'hallticket_23UPY201.pdf', '2026-03-11 16:10:29', 0, '23UPY201', 'B.Sc Physics'),
(10, 14, NULL, NULL, NULL, 'hallticket_23UPY202.pdf', '2026-03-11 16:10:30', 0, '23UPY202', 'B.Sc Physics'),
(11, 16, NULL, NULL, NULL, 'hallticket_23UBBA130.pdf', '2026-03-11 16:10:35', 0, '23UBBA130', 'BBA'),
(12, 17, NULL, NULL, NULL, 'hallticket_23UBBA131.pdf', '2026-03-11 16:10:37', 0, '23UBBA131', 'BBA');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `transaction_id` varchar(100) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'Success',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `admin_confirmed` tinyint(1) DEFAULT 0,
  `pay_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `student_id`, `amount`, `transaction_id`, `status`, `created_at`, `admin_confirmed`, `pay_date`) VALUES
(15, 6, 20000.00, '7708691940', 'Success', '2026-03-11 15:19:54', 1, '2026-03-11 20:49:54');

-- --------------------------------------------------------

--
-- Table structure for table `results`
--

CREATE TABLE `results` (
  `id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `subject` varchar(100) DEFAULT NULL,
  `marks` int(11) DEFAULT NULL,
  `grade` varchar(10) DEFAULT NULL,
  `exam_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `results`
--

INSERT INTO `results` (`id`, `student_id`, `subject`, `marks`, `grade`, `exam_date`, `created_at`) VALUES
(2, 3, 'C++', 50, 'Pass', '2026-11-12', '2026-03-03 13:25:18');

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `subject` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`id`, `name`, `email`, `subject`, `password`, `photo`) VALUES
(4, 'Kumar', '123@gmail.com', 'Java', '$2y$10$Oi.xX5xByNYbr21YJ5HfeuvlZyxP04JOVsI/DDAKgWQn6hnzlFip.', '1772114751.png'),
(5, 'Anand', 'Anand@gmail.com', 'Physics', '$2y$10$CMkRtq.v2ELIC3e0a1oLZuNiwSH9YX/17D6ryxUIx6i6eBH15RBTe', '1772971553734.jpg'),
(6, 'Ponmani', 'ponmani@gmail.com', 'DataScience', '$2y$10$gktWTk5GtokGeUl7guNfIOn3YbkLSZWHfXoHtqV99Q1dTNusqxMqm', '1772971748746.jpg'),
(7, 'Suresh', 'suresh@gmail.com', 'Accounts', '$2y$10$iwmowy.nt1FkYMexD7U5/.OW1w3Buzcraxg1mO9gtJCEv3s/3f2gK', '1772971924115.jpg'),
(8, 'Bharani', 'bharani@gmail.com', 'Mathematics operations', '$2y$10$vq69PsjZONtKkmtbfnzKXO5rQz24YESK3DtCEfi6PPjWwE4WgNlDC', '1772972184303.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `roll_no` varchar(50) DEFAULT NULL,
  `department` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `name`, `email`, `roll_no`, `department`, `password`, `photo`) VALUES
(5, 'Senthil M', '23uca144@mgsonline.org', '23UCA144', 'BCA', '$2y$10$Og5tY8APTf/zsV0Ik3/mAejHK.0EQI3Lr0lEaAg8/cTKiDgNY3ii2', '1772721958494.jpg'),
(6, 'Sathish M', '23uca143@mgsonline.org', '23UCA143', 'BCA', '$2y$10$fyfzAkTH1O.tIKX39SZfVu8BR739Qolhmqj7GM.bSlLSsbbr5M.jq', '1772968628442.jpg'),
(7, 'Hari', '23UBCOM120@mgsonline.org', '23UBCOM120', 'B.Com', '$2y$10$JinxIUQ1eDB1ML47JIO.I.OBA0k6zjTVagNpyt/LpgBMFs.rfb1iy', '1772969003472.jpg'),
(8, 'Arjun', '23UBCOM121@mgsonline.org', '23UBCOM121', 'B.Com', '$2y$10$ghLZSZIMNBBERADkC.miqOO/ZzU03YdMVL2LvRbH.jb7EtuWt6bZK', '1772969441741.jpg'),
(9, 'Alagu', '23UCS101@mgsonline.org', '23UCS101', 'B.Sc Computer Science', '$2y$10$sLDlvV4Llxo8FWYH/IY4W.ZlHAiGMRZ5xCSCycLNrdXhcFftEIt8W', '1772969695560.jpg'),
(10, 'Sanjay', '23UCS102@mgsonline.org', '23UCS102', 'B.Sc Computer Science', '$2y$10$lyTDNZbs/YaGu8Zucn9BL.FPQVIpC.4h8nUJbVXwCLrCYuWt/OkiO', '1772969792758.jpg'),
(11, 'Pandi', '23UMA110@msgonline.org', '23UMA110', 'B.Sc Mathematics', '$2y$10$U90GdufmGPliw34yoyeAyOK0csebk3d0YO8xlgmZheM/oWYPOCaKa', '1772970134366.jpg'),
(12, 'Guru', '23UMA111@msgonline.org', '23UMA111', 'B.Sc Mathematics', '$2y$10$oZMuzmMhqwRNASCLxDVvcONnEjw0db.ZgTKuymLYmV6AMoTuVf.fy', '1772970247496.jpg'),
(13, 'Ayyappan', '23UPY201@mgsonline.org', '23UPY201', 'B.Sc Physics', '$2y$10$zHvcUR3hOt9JIsLTwnXO4OEsIq8DbjzLjeIdqsiqDq52E1a/k9Q2e', '1772970612383.jpg'),
(14, 'Raja', '23UPY202@mgsonline.org', '23UPY202', 'B.Sc Physics', '$2y$10$fMCYhYirdJ1EWnj3Ea/eXuc1Betntp/xtiHuEeIDEvkjJuN0IFL7C', '1772970846657.jpg'),
(16, 'Jeeva', '23UBBA130@mgsonline.org', '23UBBA130', 'BBA', '$2y$10$yYTuS1qKo4bjso2laDEJwuDBUTNxKTCX3h6GPEI8pYU4tO0x7b1wS', '1772971118456.jpg'),
(17, 'Joshuva', '23UBBA131@mgsonline.org', '23UBBA131', 'BBA', '$2y$10$1VH5iDApm3AcZvldeOmFOORRNkRm9IxhDrlZ24lMqH5rFcRYtAsy.', '1772971223263.jpg'),
(18, 'Kutty', '23UBAT121@mgsonline.org', '23UBAT121', 'BA.Tamil', '$2y$10$jrv7VM4zS6FRoRuaLFN21.x7joxG8d3mCw.FRnH04i/u8HsFRKEiS', '1774031803898.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `timetable`
--

CREATE TABLE `timetable` (
  `id` int(11) NOT NULL,
  `department` varchar(50) DEFAULT NULL,
  `day` varchar(20) DEFAULT NULL,
  `subject` varchar(100) DEFAULT NULL,
  `time_slot` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `timetable`
--

INSERT INTO `timetable` (`id`, `department`, `day`, `subject`, `time_slot`) VALUES
(9, 'B.Sc Computer Science', 'Monday', 'DataScience', '12.00-1.00'),
(10, 'B.Sc Computer Science', 'Tuesday', 'DataScience', '10.00-11.00'),
(11, 'B.Sc Computer Science', 'Tuesday', 'Computer Network', '11.00-12.00'),
(12, 'B.Sc Computer Science', 'Tuesday', 'Java', '12.00-1.00'),
(13, 'BCA', 'Monday', 'C++', '10.00-11.00'),
(15, 'BCA', 'Monday', 'Java', '12.00-1.00'),
(16, 'BCA', 'Tuesday', 'Lab', '10.00-11.00'),
(17, 'BCA', 'Tuesday', 'c++', '11.00-12.00'),
(18, 'BCA', 'Tuesday', 'Python', '12.00-1.00'),
(19, 'B.Sc Mathematics', 'Monday', 'Statistic', '10.00-11.00'),
(20, 'B.Sc Mathematics', 'Monday', 'operations', '11.00-12.00'),
(21, 'B.Sc Mathematics', 'Monday', 'Arranging maths', '12.00-1.00'),
(22, 'B.Sc Mathematics', 'Tuesday', 'Maths Tricks', '10.00-11.00'),
(23, 'B.Sc Mathematics', 'Tuesday', 'R Program', '11.00-12.00'),
(24, 'B.Sc Mathematics', 'Tuesday', 'Java Lab', '12.00-1.00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `admissions`
--
ALTER TABLE `admissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `approve_students`
--
ALTER TABLE `approve_students`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `exam_schedule`
--
ALTER TABLE `exam_schedule`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fees`
--
ALTER TABLE `fees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `halltickets`
--
ALTER TABLE `halltickets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `results`
--
ALTER TABLE `results`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `timetable`
--
ALTER TABLE `timetable`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `admissions`
--
ALTER TABLE `admissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `approve_students`
--
ALTER TABLE `approve_students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `exam_schedule`
--
ALTER TABLE `exam_schedule`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `fees`
--
ALTER TABLE `fees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `halltickets`
--
ALTER TABLE `halltickets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `results`
--
ALTER TABLE `results`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `timetable`
--
ALTER TABLE `timetable`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
