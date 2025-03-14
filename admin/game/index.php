<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Who Wants to Be a Billionaire</title>
    <link rel="stylesheet" href="css/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">

    <?php
    // Extract quiz_id from URL
    $quiz_id = isset($_GET['quiz_id']) ? intval($_GET['quiz_id']) : 0;

    // If no quiz_id is provided, redirect to the quiz selection page
    if ($quiz_id === 0) {
        header('Location: create_quiz.php');
        exit;
    }

    // Get quiz title for display
    require_once 'db_connect.php';
    $quiz_title = '';

    $stmt = $conn->prepare("SELECT game_title FROM quiz WHERE quiz_id = ?");
    $stmt->bind_param("i", $quiz_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $quiz_title = $row['game_title'];
    } else {
        $quiz_title = 'Who want to be a Quizionnaire';
    }
    ?>
    <script>
        const quizId = <?php echo $quiz_id; ?>;
        const quizTitle = "<?php echo addslashes($quiz_title); ?>";
    </script>

</head>

<body>
    <div class="game-container">
        <div class="game-header">
            <div class="money-ladder">
                <div class="money-item active">$1,000,000</div>
                <div class="money-item">$500,000</div>
                <div class="money-item">$250,000</div>
                <div class="money-item">$125,000</div>
                <div class="money-item">$64,000</div>
                <div class="money-item">$32,000</div>
                <div class="money-item">$16,000</div>
                <div class="money-item">$8,000</div>
                <div class="money-item">$4,000</div>
                <div class="money-item">$2,000</div>
                <div class="money-item">$1,000</div>
            </div>
            <div class="game-logo">
                <div class="logo-circle">
                    <div class="logo-inner">
                        <h1>BILLIONAIRE</h1>
                    </div>
                </div>
            </div>
            <div class="lifelines">
                <button class="lifeline-btn" id="fifty-fifty">50:50</button>
                <button class="lifeline-btn" id="hint">Hint</button>
                <button class="lifeline-btn" id="skip">Skip</button>
            </div>
        </div>

        <div class="game-content">
            <div class="question-container">
                <h2 id="question">Question goes here</h2>
            </div>
            <div class="answers-container">
                <button class="answer-btn" data-option="A">A: <span></span></button>
                <button class="answer-btn" data-option="B">B: <span></span></button>
                <button class="answer-btn" data-option="C">C: <span></span></button>
                <button class="answer-btn" data-option="D">D: <span></span></button>
            </div>
        </div>

        <div class="hint-modal" id="hintModal">
            <div class="hint-content">
                <h3>Hint</h3>
                <p id="hintText"></p>
                <button class="close-hint">Close</button>
            </div>
        </div>
    </div>
    <script src="js/jquery-3.7.1.js"></script>
    <script src="script.js"></script>
</body>

</html>