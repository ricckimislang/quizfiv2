<?php
require_once 'db_connect.php';

if (isset($_GET['quiz_id'])) {
    $quiz_id = intval($_GET['quiz_id']);

    $stmt = $conn->prepare("SELECT game_title FROM quiz WHERE quiz_id = ?");
    $stmt->bind_param("i", $quiz_id);
    $stmt->execute();
    $quiz_result = $stmt->get_result();

    if ($quiz = $quiz_result->fetch_assoc()) {
        $quiz_title = $quiz['game_title'];

        $stmt = $conn->prepare("SELECT * FROM questions WHERE quiz_id = ?");
        $stmt->bind_param("i", $quiz_id);
        $stmt->execute();
        $questions = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    } else {
        echo "Quiz not found.";
        exit;
    }
} else {
    die("Quiz ID is required.");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>View Quiz</title>
    <link rel="stylesheet" href="css/view_quiz.css">
</head>

<body>
    <div class="container">
        <div class="navigation">
            <a href="create_quiz.php" style="color: #2196F3;">← Back to Quizzes</a>
        </div>
        <h1><?php echo htmlspecialchars($quiz_title); ?></h1>
        <div class="card">
            <div class="question-list">
                <?php if (empty($questions)): ?>
                    <p>No questions have been added to this quiz yet. <a href="add_questions.php?quiz_id=<?php echo $quiz_id; ?>" style="color: #2196F3;">Click here</a> to add questions.</p>
                <?php else: ?>
                    <?php foreach ($questions as $index => $question): ?>
                        <div class="question-item">
                            <div class="question-header">
                                <h3>Question <?php echo $index + 1; ?></h3>
                                <div class="actions">
                                    <a href="edit_question.php?question_id=<?php echo $question['question_id']; ?>" class="btn-small btn-primary">Edit</a>
                                    <a href="javascript:void(0)" class="btn-small btn-danger" onclick="deleteQuestion(<?php echo $question['question_id']; ?>)">Delete</a>
                                </div>
                            </div>
                            <p><?php echo htmlspecialchars($question['question_text']); ?></p>
                            <div class="question-options">
                                <div class="option <?php echo $question['correct_answer'] == 'A' ? 'correct' : ''; ?>">
                                    A: <?php echo htmlspecialchars($question['option_a']); ?>
                                </div>
                                <div class="option <?php echo $question['correct_answer'] == 'B' ? 'correct' : ''; ?>">
                                    B: <?php echo htmlspecialchars($question['option_b']); ?>
                                </div>
                                <div class="option <?php echo $question['correct_answer'] == 'C' ? 'correct' : ''; ?>">
                                    C: <?php echo htmlspecialchars($question['option_c']); ?>
                                </div>
                                <div class="option <?php echo $question['correct_answer'] == 'D' ? 'correct' : ''; ?>">
                                    D: <?php echo htmlspecialchars($question['option_d']); ?>
                                </div>
                            </div>
                            <?php if (!empty($question['hint'])): ?>
                                <p style="margin-top: 10px;"><strong>Hint:</strong> <?php echo htmlspecialchars($question['hint']); ?></p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
        <script src="js/jquery-3.7.1.js"></script>
        <script>
            function deleteQuestion(questionId) {
                // Create and show confirmation modal
                const modal = document.createElement('div');
                modal.style.cssText = `
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0,0,0,0.5);
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 1000;
            `;

                const modalContent = document.createElement('div');
                modalContent.style.cssText = `
                background: rgba(0, 20, 80, 0.8);
                padding: 20px;
                border-radius: 5px;
                text-align: center;
            `;

                modalContent.innerHTML = `
                <h3>Delete Quiz</h3>
                <p>Are you sure you want to delete this question?</p>
                <button onclick="confirmDelete(${questionId})" style="margin: 5px; padding: 5px 10px; background: #dc3545; color: white; border: none; border-radius: 3px;">Confirm</button>
                <button onclick="closeDeleteModal()" style="margin: 5px; padding: 5px 10px; background: #6c757d; color: white; border: none; border-radius: 3px;">Cancel</button>
            `;

                modal.appendChild(modalContent);
                modal.id = 'deleteQuizModal';
                document.body.appendChild(modal);
            }

            function closeDeleteModal() {
                const modal = document.getElementById('deleteQuizModal');
                if (modal) {
                    modal.remove();
                }
            }

            function confirmDelete(questionId) {
                fetch('process/question/delete_question.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `question_id=${questionId}`
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            closeDeleteModal();
                            window.location.reload();
                        } else {
                            alert(data.message || 'Error deleting question');
                            closeDeleteModal();
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while deleting the question');
                        closeDeleteModal();
                    });
            }
        </script>
</body>

</html>