<?php
require_once '../db_connect.php';

// Validate request method (Allow GET instead of POST)
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    exit(json_encode(['error' => 'Only GET requests are allowed']));
}

// Get quiz_id from URL (e.g., get_questions.php?quiz_id=1)
if (!$quiz_id = filter_input(INPUT_GET, 'quiz_id', FILTER_VALIDATE_INT)) {
    http_response_code(400);
    exit(json_encode(['error' => 'Invalid quiz ID']));
}

try {
    $stmt = $conn->prepare("SELECT question_text, option_a, option_b, option_c, option_d, correct_answer, hint FROM questions WHERE quiz_id = ?");
    $stmt->bind_param("i", $quiz_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $questions = [];
    while ($row = $result->fetch_assoc()) {
        $questions[] = $row;
    }
    header('Content-Type: application/json');
    echo json_encode($questions);
} catch (Exception $e) {
    http_response_code(500);
    exit(json_encode(['error' => 'Server error: ' . $e->getMessage()]));
} finally {
    $stmt->close();
}
