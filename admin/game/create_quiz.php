<?php
// Include database connection
require_once 'db_connect.php';
session_start();
include_once '../includes/session.php';
// Initialize variables
$success_message = '';
$error_message = '';
$quiz_id = null;

// Check for session message
if (isset($_SESSION['success_message'])) {
    $success_message = $_SESSION['success_message'];
    unset($_SESSION['success_message']); // Clear the message after displaying
}

// Get all quizzes for display
$quizzes = [];
$result = $conn->query("SELECT * FROM quiz ORDER BY created_at DESC");
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $quizzes[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Quiz - Billionaire Game</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/edit_quiz.css">
    <link rel="stylesheet" href="css/create_quiz.css">
</head>

<body>
    <div class="container">
        <a href="../index.php" class="home-link">← Back to Admin Dashboard</a>
        <h1>Billionaire Quiz Creator</h1>

        <div class="card">
            <h2>Create New Quiz</h2>

            <?php if (!empty($success_message)): ?>
                <div class="alert alert-success">
                    <?php echo $success_message; ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($error_message)): ?>
                <div class="alert alert-danger">
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>

            <form id="create_quiz_form">
                <div class="form-group">
                    <label for="game_title">Quiz Title:</label>
                    <input type="text" id="game_title" name="game_title" required>
                </div>

                <button type="submit">Create Quiz</button>
            </form>
        </div>

        <?php if ($quiz_id): ?>
            <div class="card">
                <h2>Add Questions to Your Quiz</h2>
                <p>Your quiz has been created. <a href="add_questions.php?quiz_id=<?php echo $quiz_id; ?>" style="color: #2196F3;">Click here</a> to add questions.</p>
            </div>
        <?php endif; ?>

        <div class="card">
            <h2>Your Quizzes</h2>

            <?php if (empty($quizzes)): ?>
                <p>No quizzes created yet.</p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($quizzes as $quiz): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($quiz['game_title']); ?></td>
                                <td><?php echo date('M d, Y H:i', strtotime($quiz['created_at'])); ?></td>
                                <td class="actions">
                                    <a href="add_questions.php?quiz_id=<?php echo $quiz['quiz_id']; ?>" class="btn-small btn-primary">Add Questions</a>
                                    <a href="view_quiz.php?quiz_id=<?php echo $quiz['quiz_id']; ?>" class="btn-small btn-primary">View</a>
                                    <a href="javascript:void(0);" onclick="openEditQuizModal(<?php echo $quiz['quiz_id']; ?>)" class="btn-small btn-primary">Edit</a>
                                    <a href="javascript:void(0);" class="btn-small btn-danger" onclick="deleteQuiz(<?php echo intval($quiz['quiz_id']); ?>)">Delete</a>
                                    <?php
                                    if ($quiz['is_published'] == 'not'):
                                        echo '<button data-quiz-id="' . $quiz['quiz_id'] . '" data-status="not" class="publish-quiz-btn btn-small btn-danger">Unpublish</button>';
                                    else:
                                        echo '<button data-quiz-id="' . $quiz['quiz_id'] . '" data-status="yes" class="publish-quiz-btn btn-small btn-success">Publish</button>';
                                    endif;
                                    ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
    <?php include 'edit_quiz.php'; ?>

    <script src="js/jquery-3.7.1.js"></script>
    <script>
        document.getElementById('create_quiz_form').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);

            fetch('process/quiz/create_quiz.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                    } else {
                        alert(data.message || 'An error occurred while creating the quiz');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while creating the quiz');
                });
        });
    </script>
    <script>
        // Function to open the modal with quiz data
        function openEditQuizModal(quizId) {
            // Fetch quiz data
            fetch(`edit_quiz.php?get_quiz=1&quiz_id=${quizId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('quiz_id').value = quizId;
                        document.getElementById('edit_game_title').value = data.quiz.game_title;
                        document.getElementById('editQuizModal').style.display = 'block';
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while fetching quiz data');
                });
        }

        // Close the modal when clicking the X
        document.querySelector('.close').addEventListener('click', function() {
            document.getElementById('editQuizModal').style.display = 'none';
        });

        // Close the modal when clicking outside of it
        window.addEventListener('click', function(event) {
            if (event.target == document.getElementById('editQuizModal')) {
                document.getElementById('editQuizModal').style.display = 'none';
            }
        });

        // Handle form submission
        document.getElementById('editQuizForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);

            fetch('process/quiz/edit_quiz.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    const messageDiv = document.getElementById('message');
                    messageDiv.textContent = data.message;
                    messageDiv.className = data.success ? 'message success' : 'message error';
                    messageDiv.style.display = 'block';

                    if (data.success) {
                        // Refresh the page after a short delay
                        setTimeout(function() {
                            window.location.reload();
                        }, 1000);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    const messageDiv = document.getElementById('message');
                    messageDiv.textContent = 'An error occurred while updating the quiz';
                    messageDiv.className = 'message error';
                    messageDiv.style.display = 'block';
                });
        });
    </script>
    <script>
        function deleteQuiz(quizId) {
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
                <p>Are you sure you want to delete this quiz?</p>
                <button onclick="confirmDelete(${quizId})" style="margin: 5px; padding: 5px 10px; background: #dc3545; color: white; border: none; border-radius: 3px;">Confirm</button>
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

        function confirmDelete(quizId) {
            fetch(`process/quiz/delete_quiz.php?quiz_id=${quizId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    closeDeleteModal();
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while deleting the quiz');
                    closeDeleteModal();
                });
        }

        function closePublishModal() {
            const modal = document.getElementById('publishQuizModal');
            if (modal) {
                modal.remove();
            }
        }

        function publishQuiz(quizId, currentStatus) {
            const formData = new FormData();
            formData.append('quiz_id', quizId);
            formData.append('current_status', currentStatus);

            fetch('process/quiz/publish_quiz.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    } else {
                        alert(data.message || 'An error occurred while updating quiz status');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while updating quiz status');
                    closePublishModal();
                });
        }

        // Add event listeners to all publish buttons
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.publish-quiz-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const quizId = this.getAttribute('data-quiz-id');
                    const currentStatus = this.getAttribute('data-status');
                    const action = currentStatus === 'yes' ? 'unpublish' : 'publish';

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
                        <h3>${action.charAt(0).toUpperCase() + action.slice(1)} Quiz</h3>
                        <p>Are you sure you want to ${action} this quiz?</p>
                        <button onclick="publishQuiz(${quizId}, '${currentStatus}')" style="margin: 5px; padding: 5px 10px; background:rgb(53, 220, 73); color: white; border: none; border-radius: 3px;">Confirm</button>
                        <button onclick="closePublishModal()" style="margin: 5px; padding: 5px 10px; background: #6c757d; color: white; border: none; border-radius: 3px;">Cancel</button>
                    `;

                    modal.appendChild(modalContent);
                    modal.id = 'publishQuizModal';
                    document.body.appendChild(modal);
                });
            });
        });
    </script>
</body>
<!-- Include the Edit Quiz Modal -->


</html>