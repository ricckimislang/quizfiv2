<?php
session_start();
include('includes/header.php');
include('includes/session.php');
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="css/loading-screen.css">
<link rel="stylesheet" href="css/leaderboard.css">

<body>
    <!-- Loading Screen -->
    <?php include("includes/loading-screen.php"); ?>
    <header id="header" class="header fixed-top d-flex align-items-center">
        <?php include 'includes/nav-top.php'; ?>
        <?php include 'includes/sidebar.php'; ?>

        <main id="main" class="main">
            <div class="leaderboard-container">
                <div class="leaderboard-header">
                    <div class="d-flex flex-row justify-content-center">
                        <h1>🏆</h1>
                        <h2>Top Performers</h2>
                    </div>

                    <p>Our brightest quiz champions</p>
                </div>

                <?php
                // Fetch top 8 students based on their scores
                $query = "SELECT s.*, 
                     (SELECT COUNT(*) FROM students qa 
                      WHERE qa.student_id = s.student_id AND qa.score IS NOT NULL) as total_quizzes,
                     (SELECT SUM(score) FROM students qa 
                      WHERE qa.student_id = s.student_id) as total_score
                     FROM students s
                     WHERE (SELECT SUM(score) FROM students qa 
                           WHERE qa.student_id = s.student_id) IS NOT NULL
                     ORDER BY total_score DESC
                     LIMIT 8";

                $result = $conn->query($query);

                if ($result && $result->num_rows > 0) {
                    $topStudents = array();
                    while ($row = $result->fetch_assoc()) {
                        $topStudents[] = $row;
                    }
                    ?>

                    <div class="podium-container">
                        <?php
                        // Ensure we have exactly 3 positions
                        for ($i = 0; $i < 3; $i++) {
                            $position = $i + 1;
                            $student = isset($topStudents[$i]) ? $topStudents[$i] : null;
                            ?>
                            <div class="podium-item position-<?php echo $position; ?>">
                                <?php if ($position == 1) { ?>
                                    <i class="fas fa-crown crown"></i>
                                <?php } ?>

                                <div class="avatar-container">
                                    <img src="<?php echo $student ? 'assets/students/' . ($student['profile_pic'] ?? 'profile.jpg') : 'assets/students/profile.jpg'; ?>"
                                        alt="Player <?php echo $position; ?>">
                                    <div class="rank-badge"><?php echo $position; ?></div>
                                </div>

                                <h3 class="player-name">
                                    <?php echo $student ? $student['firstname'] . ' ' . $student['lastname'] : 'Position ' . $position; ?>
                                </h3>

                                <div class="podium-base">
                                    <div class="score" data-score="<?php echo $student ? $student['total_score'] : '0'; ?>">
                                        <?php echo $student ? $student['total_score'] : '0'; ?>
                                    </div>
                                    <?php
                                    $icon = '';
                                    switch ($position) {
                                        case 1:
                                            $icon = '<i class="fas fa-trophy"></i>';
                                            break;
                                        case 2:
                                            $icon = '<i class="fas fa-medal"></i>';
                                            break;
                                        case 3:
                                            $icon = '<i class="fas fa-award"></i>';
                                            break;
                                    }
                                    echo $icon;
                                    ?>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                    <?php
                } else {
                    echo "<p class='text-center'>No quiz attempts recorded yet.</p>";
                }
                ?>

                <!-- Display remaining top players (4-8) -->
                <?php if (count($topStudents) > 3) { ?>
                    <div class="remaining-leaders">
                        <h2>Other Top Performers</h2>
                        <div class="leaders-list">
                            <?php
                            for ($i = 3; $i < count($topStudents); $i++) {
                                $position = $i + 1;
                                $student = $topStudents[$i];
                                ?>
                                <div class="leader-item">
                                    <div class="leader-rank"><?php echo $position; ?></div>
                                    <div class="leader-avatar">
                                        <img src="<?php echo 'assets/students/' . ($student['profile_pic'] ?? 'profile.jpg'); ?>"
                                            alt="Player <?php echo $position; ?>">
                                    </div>
                                    <div class="leader-info">
                                        <div class="leader-name">
                                            <?php echo $student['firstname'] . ' ' . $student['lastname']; ?>
                                        </div>
                                        <div class="leader-score" data-score="<?php echo $student['total_score']; ?>">
                                            <?php echo $student['total_score']; ?> pts
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </main>

        <script>
            // Animate score counting
            function animateValue(element, start, end, duration) {
                if (start === end) return;
                const range = end - start;
                let current = start;
                const increment = end > start ? 1 : -1;
                const stepTime = Math.abs(Math.floor(duration / range));
                const timer = setInterval(() => {
                    current += increment;
                    element.textContent = current;
                    if (current === end) {
                        clearInterval(timer);
                    }
                }, stepTime);
            }

            // Animate all score elements when page loads
            document.addEventListener('DOMContentLoaded', () => {
                const loadingScreen = document.getElementById('loading-screen');

                window.addEventListener('load', () => {
                    loadingScreen.style.opacity = '0';
                    setTimeout(() => {
                        loadingScreen.style.display = 'none';
                    }, 500);
                });

                document.querySelectorAll('.score').forEach(scoreElement => {
                    const finalScore = parseInt(scoreElement.dataset.score);
                    animateValue(scoreElement, 0, finalScore, 2000);
                });
            });
        </script>

        <?php include('js/scripts.php'); ?>
</body>

</html>