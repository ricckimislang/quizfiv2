<?php
require_once '../db_connect.php';


// Process form submission for updating a question
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $question_id = intval($_POST['question_id']);
    $quiz_id = intval($_POST['quiz_id']);
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
        // Update question
        $stmt = $conn->prepare("UPDATE questions SET question_text = ?, option_a = ?, option_b = ?, option_c = ?, option_d = ?, correct_answer = ?, hint = ? WHERE question_id = ?");
        $stmt->bind_param("sssssssi", $question_text, $option_a, $option_b, $option_c, $option_d, $correct_answer, $hint, $question_id);
        
        if ($stmt->execute()) {
            $success_message = "Question updated successfully!";
            echo json_encode(['success' => true, 'message' => $success_message]);
        } else {
            $error_message = "Error: " . $stmt->error;
            echo json_encode(['success' => false, 'message' => $error_message]);
        }
        
        $stmt->close();
    }
}
