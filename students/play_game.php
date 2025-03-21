<?php
session_start();
include_once 'includes/session.php';
include_once 'includes/header.php';
include_once 'db/gameDb.php';

?>
<link rel="stylesheet" href="css/play_game.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Nunito:wght@400;700;800&display=swap">
<link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
<link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
<body>
    <header id="header" class="header fixed-top d-flex align-items-center">
        <?php include 'includes/nav-top.php'; ?>
    </header>

    <?php include 'includes/sidebar.php'; ?>

    <main id="main" class="main">
        <div class="game-container">
            <div class="welcome-content">
                <h2><i class='bx bx-diamond'></i>Who want to be a Quizzionnaire?<i class='bx bx-diamond'></i></h2>
            </div>
            <div class="select-game-list">
                <?php
                // Initialize empty array before the loop
                $quizzes = [];

                // Modified query to get more stats
                $stmt = $connGame->prepare("SELECT q.* FROM quiz q WHERE q.is_published = 'yes' GROUP BY q.quiz_id");
                $stmt->execute();
                $result = $stmt->get_result();

                // Fetch all rows at once
                while ($row = $result->fetch_assoc()) {
                    $quizzes[] = $row;
                }

                if (empty($quizzes)):
                    echo "<div class='no-quiz'>";
                    echo "<p><i class='bx bx-error-circle'></i> No quizzes available at the moment.</p>";
                    echo "</div>";
                else:
                    foreach ($quizzes as $quiz):
                        $difficulty = isset($quiz['difficulty']) ? $quiz['difficulty'] : 'Normal';
                        $highScore = isset($quiz['high_score']) ? number_format($quiz['high_score']) : '1000';
                ?>
                        <div class="quiz-card">
                            <div class="quiz-card-header">
                                <h3 class="quiz-title">
                                    <i class='bx bx-trophy'></i>
                                    <?php echo htmlspecialchars($quiz['game_title']); ?>
                                </h3>
                                <div class="quiz-meta">
                                    <span><i class='bx bx-calendar'></i> <?php echo date('M d, Y', strtotime($quiz['created_at'])); ?></span>
                                    <span><i class='bx bx-signal-4'></i> <?php echo $difficulty; ?></span>
                                </div>
                            </div>
                            <div class="quiz-description">
                                <?php echo htmlspecialchars($quiz['description'] ?? 'Test your knowledge with this exciting quiz and climb your way to the top! Are you ready to become a Quizionnaire?'); ?>
                            </div>
                            <div class="quiz-footer">
                                <div class="quiz-stats">
                                    <div class="quiz-stat">
                                        <i class='bx bx-user'></i>
                                        <?php echo $quiz['total_players'] ?? '0'; ?> players
                                    </div>
                                    <div class="quiz-stat">
                                        <i class='bx bx-dollar'></i>
                                        $<?php echo $highScore; ?>
                                    </div>
                                </div>
                                <button class="play-button" onclick="PlayGame('<?php echo $quiz['game_title']; ?>', '<?php echo $quiz['quiz_id']; ?>')">
                                    <i class='bx bx-play'></i> Play Now
                                </button>
                            </div>
                        </div>
                <?php
                    endforeach;
                endif;
                ?>
            </div>
        </div>
    </main>
    <script src="js/main.js"></script>
    <script>
        function PlayGame(gameTitle, gameId) {
            // First check if student already attempted this week
            fetch('game_process/quiz/check_attempt.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        quiz_id: gameId,
                        user_id: <?php echo $user_id; ?>
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.canAttempt) {
                        // Show game start dialog if allowed
                        Swal.fire({
                            title: `Start "${gameTitle}"`,
                            html: `
                            <div style="margin-bottom: 20px;">
                                <img src="assets/img/thinking.gif" alt="Quiz Ready" style="width: 150px; height: 150px; border-radius: 50%; margin-bottom: 15px;">
                                <p style="font-size: .9em; color: #666;">Are you ready to test your knowledge and win big?</p>
                                <p style="font-size: 0.8em; color: #888; margin-top: 10px;">Make sure you're in a quiet place and ready to focus!</p>
                                <div style="margin-top: 15px; padding: 10px; background: #fff3cd; border-radius: 5px; border: 1px solid #ffeeba;">
                                    <i class="bx bx-time" style="color: #856404;"></i>
                                    <span style="color: #856404; font-size: 0.6em;">Note: This Game can only be played once per week</span>
                                </div>
                            </div>
                        `,
                            showCancelButton: true,
                            confirmButtonColor: '#28a745',
                            cancelButtonColor: '#6c757d',
                            confirmButtonText: '<i class="bx bx-play"></i> Start Game',
                            cancelButtonText: 'Maybe Later',
                            background: '#fff',
                            backdrop: `
                            rgba(0,0,123,0.4)
                            url("images/confetti.gif")
                            left top
                            no-repeat
                        `,
                            customClass: {
                                confirmButton: 'btn btn-success btn-lg',
                                cancelButton: 'btn btn-secondary btn-lg',
                                title: 'h3'
                            }
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = `game_time.php?quiz=${encodeURIComponent(gameId)}`;
                            }
                        });
                    } else {
                        // Show error if attempt not allowed
                        Swal.fire({
                            icon: 'error',
                            title: 'Cannot Start Game',
                            text: 'You have already attempted this quiz this week. Please try again next week.',
                            confirmButtonColor: '#dc3545'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Something went wrong! Please try again later.',
                        confirmButtonColor: '#dc3545'
                    });
                });
        }
    </script>
</body>