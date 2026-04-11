-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 11, 2026 at 03:58 PM
-- Server version: 8.4.3
-- PHP Version: 8.4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `student_management_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `id` int NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `message` text COLLATE utf8mb4_general_ci NOT NULL,
  `sender_id` int NOT NULL,
  `sender_role` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `target_audience` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`id`, `title`, `message`, `sender_id`, `sender_role`, `target_audience`, `created_at`) VALUES
(1, 'Software Engineering Quiz', 'Your quiz will be taken on Monday 15-03-2026. Be Prepared!', 5, 'teacher', '2', '2026-03-11 20:56:32');

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `attendance_id` int NOT NULL,
  `student_id` int NOT NULL,
  `class_id` int NOT NULL,
  `subject_id` int NOT NULL,
  `teacher_id` int NOT NULL,
  `date` date NOT NULL,
  `status` enum('Present','Absent','Leave') COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`attendance_id`, `student_id`, `class_id`, `subject_id`, `teacher_id`, `date`, `status`) VALUES
(1, 9, 1, 1, 5, '2026-03-03', 'Present'),
(3, 9, 1, 1, 5, '2026-03-08', 'Present'),
(4, 9, 2, 2, 6, '2026-03-09', 'Present'),
(5, 9, 2, 1, 5, '2026-03-10', 'Absent'),
(6, 9, 2, 1, 5, '2026-03-09', 'Present');

-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

CREATE TABLE `classes` (
  `class_id` int NOT NULL,
  `class_name` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `section` enum('A','B','C') COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `class_subject_teacher`
--

CREATE TABLE `class_subject_teacher` (
  `id` int NOT NULL,
  `class_id` int DEFAULT NULL,
  `subject_id` int DEFAULT NULL,
  `teacher_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `class_subject_teacher`
--

INSERT INTO `class_subject_teacher` (`id`, `class_id`, `subject_id`, `teacher_id`) VALUES
(1, 3, 1, 5),
(2, 2, 1, 5),
(3, 3, 1, 5),
(4, 1, 1, 5),
(5, 2, 2, 5),
(6, 3, 2, 5),
(7, 2, 3, 5),
(8, 3, 3, 5),
(9, 2, 2, 6);

-- --------------------------------------------------------

--
-- Table structure for table `fee_payments`
--

