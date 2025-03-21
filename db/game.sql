-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Mar 17, 2025 at 06:59 AM
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
-- Database: `game`
--

-- --------------------------------------------------------

--
-- Table structure for table `game_attempts`
--

DROP TABLE IF EXISTS `game_attempts`;
CREATE TABLE IF NOT EXISTS `game_attempts` (
  `attempt_id` int NOT NULL AUTO_INCREMENT,
  `game_id` int NOT NULL,
  `user_id` int NOT NULL,
  `attempt_date` date NOT NULL,
  `next_attempt_date` date DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`attempt_id`),
  KEY `game_id` (`game_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

DROP TABLE IF EXISTS `questions`;
CREATE TABLE IF NOT EXISTS `questions` (
  `question_id` int NOT NULL AUTO_INCREMENT,
  `quiz_id` int NOT NULL,
  `question_text` text NOT NULL,
  `option_a` varchar(255) NOT NULL,
  `option_b` varchar(255) NOT NULL,
  `option_c` varchar(255) NOT NULL,
  `option_d` varchar(255) NOT NULL,
  `correct_answer` char(1) NOT NULL,
  `hint` text,
  PRIMARY KEY (`question_id`),
  KEY `fk_quiz` (`quiz_id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`question_id`, `quiz_id`, `question_text`, `option_a`, `option_b`, `option_c`, `option_d`, `correct_answer`, `hint`) VALUES
(1, 1, 'Which SQL command retrieves all columns from the \"employees\" table?', 'SELECT * FROM employees;', 'GET * FROM employees;', 'FETCH * FROM employees;', 'RETRIEVE * FROM employees;', 'A', 'Use SELECT for retrieving data.'),
(2, 1, 'Which clause filters records based on a condition?', 'ORDER BY', 'WHERE', 'GROUP BY', 'HAVING', 'B', 'Filtering happens before grouping.'),
(3, 1, 'Which function returns the number of rows in a result set?', 'SUM()', 'COUNT()', 'TOTAL()', 'ROWS()', 'B', 'Think about counting records.'),
(4, 1, 'What does INNER JOIN do?', 'Returns only matching rows', 'Returns all left table rows', 'Returns all right table rows', 'Returns all rows from both tables', 'A', 'It matches records from both tables.'),
(5, 1, 'Which SQL clause groups records?', 'ORDER BY', 'GROUP BY', 'PARTITION BY', 'COLLATE', 'B', 'Used for aggregations like SUM or COUNT.'),
(6, 1, 'How to retrieve the second highest salary from employees?', 'SELECT MAX(salary) FROM employees;', 'SELECT salary FROM employees ORDER BY salary DESC LIMIT 2;', 'SELECT MAX(salary) FROM employees WHERE salary < (SELECT MAX(salary) FROM employees);', 'SELECT TOP 2 salary FROM employees;', 'C', 'Use a subquery to exclude the highest salary.'),
(7, 1, 'What does the HAVING clause do?', 'Filters records before aggregation', 'Filters groups after aggregation', 'Sorts results', 'Joins tables', 'B', 'It filters aggregated results.'),
(8, 1, 'What is a Common Table Expression (CTE) used for?', 'Temporary tables', 'Readable and reusable queries', 'Updating records faster', 'Speeding up joins', 'B', 'CTEs simplify complex queries.'),
(9, 1, 'Which query finds duplicate customers in a \"sales\" table?', 'SELECT customer_id FROM sales GROUP BY customer_id HAVING COUNT(*) > 1;', 'SELECT customer_id FROM sales GROUP BY customer_id;', 'SELECT DISTINCT customer_id FROM sales;', 'SELECT customer_id FROM sales WHERE COUNT(customer_id) > 1;', 'A', 'Use GROUP BY and HAVING COUNT > 1.'),
(10, 1, 'How do you find the latest order for each customer?', 'SELECT customer_id, MAX(order_date) FROM orders GROUP BY customer_id;', 'SELECT * FROM orders WHERE order_date = (SELECT MAX(order_date) FROM orders);', 'SELECT customer_id FROM orders ORDER BY order_date DESC LIMIT 1;', 'SELECT DISTINCT customer_id, order_date FROM orders;', 'A', 'Use MAX(order_date) with GROUP BY.');

-- --------------------------------------------------------

--
-- Table structure for table `quiz`
--

DROP TABLE IF EXISTS `quiz`;
CREATE TABLE IF NOT EXISTS `quiz` (
  `quiz_id` int NOT NULL AUTO_INCREMENT,
  `game_title` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `is_published` enum('not','yes') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`quiz_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quiz`
--

INSERT INTO `quiz` (`quiz_id`, `game_title`, `created_at`, `is_published`) VALUES
(1, 'Advanced SQL Challenge', '2025-03-17 05:30:56', 'yes');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
