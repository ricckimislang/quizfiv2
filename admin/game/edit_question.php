<?php
// Include database connection
require_once 'db_connect.php';

// Initialize variables
$success_message = '';
$error_message = '';
$question_id = null;
$quiz_id = null;
$question_text = '';
$option_a = '';
$option_b = '';
$option_c = '';
$option_d = '';
$correct_answer = '';
$hint = '';

// Check if question_id is provided
if (isset($_GET['question_id'])) {
    $question_id = intval($_GET['question_id']);

    // Get question details
    $stmt = $conn->prepare("SELECT * FROM questions WHERE question_id = ?");
    $stmt->bind_param("i", $question_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $question = $result->fetch_assoc();
        $quiz_id = $question['quiz_id'];
        $question_text = $question['question_text'];
        $option_a = $question['option_a'];
        $option_b = $question['option_b'];
        $option_c = $question['option_c'];
        $option_d = $question['option_d'];
        $correct_answer = $question['correct_answer'];
        $hint = $question['hint'];
    } else {
        $error_message = "Question not found";
    }

    $stmt->close();
} else {
    $error_message = "Question ID is required";
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Question - Billionaire Game</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/edit_question.css">
</head>

<body>
    <div class="container">
        <div class="navigation">
            <?php if ($quiz_id): ?>
                <a href="javascript:history.back()" style="color: #2196F3;">← Back</a>
            <?php else: ?>

                <a href="create_quiz.php" style="color: #2196F3;">← Back to Quizzes</a>
            <?php endif; ?>
        </div>

        <h1>Edit Question</h1>

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

        <div id="message" class="alert" style="display: none;"></div>

        <?php if ($question_id && $quiz_id): ?>
            <div class="card">
                <form id="edit_question_form">
                    <input type="hidden" name="question_id" value="<?php echo $question_id; ?>">
                    <input type="hidden" name="quiz_id" value="<?php echo $quiz_id; ?>">

                    <div class="form-group">
                        <label for="question_text">Question:</label>
                        <textarea id="question_text" name="question_text" required><?php echo htmlspecialchars($question_text); ?></textarea>
                    </div>

                    <div class="options-grid">
                        <div class="form-group">
                            <label for="option_a">Option A:</label>
                            <input type="text" id="option_a" name="option_a" value="<?php echo htmlspecialchars($option_a); ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="option_b">Option B:</label>
                            <input type="text" id="option_b" name="option_b" value="<?php echo htmlspecialchars($option_b); ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="option_c">Option C:</label>
                            <input type="text" id="option_c" name="option_c" value="<?php echo htmlspecialchars($option_c); ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="option_d">Option D:</label>
                            <input type="text" id="option_d" name="option_d" value="<?php echo htmlspecialchars($option_d); ?>" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="correct_answer">Correct Answer:</label>
                        <select id="correct_answer" name="correct_answer" required>
                            <option value="">Select correct answer</option>
                            <option value="A" <?php echo ($correct_answer == 'A') ? 'selected' : ''; ?>>A</option>
                            <option value="B" <?php echo ($correct_answer == 'B') ? 'selected' : ''; ?>>B</option>
                            <option value="C" <?php echo ($correct_answer == 'C') ? 'selected' : ''; ?>>C</option>
                            <option value="D" <?php echo ($correct_answer == 'D') ? 'selected' : ''; ?>>D</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="hint">Hint (Optional):</label>
                        <textarea id="hint" name="hint"><?php echo htmlspecialchars($hint); ?></textarea>
                    </div>

                    <button type="submit">Update Question</button>
                </form>
            </div>
        <?php endif; ?>
    </div>
    <script src="js/jquery-3.7.1.js"></script>
    <script>
        document.getElementById('edit_question_form').addEventListener('submit', function(e) {
            const messageDiv = document.getElementById('message');
            e.preventDefault();
            const formData = new FormData(this);

            fetch('process/question/edit_question.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    
                    messageDiv.textContent = data.message;
                    messageDiv.className = data.success ? 'alert alert-success' : 'alert alert-danger';
                    messageDiv.style.display = 'block';

                    if (data.success) {
                        setTimeout(function() {
                            window.location.reload();
                        }, 1000);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    
                    messageDiv.textContent = 'An error occurred while updating the question';
                    messageDiv.className = 'alert alert-danger';
                    messageDiv.style.display = 'block';
                });
        });
    </script>
</body>

</html>