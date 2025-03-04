<?php
session_start();
include_once '../db/dbconn.php';

// Check if user is logged in and is a teacher
if (!isset($_SESSION['user_id']) || $_SESSION['usertype'] !== 'educator') {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized access']);
    exit();
}

// Check if classroom_id and student_ids were provided
if (!isset($_POST['classroom_id']) || !isset($_POST['student_ids']) || !is_array($_POST['student_ids'])) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid parameters']);
    exit();
}

$classroom_id = (int) $_POST['classroom_id'];
$student_ids = $_POST['student_ids'];

// Validate that this teacher owns this classroom
$teacherCheck = $conn->prepare("SELECT classroom_id FROM classroom WHERE classroom_id = ? AND user_id = ?");
$teacherCheck->bind_param("ii", $classroom_id, $_SESSION['user_id']);
$teacherCheck->execute();
$result = $teacherCheck->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['status' => 'error', 'message' => 'You do not have permission to modify this classroom']);
    exit();
}

// Prepare the deletion query with parameter binding for security
$stmt = $conn->prepare("DELETE FROM student_classroom WHERE classroom_id = ? AND student_id = ?");

$success = true;
$errorMessage = '';

// Start transaction to ensure atomicity
$conn->begin_transaction();

try {
    foreach ($student_ids as $student_id) {
        $student_id = (int) $student_id;
        $stmt->bind_param("ii", $classroom_id, $student_id);

        if (!$stmt->execute()) {
            throw new Exception("Failed to remove student ID: $student_id");
        }
    }

    // If we got here, all operations were successful
    $conn->commit();
    echo json_encode(['status' => 'success', 'message' => 'Students removed successfully']);
} catch (Exception $e) {
    // An error occurred, rollback changes
    $conn->rollback();
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}

$stmt->close();
$conn->close();
