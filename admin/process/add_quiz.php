<?php
session_start();
require_once '../db/dbconn.php';

header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];
    $quiz_title = trim($_POST['quiz_title']);
    $quiz_description = trim($_POST['quiz_description']);
    $difficulty = $_POST['difficulty'];

    // Validate input fields
    if (empty($quiz_title) || empty($quiz_description) || empty($difficulty)) {
        http_response_code(400);
        echo json_encode(['error' => 'All fields are required.']);
        exit;
    }

    // Insert quiz into database
    $stmt = $conn->prepare("INSERT INTO quiz (quiz_title, quiz_description, difficulty, created_at) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("sss", $quiz_title, $quiz_description, $difficulty);

    if ($stmt->execute()) {
        $quiz_id = $stmt->insert_id;
        $stmt->close();

        // Insert questions
        foreach ($_POST as $key => $value) {
            if (strpos($key, 'question_text_') !== false) {
                $question_number = str_replace('question_text_', '', $key);
                $question_text = trim($value);
                $question_type = $_POST['question_type_' . $question_number];

                // Initialize variables
                $option_a = $option_b = $option_c = $option_d = $correct_answer = $short_answer = null;

                // Set values based on question type
                if ($question_type === 'Multiple Choice') {
                    $option_a = trim($_POST['option_a_' . $question_number]);
                    $option_b = trim($_POST['option_b_' . $question_number]);
                    $option_c = trim($_POST['option_c_' . $question_number]);
                    $option_d = trim($_POST['option_d_' . $question_number]);
                    $correct_answer = $_POST['correct_answer_' . $question_number];
                } elseif ($question_type === 'True/False') {
                    $correct_answer = $_POST['correct_answer_' . $question_number];
                } elseif ($question_type === 'Short Answer') {
                    $short_answer = trim($_POST['short_answer_' . $question_number]);
                }

                // Insert into questions table
                $stmt = $conn->prepare("INSERT INTO questions (quiz_id, quiz_question, quiz_type, option_a, option_b, option_c, option_d, correct_answer, short_answer) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

                if (!$stmt) {
                    error_log("Prepare failed: " . $conn->error);
                    http_response_code(500);
                    echo json_encode(['error' => 'Failed to prepare statement: ' . $conn->error]);
                    exit;
                }

                $stmt->bind_param(
                    "issssssss",
                    $quiz_id,
                    $question_text,
                    $question_type,
                    $option_a,
                    $option_b,
                    $option_c,
                    $option_d,
                    $correct_answer,
                    $short_answer
                );

                if (!$stmt->execute()) {
                    error_log("Execute failed: " . $stmt->error);
                    http_response_code(500);
                    echo json_encode(['error' => 'Failed to save question: ' . $stmt->error]);
                    exit;
                }

                $stmt->close();
            }
        }

        echo json_encode(['success' => 'Quiz created successfully!']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to save quiz.']);
    }

    $conn->close();
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Invalid request method.']);
}