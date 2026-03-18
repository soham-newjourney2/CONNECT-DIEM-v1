-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 26, 2026 at 07:25 AM
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
-- Database: `connect_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `admin_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('superadmin','moderator','staff') DEFAULT 'staff',
  `profile_pic` varchar(255) DEFAULT 'default_admin.png',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`admin_id`, `username`, `email`, `password`, `role`, `profile_pic`, `created_at`) VALUES
(1, 'superadmin', 'admin1@connect.com', 'admin123', 'superadmin', 'default_admin.png', '2025-10-21 18:32:46'),
(2, 'moderator1', 'admin2@connect.com', 'admin123', 'moderator', 'default_admin.png', '2025-10-21 18:32:46'),
(3, 'staff1', 'admin3@connect.com', 'admin123', 'staff', 'default_admin.png', '2025-10-21 18:32:46'),
(4, 'staff2', 'admin4@connect.com', 'admin123', 'staff', 'default_admin.png', '2025-10-21 18:32:46'),
(5, 'moderator2', 'admin5@connect.com', 'admin123', 'moderator', 'default_admin.png', '2025-10-21 18:32:46'),
(6, 'staff3', 'admin6@connect.com', 'admin123', 'staff', 'default_admin.png', '2025-10-21 18:32:46'),
(7, 'staff4', 'admin7@connect.com', 'admin123', 'staff', 'default_admin.png', '2025-10-21 18:32:46'),
(8, 'superadmin2', 'admin8@connect.com', 'admin123', 'superadmin', 'default_admin.png', '2025-10-21 18:32:46'),
(9, 'staff5', 'admin9@connect.com', 'admin123', 'staff', 'default_admin.png', '2025-10-21 18:32:46'),
(10, 'moderator3', 'admin10@connect.com', 'admin123', 'moderator', 'default_admin.png', '2025-10-21 18:32:46'),
(11, 'admin1@connect.com', 'admin5@gmail.com', '$2y$10$74.Myc6VXxU0WZBdwAkw1eRycAXfe3pND.uYzYSfGVOTYSycCSTlK', 'superadmin', 'default_admin.png', '2025-10-24 13:42:33');

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `attendance_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `faculty_id` int(11) NOT NULL,
  `subject` varchar(100) DEFAULT NULL,
  `attendance_date` date DEFAULT NULL,
  `status` enum('Present','Absent','Late') DEFAULT 'Present',
  `remarks` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`attendance_id`, `student_id`, `faculty_id`, `subject`, `attendance_date`, `status`, `remarks`) VALUES
(1, 1, 1, 'Data Structures', '2025-10-01', 'Present', ''),
(2, 2, 1, 'Data Structures', '2025-10-01', 'Absent', 'Sick'),
(3, 3, 2, 'Signals', '2025-10-02', 'Present', ''),
(4, 4, 3, 'Thermodynamics', '2025-10-03', 'Late', 'Missed bus'),
(5, 5, 1, 'Algorithms', '2025-10-04', 'Present', ''),
(6, 6, 1, 'Algorithms', '2025-10-04', 'Present', ''),
(7, 7, 2, 'Electronics', '2025-10-05', 'Absent', 'Family function'),
(8, 8, 3, 'Mechanics', '2025-10-06', 'Present', ''),
(10, 10, 1, 'OS', '2025-10-07', 'Present', ''),
(11, 1, 1, 'Data Structures', '2025-10-20', 'Present', ''),
(12, 2, 1, 'Data Structures', '2025-10-20', 'Absent', 'Sick'),
(13, 5, 1, 'Algorithms', '2025-10-21', 'Present', ''),
(14, 6, 1, 'Algorithms', '2025-10-21', 'Late', 'Late by 10 mins'),
(16, 10, 1, 'OS', '2025-10-22', 'Absent', 'Family emergency'),
(17, 3, 2, 'Signals', '2025-10-20', 'Present', ''),
(18, 7, 2, 'Electronics', '2025-10-21', 'Absent', 'Family function'),
(19, 4, 3, 'Thermodynamics', '2025-10-20', 'Late', 'Traffic'),
(20, 8, 3, 'Mechanics', '2025-10-21', 'Present', ''),
(21, 1, 1, 'Algorithms', '2025-10-23', 'Present', ''),
(22, 2, 1, 'OS', '2025-10-23', 'Late', 'Came late'),
(23, 3, 2, 'Signals', '2025-10-23', 'Absent', 'Sick'),
(24, 4, 3, 'Thermodynamics', '2025-10-23', 'Present', ''),
(25, 5, 1, 'OS', '2025-10-24', 'Late', 'Traffic'),
(26, 6, 1, 'Data Structures', '2025-10-24', 'Present', ''),
(27, 7, 2, 'Electronics', '2025-10-24', 'Present', ''),
(28, 8, 3, 'Mechanics', '2025-10-24', 'Absent', 'Family emergency'),
(30, 10, 1, 'Data Structures', '2025-10-24', 'Absent', 'Medical'),
(31, 1, 1, 'Data Structures', '2025-10-21', 'Present', ''),
(32, 2, 1, 'Data Structures', '2025-10-21', 'Present', ''),
(34, 5, 1, 'Chemistry', '2025-10-27', 'Present', ''),
(35, 3, 1, 'Chemistry', '2025-10-27', 'Present', ''),
(36, 7, 1, 'Chemistry', '2025-10-27', 'Present', ''),
(37, 10, 1, 'Chemistry', '2025-10-27', 'Present', ''),
(38, 4, 1, 'Chemistry', '2025-10-27', 'Present', ''),
(39, 1, 1, 'Chemistry', '2025-10-27', 'Late', 'Sick'),
(40, 6, 1, 'Chemistry', '2025-10-27', 'Present', ''),
(41, 8, 1, 'Chemistry', '2025-10-27', 'Present', ''),
(42, 2, 1, 'Chemistry', '2025-10-27', 'Present', ''),
(43, 5, 1, 'Python', '2025-10-31', 'Present', ''),
(44, 3, 1, 'Python', '2025-10-31', 'Present', ''),
(45, 7, 1, 'Python', '2025-10-31', 'Present', ''),
(46, 10, 1, 'Python', '2025-10-31', 'Late', ''),
(47, 4, 1, 'Python', '2025-10-31', 'Present', ''),
(48, 1, 1, 'Python', '2025-10-31', 'Absent', 'Uninformed'),
(49, 6, 1, 'Python', '2025-10-31', 'Present', ''),
(50, 8, 1, 'Python', '2025-10-31', 'Present', ''),
(51, 2, 1, 'Python', '2025-10-31', 'Present', ''),
(52, 11, 1, 'Python', '2025-10-31', 'Present', '');

-- --------------------------------------------------------

--
-- Table structure for table `collaboration`
--

CREATE TABLE `collaboration` (
  `post_id` int(11) NOT NULL,
  `author_type` enum('student','faculty') NOT NULL,
  `author_id` int(11) NOT NULL,
  `title` varchar(200) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `attachment` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `collaboration`
--

INSERT INTO `collaboration` (`post_id`, `author_type`, `author_id`, `title`, `content`, `attachment`, `created_at`) VALUES
(2, 'student', 2, 'Signals Discussion', 'Discussing Signals & Systems problems.', NULL, '2025-10-21 18:32:47'),
(4, 'student', 3, 'Robotics Ideas', 'Sharing Robotics project ideas.', NULL, '2025-10-21 18:32:47'),
(5, 'faculty', 2, 'Electronics Help', 'Q&A for electronics students.', NULL, '2025-10-21 18:32:47'),
(6, 'student', 4, 'Thermodynamics Help', 'Post your questions here.', NULL, '2025-10-21 18:32:47'),
(7, 'student', 5, 'OS Study Group', 'Join OS group discussion.', NULL, '2025-10-21 18:32:47'),
(8, 'faculty', 3, 'Mechanics Insights', 'Key concepts for mechanics.', NULL, '2025-10-21 18:32:47'),
(9, 'student', 6, 'AI Discussion', 'AI basics and projects.', NULL, '2025-10-21 18:32:47'),
(10, 'faculty', 1, 'Coding Challenges', 'Weekly coding challenges.', NULL, '2025-10-21 18:32:47');

-- --------------------------------------------------------

--
-- Table structure for table `doubts`
--

CREATE TABLE `doubts` (
  `doubt_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `faculty_id` int(11) DEFAULT NULL,
  `subject` varchar(100) DEFAULT NULL,
  `question` text NOT NULL,
  `answer` text DEFAULT NULL,
  `status` enum('Open','Answered','Closed') DEFAULT 'Open',
  `created_at` datetime DEFAULT current_timestamp(),
  `answered_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doubts`
