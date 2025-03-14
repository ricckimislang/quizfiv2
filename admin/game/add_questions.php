<?php
// Include database connection
require_once 'db_connect.php';

// Initialize variables
$success_message = '';
$error_message = '';
$quiz_id = null;
$quiz_title = '';

// Check if quiz_id is provided
if (isset($_GET['quiz_id'])) {
    $quiz_id = intval($_GET['quiz_id']);

    // Get quiz details
    $stmt = $conn->prepare("SELECT game_title FROM quiz WHERE quiz_id = ?");
    $stmt->bind_param("i", $quiz_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $quiz = $result->fetch_assoc();
        $quiz_title = $quiz['game_title'];
    } else {
        $error_message = "Quiz not found";
    }

    $stmt->close();
} else {
    $error_message = "Quiz ID is required";
}


// Get existing questions for this quiz
$questions = [];
if ($quiz_id) {
    $stmt = $conn->prepare("SELECT * FROM questions WHERE quiz_id = ? ORDER BY question_id ASC");
    $stmt->bind_param("i", $quiz_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $questions[] = $row;
        }
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Questions - Billionaire Game</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/add_questions.css">
</head>

<body>
    <div class="container">
        <div class="navigation">
            <a href="create_quiz.php" style="color: #2196F3;">← Back to Quizzes</a>
        </div>

        <h1>Add Questions to Quiz</h1>

        <?php if (!empty($quiz_title)): ?>
            <h2 style="text-align: center; margin-bottom: 20px;">Quiz: <?php echo htmlspecialchars($quiz_title); ?></h2>
        <?php endif; ?>

        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($success_message)): ?>
            <div class="alert alert-success">
                <?php echo $success_message; ?>
            </div>
        <?php endif; ?>

        <?php if ($quiz_id): ?>
            <div class="card">
                <h2>Add New Question</h2>

                <form id="addQuestionForm">
                    <input type="hidden" name="quiz_id" value="<?php echo $quiz_id; ?>">
                    <div class="form-group">
                        <label for="question_text">Question:</label>
                        <textarea id="question_text" name="question_text" required><?php echo isset($question_text) ? htmlspecialchars($question_text) : ''; ?></textarea>
                    </div>

                    <div class="options-grid">
                        <div class="form-group">
                            <label for="option_a">Option A:</label>
                            <input type="text" id="option_a" name="option_a" value="<?php echo isset($option_a) ? htmlspecialchars($option_a) : ''; ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="option_b">Option B:</label>
                            <input type="text" id="option_b" name="option_b" value="<?php echo isset($option_b) ? htmlspecialchars($option_b) : ''; ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="option_c">Option C:</label>
                            <input type="text" id="option_c" name="option_c" value="<?php echo isset($option_c) ? htmlspecialchars($option_c) : ''; ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="option_d">Option D:</label>
                            <input type="text" id="option_d" name="option_d" value="<?php echo isset($option_d) ? htmlspecialchars($option_d) : ''; ?>" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="correct_answer">Correct Answer:</label>
                        <select id="correct_answer" name="correct_answer" required>
                            <option value="">Select correct answer</option>
                            <option value="A" <?php echo (isset($correct_answer) && $correct_answer == 'A') ? 'selected' : ''; ?>>A</option>
                            <option value="B" <?php echo (isset($correct_answer) && $correct_answer == 'B') ? 'selected' : ''; ?>>B</option>
                            <option value="C" <?php echo (isset($correct_answer) && $correct_answer == 'C') ? 'selected' : ''; ?>>C</option>
                            <option value="D" <?php echo (isset($correct_answer) && $correct_answer == 'D') ? 'selected' : ''; ?>>D</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="hint">Hint (Optional):</label>
                        <textarea id="hint" name="hint"><?php echo isset($hint) ? htmlspecialchars($hint) : ''; ?></textarea>
                    </div>

                    <button type="submit">Add Question</button>
                </form>
            </div>

            <div class="card">
                <h2>Existing Questions</h2>

                <?php if (empty($questions)): ?>
                    <p>No questions added yet.</p>
                <?php else: ?>
                    <div class="question-list">
                        <?php foreach ($questions as $index => $question): ?>
                            <div class="question-item">
                                <div class="question-header">
                                    <h3>Question <?php echo $index + 1; ?></h3>
                                    <div class="actions">
                                        <a href="edit_question.php?question_id=<?php echo $question['question_id']; ?>" class="btn-small btn-primary">Edit</a>
                                        <a href="javascript:void(0)" onclick="deleteQuestion(<?php echo $question['question_id']; ?>)" class="btn-small btn-danger">Delete</a>
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
                    </div>
                <?php endif; ?>

                <?php if (count($questions) >= 10): ?>
                    <div style="margin-top: 20px; text-align: center;">
                        <a href="play_quiz.php?quiz_id=<?php echo $quiz_id; ?>" class="btn-primary" style="padding: 10px 20px; text-decoration: none; display: inline-block;">Play This Quiz</a>
                    </div>
                <?php else: ?>
                    <div style="margin-top: 20px;">
                        <p>Must Have 10 questions to play this quiz.</p>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
    <script src="js/jquery-3.7.1.js"></script>
    <script>
        document.getElementById('addQuestionForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);

            fetch('process/question/add_question.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        setTimeout(function() {
                            window.location.reload();
                        }, 1000);
                    } else {
                        alert(data.message || 'An error occurred while adding the question');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while adding the question');
                });
        });




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