<?php
session_start();
require_once '../db/dbconn.php';
header('Content-Type: application/json');

if (isset($_POST['roomId'])) {

    $roomId = $_POST['roomId'];
    $studentId = $_POST['studentId'];

    $stmt = $conn->prepare("DELETE FROM student_classroom WHERE classroom_id = ? AND student_id = ?");

    try {
        $conn->begin_transaction();
        $stmt->bind_param("ii", $roomId, $studentId);
        if ($stmt->execute()) {
            $conn->commit();
            echo json_encode(['status' => 'success', 'message' => 'Classroom left successfully.']);
        } else {
            $conn->rollback();
            echo json_encode(['status' => 'error', 'message' => 'Failed to leave classroom.']);
        }
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}
