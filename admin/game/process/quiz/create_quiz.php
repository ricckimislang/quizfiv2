<?php
require_once '../db_connect.php';
session_start();

// Process form submission for creating a new quiz
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['game_title'])) {
    $game_title = trim($_POST['game_title']);

    if (empty($game_title)) {
        $error_message = "Quiz title is required";
        echo json_encode(['success' => false, 'message' => $error_message]);
    } else {
        // Check if quiz title already exists
        $check_stmt = $conn->prepare("SELECT COUNT(*) FROM quiz WHERE game_title = ?");
        $check_stmt->bind_param("s", $game_title);
        $check_stmt->execute();
        $check_stmt->bind_result($count);
        $check_stmt->fetch();
        $check_stmt->close();

        if ($count > 0) {
            $error_message = "A quiz with this title already exists";
            echo json_encode(['success' => false, 'message' => 'A quiz with this title already exists']);
        } else {
            // Insert new quiz
            $stmt = $conn->prepare("INSERT INTO quiz (game_title) VALUES (?)");
            $stmt->bind_param("s", $game_title);

            if ($stmt->execute()) {
                $quiz_id = $conn->insert_id;
                $_SESSION['success_message'] = "Quiz created successfully! Now add questions.";
                echo json_encode(['success' => true]);
            } else {
                $error_message = "Error: " . $stmt->error;
                echo json_encode(['success' => false, 'message' => $error_message]);
            }

            $stmt->close();
        }
    }
}

?>