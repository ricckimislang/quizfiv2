<?php
require_once '../db/dbconn.php';

header('Content-Type: application/json'); // Ensure the response is JSON

if (isset($_POST['difficulty'], $_POST['perfect'], $_POST['consolation'])) {
    // Get the posted data and sanitize it
    $category = $_POST['difficulty'];
    $perfect = $_POST['perfect'];
    $consolation = $_POST['consolation'];

    // Corrected SQL query with SET keyword
    $stmtRe = "UPDATE categories SET perfect = ?, consolation = ? WHERE difficulty = ?";

    // Prepare the statement
    if ($stmt = $conn->prepare($stmtRe)) {
        // Bind parameters (assuming perfect and consolation are strings)
        $stmt->bind_param('sss', $perfect, $consolation, $category);

        // Execute the query and handle the result
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Rewards updated successfully.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error updating rewards: ' . $stmt->error]);
        }

        // Close the statement
        $stmt->close();
    } else {
        // If prepare fails, provide feedback
        echo json_encode(['status' => 'error', 'message' => 'Failed to prepare statement: ' . $conn->error]);
    }
} else {
    // Handle missing data case
    echo json_encode(['status' => 'error', 'message' => 'Required data not provided']);
}

// Close the database connection
$conn->close();
