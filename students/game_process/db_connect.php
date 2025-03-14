<?php
// Database connection parameters
$servername = "localhost";
$username = "root"; // Default WAMP username
$password = ""; // Default WAMP password
$dbname = "game";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database if it doesn't exist
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sql) !== TRUE) {
    die("Error creating database: " . $conn->error);
}

// Select the database
$conn->select_db($dbname);

// Create quiz table
$sql = "CREATE TABLE IF NOT EXISTS quiz (
    quiz_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    game_title VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql) !== TRUE) {
    die("Error creating quiz table: " . $conn->error);
}

// Create questions table
$sql = "CREATE TABLE IF NOT EXISTS questions (
    question_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    quiz_id INT(11) NOT NULL,
    question_text TEXT NOT NULL,
    option_a VARCHAR(255) NOT NULL,
    option_b VARCHAR(255) NOT NULL,
    option_c VARCHAR(255) NOT NULL,
    option_d VARCHAR(255) NOT NULL,
    correct_answer CHAR(1) NOT NULL,
    hint TEXT,
    FOREIGN KEY (quiz_id) REFERENCES quiz(quiz_id) ON DELETE CASCADE
)";

if ($conn->query($sql) !== TRUE) {
    die("Error creating questions table: " . $conn->error);
}
?> 