<?php
require_once 'db_connect.php';



// Get quiz details for AJAX request
if (isset($_GET['get_quiz']) && isset($_GET['quiz_id'])) {
    $quiz_id = intval($_GET['quiz_id']);

    $stmt = $conn->prepare("SELECT game_title FROM quiz WHERE quiz_id = ?");
    $stmt->bind_param("i", $quiz_id);
    $stmt->execute();
    $quiz_result = $stmt->get_result();

    if ($quiz = $quiz_result->fetch_assoc()) {
        echo json_encode(['success' => true, 'quiz' => $quiz]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Quiz not found']);
    }
    exit;
}

// Only output HTML if this file is not being included
if (basename($_SERVER['PHP_SELF']) === 'edit_quiz.php'):
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <title>Edit Quiz Modal</title>
        <link rel="stylesheet" href="css/edit_quiz.css">
    </head>

    <body>
    <?php endif; ?>

    <!-- Edit Quiz Modal -->
    <div id="editQuizModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Edit Quiz</h2>
            <div id="message" class="message" style="display: none;"></div>
            <form id="editQuizForm">
                <input type="hidden" id="quiz_id" name="quiz_id">
                <label for="edit_game_title">Quiz Title:</label>
                <input type="text" id="edit_game_title" name="edit_game_title" required>
                <button type="submit">Update Quiz</button>
            </form>
        </div>
    </div>
    </body>
    </html>