-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Mar 04, 2025 at 05:20 AM
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
(1, 'Easy', 100, 250),
(2, 'Moderate', 1500, 500),
(3, 'Hard', 5000, 1000);

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
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `classroom`
--

INSERT INTO `classroom` (`classroom_id`, `user_id`, `classroom_code`, `classroom_name`, `classroom_desc`, `profile_path`) VALUES
(5, 4, 'jHLMKwioHn', 'ASSURANCE 1', 'assurance and security 1', 'assets/classroom-bg/code-sub-bg.png'),
(3, 4, '63YetmPAl9', 'Capstone', 'capstoneuy', 'assets/classroom-bg/general-bg.png'),
(6, 4, 'wrry1vIclO', 'ICT12', '', NULL),
(7, 4, 'HT4sq2WNkv', 'IT ELEC3', '', NULL),
(8, 8, 'Vc6HSLJd1W', 'PHP3', '', NULL),
(9, 8, 'ovoEQJcCN4', 'IT ELEC11', '', NULL);

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
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `educators`
--

INSERT INTO `educators` (`educator_id`, `user_id`, `lastname`, `firstname`, `department`, `profile_path`) VALUES
(1, 4, 'Buante', 'Jelly', 'BSAIS', NULL),
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
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchased_vouchers`
--

INSERT INTO `purchased_vouchers` (`purchase_id`, `user_id`, `voucher_id`, `wifi_code`, `duration`, `start_time`, `status`) VALUES
(1, 3, 1, 'FB9A756E264AE4006510', '01:00:00', '2025-02-09 14:31:16', 'used'),
(2, 3, 1, 'E392A220FC7B84BDC83C', '01:00:00', '2024-11-06 17:50:27', 'unused'),
(3, 3, 1, '961727C27F5C606C9254', '01:00:00', '2024-11-08 16:09:24', 'unused'),
(4, 3, 1, '7354E6766AA6922368F1', '01:00:00', NULL, 'unused'),
(5, 3, 1, '9508676A8812E4871927', '01:00:00', NULL, 'unused'),
(6, 3, 1, '68295452FEA7C96D1839', '01:00:00', NULL, 'unused'),
(7, 3, 1, '79241FF578592CF237AF', '01:00:00', NULL, 'unused'),
(8, 3, 1, 'D7DF43CC4DC1C43AA391', '01:00:00', NULL, 'unused'),
(9, 3, 1, '5D4E64A4261E8166A5B3', '01:00:00', NULL, 'unused'),
(10, 3, 1, '2CF4A6399DA4066AA5C2', '01:00:00', NULL, 'unused'),
(11, 3, 1, '14833014E0BE63B0E087', '01:00:00', NULL, 'unused'),
(12, 3, 3, '44B826B95CB43815D285', '03:00:00', '2025-02-16 21:38:47', 'used'),
(13, 3, 1, '148AD61347E879F288DC', '01:00:00', NULL, 'unused');

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
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`question_id`, `quiz_id`, `quiz_question`, `quiz_type`, `option_a`, `option_b`, `option_c`, `option_d`, `correct_answer`, `short_answer`) VALUES
(1, 1, '1+1', 'Multiple Choice', '1', '2', '3', '4', 'C', NULL),
(2, 1, '1+2', 'Multiple Choice', '1', '2', '3', '4', 'A', NULL),
(3, 1, '1+3', 'Multiple Choice', '1', '2', '3', '3', 'A', NULL),
(4, 1, '1=4', 'Multiple Choice', '1', '2', '3', '4', 'D', NULL),
(5, 1, '5+5=5?', 'True/False', NULL, NULL, NULL, NULL, 'True', NULL),
(6, 1, '5+5=7?', 'True/False', NULL, NULL, NULL, NULL, 'False', NULL),
(7, 1, '9+9=?', 'True/False', NULL, NULL, NULL, NULL, 'True', NULL),
(8, 1, '9+9-12?', 'Short Answer', NULL, NULL, NULL, NULL, NULL, '25'),
(9, 1, '1+25=?', 'Short Answer', NULL, NULL, NULL, NULL, NULL, '25'),
(10, 1, '20-10=?', 'Short Answer', NULL, NULL, NULL, NULL, NULL, '25');

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
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `quiz`
--

INSERT INTO `quiz` (`quiz_id`, `user_id`, `classroom_id`, `quiz_title`, `quiz_description`, `difficulty`, `created_at`) VALUES
(1, 0, 0, 'Math', 'General Math 111', 'Easy', '2025-02-19 09:34:39');

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
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `quiz_attempt`
--

INSERT INTO `quiz_attempt` (`attempt_id`, `user_id`, `quiz_id`, `classroom_id`, `attempt_time`, `next_attempt_time`, `score_status`, `awarded_points`) VALUES
(4, 5, 2, 0, '2025-02-19 01:37:32', '2025-02-26 01:37:32', '10', 800);

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
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`student_id`, `user_id`, `lastname`, `firstname`, `year`, `department`, `score`, `duration`, `status`, `start_time`, `ranking_id`, `ip_address`, `profile_path`) VALUES
(2, 3, 'Mislang', 'Riccki Rek', '4th', 'BSIT', 7200, 31822, 'active', '0000-00-00 00:00:00', NULL, '::1', 'assets/avatars/male-2.jpg'),
(3, 5, 'Calay', 'Jember', '4th', 'BSIT/CICT', 0, 3600, 'inactive', '0000-00-00 00:00:00', NULL, '::1', NULL),
(4, 7, 'Jordan', 'Jay', '4th', 'BSITBA', 0, 0, 'inactive', '0000-00-00 00:00:00', NULL, NULL, NULL),
(5, 9, 'Kiro', 'John', '4th', 'CRIMINALOGY', 0, 0, 'inactive', '0000-00-00 00:00:00', NULL, NULL, NULL),
(6, 10, 'Kane', 'Urbayo', '4th', 'CICT', 0, 0, 'inactive', '0000-00-00 00:00:00', NULL, NULL, NULL),
(7, 11, 'Monsion', 'Javidec', '1st', 'BSITBA', 0, 0, 'inactive', '0000-00-00 00:00:00', NULL, NULL, NULL),
(8, 12, 'Guevarra', 'Vincent', '1st', 'CICT', 0, 0, 'inactive', '0000-00-00 00:00:00', NULL, NULL, NULL),
(9, 13, 'Abanil', 'Arc', '4th', 'BSIT', 0, 0, 'inactive', '0000-00-00 00:00:00', NULL, '::1', 'assets/avatars/male-1.jpg'),
(17, 23, 'testing', 'test', '1st', 'testjg', 0, 0, 'inactive', '0000-00-00 00:00:00', NULL, NULL, 'assets/avatars/no-profile.jpg');

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
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `student_classroom`
--

INSERT INTO `student_classroom` (`student_classroom_id`, `classroom_id`, `student_id`) VALUES
(7, 5, 2),
(6, 5, 1),
(8, 3, 2),
(9, 4, 2);

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
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `user_email`, `username`, `password`, `usertype`) VALUES
(1, 'admin@gmail.com', 'admin', '21232f297a57a5a743894a0e4a801fc3', 'admin'),
(2, 'jordan@gmail.com', 'Jordan_Led', 'f8894d2c589ac837633c4ade8665980a', 'student'),
(3, 'rik@gmail.com', 'Mislang_Rek', 'f8894d2c589ac837633c4ade8665980a', 'student'),
(4, 'buanteJel@gmail.com', 'jb', 'f8894d2c589ac837633c4ade8665980a', 'educator'),
(5, 'jember@gmail.com', 'Calay_Jem', 'f8894d2c589ac837633c4ade8665980a', 'student'),
(6, 'rose@gmail.com', 'Gepolani_Ros', 'f8894d2c589ac837633c4ade8665980a', 'educator'),
(7, 'Jav@gmail.com', 'Monsion_Jav', 'f8894d2c589ac837633c4ade8665980a', 'student'),
(9, 'kiro@gmail.com', 'John_kir', 'f8894d2c589ac837633c4ade8665980a', 'student'),
(10, 'kane@gmail.com', 'Kane_Urb', 'f8894d2c589ac837633c4ade8665980a', 'student'),
(11, 'jav@gmail.com', 'Monsion_Ang', 'f8894d2c589ac837633c4ade8665980a', 'student'),
(12, 'vincent@gmail.com', 'Guevarra_Vin', 'f8894d2c589ac837633c4ade8665980a', 'student'),
(13, 'arcaba@gmail.com', 'abanil_arc', 'f8894d2c589ac837633c4ade8665980a', 'student'),
(23, 'test@gmail.com', 'testing_tes', 'f8894d2c589ac837633c4ade8665980a', 'student');

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
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vouchers`
--

INSERT INTO `vouchers` (`voucher_id`, `voucher_name`, `voucher_description`, `points`, `quantity`, `duration`) VALUES
(2, '2HR', 'Promo!', 2000, 10, '02:00:00'),
(3, '3HR', 'Promo!', 3000, 9, '03:00:00'),
(4, '4HR', 'sulit promo', 4000, 2, '04:00:00'),
(5, '5hr', 'sample', 5000, 5, '05:00:00'),
(6, '10hr', 'sample', 10000, 15, '10:00:00'),
(8, '2days', 'sample', 40000, 15, '23:00:00'),
(9, '3hr', 'sample', 3000, 8, '03:00:00'),
(10, '12hr', 'sample', 12000, 12, '12:00:00'),
(11, '51hr', 'sample', 3000, 8, '01:00:00');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
