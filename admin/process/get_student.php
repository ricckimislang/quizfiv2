<?php
include_once '../db/dbconn.php';
header('Content-Type: application/json');
session_start();

if (isset($_POST['student_id'])) {
    $student_id = $_POST['student_id'];

    // Check if the connection is successful
    if (!$conn) {
        die(json_encode(['error' => "Connection failed: " . mysqli_connect_error()]));
    }

    // Prepare the SQL statement
    $stmt = $conn->prepare("SELECT * FROM students WHERE student_id = ?");

    if ($stmt) {
        $stmt->bind_param("i", $student_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $student_userId = $row['user_id'];

            // Prepare the second SQL statement to fetch user details
            $studentAccount = $conn->prepare("SELECT user_email, username FROM user WHERE user_id = ?");
            $studentAccount->bind_param("i", $student_userId);
            $studentAccount->execute();
            $studendAccountResult = $studentAccount->get_result();
            $studentAccountData = $studendAccountResult->fetch_assoc();

            // Combine student data with user account data
            $data = $row;
            $data['user_email'] = $studentAccountData['user_email'];
            $data['username'] = $studentAccountData['username'];

            echo json_encode($data); // Send combined data as JSON response
        } else {
            echo json_encode(['error' => 'Student not found']); // Send error response if student not found
        }
    } else {
        echo json_encode(['error' => 'Failed to prepare statement']); // Send error response if failed to prepare statement
    }
} else {
    echo json_encode(['error' => 'Student ID not provided']); // Send error response if student_id is not set
}
?>