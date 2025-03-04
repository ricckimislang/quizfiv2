<?php
require_once '../db/dbconn.php';
header('Content-type: application/json');

if (isset($_POST['student_id'])) {
    $student_id = $_POST['student_id'];

    // get the user id first before deletin ang student
    $conn->begin_transaction(); // Start transaction

    $stmt = $conn->prepare("SELECT user_id FROM students WHERE student_id = ?");
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $stmt->bind_result($user_id);
    $stmt->fetch();
    $stmt->close();

    if ($user_id) {
        $stmt = $conn->prepare("DELETE FROM students WHERE student_id = ?");
        $stmt->bind_param("i", $student_id);
        $stmt->execute();
        $stmt->close();

        $stmt = $conn->prepare("DELETE FROM user WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->close();

        $conn->commit(); // Commit the transaction if everything is successful
        echo json_encode(['success' => 'Student deleted successfully.']);
    } else {
        $conn->rollback(); // Rollback if no user found
        echo json_encode(['status' => 'error', 'message' => 'Student ID not found.']);
    }
} elseif (isset($_POST['educator_id'])) {
    $educator_id = $_POST['educator_id'];

    // get the user id first before deletin ang educator
    $conn->begin_transaction(); // Start transaction
    $stmt = $conn->prepare("SELECT user_id FROM educators WHERE educator_id = ?");
    $stmt->bind_param("i", $educator_id);
    $stmt->execute();
    $stmt->bind_result($user_id);
    $stmt->fetch();
    $stmt->close();
    if ($user_id) {
        $stmt = $conn->prepare("DELETE FROM educators WHERE educator_id = ?");
        $stmt->bind_param("i", $educator_id);
        $stmt->execute();
        $stmt->close();
        $stmt = $conn->prepare("DELETE FROM user WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->close();
        $conn->commit(); // Commit the transaction if everything is successful
        echo json_encode(['status' => 'success', 'message' => 'Educator and associated user deleted.']);
    } else {
        $conn->rollback(); // Rollback if no user found
        echo json_encode(['status' => 'error', 'message' => 'Educator ID not found.']);
    }
} else {
    $response = array("status" => "error", "message" => "No ID provided.");
    echo json_encode($response);
}
