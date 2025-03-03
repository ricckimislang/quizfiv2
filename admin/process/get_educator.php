<?php
include_once '../db/dbconn.php';
header('Content-Type: application/json');
session_start();

if (isset($_POST['educator_id'])) {
    $educator_id = $_POST['educator_id'];

    // Check if the connection is successful
    if (!$conn) {
        die(json_encode(['error' => "Connection failed: " . mysqli_connect_error()]));
    }

    // Prepare the SQL statement
    $stmt = $conn->prepare("SELECT * FROM educators WHERE educator_id = ?");

    if ($stmt) {
        $stmt->bind_param("i", $educator_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $educator_userId = $row['user_id'];

            // Prepare the second SQL statement to fetch user details
            $educatorAccount = $conn->prepare("SELECT user_email, username FROM user WHERE user_id = ?");
            $educatorAccount->bind_param("i", $educator_userId);
            $educatorAccount->execute();
            $studendAccountResult = $educatorAccount->get_result();
            $educatorAccountData = $studendAccountResult->fetch_assoc();

            // Combine student data with user account data
            $data = $row;
            $data['user_email'] = $educatorAccountData['user_email'];
            $data['username'] = $educatorAccountData['username'];

            echo json_encode($data); // Send combined data as JSON response
        } else {
            echo json_encode(['error' => 'Educator not found']); // Send error response if Educator not found
        }
    } else {
        echo json_encode(['error' => 'Failed to prepare statement']); // Send error response if failed to prepare statement
    }
} else {
    echo json_encode(['error' => 'Educator ID not provided']); // Send error response if Educator_id is not set
}
?>