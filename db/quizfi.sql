-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Feb 16, 2025 at 01:00 AM
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
(1, 'Easy', 800, 250),
(2, 'Moderate', 2500, 500),
(3, 'Hard', 3000, 200);

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
  PRIMARY KEY (`classroom_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `classroom`
--

INSERT INTO `classroom` (`classroom_id`, `user_id`, `classroom_code`, `classroom_name`, `classroom_desc`) VALUES
(5, 4, 'jHLMKwioHn', 'ASSURANCE 1', 'assurance and security 1'),
(3, 4, '63YetmPAl9', 'Capstone', 'capstoneuy'),
(4, 6, 'DReI06O2k3', 'OJT MANUAL', 'manual manual'),
(6, 4, 'wrry1vIclO', 'ICT12', ''),
(7, 4, 'HT4sq2WNkv', 'IT ELEC3', ''),
(8, 8, 'Vc6HSLJd1W', 'PHP3', ''),
(9, 8, 'ovoEQJcCN4', 'IT ELEC11', '');

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
  PRIMARY KEY (`educator_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `educators`
--

INSERT INTO `educators` (`educator_id`, `user_id`, `lastname`, `firstname`, `department`) VALUES
(1, 4, 'BUANTE', 'JELLY', 'BSAIS'),
(2, 6, 'Marie', 'Rose', 'CICT'),
(3, 8, 'John?', 'Paul', 'CICT');

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
(1, 3, 1, 'FB9A756E264AE4006510', '01:00:00', '2025-02-09 14:31:16', 'used'),
(2, 3, 1, 'E392A220FC7B84BDC83C', '01:00:00', '2024-11-06 17:50:27', 'unused'),
(3, 3, 1, '961727C27F5C606C9254', '01:00:00', '2024-11-08 16:09:24', 'unused'),
(4, 3, 1, '7354E6766AA6922368F1', '01:00:00', NULL, 'unused'),
(5, 3, 1, '9508676A8812E4871927', '01:00:00', NULL, 'unused');

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
) ENGINE=MyISAM AUTO_INCREMENT=118 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`question_id`, `quiz_id`, `quiz_question`, `quiz_type`, `option_a`, `option_b`, `option_c`, `option_d`, `correct_answer`, `short_answer`) VALUES
(1, 1, 'Which SQL command is used to extract data from a database?', 'Multiple Choice', 'EXTRACT', 'SELECT', 'GET', 'RETRIEVE', 'B', NULL),
(2, 1, 'The SQL WHERE clause is used to filter records.', 'True/False', NULL, NULL, NULL, NULL, 'True', NULL),
(3, 1, 'What does the acronym SQL stand for?', 'Short Answer', NULL, NULL, NULL, NULL, NULL, 'STRUCTURED QUERY LANGUAGE'),
(4, 1, 'Which of the following is not a valid SQL data type? ', 'Multiple Choice', 'VARCHAR', 'INT', 'FLOAT', 'ARRAY', 'D', NULL),
(5, 1, 'In SQL, NULL is the same as zero or an empty string.', 'True/False', NULL, NULL, NULL, NULL, 'False', NULL),
(6, 1, 'What SQL clause is used to sort the result set?', 'Short Answer', NULL, NULL, NULL, NULL, NULL, 'ORDER BY'),
(7, 1, 'Which SQL command is used to update data in a database?', 'Multiple Choice', 'MODIFY', 'SAVE', 'UPDATE', 'CHANGE', 'C', NULL),
(8, 1, 'The SQL INNER JOIN keyword returns all records when there is a match in either left or right table.', 'True/False', NULL, NULL, NULL, NULL, 'False', NULL),
(9, 1, 'What SQL function is used to count the number of rows that match the specified criteria?', 'Short Answer', NULL, NULL, NULL, NULL, NULL, 'COUNT()'),
(114, 49, 'testtt', 'True/False', NULL, NULL, NULL, NULL, 'False', NULL),
(113, 48, 'test', 'True/False', NULL, NULL, NULL, NULL, 'False', NULL),
(110, 45, 'What is the name of quizfi?', 'Short Answer', NULL, NULL, NULL, NULL, NULL, 'quizfi'),
(111, 46, 'sample', 'True/False', NULL, NULL, NULL, NULL, 'True', NULL),
(112, 47, 'testing quiz', 'True/False', NULL, NULL, NULL, NULL, 'False', NULL),
(107, 43, 'TESTING', 'Short Answer', NULL, NULL, NULL, NULL, NULL, 'TESTING'),
(108, 43, '', 'Multiple Choice', 'AB', 'C', 'D', 'CD', 'True', NULL),
(106, 43, 'TESTING', 'True/False', NULL, NULL, NULL, NULL, 'True', NULL),
(104, 42, 'Working?', 'Short Answer', NULL, NULL, NULL, NULL, NULL, 'yes'),
(105, 43, 'Offline TEsting', 'Multiple Choice', 'AB', 'C', 'D', 'CD', 'A', NULL),
(103, 42, 'Offline', 'Multiple Choice', 'off', 'line', 'tes', 'ting', 'A', NULL),
(102, 42, 'Offline Test', 'True/False', NULL, NULL, NULL, NULL, 'True', NULL),
(109, 44, 'LIFE OF RIZAL', 'True/False', NULL, NULL, NULL, NULL, 'True', NULL),
(115, 50, 'sample', 'Multiple Choice', 'a', 'b', 'c', 'd', 'A', NULL),
(116, 51, '4', 'True/False', NULL, NULL, NULL, NULL, 'True', NULL),
(117, 52, '5', 'True/False', NULL, NULL, NULL, NULL, 'False', NULL);

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
  PRIMARY KEY (`quiz_id`),
  KEY `user_id` (`user_id`),
  KEY `classroom_id` (`classroom_id`)
) ENGINE=MyISAM AUTO_INCREMENT=53 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `quiz`
--

