<?php
session_start();
include('includes/header.php');
include('includes/session.php');
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="css/loading-screen.css">
<link rel="stylesheet" href="css/take_quiz.css">

<body>
    <!-- Loading Screen -->
    <?php include("includes/loading-screen.php"); ?>

    <header id="header" class="header fixed-top d-flex align-items-center">
        <?php include 'includes/nav-top.php'; ?>
    </header>

    <?php include 'includes/sidebar.php'; ?>

    <main id="main" class="main">
        <div class="quiz-selection">
            <div class="section-header">
                <h2><i class="fas fa-brain me-3"></i>Challenge Your Mind</h2>
                <p>Choose your difficulty level and embark on an exciting learning journey</p>
            </div>

            <div class="difficulty-filters">
                <button class="difficulty-btn easy active" data-difficulty="Easy">
                    <i class="fas fa-star-half-alt"></i>
                    <span>Easy</span>
                    <small>(Beginner)</small>
                </button>
                <button class="difficulty-btn moderate" data-difficulty="Moderate">
                    <i class="fas fa-star"></i>
                    <span>Moderate</span>
                    <small>(Intermediate)</small>
                </button>
                <button class="difficulty-btn hard" data-difficulty="Hard">
                    <i class="fas fa-crown"></i>
                    <span>Hard</span>
                    <small>(Advanced)</small>
                </button>
            </div>

            <div class="quiz-grid">
                <!-- Query to show quiz cards -->
                <?php
                // Determine the selected difficulty
                $selectedDifficulty = isset($_GET['difficulty']) ? $_GET['difficulty'] : 'Easy';

                // Pagination logic
                $limit = 4; // Limit to 3 quizzes per page
                $page = isset($_GET['page']) ? (int) $_GET['page'] : 1; // Get current page or default to 1
                $offset = ($page - 1) * $limit; // Calculate offset for SQL query
                
                // Fetch total number of quizzes to calculate total pages
                $totalQuizzesQuery = "SELECT COUNT(*) as total FROM quiz WHERE difficulty = ? AND classroom_id = 0";
                $totalStmt = $conn->prepare($totalQuizzesQuery);
                if ($totalStmt === false) {
                    die('Error preparing the SQL statement: ' . $conn->error);
                }
                $totalStmt->bind_param('s', $selectedDifficulty);
                $totalStmt->execute();
                $totalResult = $totalStmt->get_result();
                $totalRow = $totalResult->fetch_assoc();
                $totalQuizzes = $totalRow['total'];
                $totalPages = ceil($totalQuizzes / $limit); // Calculate total pages
                
                // Fetch quizzes for the current page based on the selected difficulty with LIMIT and OFFSET
                $quizzesQuery = "SELECT * FROM quiz WHERE difficulty = ? AND classroom_id = 0 LIMIT ? OFFSET ?";
                $qstmt = $conn->prepare($quizzesQuery);
                if ($qstmt === false) {
                    die('Error preparing the SQL statement: ' . $conn->error);
                }

                $qstmt->bind_param('sii', $selectedDifficulty, $limit, $offset);
                $qstmt->execute();
                $qresult = $qstmt->get_result();

                $counter = 0; // Counter to track quizzes per row
                
                while ($quiz = $qresult->fetch_assoc()) {
                    // Start a new row after every 4 quizzes
                    if ($counter % 4 == 0 && $counter > 0) {
                        echo '</div><div class="row card-grid">'; // Close current row and start a new one
                    }
                    ?>

                    <!-- Easy Quiz Cards -->
                    <div class="quiz-card" data-difficulty="<?php echo htmlspecialchars($quiz['difficulty']); ?>">
                        <div class="quiz-card-header">
                            <span
                                class="difficulty-badge <?php echo htmlspecialchars($quiz['difficulty']); ?>"><?php echo htmlspecialchars($quiz['difficulty']); ?></span>
                        </div>
                        <div class="quiz-card-body">
                            <h3 class="quiz-title">
                                <?php echo htmlspecialchars($quiz['quiz_title']); ?>
                            </h3>
                            <p class="quiz-description"><?php echo htmlspecialchars($quiz['quiz_description']); ?></p>
                            <div class="quiz-meta">
                                <span><i class="fas fa-clock"></i>
                                    <?php
                                    // Dynamic time based on difficulty
                                    switch ($quiz['difficulty']) {
                                        case 'Easy':
                                            echo '10 mins';
                                            break;
                                        case 'Moderate':
                                            echo '20 mins';
                                            break;
                                        case 'Hard':
                                            echo '30 mins';
                                            break;
                                        default:
                                            echo '10 mins'; // fallback
                                    }
                                    ?>
                                </span>
                                <span><i class="fas fa-question-circle"></i>
                                    <?php
                                    // Dynamic time based on difficulty
                                    switch ($quiz['difficulty']) {
                                        case 'Easy':
                                            echo '10';
                                            break;
                                        case 'Moderate':
                                            echo '20';
                                            break;
                                        case 'Hard':
                                            echo '30';
                                            break;
                                        default:
                                            echo '10'; // fallback
                                    }
                                    ?>
                                    questions</span>
                            </div>
                            <button class="start-quiz-btn"
                                onclick="startQuiz('<?php echo htmlspecialchars($quiz['quiz_title']); ?>',<?php echo $quiz['quiz_id']; ?>)">
                                <i class="fas fa-play"></i>Start Quiz
                            </button>
                        </div>
                    </div>

                    <?php
                    $counter++; // Increment counter after displaying a quiz
                }

                // Close the last row if there are quizzes
                if ($counter > 0) {
                    echo '</div>';
                }

                // Pagination controls
                if ($totalPages > 1) {
                    echo '<div class="pagination">';

                    // Previous page link
                    if ($page > 1) {
                        echo '<a href="?difficulty=' . $selectedDifficulty . '&page=' . ($page - 1) . '">&laquo; Previous</a>';
                    }

                    // Display page numbers
                    for ($i = 1; $i <= $totalPages; $i++) {
                        if ($i == $page) {
                            echo '<span class="current-page">' . $i . '</span>';
                        } else {
                            echo '<a href="?difficulty=' . $selectedDifficulty . '&page=' . $i . '">' . $i . '</a>';
                        }
                    }

                    // Next page link
                    if ($page < $totalPages) {
                        echo '<a href="?difficulty=' . $selectedDifficulty . '&page=' . ($page + 1) . '">Next &raquo;</a>';
                    }

                    echo '</div>';
                }
                ?>

            </div>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            // Difficulty filter functionality with smooth transitions
            const difficultyBtns = document.querySelectorAll('.difficulty-btn');
            const quizCards = document.querySelectorAll('.quiz-card');

            difficultyBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    // Update active button with smooth transition
                    difficultyBtns.forEach(b => b.classList.remove('active'));
                    btn.classList.add('active');

                    // Get the selected difficulty
                    const difficulty = btn.dataset.difficulty;

                    // Redirect to the page with the selected difficulty
                    window.location.href = `?difficulty=${difficulty}`;
                });
            });
        });

        function startQuiz(quizTitle, quizId) {
            console.log("Quiz clicked: " + quizTitle); // Debugging line
            $.ajax({
                type: "POST",
                url: "process/check_availability.php", // Ensure this path is correct
                data: {
                    quizId: quizId, // Match the parameter name
                },
                dataType: "json",
                success: function (response) {
                    if (response.success) {
                        Swal.fire({
                            title: 'Take Quiz?',
                            text: "Are you sure you want to take this quiz: " + quizTitle + "?",
                            html: `<p style="font-size: 18px; color: #333;">Are you sure you want to take this quiz: <strong>${quizTitle}</strong></p>`,

                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonText: 'Yes, start quiz!',
                            cancelButtonText: 'No, cancel!',
                            reverseButtons: true,
                            customClass: {
                                confirmButton: 'gradient-button'
                            }
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Log the attempt and redirect to the quiz
                                window.location.href = "quiz_time.php?quiz_id=" + quizId;
                            } else if (result.dismiss === Swal.DismissReason.cancel) {
                                Swal.fire(
                                    'Cancelled',
                                    'You chose not to take the quiz.',
                                    'error'
                                );
                            }
                        });
                    } else {
                        Swal.fire({
                            title: '🚫 Not Allowed',
                            text: response.message, // Use the message from the PHP response
                            icon: 'warning',
                            confirmButtonText: 'Okay',
                            customClass: {
                                confirmButton: 'gradient-button' // Apply custom CSS class
                            }
                        });
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.error("AJAX Error: " + textStatus + ": " + errorThrown);
                    Swal.fire({
                        title: 'Error',
                        text: "There was an error checking quiz availability. Please try again later.",
                        icon: 'error',
                        confirmButtonText: 'Okay'
                    });
                }
            });
        }
    </script>

    <?php include('js/scripts.php'); ?>
</body>

</html>