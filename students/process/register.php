<?php
require_once '../db/dbconn.php';

try {
    // Retrieve POST data
    $email = $_POST['email'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $year = $_POST['year'];
    $department = $_POST['department'];

    // Generate username and hash password
    $username = $lastName . '_' . substr($firstName, 0, 3);
    $password = 'Seait123';
    $hashedPassword = md5($password);

    // File upload handling
    if (isset($_FILES['idPhoto']) && $_FILES['idPhoto']['error'] == 0) {
        $allow = ['jpeg', 'jpg', 'png'];
        $fileExt = strtolower(pathinfo($_FILES['idPhoto']['name'], PATHINFO_EXTENSION));

        if (!in_array($fileExt, $allow)) {
            echo json_encode(['success' => false, 'message' => 'Invalid file format.']);
            exit;
        }

        // Check for duplicate user
        $checkDuplicateStmt = $conn->prepare("SELECT * FROM user WHERE username = ? OR user_email = ?");
        $checkDuplicateStmt->bind_param('ss', $username, $email);
        $checkDuplicateStmt->execute();
        $duplicateResult = $checkDuplicateStmt->get_result();

        if ($duplicateResult->num_rows > 0) {
            echo json_encode(['duplicate' => true, 'message' => 'User already exists.']);
            exit;
        }

        // Insert new user
        $stmt = $conn->prepare("INSERT INTO user (user_email, username, password, usertype) VALUES (?,?,?,'student')");
        $stmt->bind_param('sss', $email, $username, $hashedPassword);
        $stmt->execute();

        if (!$stmt) {
            echo json_encode(['success' => false, 'message' => 'Failed to register user.']);
            exit;
        }

        // Get the inserted user ID
        $user_id = $conn->insert_id;

        // Default profile path
        $profilePath = "assets/avatars/no-profile.jpg";
        $targetDir = "../assets/students/school_id/";
        $fileName = $lastName . '_' . time() . '.' . $fileExt;
        $targetFilePath = $targetDir . $fileName;

        if (move_uploaded_file($_FILES['idPhoto']['tmp_name'], $targetFilePath)) {
            $id_photo_path = "assets/students/school_id/" . $fileName;
        } else {
            echo json_encode(['success' => false, 'message' => 'File upload failed.']);
            exit;
        }

        // Insert student details
        $studentInsert = $conn->prepare('INSERT INTO students (user_id, firstname, lastname, year, department, id_photo,  profile_path) VALUES (?,?,?,?,?,?, "assets/avatars/no-profile.jpg")');
        $studentInsert->bind_param('isssss', $user_id, $firstName, $lastName, $year, $department, $profilePath);
        $studentInsert->execute();

        if (!$studentInsert) {
            echo json_encode(['success' => false, 'message' => 'Failed to register student.']);
            exit;
        }

        echo json_encode(['success' => true, 'message' => 'User registered successfully!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'No file uploaded.']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
