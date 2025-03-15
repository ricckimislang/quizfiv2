<?php
require_once '../db/dbconn.php';
header('Content-Type: application/json');

if (!isset($_POST['password_user_id']) || !isset($_POST['currentPassword']) || !isset($_POST['newPassword']) || !isset($_POST['confirmPassword'])) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

$user_id = $_POST['password_user_id'];
$currentPassword = $_POST['currentPassword'];
$newPassword = $_POST['newPassword'];
$confirmPassword = $_POST['confirmPassword'];

try {

    $stmt = $conn->prepare("SELECT password FROM user WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    $currentPassword = md5($currentPassword);

    if ($currentPassword !== $row['password']) {
        echo json_encode(['success' => false, 'message' => 'Current password is incorrect']);
        exit;
    }

    if ($newPassword !== $confirmPassword) {
        echo json_encode(['success' => false, 'message' => 'New password and confirm password do not match']);
        exit;
    }

    $hashedPassword = md5($newPassword);

    $updatePassword = $conn->prepare("UPDATE user SET password = ? WHERE user_id = ?");
    $updatePassword->bind_param("si", $hashedPassword, $user_id);
    $updatePassword->execute();

    echo json_encode(['success' => true, 'message' => 'Password updated successfully']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error updating password']);
} finally {
    if (isset($stmt)) $stmt->close();
    if (isset($updatePassword)) $updatePassword->close();
    $conn->close();
}
