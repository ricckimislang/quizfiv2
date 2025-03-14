<?php

require_once '../db_connect.php';

// Process AJAX request to update quiz
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_game_title'])) {
    $quiz_id = intval($_POST['quiz_id']);
    $new_title = trim($_POST['edit_game_title']);

    if (!empty($new_title)) {
        $stmt = $conn->prepare("UPDATE quiz SET game_title = ? WHERE quiz_id = ?");
        $stmt->bind_param("si", $new_title, $quiz_id);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Quiz updated successfully!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error updating quiz: ' . $stmt->error]);
        }

        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Quiz title cannot be empty']);
    }
    exit;
}
