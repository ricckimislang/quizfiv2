<?php
session_start();
include '../db/dbconn.php';

$data = json_decode(file_get_contents('php://input'), true);
$quiz_id = $data['quiz_id'];
$user_id = $data['user_id'];
$score = $data['score'];
$classroom_id = $data['classroom_id'];

// Retrieve the quiz difficulty
$difficultyQuery = "SELECT difficulty FROM quiz WHERE quiz_id = ?";
$difficultyStmt = $conn->prepare($difficultyQuery);
$difficultyStmt->bind_param('i', $quiz_id);
$difficultyStmt->execute();
$difficultyStmt->bind_result($difficulty);
$difficultyStmt->fetch();
$difficultyStmt->close();

// Retrieve points based on difficulty from the categories table
$pointsQuery = "SELECT perfect, consolation FROM categories WHERE difficulty = ?";
$pointsStmt = $conn->prepare($pointsQuery);
$pointsStmt->bind_param('s', $difficulty);
$pointsStmt->execute();
$pointsStmt->bind_result($perfectPoints, $consolationPoints);
$pointsStmt->fetch();
$pointsStmt->close();

// Retrieve the total number of questions for the quiz
$totalQuestionsQuery = "SELECT COUNT(*) FROM questions WHERE quiz_id = ?";
$totalQuestionsStmt = $conn->prepare($totalQuestionsQuery);
$totalQuestionsStmt->bind_param('i', $quiz_id);
$totalQuestionsStmt->execute();
$totalQuestionsStmt->bind_result($totalQuestions);
$totalQuestionsStmt->fetch();
$totalQuestionsStmt->close();

// Determine awarded points based on whether the score is perfect
$awardedPoints = ($score == $totalQuestions) ? $perfectPoints : $consolationPoints;

// Insert into quiz_attempt with the awarded points
$attempt_time = date('Y-m-d H:i:s');
$next_attempt_time = date('Y-m-d H:i:s', strtotime('+7 days'));
$query = "INSERT INTO quiz_attempt (user_id, quiz_id, classroom_id,attempt_time, next_attempt_time, score_status, awarded_points) 
          VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param('iiisssi', $user_id, $quiz_id, $classroom_id,$attempt_time, $next_attempt_time, $score, $awardedPoints);

$updateScoreQuery = "UPDATE students SET score = score + ? WHERE user_id = ?";
$updateScoreStmt = $conn->prepare($updateScoreQuery);
$updateScoreStmt->bind_param('ii', $awardedPoints, $user_id);


if ($stmt->execute()) {
    $updateScoreStmt->execute();
    if ($updateScoreStmt->affected_rows > 0) {
        echo json_encode(['success' => true, 'points_awarded' => $awardedPoints]);
    } else {
        echo json_encode(['success' => false, 'error' => $updateScoreStmt->error]);
    }
} else {
    echo json_encode(['success' => false, 'error' => $stmt->error]);
}

$stmt->close();
$conn->close();