--

INSERT INTO `doubts` (`doubt_id`, `student_id`, `faculty_id`, `subject`, `question`, `answer`, `status`, `created_at`, `answered_at`) VALUES
(1, 1, 1, 'Data Structures', 'What is the difference between array and linked list?', NULL, 'Open', '2025-10-21 18:32:47', NULL),
(2, 2, 1, 'Data Structures', 'Explain stack vs queue.', NULL, 'Answered', '2025-10-21 18:32:47', NULL),
(3, 3, 2, 'Signals', 'Fourier transform doubts', NULL, 'Open', '2025-10-21 18:32:47', NULL),
(4, 4, 3, 'Thermodynamics', 'Second law explanation', NULL, 'Closed', '2025-10-21 18:32:47', NULL),
(5, 5, 1, 'Algorithms', 'Greedy vs DP', NULL, 'Open', '2025-10-21 18:32:47', NULL),
(6, 6, 1, 'Algorithms', 'Recursion doubts', NULL, 'Answered', '2025-10-21 18:32:47', NULL),
(7, 7, 2, 'Electronics', 'Op-amp questions', NULL, 'Open', '2025-10-21 18:32:47', NULL),
(8, 8, 3, 'Mechanics', 'Newton law example', NULL, 'Closed', '2025-10-21 18:32:47', NULL),
(10, 10, 1, 'OS', 'Deadlock handling', NULL, 'Answered', '2025-10-21 18:32:47', NULL),
(11, 1, 1, 'Python', 'Explain me steps to learn backend using pthon', 'uq', 'Answered', '2025-10-22 00:02:05', NULL),
(12, 1, 1, 'Chemistry', 'What is Thermodynamics', 'ans', 'Answered', '2025-10-22 21:05:30', NULL),
(13, 1, NULL, 'math', 'limits', NULL, 'Open', '2025-10-23 13:34:39', NULL),
(14, 1, NULL, 'Python', 'can you please tell me about oops', NULL, 'Open', '2025-10-24 12:52:01', NULL),
(15, 1, 1, 'Chemistry', 'Can you please explain me about NERNST equation', 'Ans\r\n', 'Answered', '2025-10-24 13:00:43', NULL),
(16, 1, 1, 'Python', 'I want to know about exception handeling', 'xgffghfdghhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhh', 'Answered', '2025-10-24 23:42:41', NULL),
(17, 1, 1, 'Python', 'What is List', 'ttttt', 'Answered', '2025-11-14 15:03:48', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `event_id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `department` varchar(100) DEFAULT NULL,
  `event_date` date DEFAULT NULL,
  `event_time` time DEFAULT NULL,
  `venue` varchar(150) DEFAULT NULL,
  `organizer` varchar(100) DEFAULT NULL,
  `banner_image` varchar(255) DEFAULT NULL,
  `qr_code` varchar(255) DEFAULT NULL,
  `feedback_form_link` varchar(255) DEFAULT NULL,
  `created_by` enum('admin','faculty') DEFAULT 'admin',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`event_id`, `title`, `description`, `department`, `event_date`, `event_time`, `venue`, `organizer`, `banner_image`, `qr_code`, `feedback_form_link`, `created_by`, `created_at`) VALUES
(2, 'Robotics Workshop', 'Hands-on Robotics', 'ECE', '2025-10-15', '11:00:00', 'Lab 2', 'Dr. S. Mehta', NULL, NULL, NULL, 'admin', '2025-10-21 18:32:47'),
(4, 'Mechanical Expo', 'ME Innovations', 'ME', '2025-10-20', '09:30:00', 'ME Lab', 'Dr. R. Das', NULL, NULL, NULL, 'admin', '2025-10-21 18:32:47'),
(5, '', NULL, NULL, NULL, NULL, NULL, NULL, '', '', NULL, 'admin', '2025-10-21 18:32:47'),
(6, 'Cybersecurity Seminar', 'Network Security', 'IT', '2025-10-25', '15:00:00', 'Lab 5', 'Dr. A. Singh', NULL, NULL, NULL, 'admin', '2025-10-21 18:32:47'),
(7, 'Entrepreneurship Talk', 'Startup Strategies', 'All', '2025-11-18', '13:00:00', 'Conference Hall', 'Admin', NULL, NULL, NULL, 'admin', '2025-10-21 18:32:47'),
(8, 'Research Symposium', 'Recent Research', 'CSE', '2025-11-22', '10:30:00', 'Auditorium', 'Dr. P. Sen', NULL, NULL, NULL, 'admin', '2025-10-21 18:32:47'),
(10, 'Renewable Energy Seminar', 'Sustainable Energy', 'ME', '2025-11-30', '09:00:00', 'ME Lab', 'Dr. N. Chatterjee', NULL, NULL, NULL, 'admin', '2025-10-21 18:32:47');

-- --------------------------------------------------------

--
-- Table structure for table `event_registrations`
--

CREATE TABLE `event_registrations` (
  `registration_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `check_in_status` enum('Pending','Checked-in') DEFAULT 'Pending',
  `check_in_time` datetime DEFAULT NULL,
  `feedback_submitted` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `event_registrations`
--

INSERT INTO `event_registrations` (`registration_id`, `event_id`, `student_id`, `check_in_status`, `check_in_time`, `feedback_submitted`) VALUES
(1, 6, 1, 'Pending', NULL, 0),
(2, 7, 1, 'Pending', NULL, 0),
(5, 8, 1, 'Pending', NULL, 0),
(6, 5, 1, 'Pending', NULL, 0),
(7, 10, 1, 'Pending', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `faculty`
--

CREATE TABLE `faculty` (
  `faculty_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `department` varchar(100) DEFAULT NULL,
  `designation` varchar(100) DEFAULT NULL,
  `contact` varchar(15) DEFAULT NULL,
  `profile_pic` varchar(255) DEFAULT 'default_faculty.png',
  `join_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faculty`
--

INSERT INTO `faculty` (`faculty_id`, `name`, `email`, `password`, `department`, `designation`, `contact`, `profile_pic`, `join_date`) VALUES
(1, 'Dr. A. Roy', 'aroy1@iem.edu', 'faculty123', 'CSE', 'Professor', NULL, 'default_faculty.png', '2025-10-21 18:32:46'),
(2, 'Dr. S. Mehta', 'smehta@iem.edu', 'faculty123', 'ECE', 'Associate Professor', NULL, 'default_faculty.png', '2025-10-21 18:32:46'),
(3, 'Dr. R. Das', 'rdas@iem.edu', 'faculty123', 'ME', 'Assistant Professor', NULL, 'default_faculty.png', '2025-10-21 18:32:46'),
(4, 'Dr. P. Sen', 'psen@iem.edu', 'faculty123', 'CSE', 'Professor', NULL, 'default_faculty.png', '2025-10-21 18:32:46'),
(5, 'Dr. K. Ghosh', 'kghosh@iem.edu', 'faculty123', 'IT', 'Associate Professor', NULL, 'default_faculty.png', '2025-10-21 18:32:46'),
(6, 'Dr. M. Roy', 'mroy@iem.edu', 'faculty123', 'CSE', 'Assistant Professor', NULL, 'default_faculty.png', '2025-10-21 18:32:46'),
(7, 'Dr. T. Banerjee', 'tbanerjee@iem.edu', 'faculty123', 'ECE', 'Professor', NULL, 'default_faculty.png', '2025-10-21 18:32:46'),
(8, 'Dr. N. Chatterjee', 'nchatterjee@iem.edu', 'faculty123', 'ME', 'Associate Professor', NULL, 'default_faculty.png', '2025-10-21 18:32:46'),
(9, 'Dr. L. Kumar', 'lkumar@iem.edu', 'faculty123', 'CSE', 'Assistant Professor', NULL, 'default_faculty.png', '2025-10-21 18:32:46'),
(10, 'Dr. A. Singh', 'asingh@iem.edu', 'faculty123', 'IT', 'Professor', NULL, 'default_faculty.png', '2025-10-21 18:32:46');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `feedback_id` int(11) NOT NULL,
  `event_id` int(11) DEFAULT NULL,
  `student_id` int(11) DEFAULT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` between 1 and 5),
  `comments` text DEFAULT NULL,
  `submitted_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`feedback_id`, `event_id`, `student_id`, `rating`, `comments`, `submitted_at`) VALUES
(3, 2, 3, 5, 'Excellent workshop', '2025-10-21 18:32:47'),
(5, 4, 5, 3, 'Could be better', '2025-10-21 18:32:47'),
(6, 5, 6, 5, 'Loved it', '2025-10-21 18:32:47'),
(7, 6, 7, 4, 'Well organized', '2025-10-21 18:32:47'),
(8, 7, 8, 5, 'Very useful', '2025-10-21 18:32:47');

-- --------------------------------------------------------

--
-- Table structure for table `marks`
--

CREATE TABLE `marks` (
  `mark_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `faculty_id` int(11) DEFAULT NULL,
  `subject` varchar(100) DEFAULT NULL,
  `exam_type` enum('Midterm','Final','Assignment','Project') DEFAULT NULL,
  `score` float DEFAULT NULL,
  `total` float DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `recorded_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `marks`
--

INSERT INTO `marks` (`mark_id`, `student_id`, `faculty_id`, `subject`, `exam_type`, `score`, `total`, `remarks`, `recorded_at`) VALUES
(1, 1, 1, 'Data Structures', 'Midterm', 85, 100, 'Good', '2025-10-21 18:32:47'),
(2, 2, 1, 'Data Structures', 'Midterm', 78, 100, '', '2025-10-21 18:32:47'),
(3, 3, 2, 'Signals', 'Final', 92, 100, 'Excellent', '2025-10-21 18:32:47'),
(4, 4, 3, 'Thermodynamics', 'Assignment', 70, 80, '', '2025-10-21 18:32:47'),
(5, 5, 1, 'Algorithms', 'Project', 95, 100, 'Well Done', '2025-10-21 18:32:47'),
(6, 6, 1, 'Algorithms', 'Assignment', 88, 100, '', '2025-10-21 18:32:47'),
(7, 7, 2, 'Electronics', 'Midterm', 76, 100, '', '2025-10-21 18:32:47'),
(8, 8, 3, 'Mechanics', 'Final', 89, 100, 'Great', '2025-10-21 18:32:47'),
(10, 10, 1, 'OS', 'Project', 92, 100, '', '2025-10-21 18:32:47'),
(12, 5, 1, 'Python', 'Assignment', 10, 10, '', '2025-10-24 13:14:21'),
(13, 3, 1, 'Python', 'Assignment', 7, 10, '', '2025-10-24 13:14:21'),
(14, 7, 1, 'Python', 'Assignment', 10, 10, '', '2025-10-24 13:14:21'),
(15, 10, 1, 'Python', 'Assignment', 4, 10, '', '2025-10-24 13:14:21'),
(16, 4, 1, 'Python', 'Assignment', 10, 10, '', '2025-10-24 13:14:21'),
(17, 1, 1, 'Python', 'Assignment', 9, 10, '', '2025-10-24 13:14:21'),
(18, 6, 1, 'Python', 'Assignment', 8, 10, '', '2025-10-24 13:14:21'),
(19, 8, 1, 'Python', 'Assignment', 10, 10, '', '2025-10-24 13:14:21'),
(20, 2, 1, 'Python', 'Assignment', 9, 10, '', '2025-10-24 13:14:21'),
(21, 5, 1, 'Chemistry', 'Midterm', 23, 30, '', '2025-10-24 23:58:03'),
(22, 3, 1, 'Chemistry', 'Midterm', 27, 30, 'recheck', '2025-10-24 23:58:03'),
(23, 7, 1, 'Chemistry', 'Midterm', 15, 30, '', '2025-10-24 23:58:03'),
(24, 10, 1, 'Chemistry', 'Midterm', 16, 30, '', '2025-10-24 23:58:03'),
(25, 4, 1, 'Chemistry', 'Midterm', 30, 30, '', '2025-10-24 23:58:03'),
(27, 6, 1, 'Chemistry', 'Midterm', 22, 30, '', '2025-10-24 23:58:03'),
(28, 8, 1, 'Chemistry', 'Midterm', 24, 30, '', '2025-10-24 23:58:03'),
(29, 2, 1, 'Chemistry', 'Midterm', 27, 30, '', '2025-10-24 23:58:03'),
(30, 11, 1, 'Chemistry', 'Midterm', 27, 30, '', '2025-10-24 23:58:03');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `notification_id` int(11) NOT NULL,
  `title` varchar(150) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `target` enum('all','students','faculty','admins') DEFAULT 'all',
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`notification_id`, `title`, `message`, `target`, `created_by`, `created_at`) VALUES
(1, 'Welcome Students', 'Welcome to Connect portal!', 'students', 1, '2025-10-21 18:32:47'),
(2, 'System Maintenance', 'Server maintenance on 25-Oct', 'all', 1, '2025-10-21 18:32:47'),
(3, 'Exam Reminder', 'Midterm exams start next week', 'students', 1, '2025-10-21 18:32:47'),
(4, 'Event Update', 'TechFest updated with new schedule', 'all', 1, '2025-10-21 18:32:47'),
(5, 'Workshop Alert', 'Robotics Workshop registration open', 'students', 2, '2025-10-21 18:32:47'),
(6, 'Faculty Meeting', 'Meeting for faculty members', 'faculty', 2, '2025-10-21 18:32:47'),
(7, 'New Resource', 'DSA notes uploaded', 'students', 1, '2025-10-21 18:32:47'),
(8, 'Cybersecurity Seminar', 'Join Cybersecurity seminar', 'all', 10, '2025-10-21 18:32:47'),
(9, 'Research Paper Submission', 'Submit papers by end of month', 'faculty', 1, '2025-10-21 18:32:47'),
(10, 'Holiday Notice', 'Institute closed on 2-Nov', 'all', 1, '2025-10-21 18:32:47'),
(11, 'Server Maintanance', 'for 2 hrs from 12.00 - 14.00 at 27/10/25', 'all', 1, '2025-10-24 13:23:14'),
(12, 'System Maintenance', 'Server maintenance on 31-Oct', 'all', 1, '2025-10-25 00:00:49');

-- --------------------------------------------------------

--
-- Table structure for table `resources`
--

CREATE TABLE `resources` (
  `resource_id` int(11) NOT NULL,
  `title` varchar(200) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `uploaded_by` enum('faculty','admin') DEFAULT 'faculty',
  `faculty_id` int(11) DEFAULT NULL,
  `upload_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `resources`
--

INSERT INTO `resources` (`resource_id`, `title`, `description`, `file_path`, `category`, `uploaded_by`, `faculty_id`, `upload_date`) VALUES
(2, 'Signals Notes', 'Signals & Systems', 'uploads/faculty_resources/signals.pdf', 'ECE (Approved)', 'faculty', 2, '2025-10-21 18:32:47'),
(3, 'Thermodynamics PDF', 'Thermo notes', 'uploads/faculty_resources/thermo.pdf', 'ME', 'faculty', 3, '2025-10-21 18:32:47'),
(4, 'Algorithms Slides', 'Algorithm concepts', 'uploads/faculty_resources/algorithms.pdf', 'CSE', 'faculty', 1, '2025-10-21 18:32:47'),
(5, 'Electronics Lab Manual', 'Lab guide', 'uploads/faculty_resources/electronics.pdf', 'ECE', 'faculty', 2, '2025-10-21 18:32:47'),
(6, 'Mechanics Notes', 'Mechanics concepts', 'uploads/faculty_resources/mechanics.pdf', 'ME', 'faculty', 3, '2025-10-21 18:32:47'),
(7, 'OS Notes', 'Operating System', 'uploads/faculty_resources/os.pdf', 'CSE', 'faculty', 1, '2025-10-21 18:32:47'),
(8, 'Networking PDF', 'Computer Networks', 'uploads/faculty_resources/network.pdf', 'IT', 'faculty', 10, '2025-10-21 18:32:47'),
(9, 'AI Tutorial', 'AI basics', 'uploads/faculty_resources/ai.pdf', 'CSE', 'faculty', 1, '2025-10-21 18:32:47'),
(10, 'Robotics Manual', 'Robotics Guide', 'uploads/faculty_resources/robotics.pdf', 'ECE', 'faculty', 2, '2025-10-21 18:32:47');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `student_id` int(11) NOT NULL,
  `roll_no` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `department` varchar(100) DEFAULT NULL,
  `year` int(11) DEFAULT NULL,
  `section` varchar(10) DEFAULT NULL,
  `contact` varchar(15) DEFAULT NULL,
  `profile_pic` varchar(255) DEFAULT 'default_student.png',
  `join_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`student_id`, `roll_no`, `name`, `email`, `password`, `department`, `year`, `section`, `contact`, `profile_pic`, `join_date`) VALUES
(1, 'IEMCSE202501', 'Rahul Saha', 'rahul1@iem.edu', 'student123', 'CSE', 3, 'A', NULL, 'default_student.png', '2025-10-21 18:32:47'),
(2, 'IEMCSE202502', 'Sneha Das', 'sneha2@iem.edu', 'student123', 'CSE', 3, 'A', NULL, 'default_student.png', '2025-10-21 18:32:47'),
(3, 'IEMECE202503', 'Arjun Roy', 'arjun3@iem.edu', 'student123', 'ECE', 2, 'B', NULL, 'default_student.png', '2025-10-21 18:32:47'),
(4, 'IEMEME202504', 'Priya Sen', 'priya4@iem.edu', 'student123', 'ME', 4, 'A', NULL, 'default_student.png', '2025-10-21 18:32:47'),
(5, 'IEMCSE202505', 'Ankit Kumar', 'ankit5@iem.edu', 'student123', 'CSE', 1, 'C', NULL, 'default_student.png', '2025-10-21 18:32:47'),
(6, 'IEMCSE202506', 'Ritika Ghosh', 'ritika6@iem.edu', 'student123', 'CSE', 2, 'B', NULL, 'default_student.png', '2025-10-21 18:32:47'),
(7, 'IEMECE202507', 'Karan Mehta', 'karan7@iem.edu', 'student123', 'ECE', 3, 'C', NULL, 'default_student.png', '2025-10-21 18:32:47'),
(8, 'IEMEME202508', 'Simran Das', 'simran8@iem.edu', 'student123', 'ME', 2, 'A', NULL, 'default_student.png', '2025-10-21 18:32:47'),
(10, 'IEMCSE202510', 'Neha Roy', 'neha10@iem.edu', 'student123', 'CSE', 3, 'C', NULL, 'default_student.png', '2025-10-21 18:32:47'),
(11, 'IEMCSE202574', 'Soham', 'Soham@connect.com', '$2y$10$ySKwwOMgwkwxLVZVzbHH2.d5iJpKCFeS9342504sEej7Gq7mQazcS', 'CSE', 1, 'B1', 'XXXXXXXXX', 'default_student.png', '2025-10-24 13:36:55'),
(12, '', 'asmita', 'asmita1@connect.com', '$2y$10$MczgWsB1Ql0hCWUVV/z7He0SJZKjUX1Z7ZHCp4cmhXIyrs7Z14hqK', NULL, NULL, NULL, NULL, 'default_student.png', '2025-10-25 00:05:48');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`attendance_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `faculty_id` (`faculty_id`);

--
-- Indexes for table `collaboration`
--
ALTER TABLE `collaboration`
  ADD PRIMARY KEY (`post_id`);

--
-- Indexes for table `doubts`
--
ALTER TABLE `doubts`
  ADD PRIMARY KEY (`doubt_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `faculty_id` (`faculty_id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`event_id`);

--
-- Indexes for table `event_registrations`
--
ALTER TABLE `event_registrations`
  ADD PRIMARY KEY (`registration_id`),
  ADD KEY `event_id` (`event_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `faculty`
--
ALTER TABLE `faculty`
  ADD PRIMARY KEY (`faculty_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`feedback_id`),
  ADD KEY `event_id` (`event_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `marks`
--
ALTER TABLE `marks`
  ADD PRIMARY KEY (`mark_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `faculty_id` (`faculty_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notification_id`);

--
-- Indexes for table `resources`
--
ALTER TABLE `resources`
  ADD PRIMARY KEY (`resource_id`),
  ADD KEY `faculty_id` (`faculty_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`student_id`),
  ADD UNIQUE KEY `roll_no` (`roll_no`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `attendance_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `collaboration`
--
ALTER TABLE `collaboration`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `doubts`
--
ALTER TABLE `doubts`
  MODIFY `doubt_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `event_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `event_registrations`
--
ALTER TABLE `event_registrations`
  MODIFY `registration_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `faculty`
--
ALTER TABLE `faculty`
  MODIFY `faculty_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `feedback_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `marks`
--
ALTER TABLE `marks`
  MODIFY `mark_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `resources`
--
ALTER TABLE `resources`
  MODIFY `resource_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `attendance_ibfk_2` FOREIGN KEY (`faculty_id`) REFERENCES `faculty` (`faculty_id`) ON DELETE CASCADE;

--
-- Constraints for table `doubts`
--
ALTER TABLE `doubts`
  ADD CONSTRAINT `doubts_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `doubts_ibfk_2` FOREIGN KEY (`faculty_id`) REFERENCES `faculty` (`faculty_id`) ON DELETE SET NULL;

--
-- Constraints for table `event_registrations`
--
ALTER TABLE `event_registrations`
  ADD CONSTRAINT `event_registrations_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`event_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `event_registrations_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`) ON DELETE CASCADE;

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`event_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `feedback_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`) ON DELETE CASCADE;

--
-- Constraints for table `marks`
--
ALTER TABLE `marks`
  ADD CONSTRAINT `marks_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `marks_ibfk_2` FOREIGN KEY (`faculty_id`) REFERENCES `faculty` (`faculty_id`) ON DELETE SET NULL;

--
-- Constraints for table `resources`
--
ALTER TABLE `resources`
  ADD CONSTRAINT `resources_ibfk_1` FOREIGN KEY (`faculty_id`) REFERENCES `faculty` (`faculty_id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
