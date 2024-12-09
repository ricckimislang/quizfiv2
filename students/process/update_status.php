<?php
require_once '../db/dbconn.php'; // Ensure your database connection is included
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate that the user_id and timer_status are set
    if (isset($_POST['user_id']) && isset($_POST['timer_status'])) {
        $user_id = $_POST['user_id'];
        $timer_status = $_POST['timer_status'];

        // Optional: Validate input types (if necessary)
        if (!is_numeric($user_id) || !in_array($timer_status, ['active', 'inactive'])) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
            exit;
        }

        // Prepare and execute your SQL statement to update the timer status
        $stmt = $conn->prepare("UPDATE students SET status = ? WHERE user_id = ?");
        $stmt->bind_param("si", $timer_status, $user_id);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'You are now Connected.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => $stmt->error]);
        }

        $stmt->close();
    } else {
        // Handle the case where input parameters are not set
        echo json_encode(['status' => 'error', 'message' => 'user_id and timer_status are required']);
    }
} else {
    // Handle invalid request methods
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
