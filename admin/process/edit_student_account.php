<?php
session_start();
require_once '../db/dbconn.php';
header('Content-Type: application/json');

if (isset($_POST['student_id2'])) {
    $student_id = $_POST['student_id2'];
    $student_email = $_POST['account_email'];
    $student_user = $_POST['account_user'];

    $student_id = $_POST['student_id2'];
    $user_id = $conn->prepare("SELECT user_id FROM students WHERE student_id = ?");
    $user_id->bind_param("i", $student_id);
    $user_id->execute();
    $user_id_result = $user_id->get_result();
    $user_id_row = $user_id_result->fetch_assoc();
    $user_id = $user_id_row['user_id'];

    $userAccount = $conn->prepare("UPDATE user SET user_email = ?, username = ? WHERE user_id = ?");
    $userAccount->bind_param("ssi", $student_email, $student_user, $user_id);

    if ($userAccount->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Student Account Successfully Updated!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update student Account. Please try again.']);
    }

} else {
    echo json_encode(['status' => 'error', 'message' => 'Student ID is Required']);
}

?>