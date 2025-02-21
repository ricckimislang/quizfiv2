<?php
include_once '../db/dbconn.php';
header('Content-Type: application/json');
session_start();

if (isset($_POST['student_id'])) {

    $student_id = $_POST['student_id'];

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $stmt = $conn->prepare("SELECT * FROM students WHERE student_id = ?");

    if ($stmt) {
        $stmt->bind_param("s", $student_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            echo json_encode($row); // Send student data as JSON response
        } else {
            echo json_encode(['error' => 'Student not found']); // Send error response if student not found
        }
    } else {
        echo json_encode(['error' => 'Failed to prepare statement']); // Send error response if failed to prepare statement
    }
}
