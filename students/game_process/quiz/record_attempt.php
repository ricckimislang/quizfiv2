<?php
session_start();
require_once '../db_connect.php';
require_once '../db_connect_student.php';

header('Content-Type: application/json');

// Validate input parameters
if (!isset($_POST['quiz_id']) || !isset($_POST['score']) || !isset($_SESSION['user_id'])) {
    http_response_code(400);
    echo json_encode([
        'success' => false, 
        'message' => 'Missing required parameters'
    ]);
    exit;
}

// Validate data types and ranges
$quiz_id = filter_var($_POST['quiz_id'], FILTER_VALIDATE_INT);
$score = filter_var($_POST['score'], FILTER_VALIDATE_INT);
$user_id = filter_var($_POST['user_id'], FILTER_VALIDATE_INT);

if ($quiz_id === false || $score === false || $user_id === false) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Invalid parameter types'
    ]);
    exit;
}

try {
    $conn->begin_transaction();

    $current_date = date('Y-m-d H:i:s');
    
    // Check if user already attempted this quiz recently
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
            throw new Exception('You can only attempt this quiz once per week');
        }
    }

    // Insert the new attempt record
    $insert_stmt = $conn->prepare("INSERT INTO game_attempts (game_id, user_id, attempt_date, status) 
                                  VALUES (?, ?, ?, ?)");
    $status = 'done';
    $insert_stmt->bind_param("iiss", $quiz_id, $user_id, $current_date, $status);
    $insert_stmt->execute();


    // insert the score to student account
    $addScore = $connStudent->prepare("UPDATE students SET score = score + ? WHERE user_id = ?");
    $addScore->bind_param("ii", $score, $user_id);
    $addScore->execute();

    $conn->commit();
    
    echo json_encode([
        'success' => true, 
        'message' => 'Attempt recorded successfully',
        'score' => $score
    ]);

} catch (Exception $e) {
    $conn->rollback();
    http_response_code(500);
    echo json_encode([
        'success' => false, 
        'message' => 'Error: ' . $e->getMessage()
    ]);
}
