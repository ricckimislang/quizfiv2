<?php
session_start();
require_once '../db_connect.php';

header('Content-Type: application/json');

// Get and validate input
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['quiz_id']) || !isset($data['user_id'])) {
    http_response_code(400);
    echo json_encode([
        'canAttempt' => false,
        'message' => 'Missing required parameters'
    ]);
    exit;
}

$quiz_id = filter_var($data['quiz_id'], FILTER_VALIDATE_INT);
$user_id = filter_var($data['user_id'], FILTER_VALIDATE_INT);

if ($quiz_id === false || $user_id === false) {
    http_response_code(400);
    echo json_encode([
        'canAttempt' => false,
        'message' => 'Invalid parameter types'
    ]);
    exit;
}

try {
    // Check if user already attempted this quiz in the last week
    $check_stmt = $conn->prepare("SELECT attempt_date FROM game_attempts 
                                 WHERE game_id = ? AND user_id = ?
                                 ORDER BY attempt_date DESC LIMIT 1");
    $check_stmt->bind_param("ii", $quiz_id, $user_id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        $last_attempt = strtotime($row['attempt_date']);
        $week_ago = strtotime('-1 week');
        
        if ($last_attempt > $week_ago) {
            echo json_encode([
                'canAttempt' => false,
                'message' => 'You can only attempt this quiz once per week'
            ]);
            exit;
        }
    }

    // If we get here, user can attempt the quiz
    echo json_encode([
        'canAttempt' => true,
        'message' => 'You may proceed with the quiz'
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'canAttempt' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
}
?>
