<?php
// Include database connection
require_once '../../db_connect.php';  // Fix path to go up two levels

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get POST data
    $question_id = isset($_POST['question_id']) ? intval($_POST['question_id']) : 0;
    
    if ($question_id === 0) {
        echo json_encode(['success' => false, 'message' => 'Question ID is required']);
        exit;
    }
    
    // Delete the question
    $stmt = $conn->prepare("DELETE FROM questions WHERE question_id = ?");
    $stmt->bind_param("i", $question_id);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Question deleted successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error deleting question: ' . $stmt->error]);
    }
    
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method Question ID is required']);
}
?> 