INSERT INTO `quiz` (`quiz_id`, `user_id`, `classroom_id`, `quiz_title`, `quiz_description`, `difficulty`) VALUES
(1, 0, 0, 'Introduction to SQL', 'This comprehensive SQL quiz covers fundamental concepts and commands, testing your knowledge of data manipulation, querying, and database management through a mix of multiple-choice, true/false, and short-answer questions suitable for beginners to interme', 'Easy'),
(49, 4, 5, 'testtt', 'testtt', 'Hard'),
(48, 4, 5, 'test', 'test', 'Moderate'),
(47, 4, 3, 'qtesting', 'qq', 'Hard'),
(45, 0, 0, 'ASSURANCE 2', 'assurance and security 2', 'Hard'),
(46, 4, 5, 'OJT BOYS', 'Ojt lang po', 'Easy'),
(42, 0, 0, 'Offline Test', 'Testing Offline', 'Easy'),
(44, 0, 0, 'LIFE OF RIZAL', 'history of Rizal', 'Moderate'),
(50, 0, 0, '3', '3', 'Easy'),
(51, 0, 0, '4', '4', 'Easy'),
(52, 0, 0, '5', '5', 'Easy');

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
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `quiz_attempt`
--

INSERT INTO `quiz_attempt` (`attempt_id`, `user_id`, `quiz_id`, `classroom_id`, `attempt_time`, `next_attempt_time`, `score_status`, `awarded_points`) VALUES
(14, 3, 1, 0, '2025-02-06 00:01:37', '2025-02-13 00:01:37', '3', 250),
(13, 3, 51, 0, '2025-01-26 21:07:41', '2025-02-02 21:07:41', '1', 800);

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
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`student_id`, `user_id`, `lastname`, `firstname`, `year`, `department`, `score`, `duration`, `status`, `start_time`, `ranking_id`, `ip_address`, `profile_path`) VALUES
(1, 2, 'Jordan', 'Ledons', '4th', 'CICT', 0, 3600, 'inactive', '0000-00-00 00:00:00', NULL, NULL, NULL),
(2, 3, 'Mislang', 'Rek', '1st', 'CICT', 15400, 21022, 'inactive', '0000-00-00 00:00:00', NULL, '::1', 'assets/avatars/male-2.jpg'),
(3, 5, 'Shoes', 'Jember', '4th', 'CICT', 0, 3600, 'inactive', '0000-00-00 00:00:00', NULL, '::1', NULL),
(4, 7, 'Jordan', 'Javidec', '1st', 'CICT', 0, 0, 'inactive', '0000-00-00 00:00:00', NULL, NULL, NULL),
(5, 9, 'Quir', 'John', '4th', 'CJGE', 0, 0, 'inactive', '0000-00-00 00:00:00', NULL, NULL, NULL),
(6, 10, 'Kane', 'Urbayo', '4th', 'CICT', 0, 0, 'inactive', '0000-00-00 00:00:00', NULL, NULL, NULL),
(7, 11, 'Monsion', 'Angelo', '4th', 'CICT', 0, 0, 'inactive', '0000-00-00 00:00:00', NULL, NULL, NULL),
(8, 12, 'Guevarra', 'Vincent', '1st', 'CICT', 0, 0, 'inactive', '0000-00-00 00:00:00', NULL, NULL, NULL),
(9, 13, 'Abanil', 'Arc', '1st', 'CICT', 0, 0, 'inactive', '0000-00-00 00:00:00', NULL, NULL, NULL);

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
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `student_classroom`
--

