<?php
session_start();
include '../../db/dbconn.php';
header("Content-Type: application/json");

// Use prepared statements to prevent SQL injection
$quiz_id = intval($_GET['quiz_id']);
$current_id = intval($_GET['current_id']);

$stmt = $conn->prepare("SELECT * FROM questions WHERE quiz_id = ? AND question_id > ? ORDER BY question_id ASC LIMIT 1");
$stmt->bind_param("ii", $quiz_id, $current_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $nextQuestion = $result->fetch_assoc();
    echo json_encode(['success' => true, 'question' => $nextQuestion]);
} else {
    echo json_encode(['success' => false]); // No more questions
}
$stmt->close();