<?php
// Database connection
require_once("../db/dbconn.php");

if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $hashed_password = md5($password);
    $sql = "SELECT * FROM user WHERE username='$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($hashed_password == $row['password']) {
            session_start();
            $_SESSION['username'] = $username;
            $_SESSION['user_id'] = $row['user_id'];
            $user_type = $row['usertype'];
            echo json_encode(['status' => 'success', 'message' => 'Logged In successfully', 'userType' => $user_type,]);
        } else {
            echo json_encode(['status' => 'wrong password', 'message' => 'username or password is wrong!']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    }
}
