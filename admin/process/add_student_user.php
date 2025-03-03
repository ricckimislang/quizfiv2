<?php

session_start();
include '../db/dbconn.php';
header('Content-Type: application/json');


if (isset($_POST['studentEmail-reg'], $_POST['studentFname-reg'], $_POST['studentLname-reg'], $_POST['studentYear-reg'], $_POST['studentDepartment-reg'])) {
    $studentEmail = $_POST['studentEmail-reg'];
    $studentFname = $_POST['studentFname-reg'];
    $studentLname = $_POST['studentLname-reg'];
    $studentYear = $_POST['studentYear-reg'];
    $studentDepartment = $_POST['studentDepartment-reg'];

    // create username and password
    $username = $studentLname . '_' . substr($studentFname, 0, 3);
    $password = 'Seait123';
    $hashed_password = md5($password);

    // Check for duplicate user by username before inserting
    $checkDuplicateStmt = $conn->prepare("SELECT * FROM user WHERE username = ? AND user_email = ?");
    $checkDuplicateStmt->bind_param('ss', $username, $studentEmail);
    $checkDuplicateStmt->execute();
    $duplicateResult = $checkDuplicateStmt->get_result();

    if ($duplicateResult->num_rows > 0) {
        echo json_encode(['status' => 'duplicate', 'message' => 'User already exists.']);
        exit;
    }

    $studentRegister = $conn->prepare('INSERT INTO user (user_email, username, password, usertype) VALUES (?,?,?,"student")');
    $studentRegister->bind_param('sss', $studentEmail, $username, $hashed_password);
    $studentRegister->execute();

    if ($studentRegister) {
        $user_id = $conn->insert_id;
        $studentInsert = $conn->prepare('INSERT INTO students (user_id, firstname, lastname, year, department, profile_path) VALUES (?,?,?,?,?,"assets/avatars/no-profile.jpg")');
        $studentInsert->bind_param('issss', $user_id, $studentFname, $studentLname, $studentYear, $studentDepartment);

        if ($studentInsert->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Student registered successfully.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to register student.']);
        }
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Missing required fields.']);
}
