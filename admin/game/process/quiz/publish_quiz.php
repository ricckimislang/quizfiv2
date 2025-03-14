<?php
require_once '../db_connect.php';

if (isset($_POST['quiz_id']) && isset($_POST['current_status'])) {
    $quiz_id = $_POST['quiz_id'];
    $current_status = $_POST['current_status'];
    $new_status = $current_status === 'yes' ? 'not' : 'yes'; // Toggle between 'yes' and 'not'

    try {
        $stmt = $conn->prepare("UPDATE quiz SET is_published = ? WHERE quiz_id = ?");
        $stmt->bind_param("si", $new_status, $quiz_id);
        
        if ($stmt->execute()) {
            $message = $new_status === 'yes' ? 'Quiz published successfully' : 'Quiz unpublished successfully';
            echo json_encode(['success' => true, 'message' => $message, 'new_status' => $new_status]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update quiz status']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Missing required parameters']);
}
