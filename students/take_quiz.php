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
                <button class="difficulty-btn easy active" data-difficulty="easy">
                    <i class="fas fa-star-half-alt"></i>
                    <span>Easy</span>
                    <small>(Beginner)</small>
                </button>
                <button class="difficulty-btn moderate" data-difficulty="moderate">
                    <i class="fas fa-star"></i>
                    <span>Moderate</span>
                    <small>(Intermediate)</small>
                </button>
                <button class="difficulty-btn hard" data-difficulty="hard">
                    <i class="fas fa-crown"></i>
                    <span>Hard</span>
                    <small>(Advanced)</small>
                </button>
            </div>

            <div class="quiz-grid">
                <!-- Easy Quiz Cards -->
                <div class="quiz-card" data-difficulty="easy">
                    <div class="quiz-card-header">
                        <span class="difficulty-badge easy">Easy</span>
                    </div>
                    <div class="quiz-card-body">
                        <h3 class="quiz-title">
                            <i class="fas fa-calculator me-2"></i>
                            Basic Mathematics
                        </h3>
                        <p class="quiz-description">Master fundamental math concepts through engaging problems. Perfect
                            for building a strong foundation in arithmetic and basic problem-solving.</p>
                        <div class="quiz-meta">
                            <span><i class="fas fa-clock"></i>15 mins</span>
                            <span><i class="fas fa-question-circle"></i>10 questions</span>
                        </div>
                        <button class="start-quiz-btn" onclick="startQuiz(1)">
                            <i class="fas fa-play"></i>Start Quiz
                        </button>
                    </div>
                </div>

                <div class="quiz-card" data-difficulty="easy">
                    <div class="quiz-card-header">
                        <span class="difficulty-badge easy">Easy</span>
                    </div>
                    <div class="quiz-card-body">
                        <h3 class="quiz-title">
                            <i class="fas fa-globe me-2"></i>
                            General Knowledge
                        </h3>
                        <p class="quiz-description">Explore fascinating facts about the world around us. An excellent
                            starting point for expanding your knowledge across various topics.</p>
                        <div class="quiz-meta">
                            <span><i class="fas fa-clock"></i>20 mins</span>
                            <span><i class="fas fa-question-circle"></i>15 questions</span>
                        </div>
                        <button class="start-quiz-btn" onclick="startQuiz(2)">
                            <i class="fas fa-play"></i>Start Quiz
                        </button>
                    </div>
                </div>

                <!-- Moderate Quiz Cards -->
                <div class="quiz-card" data-difficulty="moderate">
                    <div class="quiz-card-header">
                        <span class="difficulty-badge moderate">Moderate</span>
                    </div>
                    <div class="quiz-card-body">
                        <h3 class="quiz-title">
                            <i class="fas fa-landmark me-2"></i>
                            World History
                        </h3>
                        <p class="quiz-description">Journey through time with questions about significant historical
                            events, civilizations, and influential figures who shaped our world.</p>
                        <div class="quiz-meta">
                            <span><i class="fas fa-clock"></i>25 mins</span>
                            <span><i class="fas fa-question-circle"></i>20 questions</span>
                        </div>
                        <button class="start-quiz-btn" onclick="startQuiz(3)">
                            <i class="fas fa-play"></i>Start Quiz
                        </button>
                    </div>
                </div>

                <div class="quiz-card" data-difficulty="moderate">
                    <div class="quiz-card-header">
                        <span class="difficulty-badge moderate">Moderate</span>
                    </div>
                    <div class="quiz-card-body">
                        <h3 class="quiz-title">
                            <i class="fas fa-microchip me-2"></i>
                            Science & Technology
                        </h3>
                        <p class="quiz-description">Discover the wonders of scientific discoveries and technological
                            innovations that drive our modern world forward.</p>
                        <div class="quiz-meta">
                            <span><i class="fas fa-clock"></i>30 mins</span>
                            <span><i class="fas fa-question-circle"></i>25 questions</span>
                        </div>
                        <button class="start-quiz-btn" onclick="startQuiz(4)">
                            <i class="fas fa-play"></i>Start Quiz
                        </button>
                    </div>
                </div>

                <!-- Hard Quiz Cards -->
                <div class="quiz-card" data-difficulty="hard">
                    <div class="quiz-card-header">
                        <span class="difficulty-badge hard">Hard</span>
                    </div>
                    <div class="quiz-card-body">
                        <h3 class="quiz-title">
                            <i class="fas fa-atom me-2"></i>
                            Advanced Physics
                        </h3>
                        <p class="quiz-description">Challenge yourself with complex physics problems and theoretical
                            concepts. Test your understanding of the fundamental laws of the universe.</p>
                        <div class="quiz-meta">
                            <span><i class="fas fa-clock"></i>45 mins</span>
                            <span><i class="fas fa-question-circle"></i>30 questions</span>
                        </div>
                        <button class="start-quiz-btn" onclick="startQuiz(5)">
                            <i class="fas fa-play"></i>Start Quiz
                        </button>
                    </div>
                </div>

                <div class="quiz-card" data-difficulty="hard">
                    <div class="quiz-card-header">
                        <span class="difficulty-badge hard">Hard</span>
                    </div>
                    <div class="quiz-card-body">
                        <h3 class="quiz-title">
                            <i class="fas fa-square-root-alt me-2"></i>
                            Advanced Mathematics
                        </h3>
                        <p class="quiz-description">Push your mathematical abilities to the limit with advanced concepts
                            and challenging problem-solving scenarios.</p>
                        <div class="quiz-meta">
                            <span><i class="fas fa-clock"></i>40 mins</span>
                            <span><i class="fas fa-question-circle"></i>25 questions</span>
                        </div>
                        <button class="start-quiz-btn" onclick="startQuiz(6)">
                            <i class="fas fa-play"></i>Start Quiz
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Remove loading screen immediately
            const loadingScreen = document.getElementById('loading-screen');
            if (loadingScreen) {
                loadingScreen.style.display = 'none';
            }

            // Difficulty filter functionality with smooth transitions
            const difficultyBtns = document.querySelectorAll('.difficulty-btn');
            const quizCards = document.querySelectorAll('.quiz-card');

            difficultyBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    // Update active button with smooth transition
                    difficultyBtns.forEach(b => b.classList.remove('active'));
                    btn.classList.add('active');

                    // Filter quiz cards with animation
                    const difficulty = btn.dataset.difficulty;
                    quizCards.forEach(card => {
                        if (difficulty === 'all' || card.dataset.difficulty === difficulty) {
                            card.style.display = 'block';
                            card.style.animation = 'fadeIn 0.6s ease-out forwards';
                        } else {
                            card.style.display = 'none';
                        }
                    });
                });
            });
        });

        function startQuiz(quizId) {
            // Enhanced confirmation dialog
            if (confirm('Ready to begin? Make sure you have enough time to complete the quiz. Click OK to start your learning journey!')) {
                window.location.href = `start_quiz.php?id=${quizId}`;
            }
        }
    </script>

    <?php include('js/scripts.php'); ?>
</body>

</html>