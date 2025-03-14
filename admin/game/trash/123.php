<?php
// Include database connection
require_once 'db_connect.php';

// Initialize variables
$error_message = '';
$quiz_id = null;
$quiz_title = '';
$questions = [];

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

        // Get questions for this quiz
        $stmt = $conn->prepare("SELECT * FROM questions WHERE quiz_id = ? ORDER BY question_id ASC");
        $stmt->bind_param("i", $quiz_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $questions[] = $row;
            }
        } else {
            $error_message = "No questions found for this quiz";
        }
    } else {
        $error_message = "Quiz not found";
    }

    $stmt->close();
} else {
    $error_message = "Quiz ID is required";
}

// Convert questions to JSON for JavaScript
$questions_json = json_encode($questions);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($quiz_title); ?> - Billionaire Game</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Additional styles specific to the play page */
        .back-link {
            position: absolute;
            top: 20px;
            left: 20px;
            color: white;
            text-decoration: none;
            z-index: 10;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .back-link {
                top: 10px;
                left: 10px;
            }
        }
    </style>
</head>

<body>
    <a href="create_quiz.php" class="back-link">← Back to Quizzes</a>

    <?php if (!empty($error_message)): ?>
        <div style="text-align: center; padding: 50px; color: white;">
            <h2>Error</h2>
            <p><?php echo $error_message; ?></p>
            <a href="create_quiz.php" style="color: #2196F3;">Go back to quizzes</a>
        </div>
    <?php else: ?>
        <div class="game-container">
            <div class="game-header">
                <div class="money-ladder">
                    <?php
                    $money_values = [
                        "$1,000",
                        "$2,000",
                        "$4,000",
                        "$8,000",
                        "$16,000",
                        "$32,000",
                        "$64,000",
                        "$125,000",
                        "$250,000",
                        "$500,000",
                        "$1,000,000"
                    ];
                    $total_questions = count($questions);
                    $money_items = array_slice($money_values, 0, $total_questions);
                    $money_items = array_reverse($money_items);

                    foreach ($money_items as $index => $value):
                    ?>
                        <div class="money-item <?php echo $index === 0 ? 'active' : ''; ?>"><?php echo $value; ?></div>
                    <?php endforeach; ?>
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
                    <h2 id="question">Loading question...</h2>
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

        <script>
            // Game data from PHP
            const questions = <?php echo $questions_json; ?>;
            const moneyValues = <?php echo json_encode(array_reverse($money_items)); ?>;

            // Game state
            let currentQuestion = 0;
            let moneyIndex = 0;
            let lifelines = {
                fiftyFifty: true,
                hint: true,
                skip: true
            };
            let thinkingSound = null;

            // DOM elements
            const questionElement = document.getElementById('question');
            const answerButtons = document.querySelectorAll('.answer-btn');
            const moneyItems = document.querySelectorAll('.money-item');
            const hintModal = document.getElementById('hintModal');
            const hintText = document.getElementById('hintText');
            const closeHintBtn = document.querySelector('.close-hint');

            // Sound effects (using free sounds)
            const sounds = {
                intro: new Audio('https://assets.mixkit.co/active_storage/sfx/212/212-preview.mp3'),
                correct: new Audio('https://assets.mixkit.co/active_storage/sfx/270/270-preview.mp3'),
                wrong: new Audio('https://assets.mixkit.co/active_storage/sfx/277/277-preview.mp3'),
                lifeline: new Audio('https://assets.mixkit.co/active_storage/sfx/220/220-preview.mp3'),
                win: new Audio('https://assets.mixkit.co/active_storage/sfx/583/583-preview.mp3'),
                thinking: new Audio('https://assets.mixkit.co/active_storage/sfx/209/209-preview.mp3')
            };

            // Initialize game
            function initGame() {
                // Create welcome screen
                createWelcomeScreen();
            }

            // Create welcome screen
            function createWelcomeScreen() {
                const welcomeScreen = document.createElement('div');
                welcomeScreen.className = 'welcome-screen';
                welcomeScreen.innerHTML = `
                    <div class="welcome-content">
                        <h1>${<?php echo json_encode(htmlspecialchars($quiz_title)); ?>}</h1>
                        <p>Test your knowledge and win big!</p>
                        <button id="start-game">Start Game</button>
                    </div>
                `;
                document.body.appendChild(welcomeScreen);

                // Add styles for welcome screen
                const style = document.createElement('style');
                style.textContent = `
                    .welcome-screen {
                        position: fixed;
                        top: 0;
                        left: 0;
                        width: 100%;
                        height: 100%;
                        background: linear-gradient(135deg, #0a1647, #0d2e8a);
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        z-index: 1000;
                    }
                    .welcome-content {
                        text-align: center;
                        background: rgba(0, 20, 80, 0.8);
                        padding: 3rem;
                        border-radius: 20px;
                        box-shadow: 0 0 50px rgba(0, 100, 255, 0.5);
                        border: 2px solid rgba(100, 150, 255, 0.3);
                        animation: pulse 2s infinite alternate;
                    }
                    @keyframes pulse {
                        0% { transform: scale(1); }
                        100% { transform: scale(1.05); }
                    }
                    .welcome-content h1 {
                        font-size: 2.5rem;
                        margin-bottom: 1rem;
                        color: gold;
                        text-shadow: 0 0 10px rgba(255, 215, 0, 0.5);
                    }
                    .welcome-content p {
                        font-size: 1.2rem;
                        margin-bottom: 2rem;
                        color: white;
                    }
                    #start-game {
                        padding: 1rem 2rem;
                        font-size: 1.2rem;
                        background: linear-gradient(135deg, #2196F3, #0d47a1);
                        color: white;
                        border: none;
                        border-radius: 50px;
                        cursor: pointer;
                        transition: all 0.3s ease;
                        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
                    }
                    #start-game:hover {
                        transform: translateY(-3px);
                        box-shadow: 0 7px 20px rgba(0, 0, 0, 0.4);
                    }
                `;
                document.head.appendChild(style);

                // Start game when button is clicked
                document.getElementById('start-game').addEventListener('click', () => {
                    sounds.intro.play();
                    welcomeScreen.style.animation = 'fadeOut 1s forwards';
                    setTimeout(() => {
                        welcomeScreen.remove();
                        startGame();
                    }, 1000);
                });

                // Add fadeOut animation
                const fadeOutStyle = document.createElement('style');
                fadeOutStyle.textContent = `
                    @keyframes fadeOut {
                        from { opacity: 1; }
                        to { opacity: 0; }
                    }
                `;
                document.head.appendChild(fadeOutStyle);
            }

            // Start the game
            function startGame() {
                // Update money ladder
                updateMoneyLadder();

                // Setup lifelines
                setupLifelines();

                // Show first question with animation
                showQuestionWithAnimation();
            }

            // Show question with animation
            function showQuestionWithAnimation() {
                // Hide question and answers first
                questionElement.style.opacity = '0';

                // Get fresh reference to answer buttons
                const currentAnswerButtons = document.querySelectorAll('.answer-btn');

                currentAnswerButtons.forEach(btn => {
                    btn.style.opacity = '0';
                    btn.style.transform = 'translateY(20px)';
                });

                // Show question with delay
                setTimeout(() => {
                    showQuestion();

                    // Fade in question
                    questionElement.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                    questionElement.style.opacity = '1';

                    // Play thinking sound
                    thinkingSound = sounds.thinking;
                    thinkingSound.loop = true;
                    thinkingSound.volume = 0.3;
                    thinkingSound.play();

                    // Fade in answers one by one
                    const updatedButtons = document.querySelectorAll('.answer-btn');
                    updatedButtons.forEach((btn, index) => {
                        setTimeout(() => {
                            btn.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                            btn.style.opacity = '1';
                            btn.style.transform = 'translateY(0)';
                        }, 300 + (index * 200));
                    });
                }, 500);
            }

            // Show current question
            function showQuestion() {
                const question = questions[currentQuestion];
                questionElement.textContent = question.question_text;

                // Get fresh reference to answer buttons
                const currentAnswerButtons = document.querySelectorAll('.answer-btn');

                currentAnswerButtons.forEach((btn, index) => {
                    // Update button content and state
                    const optionKey = String.fromCharCode(65 + index); // A, B, C, D
                    const optionField = 'option_' + optionKey.toLowerCase();

                    btn.querySelector('span').textContent = question[optionField];
                    btn.className = 'answer-btn';
                    btn.disabled = false;
                    btn.style.opacity = '1';
                    btn.style.transform = 'translateY(0)';
                    btn.classList.remove('correct', 'wrong', 'disabled');

                    // Remove any animations or styles added by lifelines
                    btn.style.animation = 'none';

                    // Clear existing event listeners by cloning and replacing
                    const newBtn = btn.cloneNode(true);
                    btn.parentNode.replaceChild(newBtn, btn);

                    // Add new event listener
                    newBtn.addEventListener('click', () => checkAnswer(newBtn));
                });
            }

            // Check answer
            function checkAnswer(selectedButton) {
                const question = questions[currentQuestion];
                const selectedOption = selectedButton.dataset.option;

                // Stop thinking sound
                if (thinkingSound) {
                    thinkingSound.pause();
                    thinkingSound.currentTime = 0;
                }

                // Get fresh reference to answer buttons
                const currentAnswerButtons = document.querySelectorAll('.answer-btn');

                // Disable all buttons
                currentAnswerButtons.forEach(btn => btn.disabled = true);

                if (selectedOption === question.correct_answer) {
                    // Correct answer
                    selectedButton.classList.add('correct');
                    sounds.correct.play();

                    // Update money ladder with animation
                    setTimeout(() => {
                        updateMoneyLadderWithAnimation();

                        // Move to next question
                        setTimeout(() => {
                            currentQuestion++;
                            if (currentQuestion < questions.length) {
                                showQuestionWithAnimation();
                            } else {
                                // Game won
                                sounds.win.play();
                                showWinScreen();
                            }
                        }, 1500);
                    }, 1500);
                } else {
                    // Wrong answer
                    selectedButton.classList.add('wrong');
                    sounds.wrong.play();

                    // Show correct answer
                    currentAnswerButtons.forEach(btn => {
                        if (btn.dataset.option === question.correct_answer) {
                            btn.classList.add('correct');
                        }
                    });

                    // Game over
                    setTimeout(() => {
                        showGameOverScreen();
                    }, 2000);
                }
            }

            // Update money ladder with animation
            function updateMoneyLadderWithAnimation() {
                // Remove active class from all items
                moneyItems.forEach(item => item.classList.remove('active'));

                // Add active class to current item with animation
                const currentMoneyItem = moneyItems[moneyItems.length - 1 - moneyIndex];
                currentMoneyItem.classList.add('active');
                currentMoneyItem.style.animation = 'pulse 0.5s 3';

                // Add pulse animation
                const pulseStyle = document.createElement('style');
                pulseStyle.textContent = `
                    @keyframes pulse {
                        0%, 100% { transform: scale(1.05); }
                        50% { transform: scale(1.15); }
                    }
                `;
                document.head.appendChild(pulseStyle);

                // Increment money index
                moneyIndex++;
            }

            // Update money ladder
            function updateMoneyLadder() {
                // Set active item
                moneyItems.forEach((item, index) => {
                    if (index === moneyItems.length - 1) {
                        item.classList.add('active');
                    } else {
                        item.classList.remove('active');
                    }
                });
            }

            // Setup lifelines
            function setupLifelines() {
                // 50:50 lifeline
                document.getElementById('fifty-fifty').addEventListener('click', () => {
                    if (!lifelines.fiftyFifty) return;

                    sounds.lifeline.play();

                    const question = questions[currentQuestion];
                    const correctOption = question.correct_answer;
                    let wrongOptions = ['A', 'B', 'C', 'D'].filter(opt => opt !== correctOption);
                    wrongOptions = wrongOptions.sort(() => Math.random() - 0.5).slice(0, 2);

                    // Get fresh reference to answer buttons
                    const currentAnswerButtons = document.querySelectorAll('.answer-btn');

                    currentAnswerButtons.forEach(btn => {
                        if (wrongOptions.includes(btn.dataset.option)) {
                            btn.classList.add('disabled');
                            btn.disabled = true;

                            // Add fade out animation
                            btn.style.animation = 'fadeOut 0.5s forwards';
                        }
                    });

                    lifelines.fiftyFifty = false;
                    document.getElementById('fifty-fifty').classList.add('used');
                });

                // Hint lifeline
                document.getElementById('hint').addEventListener('click', () => {
                    if (!lifelines.hint) return;

                    sounds.lifeline.play();

                    hintText.textContent = questions[currentQuestion].hint;
                    hintModal.classList.add('active');

                    lifelines.hint = false;
                    document.getElementById('hint').classList.add('used');
                });

                // Skip lifeline
                document.getElementById('skip').addEventListener('click', () => {
                    if (!lifelines.skip) return;

                    sounds.lifeline.play();

                    // Stop thinking sound
                    if (thinkingSound) {
                        thinkingSound.pause();
                        thinkingSound.currentTime = 0;
                    }

                    // Add skip animation
                    const skipAnimation = document.createElement('div');
                    skipAnimation.className = 'skip-animation';
                    skipAnimation.textContent = 'SKIPPING...';
                    document.body.appendChild(skipAnimation);

                    // Add skip animation styles
                    const skipStyle = document.createElement('style');
                    skipStyle.textContent = `
                        .skip-animation {
                            position: fixed;
                            top: 0;
                            left: 0;
                            width: 100%;
                            height: 100%;
                            background: rgba(0, 0, 0, 0.7);
                            color: white;
                            display: flex;
                            justify-content: center;
                            align-items: center;
                            font-size: 3rem;
                            font-weight: bold;
                            z-index: 1000;
                            animation: fadeInOut 1.5s forwards;
                        }
                        @keyframes fadeInOut {
                            0% { opacity: 0; }
                            50% { opacity: 1; }
                            100% { opacity: 0; }
                        }
                    `;
                    document.head.appendChild(skipStyle);

                    setTimeout(() => {
                        skipAnimation.remove();

                        currentQuestion++;
                        if (currentQuestion < questions.length) {
                            showQuestionWithAnimation();
                            updateMoneyLadderWithAnimation();
                        } else {
                            sounds.win.play();
                            showWinScreen();
                        }

                        lifelines.skip = false;
                        document.getElementById('skip').classList.add('used');
                    }, 1500);
                });

                // Close hint modal
                closeHintBtn.addEventListener('click', () => {
                    hintModal.classList.remove('active');
                });
            }

            // Show win screen
            function showWinScreen() {
                const winScreen = document.createElement('div');
                winScreen.className = 'result-screen win-screen';
                winScreen.innerHTML = `
                    <div class="result-content">
                        <h1>Congratulations!</h1>
                        <p>You've won ${moneyValues[moneyIndex - 1]}!</p>
                        <button id="play-again">Play Again</button>
                    </div>
                `;
                document.body.appendChild(winScreen);

                // Add confetti effect
                createConfetti();

                // Add styles for win screen
                addResultScreenStyles('win');

                // Play again button
                document.getElementById('play-again').addEventListener('click', () => {
                    winScreen.remove();
                    resetGame();
                });
            }

            // Show game over screen
            function showGameOverScreen() {
                const gameOverScreen = document.createElement('div');
                gameOverScreen.className = 'result-screen game-over-screen';
                gameOverScreen.innerHTML = `
                    <div class="result-content">
                        <h1>Game Over!</h1>
                        <p>You won: ${moneyIndex > 0 ? moneyValues[moneyIndex - 1] : '$0'}</p>
                        <button id="try-again">Try Again</button>
                    </div>
                `;
                document.body.appendChild(gameOverScreen);

                // Add styles for game over screen
                addResultScreenStyles('game-over');

                // Try again button
                document.getElementById('try-again').addEventListener('click', () => {
                    gameOverScreen.remove();
                    resetGame();
                });
            }

            // Add result screen styles
            function addResultScreenStyles(type) {
                const bgColor = type === 'win' ? 'rgba(0, 100, 0, 0.9)' : 'rgba(150, 0, 0, 0.9)';
                const borderColor = type === 'win' ? 'gold' : '#ff6b6b';

                const style = document.createElement('style');
                style.textContent = `
                    .result-screen {
                        position: fixed;
                        top: 0;
                        left: 0;
                        width: 100%;
                        height: 100%;
                        background: rgba(0, 0, 0, 0.8);
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        z-index: 1000;
                        animation: fadeIn 0.5s ease;
                    }
                    .result-content {
                        text-align: center;
                        background: ${bgColor};
                        padding: 3rem;
                        border-radius: 20px;
                        box-shadow: 0 0 50px rgba(0, 0, 0, 0.5);
                        border: 3px solid ${borderColor};
                        animation: popIn 0.5s forwards;
                    }
                    .result-content h1 {
                        font-size: 2.5rem;
                        margin-bottom: 1rem;
                        color: white;
                        text-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
                    }
                    .result-content p {
                        font-size: 1.5rem;
                        margin-bottom: 2rem;
                        color: ${type === 'win' ? 'gold' : 'white'};
                        font-weight: bold;
                    }
                    #play-again, #try-again {
                        padding: 1rem 2rem;
                        font-size: 1.2rem;
                        background: linear-gradient(135deg, #2196F3, #0d47a1);
                        color: white;
                        border: none;
                        border-radius: 50px;
                        cursor: pointer;
                        transition: all 0.3s ease;
                        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
                    }
                    #play-again:hover, #try-again:hover {
                        transform: translateY(-3px);
                        box-shadow: 0 7px 20px rgba(0, 0, 0, 0.4);
                    }
                `;
                document.head.appendChild(style);
            }

            // Create confetti effect
            function createConfetti() {
                const confettiContainer = document.createElement('div');
                confettiContainer.className = 'confetti-container';
                document.body.appendChild(confettiContainer);

                // Add confetti styles
                const confettiStyle = document.createElement('style');
                confettiStyle.textContent = `
                    .confetti-container {
                        position: fixed;
                        top: 0;
                        left: 0;
                        width: 100%;
                        height: 100%;
                        pointer-events: none;
                        z-index: 999;
                    }
                    .confetti {
                        position: absolute;
                        width: 10px;
                        height: 10px;
                        background: #f00;
                        animation: fall linear forwards;
                    }
                    @keyframes fall {
                        to {
                            transform: translateY(100vh) rotate(360deg);
                        }
                    }
                `;
                document.head.appendChild(confettiStyle);

                // Create confetti pieces
                const colors = ['#f00', '#0f0', '#00f', '#ff0', '#0ff', '#f0f', '#fff'];
                for (let i = 0; i < 100; i++) {
                    const confetti = document.createElement('div');
                    confetti.className = 'confetti';
                    confetti.style.left = Math.random() * 100 + 'vw';
                    confetti.style.width = Math.random() * 10 + 5 + 'px';
                    confetti.style.height = Math.random() * 10 + 5 + 'px';
                    confetti.style.background = colors[Math.floor(Math.random() * colors.length)];
                    confetti.style.animationDuration = Math.random() * 3 + 2 + 's';
                    confetti.style.animationDelay = Math.random() * 2 + 's';
                    confettiContainer.appendChild(confetti);
                }

                // Remove confetti after animation
                setTimeout(() => {
                    confettiContainer.remove();
                }, 5000);
            }

            // Reset game
            function resetGame() {
                currentQuestion = 0;
                moneyIndex = 0;
                lifelines = {
                    fiftyFifty: true,
                    hint: true,
                    skip: true
                };

                // Reset lifeline buttons
                document.querySelectorAll('.lifeline-btn').forEach(btn => {
                    btn.classList.remove('used');
                });

                // Reset answer buttons
                document.querySelectorAll('.answer-btn').forEach(btn => {
                    btn.className = 'answer-btn';
                    btn.disabled = false;
                    btn.style.opacity = '1';
                    btn.style.transform = 'translateY(0)';
                    btn.style.animation = 'none';
                });

                // Reset money ladder
                updateMoneyLadder();

                // Show first question
                showQuestionWithAnimation();
            }

            // Start the game
            initGame();
        </script>
    <?php endif; ?>
</body>

</html>