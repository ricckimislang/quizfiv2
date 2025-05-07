-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Mar 22, 2025 at 08:53 AM
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
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `classroom`
--

INSERT INTO `classroom` (`classroom_id`, `user_id`, `classroom_code`, `classroom_name`, `classroom_desc`, `profile_path`) VALUES
(1, 7, '1LsDgLXzKb', 'Captsone II', '', NULL);

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
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `educators`
--

INSERT INTO `educators` (`educator_id`, `user_id`, `lastname`, `firstname`, `department`, `profile_path`) VALUES
(1, 7, 'buante', 'jelly', 'cict', NULL);

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
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchased_vouchers`
--

INSERT INTO `purchased_vouchers` (`purchase_id`, `user_id`, `voucher_id`, `wifi_code`, `duration`, `start_time`, `status`) VALUES
(1, 5, 1, '212604927A4E3042DB9F', '01:00:00', '2025-03-20 10:37:41', 'used'),
(2, 3, 1, '081EBDBF0DA021B35BFE', '01:00:00', '2025-03-21 14:52:57', 'used');

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
(1, 1, 'What is the value of x in 2x + 5 = 15?', 'Multiple Choice', '3', '5', '7', '10', 'B', NULL),
(2, 1, 'Solve for x: 3x - 4 = 2x + 6.', 'Multiple Choice', '8', '10', '12', '14', 'A', NULL),
(3, 1, 'What is (x + 2)(x - 2) equal to?', 'Multiple Choice', 'x² - 4', 'x² + 4', 'x² - 2', 'x² + 2', 'A', NULL),
(4, 1, 'What is the square root of 49?', 'Multiple Choice', '5', '6', '7', '8', 'C', NULL),
(5, 1, 'Factorize x² - 5x + 6.', 'Multiple Choice', '(x - 2)(x - 3)', '(x + 2)(x - 3)', '(x - 1)(x - 6)', '(x + 1)(x - 6)', 'A', NULL),
(6, 1, 'If f(x) = 3x + 2, what is f(4)?', 'Multiple Choice', '11', '14', '12', '15', 'B', NULL),
(7, 1, 'Solve for x: 2x² - 8 = 0.', 'Multiple Choice', '2', '4', '-2', 'Both A and C', 'D', NULL),
(8, 1, 'What is the sum of the roots of x² - 7x + 12 = 0?', 'Multiple Choice', '7', '12', '19', '5', 'A', NULL),
(9, 1, 'Solve for x: 5x - 3 = 2x + 9.', 'Short Answer', NULL, NULL, NULL, NULL, NULL, '4'),
(10, 1, 'What is the square root of 144?', 'Short Answer', NULL, NULL, NULL, NULL, NULL, '12'),
(11, 2, 'What is sin(30°)?', 'Multiple Choice', '1/2', '√3/2', '1', '0', 'A', NULL),
(12, 2, 'What is cos(60°)?', 'Multiple Choice', '1', '0', '√3/2', '1/2', 'D', NULL),
(13, 2, 'What is tan(45°)?', 'Multiple Choice', '0', '1', '√2', 'Undefined', 'B', NULL),
(14, 2, 'Which identity is correct?', 'Multiple Choice', 'sin²θ + cos²θ = 1', 'tan²θ + 1 = sec²θ', 'Both A and B', 'None of these', 'C', NULL),
(15, 2, 'Convert 180° to radians.', 'Multiple Choice', 'π/2', 'π', '2π', '3π/2', 'B', NULL),
(16, 2, 'The hypotenuse in a right triangle with sides 3 and 4 is 5.', 'True/False', 'True', 'False', NULL, NULL, 'A', NULL),
(17, 2, 'If sinθ = 3/5, what is cosθ?', 'Multiple Choice', '4/5', '3/5', '1/2', '5/3', 'A', NULL),
(18, 2, 'What is secθ in terms of cosine?', 'Multiple Choice', '1/sinθ', '1/cosθ', '1/tanθ', 'cosθ/sinθ', 'B', NULL),
(19, 2, 'What is cotangent equal to?', 'Multiple Choice', 'sin/cos', '1/tan', 'tan²', 'cos/sin', 'B', NULL),
(20, 2, 'If tanθ = 1, what is θ?', 'Multiple Choice', '30°', '45°', '60°', '90°', 'B', NULL),
(21, 3, 'What is the derivative of x²?', 'Multiple Choice', '2x', 'x', 'x²', '1', 'A', NULL),
(22, 3, 'Find the integral of 3x².', 'Multiple Choice', 'x³', 'x³ + C', '3x³', 'x²', 'B', NULL),
(23, 3, 'The limit of (sin x)/x as x approaches 0 is 1.', 'True/False', 'True', 'False', NULL, NULL, 'A', NULL),
(24, 3, 'Find d/dx of e^x.', 'Multiple Choice', 'e^x', 'x^e', 'xe^(x-1)', 'ln(e^x)', 'A', NULL),
(25, 3, 'Evaluate the integral of 1/x dx.', 'Multiple Choice', 'x', 'ln|x|', '1/x', 'None', 'B', NULL),
(26, 3, 'What is the derivative of cos x?', 'Multiple Choice', '-sin x', 'cos x', 'tan x', 'sec² x', 'A', NULL),
(27, 3, 'Find d/dx of ln(x).', 'Multiple Choice', '1/x', 'ln(x)', 'x', 'e^x', 'A', NULL),
(28, 3, 'What is the second derivative of x³?', 'Multiple Choice', '3x', '6x', 'x²', '9x', 'B', NULL),
(29, 3, 'Solve: lim x→∞ (1 + 1/x)^x.', 'Multiple Choice', '1', '∞', 'e', '0', 'C', NULL),
(30, 3, 'What is the derivative of tan(x)?', 'Multiple Choice', 'sec²(x)', 'cot(x)', 'cos²(x)', 'sin²(x)', 'A', NULL);

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
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `quiz`
--

INSERT INTO `quiz` (`quiz_id`, `user_id`, `classroom_id`, `quiz_title`, `quiz_description`, `difficulty`, `created_at`) VALUES
(1, 1, 0, 'Basic Algebra', 'A quiz covering basic algebra concepts.', 'Easy', '2025-03-17 05:17:26'),
(2, 1, 0, 'Trigonometry Fundamentals', 'A quiz on sine, cosine, and tangent functions.', 'Easy', '2025-03-17 05:17:26'),
(3, 1, 1, 'Advanced Calculus', 'Questions covering differentiation and integration.', 'Easy', '2025-03-17 05:17:26'),
(4, 1, 0, 'Probability and Statistics', 'Basic concepts of probability and statistical analysis.', 'Easy', '2025-03-17 05:17:26'),
(5, 1, 0, 'Geometry Essentials', 'Covers basic and advanced geometry principles.', 'Easy', '2025-03-17 05:17:26');

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `id_photo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`student_id`),
  UNIQUE KEY `ranking_id` (`ranking_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`student_id`, `user_id`, `lastname`, `firstname`, `year`, `department`, `score`, `duration`, `status`, `start_time`, `ranking_id`, `ip_address`, `profile_path`, `id_photo`) VALUES
(1, 2, 'Ledon', 'Jordan', '4th year', 'cict', 0, 0, 'inactive', '0000-00-00 00:00:00', NULL, NULL, 'assets/avatars/male-2.jpg', ''),
(2, 3, 'Mislang', 'Riccki', '4th year', 'cict', 500, 3600, 'inactive', '0000-00-00 00:00:00', NULL, '127.0.0.1', 'assets/avatars/male-2.jpg', ''),
(3, 4, 'Gepolani', 'Rose', '4th year', 'cict', 0, 0, 'inactive', '0000-00-00 00:00:00', NULL, NULL, 'assets/avatars/male-2.jpg', ''),
(4, 5, 'Urbayo', 'Kane', '4th year', 'cict', 50, 3600, 'inactive', '0000-00-00 00:00:00', NULL, '::1', 'assets/avatars/female-2.jpg', ''),
(5, 6, 'Calay', 'Jember', '4th year', 'cict', 0, 0, 'inactive', '0000-00-00 00:00:00', NULL, NULL, 'assets/avatars/male-2.jpg', ''),
(7, 9, 'student', 'user', '1', 'cict', 0, 0, 'inactive', '0000-00-00 00:00:00', NULL, '127.0.0.1', 'assets/avatars/no-profile.jpg', 'assets/students/school_id/student_1742550713.jpg');

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
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `student_classroom`
--

INSERT INTO `student_classroom` (`student_classroom_id`, `classroom_id`, `student_id`) VALUES
(1, 1, 2);

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
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `user_email`, `username`, `password`, `usertype`) VALUES
(1, 'seait@gmail.com', 'admin', 'f8894d2c589ac837633c4ade8665980a', 'admin'),
(2, 'jordan.ledon@example.com', 'jordan_ledon', 'f8894d2c589ac837633c4ade8665980a', 'student'),
(3, 'riccki.mislang@example.com', 'riccki_mislang', '202cb962ac59075b964b07152d234b70', 'student'),
(4, 'rose.gepolani@example.com', 'rose_gepolani', 'f8894d2c589ac837633c4ade8665980a', 'student'),
(5, 'kane.urbayo@example.com', 'kane_urbayo', 'f8894d2c589ac837633c4ade8665980a', 'student'),
(6, 'jember.calay@example.com', 'jember_calay', 'f8894d2c589ac837633c4ade8665980a', 'student'),
(7, 'buante@gmail.com', 'jelly_bua', 'f8894d2c589ac837633c4ade8665980a', 'educator'),
(9, 'user@gmail.com', 'student_use', 'f8894d2c589ac837633c4ade8665980a', 'student');

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
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vouchers`
--

INSERT INTO `vouchers` (`voucher_id`, `voucher_name`, `voucher_description`, `points`, `quantity`, `duration`) VALUES
(1, '1hr', '1hr wifi voucher duration', 500, 18, '01:00:00'),
(2, '2hr', '2hrs wifi voucher', 1000, 20, '02:00:00');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
