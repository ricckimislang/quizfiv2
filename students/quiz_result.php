<?php
session_start();
include 'includes/session.php';
include 'includes/header.php';
include 'db/dbconn.php'; // Include your database connection file
?>

<link rel="stylesheet" href="css/quiz_result.css">

<body>
    <main id="main" class="main">
        <section class="section mt-2">
            <div class="row" style="margin-top: 0;">
                <div class="col-12">
                    <h3 class="text-center">Quiz Results</h3>
                </div>
            </div>
            <div class="row d-flex justify-content-center align-items-center mt-4">
                <div class="col-5 profile-img">
                </div>
            </div>
            <div class="row d-flex justify-content-center align-items-center mt-4">
                <div class="col">
                    <h2 class="text-center"><?php echo $studentName; ?></h2>
                </div>
            </div>
            <div class="row">
                <div class="col box1">
                    <div class="score-container">
                        <div class="score-text">
                            <h3>Score</h3>
                            <span><?php echo $_GET['score']; ?></span>
                        </div>
                        <div class="result-img"></div>
                    </div>
                </div>
                <div class="col box2">
                    <div class="score-container">
                        <div class="score-text">
                            <h3>Points</h3>
                            <span><?php echo $student_score; ?></span>
                        </div>
                        <div class="points-img"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col box3">
                    <h4>Congratulations! You earned Points</h4>
                    <h4><?php echo $_GET['points'] ?></h4>
                </div>
            </div>
            <div class="row button-group">
                <div class="col">
                    <a href="take_quiz.php" class="btn btn-primary">New Quiz</a>
                </div>
            </div>
            <div class="row button-group">
                <div class="col">
                    <div class="col">
                        <a href="leader.php" class="btn btn-primary">Home</a>
                    </div>
                </div>
            </div>
        </section>
    </main>
</body>