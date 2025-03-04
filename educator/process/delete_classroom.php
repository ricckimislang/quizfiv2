<?php
session_start();
require_once '../db/dbconn.php';
header('Content-Type: application/json');

// Check if user is logged in and is a teacher
if (!isset($_SESSION['user_id']) || $_SESSION['usertype'] !== 'educator') {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized access']);
    exit();
}

// Validate classroom id before deleting
if (!isset($_POST['classroom_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Classroom ID not provided.']);
    exit();
}

$classroom_id = intval($_POST['classroom_id']);

// Prepare statements for deleting related data
$stdnt = $conn->prepare("DELETE FROM student_classroom WHERE classroom_id = ?");
$quizRoom = $conn->prepare("DELETE FROM quiz WHERE classroom_id = ?");
$qstn = $conn->prepare("DELETE FROM questions WHERE quiz_id IN (SELECT quiz_id FROM quiz WHERE classroom_id = ?)");
$stmt = $conn->prepare("DELETE FROM classroom WHERE classroom_id = ?");

$conn->begin_transaction();

try {
    // Bind and execute each statement
    $stdnt->bind_param("i", $classroom_id);
    $stdnt->execute();

    $quizRoom->bind_param("i", $classroom_id);
    $quizRoom->execute();

    $qstn->bind_param("i", $classroom_id);
    $qstn->execute();

    $stmt->bind_param("i", $classroom_id);
    $stmt->execute();

    // Commit the transaction
    $conn->commit();
    echo json_encode(['status' => 'success', 'message' => 'Classroom and associated data deleted successfully']);
} catch (Exception $e) {
    // Rollback the transaction in case of error
    $conn->rollback();
    echo json_encode(['status' => 'error', 'message' => 'Failed to delete classroom: ' . $e->getMessage()]);
}
