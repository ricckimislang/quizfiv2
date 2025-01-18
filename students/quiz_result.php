<?php
session_start();
include 'includes/session.php';
include 'includes/header.php';
include 'db/dbconn.php'; // Include your database connection file
?>

<link rel="stylesheet" href="css/quiz_result.css"> <!-- Updated CSS file -->

<body>
    <main id="main" class="main">
        <div class="quiz-results-container">

            <div class="results-header">
                <div class="profile-section">
                    <h2 class="student-name"><?php echo $studentName; ?></h2>
                    <div class="profile-img"></div>
                </div>
            </div>

            <div class="results-grid mt-2 ">
                <div class="result-card score-card">
                    <div class="card-content">
                        <h3>Score</h3>
                        <span class="result-value"><?php echo $_GET['score']; ?></span>
                    </div>
                    <div class="card-icon result-img"></div>
                </div>

                <div class="result-card points-card">
                    <div class="card-content">
                        <h3>Points</h3>
                        <span class="result-value"><?php echo $student_score; ?></span>
                    </div>
                    <div class="card-icon points-img"></div>
                </div>
            </div>

            <div class="congratulations-banner">
                <h4>Congratulations!</h4>
                <p>You earned <?php echo $_GET['points']; ?> points</p>
            </div>

            <div class="action-buttons">
                <a href="take_quiz.php" class="btn btn-primary">Start New Quiz</a>
                <a href="leaderboard.php" class="btn btn-secondary">Back to Home</a>
            </div>
        </div>
    </main>

    <script>
        function createParticles() {
            const container = document.querySelector('.quiz-results-container');

            for (let i = 0; i < 20; i++) {
                const particle = document.createElement('div');
                particle.classList.add('particle');

                // Random sizing
                const size = Math.random() * 20 + 5;
                particle.style.width = `${size}px`;
                particle.style.height = `${size}px`;

                // Random positioning
                particle.style.top = `${Math.random() * 100}%`;
                particle.style.left = `${Math.random() * 100}%`;

                // Random animation delay
                particle.style.animationDelay = `${Math.random() * 5}s`;

                // Random opacity
                particle.style.opacity = Math.random() * 0.8 + 0.5;

                // Random gradient background
                particle.style.background = `linear-gradient(45deg, 
                rgba(106, 17, 203, ${Math.random() * 0.3}), 
                rgba(37, 117, 252, ${Math.random() * 0.3}))`;

                container.appendChild(particle);
            }
        }

        // Create particles when the page loads
        window.addEventListener('load', createParticles);
    </script>
</body>