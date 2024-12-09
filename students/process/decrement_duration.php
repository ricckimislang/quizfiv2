<?php
// Database connection
$conn = new mysqli('localhost', 'root', 'quizfiroot', 'quizfi');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch users with active status
$query = "SELECT user_id, duration FROM students WHERE status = 'active'";
$result = $conn->query($query);

if (!$result) {
    die("Query failed: " . $conn->error);
}

while ($row = $result->fetch_assoc()) {
    $userId = $row['user_id'];
    $remainingDuration = $row['duration'];

    // Validate that duration is a positive integer
    if (is_numeric($remainingDuration) && $remainingDuration > 0) {
        // Decrement the duration by one second
        $newDuration = $remainingDuration - 1;

        // Ensure duration does not go negative
        if ($newDuration < 0) {
            $newDuration = 0;
        }

        // Update the user's remaining duration in the database
        $updateQuery = "UPDATE students SET duration = '$newDuration' WHERE user_id = '$userId'";
        if (!$conn->query($updateQuery)) {
            echo "Error updating user_id $userId: " . $conn->error . "\n";
        }
    } else {
        echo "Invalid or zero duration for user_id $userId: $remainingDuration\n";
    }
}

// Close database connection
$conn->close();
?>
