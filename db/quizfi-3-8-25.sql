-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Mar 08, 2025 at 05:40 AM
-- Server version: 9.1.0
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `quizfi`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `category_id` int NOT NULL AUTO_INCREMENT,
  `difficulty` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `perfect` int NOT NULL,
  `consolation` int NOT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `difficulty`, `perfect`, `consolation`) VALUES
(1, 'Easy', 300, 50),
(2, 'Moderate', 1500, 100),
(3, 'Hard', 5000, 200);

-- --------------------------------------------------------

--
-- Table structure for table `classroom`
--

DROP TABLE IF EXISTS `classroom`;
CREATE TABLE IF NOT EXISTS `classroom` (
  `classroom_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `classroom_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `classroom_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `classroom_desc` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `profile_path` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`classroom_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `classroom`
--

INSERT INTO `classroom` (`classroom_id`, `user_id`, `classroom_code`, `classroom_name`, `classroom_desc`, `profile_path`) VALUES
(1, 4, '2PWbETvFEo', 'IT ELEC II', '', 'assets/classroom-bg/accounting-bg.png'),
(2, 4, 'yWOykAyYjZ', 'Assurance II', '', 'assets/classroom-bg/pe-bg.png'),
(3, 4, 'tMfDFi3ZDx', 'PE III', '', NULL),
(4, 4, 'lpsHQkM3Lt', 'HCI I', '', NULL),
(5, 6, 'B8A7XLrDfI', 'Roseurance I', '', NULL),
(6, 6, 'kkn8AivJjL', 'Capstone I', '', NULL),
(7, 6, 'tGnpApU99x', 'Discrecte Math', '', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `educators`
--

DROP TABLE IF EXISTS `educators`;
CREATE TABLE IF NOT EXISTS `educators` (
  `educator_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `lastname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `firstname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `department` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `profile_path` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`educator_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `educators`
--

INSERT INTO `educators` (`educator_id`, `user_id`, `lastname`, `firstname`, `department`, `profile_path`) VALUES
(1, 4, 'Buante1', 'Jelly1', 'BSAIS1', NULL),
(2, 6, 'Marie', 'Rose', 'CICT', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `purchased_vouchers`
--

DROP TABLE IF EXISTS `purchased_vouchers`;
CREATE TABLE IF NOT EXISTS `purchased_vouchers` (
  `purchase_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `voucher_id` int NOT NULL,
  `wifi_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `duration` time DEFAULT NULL,
  `start_time` datetime DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`purchase_id`),
  KEY `user_id` (`user_id`),
  KEY `voucher_id` (`voucher_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchased_vouchers`
--

INSERT INTO `purchased_vouchers` (`purchase_id`, `user_id`, `voucher_id`, `wifi_code`, `duration`, `start_time`, `status`) VALUES
(1, 3, 1, '8E2BD2FCDAD9BD88FDA4', '01:00:00', NULL, 'used'),
(2, 3, 1, '182B8BA17FA35871ABF7', '01:00:00', NULL, 'unused'),
(3, 3, 1, '45A0E9F27F41E1579DE2', '01:00:00', NULL, 'unused'),
(4, 3, 1, '5120CA472A5A099D0D88', '01:00:00', NULL, 'unused'),
(5, 3, 1, '16A105DD4535C4BEA36B', '01:00:00', NULL, 'unused');

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

DROP TABLE IF EXISTS `questions`;
CREATE TABLE IF NOT EXISTS `questions` (
  `question_id` int NOT NULL AUTO_INCREMENT,
  `quiz_id` int NOT NULL,
  `quiz_question` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quiz_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `option_a` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `option_b` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `option_c` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `option_d` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `correct_answer` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `short_answer` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`question_id`),
  KEY `quiz_id` (`quiz_id`)
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`question_id`, `quiz_id`, `quiz_question`, `quiz_type`, `option_a`, `option_b`, `option_c`, `option_d`, `correct_answer`, `short_answer`) VALUES
(28, 1, 'If a rectangle has a length of 8 cm and a width of 3 cm, what is its perimeter?', 'Short Answer', NULL, NULL, NULL, NULL, NULL, '22 cm'),
(26, 1, 'True or False: The number 0 is considered a positive integer.', 'True/False', NULL, NULL, NULL, NULL, 'False', NULL),
(27, 1, 'What is the square root of 144?', 'Short Answer', NULL, NULL, NULL, NULL, NULL, '12'),
(25, 1, 'True or False: The sum of the angles in a triangle is always 180 degrees.', 'True/False', NULL, NULL, NULL, NULL, 'True', NULL),
(24, 1, 'If x = 3, what is the value of 2x^2 + 3x - 5?', 'Multiple Choice', '10', '12', '14', '8', 'B', NULL),
(23, 1, 'What is the area of a triangle with a base of 10 units and a height of 5 units?', 'Multiple Choice', '25 square units', '50 square units', '15 square units', '30 square units', 'A', NULL),
(22, 1, 'Which of the following is a prime number?', 'Multiple Choice', '4', '9', '11', '15', 'C', NULL),
(21, 1, 'What is the value of 7 + 5 × 2?', 'Multiple Choice', '24', '17', '12', '19', 'B', NULL),
(12, 2, 'test', 'Multiple Choice', '1', '2', '3', '4', 'A', NULL),
(13, 2, 'test3', 'Multiple Choice', '12', '3', '4', '4', 'B', NULL),
(14, 2, 'test4', 'Multiple Choice', '1', '2', '3', '4', 'C', NULL),
(15, 2, 'test5', 'True/False', NULL, NULL, NULL, NULL, 'True', NULL),
(16, 2, 'test6', 'True/False', NULL, NULL, NULL, NULL, 'False', NULL),
(17, 2, 'test7', 'True/False', NULL, NULL, NULL, NULL, 'True', NULL),
(18, 2, 'test8', 'True/False', NULL, NULL, NULL, NULL, 'False', NULL),
(19, 2, 'test9', 'True/False', NULL, NULL, NULL, NULL, 'True', NULL),
(20, 2, 'test11', 'Short Answer', NULL, NULL, NULL, NULL, NULL, '11'),
(29, 1, 'What is the value of 5^3?', 'Short Answer', NULL, NULL, NULL, NULL, NULL, '125'),
(30, 1, 'If you roll a fair six-sided die, what is the probability of rolling an even number?', 'Short Answer', NULL, NULL, NULL, NULL, NULL, '1/3');

-- --------------------------------------------------------

--
-- Table structure for table `quiz`
--

DROP TABLE IF EXISTS `quiz`;
CREATE TABLE IF NOT EXISTS `quiz` (
  `quiz_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT '0',
  `classroom_id` int DEFAULT '0',
  `quiz_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `quiz_description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `difficulty` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`quiz_id`),
  KEY `user_id` (`user_id`),
  KEY `classroom_id` (`classroom_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `quiz`
--

INSERT INTO `quiz` (`quiz_id`, `user_id`, `classroom_id`, `quiz_title`, `quiz_description`, `difficulty`, `created_at`) VALUES
(1, 0, 0, 'Mathibay!', 'First Quiz Of the Month!', 'Easy', '2025-03-05 02:18:57'),
(2, 4, 1, 'test', 'test', 'Easy', '2025-03-05 02:22:28');

-- --------------------------------------------------------

--
-- Table structure for table `quiz_attempt`
--

DROP TABLE IF EXISTS `quiz_attempt`;
CREATE TABLE IF NOT EXISTS `quiz_attempt` (
  `attempt_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `quiz_id` int NOT NULL,
  `classroom_id` int DEFAULT NULL,
  `attempt_time` timestamp NULL DEFAULT NULL,
  `next_attempt_time` timestamp NULL DEFAULT NULL,
  `score_status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `awarded_points` int NOT NULL,
  PRIMARY KEY (`attempt_id`),
  KEY `student_id` (`user_id`),
  KEY `quiz_id` (`quiz_id`),
  KEY `classroom_id` (`classroom_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `quiz_attempt`
--

INSERT INTO `quiz_attempt` (`attempt_id`, `user_id`, `quiz_id`, `classroom_id`, `attempt_time`, `next_attempt_time`, `score_status`, `awarded_points`) VALUES
(1, 3, 2, 1, '2025-03-04 20:57:48', '2025-03-11 20:57:48', '5', 50),
(2, 3, 1, 0, '2025-03-05 04:10:26', '2025-03-12 04:10:26', '1', 50);

-- --------------------------------------------------------

--
-- Table structure for table `ranking`
--

DROP TABLE IF EXISTS `ranking`;
CREATE TABLE IF NOT EXISTS `ranking` (
  `ranking_id` int NOT NULL AUTO_INCREMENT,
  `student_id` int NOT NULL,
  PRIMARY KEY (`ranking_id`),
  KEY `student_id` (`student_id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ranking`
--

INSERT INTO `ranking` (`ranking_id`, `student_id`) VALUES
(1, 1),
(2, 2),
(3, 3),
(4, 4),
(5, 5),
(6, 6),
(7, 7),
(8, 8),
(9, 9);

-- --------------------------------------------------------

--
-- Table structure for table `session`
--

DROP TABLE IF EXISTS `session`;
CREATE TABLE IF NOT EXISTS `session` (
  `session_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `duration` time NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`session_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

DROP TABLE IF EXISTS `students`;
CREATE TABLE IF NOT EXISTS `students` (
  `student_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `lastname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `firstname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `year` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `department` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `score` int NOT NULL DEFAULT '0',
  `duration` int NOT NULL DEFAULT '0',
  `status` enum('active','inactive') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'inactive',
  `start_time` timestamp NOT NULL,
  `ranking_id` int DEFAULT NULL,
  `ip_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `profile_path` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`student_id`),
  UNIQUE KEY `ranking_id` (`ranking_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`student_id`, `user_id`, `lastname`, `firstname`, `year`, `department`, `score`, `duration`, `status`, `start_time`, `ranking_id`, `ip_address`, `profile_path`) VALUES
(2, 3, 'Mislang', 'Riccki Rek', '4th', 'BSIT', 11550, 39022, 'active', '0000-00-00 00:00:00', NULL, '127.0.0.1', 'assets/avatars/female-3.jpg'),
(3, 5, 'Calay', 'Jember', '4th', 'BSIT/CICT', 0, 3600, 'inactive', '0000-00-00 00:00:00', NULL, '127.0.0.1', NULL),
(4, 7, 'Jordan', 'Jay', '4th', 'BSITBA', 0, 0, 'inactive', '0000-00-00 00:00:00', NULL, NULL, NULL),
(5, 9, 'Kiro', 'John', '4th', 'CRIMINALOGY', 0, 0, 'inactive', '0000-00-00 00:00:00', NULL, NULL, NULL),
(6, 10, 'Kane', 'Urbayo', '4th', 'CICT', 0, 0, 'inactive', '0000-00-00 00:00:00', NULL, NULL, NULL),
(7, 11, 'Monsion', 'Javidec', '1st', 'BSITBA', 0, 0, 'inactive', '0000-00-00 00:00:00', NULL, NULL, NULL),
(8, 12, 'Guevarra', 'Vincent', '1st', 'CICT', 0, 0, 'inactive', '0000-00-00 00:00:00', NULL, NULL, NULL),
(9, 13, 'Abanil1', 'Arc1', '1st', 'BSIT1', 0, 0, 'inactive', '0000-00-00 00:00:00', NULL, '127.0.0.1', 'assets/avatars/male-1.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `student_classroom`
--

DROP TABLE IF EXISTS `student_classroom`;
CREATE TABLE IF NOT EXISTS `student_classroom` (
  `student_classroom_id` int NOT NULL AUTO_INCREMENT,
  `classroom_id` int NOT NULL,
  `student_id` int NOT NULL,
  PRIMARY KEY (`student_classroom_id`),
  KEY `student_id` (`student_id`),
  KEY `classroom_id` (`classroom_id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `student_classroom`
--

INSERT INTO `student_classroom` (`student_classroom_id`, `classroom_id`, `student_id`) VALUES
(1, 1, 2),
(2, 2, 2),
(3, 3, 2),
(4, 4, 2),
(5, 5, 2),
(6, 6, 2),
(7, 7, 2);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `user_email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `usertype` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `user_email`, `username`, `password`, `usertype`) VALUES
(1, 'admin@gmail.com', 'admin', '21232f297a57a5a743894a0e4a801fc3', 'admin'),
(2, 'jordan@gmail.com', 'Jordan_Led', 'f8894d2c589ac837633c4ade8665980a', 'student'),
(3, 'rik@gmail.com', 'Mislang_Rek', 'f8894d2c589ac837633c4ade8665980a', 'student'),
(4, 'buanteJel1@gmail.com', 'jb', 'f8894d2c589ac837633c4ade8665980a', 'educator'),
(5, 'jember@gmail.com', 'Calay_Jem', 'f8894d2c589ac837633c4ade8665980a', 'student'),
(6, 'rose@gmail.com', 'Gepolani_Ros', 'f8894d2c589ac837633c4ade8665980a', 'educator'),
(7, 'Jav@gmail.com', 'Monsion_Jav', 'f8894d2c589ac837633c4ade8665980a', 'student'),
(9, 'kiro@gmail.com', 'John_kir', 'f8894d2c589ac837633c4ade8665980a', 'student'),
(10, 'kane@gmail.com', 'Kane_Urb', 'f8894d2c589ac837633c4ade8665980a', 'student'),
(11, 'jav@gmail.com', 'Monsion_Ang', 'f8894d2c589ac837633c4ade8665980a', 'student'),
(12, 'vincent@gmail.com', 'Guevarra_Vin', 'f8894d2c589ac837633c4ade8665980a', 'student'),
(13, 'arcaba1@gmail.com', 'abanil_arc1', 'f8894d2c589ac837633c4ade8665980a', 'student');

-- --------------------------------------------------------

--
-- Table structure for table `vouchers`
--

DROP TABLE IF EXISTS `vouchers`;
CREATE TABLE IF NOT EXISTS `vouchers` (
  `voucher_id` int NOT NULL AUTO_INCREMENT,
  `voucher_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `voucher_description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `points` int DEFAULT NULL,
  `quantity` int DEFAULT NULL,
  `duration` time DEFAULT NULL,
  PRIMARY KEY (`voucher_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
