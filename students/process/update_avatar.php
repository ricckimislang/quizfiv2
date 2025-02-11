<?php
session_start();
include '../db/dbconn.php';

header('Content-Type: application/json');

try {
    if (!isset($_POST['selected_avatar']) || !isset($_POST['user_id'])) {
        throw new Exception('Missing required data');
    }

    $user_id = $_POST['user_id'];
    $selected_avatar = $_POST['selected_avatar'];

    // Validate that the selected avatar exists in the avatars directory
    if (!file_exists('../' . $selected_avatar)) {
        throw new Exception('Invalid avatar selection');
    }

    // Update database with new avatar
    $stmt = $conn->prepare("UPDATE students SET profile_path = ? WHERE user_id = ?");
    $stmt->bind_param("si", $selected_avatar, $user_id);
    
    if (!$stmt->execute()) {
        throw new Exception('Error updating database');
    }

    echo json_encode([
        'status' => 'success',
        'message' => 'Avatar updated successfully'
    ]);

} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
?>