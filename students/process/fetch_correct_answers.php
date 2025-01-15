<?php
session_start();
include '../db/dbconn.php';

$quiz_id = $_GET['quiz_id'];

// Fetch the quiz difficulty level to determine points
$difficultyQuery = "SELECT difficulty FROM quiz WHERE quiz_id = $quiz_id";
$difficultyResult = $conn->query($difficultyQuery);

if ($difficultyResult && $difficultyRow = $difficultyResult->fetch_assoc()) {
    $difficulty = $difficultyRow['difficulty'];

    // Retrieve points based on the quiz difficulty
    $pointsQuery = "SELECT perfect, consolation FROM categories WHERE difficulty = ?";
    $stmt = $conn->prepare($pointsQuery);
    $stmt->bind_param("s", $difficulty);
    $stmt->execute();
    $pointsResult = $stmt->get_result();

    if ($pointsResult && $pointsRow = $pointsResult->fetch_assoc()) {
        $perfectPoints = $pointsRow['perfect'];
        $consolationPoints = $pointsRow['consolation'];
    } else {
        $perfectPoints = 10;
        $consolationPoints = 5;
    }
    $stmt->close();
} else {
    $difficulty = null;
    $perfectPoints = 0;
    $consolationPoints = 0;
}

// Fetch all questions and their correct answers or short answers, and count total questions
$answersQuery = "SELECT question_id, quiz_type, correct_answer, short_answer FROM questions WHERE quiz_id = $quiz_id";
$result = $conn->query($answersQuery);

$correctAnswers = [];
$totalQuestions = 0;

while ($row = $result->fetch_assoc()) {
    $totalQuestions++;
    if ($row['quiz_type'] === 'Short Answer') {
        $correctAnswers[$row['question_id']] = $row['short_answer'];
    } else {
        $correctAnswers[$row['question_id']] = $row['correct_answer'];
    }
}

// Prepare the response data
$responseData = [
    'correctAnswers' => $correctAnswers,
    'pointsCriteria' => [
        'perfect' => $perfectPoints,
        'consolation' => $consolationPoints
    ],
    'totalQuestions' => $totalQuestions
];

echo json_encode($responseData);
?>
