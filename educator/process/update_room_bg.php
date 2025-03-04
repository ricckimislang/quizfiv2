<?php
session_start();
require_once '../db/dbconn.php';
header('Content-Type: application/json');

try {
    if (!isset($_POST['selected_avatar']) || !isset($_POST['classroom_id'])) {
        throw new Exception('Missing required data');
    }

    $classroom_id = $_POST['classroom_id'];
    $selected_avatar = $_POST['selected_avatar'];

    // Validate that the selected avatar exists in the avatars directory
    if (!file_exists('../' . $selected_avatar)) {
        throw new Exception('Invalid avatar selection');
    }

    // Update database with new avatar
    $stmt = $conn->prepare("UPDATE classroom SET profile_path = ? WHERE classroom_id = ?");
    $stmt->bind_param("si", $selected_avatar, $classroom_id);

    if (!$stmt->execute()) {
        throw new Exception('Error updating database');
    }

    echo json_encode([
        'status' => 'success',
        'message' => 'Wallpaper updated successfully'
    ]);
} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
