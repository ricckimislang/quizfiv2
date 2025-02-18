<?php

require_once("../db/dbconn.php");
session_start();
// Set the header to ensure JSON output
header('Content-Type: application/json');

if (isset($_POST['user_id'], $_POST['classroomCode'])) {
    $user_id = $_POST['user_id'];
    $classroomCode = $_POST['classroomCode'];

    // Get classroom_id
    $roomStmt = $conn->prepare("SELECT classroom_id FROM classroom WHERE classroom_code = ?");
    $roomStmt->bind_param('s', $classroomCode);
    $roomStmt->execute();
    $roomResult = $roomStmt->get_result();

    if ($roomResult->num_rows > 0) {
        $room = $roomResult->fetch_assoc();
        $classroom_id = $room['classroom_id'];

        // Get student_id
        $studentStmt = $conn->prepare("SELECT student_id FROM students WHERE user_id = ?");
        $studentStmt->bind_param("i", $user_id);
        $studentStmt->execute();
        $studentResult = $studentStmt->get_result();

        if ($studentResult->num_rows > 0) {
            $student = $studentResult->fetch_assoc();
            $student_id = $student['student_id'];

            // Check if the student is already joined in the classroom
            $checkStmt = $conn->prepare("SELECT * FROM student_classroom WHERE classroom_id = ? AND student_id = ?");
            $checkStmt->bind_param("ii", $classroom_id, $student_id);
            $checkStmt->execute();
            $checkResult = $checkStmt->get_result();

            if ($checkResult->num_rows > 0) {
                // Student is already in the classroom
                echo json_encode(["status" => "error", "message" => "Student has already joined in this classroom"]);
            } else {
                // Insert student to classroom
                $insertStmt = $conn->prepare("INSERT INTO student_classroom (classroom_id, student_id) VALUES (?, ?)");
                $insertStmt->bind_param("ii", $classroom_id, $student_id);

                if ($insertStmt->execute()) {
                    echo json_encode(["status" => "success", "message" => "Student added to classroom successfully", "classroomId" => $classroom_id, "studentId" => $student_id]);
                } else {
                    echo json_encode(["status" => "error", "message" => "Failed to add student to classroom"]);
                }

                $insertStmt->close();
            }

            $checkStmt->close();
        } else {
            echo json_encode(["status" => "error", "message" => "Student not found"]);
        }

        $studentStmt->close();
    } else {
        echo json_encode(["status" => "error", "message" => "Classroom not found"]);
    }

    $roomStmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Invalid input"]);
}

$conn->close();
