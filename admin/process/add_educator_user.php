<?php
require_once '../db/dbconn.php';
header('Content-Type: application/json');

if (isset($_POST['educatorEmail-reg'], $_POST['educatorFname-reg'], $_POST['educatorLname-reg'], $_POST['educatorDepartment-reg'])) {
    $educatorEmail = $_POST['educatorEmail-reg'];
    $educatorFname = $_POST['educatorFname-reg'];
    $educatorLname = $_POST['educatorLname-reg'];
    $educator_department = $_POST['educatorDepartment-reg'];

    // create username and password
    $username = $educatorFname . '_' . substr($educatorLname, 0, 3);
    $password = 'Seait123';
    $hashed_password = md5($password);

    // Check for duplicate user by username before inserting
    $checkDuplicateStmt = $conn->prepare("SELECT * FROM user WHERE username = ? AND user_email = ?");
    $checkDuplicateStmt->bind_param('ss', $username, $educatorEmail);
    $checkDuplicateStmt->execute();
    $duplicateResult = $checkDuplicateStmt->get_result();

    if ($duplicateResult->num_rows > 0) {
        echo json_encode(['status' => 'duplicate', 'message' => 'User already exists']);
        exit;
    }

    // user account for educator
    $userAccount = $conn->prepare("INSERT INTO user (user_email, username, password, usertype) VALUES (?,?,?,'educator')");
    $userAccount->bind_param('sss', $educatorEmail, $username, $hashed_password);
    $userAccount->execute();

    if ($userAccount) {
        $user_id = $conn->insert_id;
        $educatorInsert = $conn->prepare('INSERT INTO educators (user_id, firstname, lastname, department) VALUES (?,?,?,?)');
        $educatorInsert->bind_param('isss', $user_id, $educatorFname, $educatorLname, $educator_department);

        if ($educatorInsert->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Educator registered successfully.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to register educator.']);
        }
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Missing required fields.']);
}