CREATE TABLE `fee_payments` (
  `payment_id` int NOT NULL,
  `student_id` int NOT NULL,
  `fee_type_id` int NOT NULL,
  `amount_paid` decimal(10,2) NOT NULL,
  `payment_date` date NOT NULL,
  `status` enum('Paid','Unpaid','Partial') DEFAULT 'Unpaid',
  `remarks` varchar(255) DEFAULT NULL,
  `recorded_by` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `fee_payments`
--

INSERT INTO `fee_payments` (`payment_id`, `student_id`, `fee_type_id`, `amount_paid`, `payment_date`, `status`, `remarks`, `recorded_by`, `created_at`) VALUES
(1, 9, 1, 2000.00, '2026-04-02', 'Paid', '', 3, '2026-04-08 18:04:58');

-- --------------------------------------------------------

--
-- Table structure for table `fee_types`
--

CREATE TABLE `fee_types` (
  `fee_type_id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `class_id` int DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `fee_types`
--

INSERT INTO `fee_types` (`fee_type_id`, `name`, `amount`, `class_id`, `due_date`, `created_at`) VALUES
(1, 'Examination Fee', 2000.00, 2, '2026-04-06', '2026-04-08 17:53:19');

-- --------------------------------------------------------

--
-- Table structure for table `marks`
--

CREATE TABLE `marks` (
  `marks_id` int NOT NULL,
  `marks` int DEFAULT NULL,
  `subject_id` int DEFAULT NULL,
  `student_id` int DEFAULT NULL,
  `total_marks` int DEFAULT '100',
  `exam_type` enum('Quiz','Mid','Final','Assignment') COLLATE utf8mb4_general_ci NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `marks`
--

INSERT INTO `marks` (`marks_id`, `marks`, `subject_id`, `student_id`, `total_marks`, `exam_type`, `date`) VALUES
(1, 23, 1, 9, 100, 'Quiz', '2026-03-17'),
(2, 14, 1, 9, 15, 'Quiz', '2026-03-19');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `student_id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `Roll_no` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `class_id` int DEFAULT NULL,
  `gender` enum('Male','Female','Other') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_general_ci,
  `Profile_Image` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- --------------------------------------------------------

--
-- Table structure for table `subject`
--

CREATE TABLE `subject` (
  `subject_id` int NOT NULL,
  `subject_name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `code` varchar(25) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `type` enum('core','elective') COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subject`
--

INSERT INTO `subject` (`subject_id`, `subject_name`, `code`, `type`) VALUES
(1, 'Applied Physics', 'AP-01', 'core'),
(2, 'Mathematics', 'MATH-02', 'elective'),
(3, 'English', 'ENG-04', 'elective'),
(4, 'Science', 'SCI-06', 'core');

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `subject_id` int NOT NULL,
  `subject_name` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `class_id` int DEFAULT NULL,
  `teacher_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `teachers`
--

CREATE TABLE `teachers` (
  `teacher_id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `qualification` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int NOT NULL,
  `Name` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Email` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Password` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Role` enum('admin','teacher','student') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Status` enum('Approved','Pending','Rejected') COLLATE utf8mb4_general_ci DEFAULT 'Pending',
  `profile_status` tinyint(1) DEFAULT '0',
  `is_first_login` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `Name`, `Email`, `Password`, `Role`, `Status`, `profile_status`, `is_first_login`) VALUES

(3, 'Admin', 'Admin@gmail.com', '$2y$10$97w079wHtcNEQFhwPgxbduwlvVkVWlWVYm19KY9MsFfB1k/oIy3Tu', 'admin', 'Approved', 0, NULL);


--
-- Indexes for dumped tables
--

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`attendance_id`),
  ADD UNIQUE KEY `student_id` (`student_id`,`subject_id`,`date`),
  ADD KEY `teacher_id` (`teacher_id`),
  ADD KEY `class_id` (`class_id`),
  ADD KEY `subject_id` (`subject_id`);

--
-- Indexes for table `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`class_id`);

--
-- Indexes for table `class_subject_teacher`
--
ALTER TABLE `class_subject_teacher`
  ADD PRIMARY KEY (`id`),
  ADD KEY `class_id` (`class_id`),
  ADD KEY `subject_id` (`subject_id`),
  ADD KEY `teacher_id` (`teacher_id`);

--
-- Indexes for table `fee_payments`
--
ALTER TABLE `fee_payments`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `fee_type_id` (`fee_type_id`),
  ADD KEY `recorded_by` (`recorded_by`);

--
-- Indexes for table `fee_types`
--
ALTER TABLE `fee_types`
  ADD PRIMARY KEY (`fee_type_id`),
  ADD KEY `class_id` (`class_id`);

--
-- Indexes for table `marks`
--
ALTER TABLE `marks`
  ADD PRIMARY KEY (`marks_id`),
  ADD KEY `subject_id` (`subject_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`student_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `subject`
--
ALTER TABLE `subject`
  ADD PRIMARY KEY (`subject_id`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`subject_id`),
  ADD KEY `teacher_id` (`teacher_id`),
  ADD KEY `class_id` (`class_id`);

--
-- Indexes for table `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`teacher_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `attendance_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `classes`
--
ALTER TABLE `classes`
  MODIFY `class_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `class_subject_teacher`
--
ALTER TABLE `class_subject_teacher`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `fee_payments`
--
ALTER TABLE `fee_payments`
  MODIFY `payment_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `fee_types`
--
ALTER TABLE `fee_types`
  MODIFY `fee_type_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `marks`
--
ALTER TABLE `marks`
  MODIFY `marks_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `student_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `subject`
--
ALTER TABLE `subject`
  MODIFY `subject_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `subject_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `teachers`
--
ALTER TABLE `teachers`
  MODIFY `teacher_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`),
  ADD CONSTRAINT `attendance_ibfk_2` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `attendance_ibfk_3` FOREIGN KEY (`class_id`) REFERENCES `classes` (`class_id`),
  ADD CONSTRAINT `attendance_ibfk_4` FOREIGN KEY (`subject_id`) REFERENCES `subject` (`subject_id`);

--
-- Constraints for table `class_subject_teacher`
--
ALTER TABLE `class_subject_teacher`
  ADD CONSTRAINT `class_subject_teacher_ibfk_1` FOREIGN KEY (`class_id`) REFERENCES `classes` (`class_id`),
  ADD CONSTRAINT `class_subject_teacher_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `subject` (`subject_id`),
  ADD CONSTRAINT `class_subject_teacher_ibfk_3` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `fee_payments`
--
ALTER TABLE `fee_payments`
  ADD CONSTRAINT `fee_payments_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`),
  ADD CONSTRAINT `fee_payments_ibfk_2` FOREIGN KEY (`fee_type_id`) REFERENCES `fee_types` (`fee_type_id`),
  ADD CONSTRAINT `fee_payments_ibfk_3` FOREIGN KEY (`recorded_by`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `fee_types`
--
ALTER TABLE `fee_types`
  ADD CONSTRAINT `fee_types_ibfk_1` FOREIGN KEY (`class_id`) REFERENCES `classes` (`class_id`);

--
-- Constraints for table `marks`
--
ALTER TABLE `marks`
  ADD CONSTRAINT `marks_ibfk_1` FOREIGN KEY (`subject_id`) REFERENCES `subject` (`subject_id`),
  ADD CONSTRAINT `marks_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`);

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `subjects`
--
ALTER TABLE `subjects`
  ADD CONSTRAINT `subjects_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`teacher_id`),
  ADD CONSTRAINT `subjects_ibfk_2` FOREIGN KEY (`class_id`) REFERENCES `classes` (`class_id`);

--
-- Constraints for table `teachers`
--
ALTER TABLE `teachers`
  ADD CONSTRAINT `teachers_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
