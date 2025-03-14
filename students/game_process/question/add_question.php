<?php
require_once '../../db_connect.php';

// Process form submission for adding a question
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get quiz_id first
    $quiz_id = isset($_POST['quiz_id']) ? intval($_POST['quiz_id']) : 0;
    
    if ($quiz_id === 0) {
        echo json_encode(['success' => false, 'message' => 'Quiz ID is required']);
        exit;
    }

    // Check number of existing questions for this quiz
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM questions WHERE quiz_id = ?");
    $stmt->bind_param("i", $quiz_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $count = $result->fetch_assoc()['count'];
    $stmt->close();

    if ($count >= 10) {
        echo json_encode(['success' => false, 'message' => 'Maximum limit of 10 questions reached for this quiz']);
        exit;
    }
    
    // Get form data
    $question_text = trim($_POST['question_text']);
    $option_a = trim($_POST['option_a']);
    $option_b = trim($_POST['option_b']);
    $option_c = trim($_POST['option_c']);
    $option_d = trim($_POST['option_d']);
    $correct_answer = $_POST['correct_answer'];
    $hint = trim($_POST['hint']);

    // Validate form data
    if (empty($question_text) || empty($option_a) || empty($option_b) || empty($option_c) || empty($option_d) || empty($correct_answer)) {
        $error_message = "All fields except hint are required";
    } else {
        // Insert new question
        $stmt = $conn->prepare("INSERT INTO questions (quiz_id, question_text, option_a, option_b, option_c, option_d, correct_answer, hint) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssssss", $quiz_id, $question_text, $option_a, $option_b, $option_c, $option_d, $correct_answer, $hint);

        if ($stmt->execute()) {
            $success_message = "Question added successfully!";

            // Clear form data for new question
            $question_text = $option_a = $option_b = $option_c = $option_d = $hint = '';
            $correct_answer = '';
            echo json_encode(['success' => true, 'message' => 'Question added successfully!']);
        } else {
            $error_message = "Error: " . $stmt->error;
            echo json_encode(['success' => false, 'message' => 'Error: ' . $stmt->error]);
        }

        $stmt->close();
    }
}
