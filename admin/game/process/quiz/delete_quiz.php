<?php
require_once '../db_connect.php';

if (!isset($_GET['quiz_id'])) {
    header("Location: create_quiz.php?error=" . urlencode("Quiz ID is required"));
    exit;
}

$quiz_id = intval($_GET['quiz_id']);

try {
    // Start transaction
    $conn->begin_transaction();

    // Delete questions first
    $stmt = $conn->prepare("DELETE FROM questions WHERE quiz_id = ?");
    $stmt->bind_param("i", $quiz_id);
    $stmt->execute();
    $stmt->close();

    // Delete quiz
    $stmt = $conn->prepare("DELETE FROM quiz WHERE quiz_id = ?");
    $stmt->bind_param("i", $quiz_id);
    $stmt->execute();
    $stmt->close();

    // Commit transaction
    $conn->commit();
    
    header("Location: ../../create_quiz.php?success=" . urlencode("Quiz deleted successfully!"));

} catch (Exception $e) {
    // Rollback on error
    $conn->rollback();
    header("Location: create_quiz.php?error=" . urlencode("Error deleting quiz: " . $e->getMessage()));
}

exit;
?>