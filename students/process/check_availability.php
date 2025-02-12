<?php
require_once("../db/dbconn.php");
session_start();

header("Content-Type: application/json");

// Ensure user_id is set in the session
if (!isset($_SESSION['user_id'])) {
    echo json_encode(array("success" => false, "message" => "User  not logged in."));
    exit();
}

if (isset($_POST['quizId'])) { // Changed to match the JavaScript
    $quiz_id = $_POST['quizId'];
    $user_id = $_SESSION['user_id']; // Get user_id from session

    // Prepare the statement
    $stmt = $conn->prepare("SELECT * FROM quiz_attempt WHERE quiz_id = ? AND user_id = ?");
    if ($stmt === false) {
        echo json_encode(array("success" => false, "message" => "Database error: " . $conn->error));
        exit();
    }

    $stmt->bind_param("ii", $quiz_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode(array("success" => false, "message" => "You have already attempted this quiz."));
    } else {
        echo json_encode(array("success" => true));
    }

    $stmt->close();
} else {
    echo json_encode(array("success" => false, "message" => "Invalid request."));
}