INSERT INTO `student_classroom` (`student_classroom_id`, `classroom_id`, `student_id`) VALUES
(5, 5, 2),
(6, 3, 2),
(7, 4, 2),
(8, 6, 2),
(9, 7, 2),
(10, 8, 2),
(13, 5, 3);

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
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `user_email`, `username`, `password`, `usertype`) VALUES
(1, 'admin@gmail.com', 'admin', '21232f297a57a5a743894a0e4a801fc3', 'admin'),
(2, 'jordan@gmail.com', 'Jordan_Led', 'f8894d2c589ac837633c4ade8665980a', 'student'),
(3, 'rik@gmail.com', 'Mislang_Rek', 'f8894d2c589ac837633c4ade8665980a', 'student'),
(4, 'buante@gmail.com', 'BUANTE_JEL', 'f8894d2c589ac837633c4ade8665980a', 'educator'),
(5, 'jember@gmail.com', 'Calay_Jem', 'f8894d2c589ac837633c4ade8665980a', 'student'),
(6, 'rose@gmail.com', 'Gepolani_Ros', 'f8894d2c589ac837633c4ade8665980a', 'educator'),
(7, 'Jav@gmail.com', 'Monsion_Jav', 'f8894d2c589ac837633c4ade8665980a', 'student'),
(8, 'psworld@gmail.com', 'Doe_Pau', 'f8894d2c589ac837633c4ade8665980a', 'educator'),
(9, 'kir@gmail.com', 'Quir_Joh', 'f8894d2c589ac837633c4ade8665980a', 'student'),
(10, 'kane@gmail.com', 'Kane_Urb', 'f8894d2c589ac837633c4ade8665980a', 'student'),
(11, 'jav@gmail.com', 'Monsion_Ang', 'f8894d2c589ac837633c4ade8665980a', 'student'),
(12, 'vincent@gmail.com', 'Guevarra_Vin', 'f8894d2c589ac837633c4ade8665980a', 'student'),
(13, 'exbjunior@gmail.com', 'Abanil_Arc', 'f8894d2c589ac837633c4ade8665980a', 'student');

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
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vouchers`
--

INSERT INTO `vouchers` (`voucher_id`, `voucher_name`, `voucher_description`, `points`, `quantity`, `duration`) VALUES
(1, '1hr', 'promo', 1000, 45, '01:00:00'),
(2, '2HR', 'Promo!', 2000, 10, '02:00:00'),
(3, '3HR', 'Promo!', 3000, 10, '03:00:00'),
(4, '4HR', 'sulit promo', 4000, 2, '04:00:00'),
(5, '5hr', 'sample', 5000, 5, '05:00:00'),
(6, '10hr', 'sample', 10000, 10, '10:00:00'),
(7, '1day', 'sample', 20000, 20, '24:00:00'),
(8, '2days', 'sample', 40000, 15, '23:00:00'),
(9, '3hr', 'sample', 3000, 8, '03:00:00'),
(10, '12hr', 'sample', 12000, 12, '12:00:00'),
(11, '51hr', 'sample', 3000, 8, '01:00:00');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
