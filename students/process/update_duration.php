<?php
// Include the database connection
session_start();
include '../db/dbconn.php';

// Query to fetch all users with active sessions
$query = "SELECT user_id, duration, status FROM students WHERE status = 'active' AND duration > 0";
$result = $conn->query($query);

// Check if there are users with active sessions
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $userId = $row['user_id'];
        $remainingTime = $row['duration']; // Time remaining in seconds
        $currentStatus = $row['status'];

        // If the timer is active, reduce the remaining time by 60 seconds
        if ($currentStatus === 'active' && $remainingTime > 0) {
            $newRemainingTime = $remainingTime - 60; // Decrease by 1 minute (60 seconds)

            // Ensure time doesn't go negative
            if ($newRemainingTime < 0) {
                $newRemainingTime = 0;
            }

            // Update the remaining time in the database
            $updateQuery = "UPDATE students SET duration = ? WHERE user_id = ?";
            $stmt = $conn->prepare($updateQuery);
            $stmt->bind_param("ii", $newRemainingTime, $userId); // Use integer type for seconds
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                echo "Successfully updated user $userId with remaining time: $newRemainingTime seconds.";
            } else {
                echo "Failed to update user $userId.";
            }

            $stmt->close();
        }
    }
} else {
    echo "No active users found.";
}

$conn->close();
