<?php
session_start();
include 'includes/session.php';
include 'includes/header.php';
include 'db/dbconn.php'; // Include your database connection file

$quizId = isset($_GET['quiz_id']) ? intval($_GET['quiz_id']) : 0;
$userId = $_SESSION['user_id']; // Get user ID from session

// Check if the quiz ID is valid
if ($quizId > 0) {
    // Prepare and execute a query to check if the user has already taken the quiz
    $stmt = $conn->prepare("SELECT * FROM quiz_attempt WHERE user_id = ? AND quiz_id = ?");
    if ($stmt === false) {
        die("Database error: " . $conn->error);
    }

    $stmt->bind_param("ii", $userId, $quizId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // User has already taken the quiz
        echo "<script>
                alert('You have already taken this quiz.');
                window.location.href = 'take_quiz.php'; // Redirect to quiz list or another page
              </script>";
        exit();
    }
    // Close the statement
    $stmt->close();
} else {
    // Invalid quiz ID
    echo "<script>
            alert('Invalid quiz ID.');
            window.location.href = 'take_quiz.php'; // Redirect to quiz list or another page
          </script>";
    exit();
}

$quiz_id = $_GET['quiz_id'];
$classroom_id = isset($_GET['roomId']) ? $_GET['roomId'] : 0;

// Fetch the first question from the database
$questionQuery = "SELECT * FROM questions WHERE quiz_id = $quiz_id ORDER BY question_id ASC LIMIT 1";
$result = $conn->query($questionQuery);
$question = $result->fetch_assoc();

// Fetch difficulty
$quizDiff = $conn->prepare("SELECT difficulty FROM quiz WHERE quiz_id = ?");
$quizDiff->bind_param("i", $quiz_id);
$quizDiff->execute();
$result = $quizDiff->get_result();
$difficultyRow = $result->fetch_assoc(); // Fetch the row
$difficulty = $difficultyRow['difficulty']; // Get the difficulty value
$quizDiff->close();
?>




<link rel="stylesheet" href="css/quiz_time.css">
<link rel="stylesheet" href="css/loading-screen.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">


<audio id="background-audio" loop muted>
    <source src="assets/img/quiz-music.mp3" type="audio/mpeg">
</audio>
<audio id="answer-sound">
    <source src="assets/img/click.mp3" type="audio/mpeg"> <!-- Add your answer sound file here -->
</audio>

<body>
    <?php include("includes/loading-screen.php"); ?>
    <div class="timer-container">
        <input type="hidden" value="<?php echo $difficulty; ?>" id="quiz_difficulty">
        <div class="timer-icon">
            <i class="fas fa-clock"></i> <!-- Use Font Awesome or any icon library -->
        </div>
        <div class="timer-content">
            <div class="timer-text">Loading...</div>
            <div class="timer-progress">
                <div class="timer-bar"></div>
            </div>
        </div>
    </div>

    <main id="main" class="main">
        <section class="section" data-question-number="1">
            <div class="quiz-question">
                <h1><?php echo $question['quiz_question']; ?></h1> <!-- Display the question text -->
            </div>
        </section>
    </main>
    <main id="main" class="main2">
        <div class="row" id="answer-options">
            <?php if ($question['quiz_type'] === 'Multiple Choice'): ?>
                <div class="col choice-A" data-label="A">
                    <button id="optionA" class="btn option-btn"
                        data-choice="A"><?php echo $question['option_a']; ?></button>
                </div>
                <div class="col choice-B" data-label="B">
                    <button id="optionB" class="btn option-btn"
                        data-choice="B"><?php echo $question['option_b']; ?></button>
                </div>
                <div class="col choice-C" data-label="C">
                    <button id="optionC" class="btn option-btn"
                        data-choice="C"><?php echo $question['option_c']; ?></button>
                </div>
                <div class="col choice-D" data-label="D">
                    <button id="optionD" class="btn option-btn"
                        data-choice="D"><?php echo $question['option_d']; ?></button>
                </div>
            <?php elseif ($question['quiz_type'] === 'True/False'): ?>
                <div class="col choice-True" data-label="True">
                    <button id="optionTrue" class="btn option-btn" data-choice="True">True</button>
                </div>
                <div class="col choice-False" data-label="False">
                    <button id="optionFalse" class="btn option-btn" data-choice="False">False</button>
                </div>
            <?php elseif ($question['quiz_type'] === 'Short Answer'): ?>
                <div class="col short-answer">
                    <input type="text" id="shortAnswer" class="short-form-control" placeholder="Type your answer here...">
                </div>
            <?php endif; ?>
        </div>
    </main>

    <?php include 'js/scripts.php'; ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let quizInProgress = true;
            let hasStartedQuiz = true;

            // Function to handle quiz interruption
            function handleQuizInterruption() {
                if (quizInProgress && hasStartedQuiz) {
                    // Immediately calculate score and redirect to results
                    calculateScore();
                    return "Quiz in progress. Your attempt will be submitted.";
                }
            }

            // Prevent accidental page reload or navigation
            window.addEventListener('beforeunload', function(e) {
                const warningMessage = handleQuizInterruption();
                if (warningMessage) {
                    e.preventDefault(); // Cancel the event
                    e.returnValue = warningMessage; // Display a generic message in most browsers
                }
            });

            // Handle browser back/forward navigation
            window.addEventListener('popstate', function(e) {
                if (quizInProgress && hasStartedQuiz) {
                    // Prevent default navigation
                    history.pushState(null, null, location.href);

                    // Show confirmation dialog
                    const confirmLeave = confirm("You are currently taking a quiz. If you leave, your current attempt will be submitted. Do you want to continue?");

                    if (confirmLeave) {
                        // Submit the quiz and redirect to results
                        calculateScore();
                    }
                }
            });

            // Modify existing calculateScore function to set quizInProgress to false
            const originalCalculateScore = calculateScore;
            calculateScore = function() {
                quizInProgress = false;
                originalCalculateScore();
            };

            // Set initial history state
            history.replaceState(null, null, location.href);
        });
    </script>

    <script>
        const audio = document.getElementById('background-audio');
        const answerSound = document.getElementById('answer-sound');

        // Function to play background audio
        function playAudio() {
            audio.volume = 0.5;
            audio.muted = false; // Unmute the audio
            audio.play().catch(function(error) {
                console.log("Audio playback failed: ", error);
            });
        }

        // Function to play answer sound
        function playAnswerSound() {
            answerSound.volume = 1.0; // Set volume to 50% (adjust as needed)
            answerSound.currentTime = 0; // Reset sound to start
            answerSound.play().catch(function(error) {
                console.log("Answer sound playback failed: ", error);
            });
        }

        // Check if the user has interacted with the page before
        if (localStorage.getItem('audioPlayed') === 'true') {
            playAudio();
        }

        // Event listener for user interaction
        window.addEventListener('click', function() {
            localStorage.setItem('audioPlayed', 'true'); // Set flag in local storage
            playAudio(); // Play audio on first click
        });

        document.addEventListener('DOMContentLoaded', function() {
            // Remove loading screen immediately
            const loadingScreen = document.getElementById('loading-screen');
            if (loadingScreen) {
                loadingScreen.style.display = 'none';
            }

            sessionStorage.clear();
            // Attach keypress event for Enter key to submit the short answer if it's the first question
            const initialShortAnswerInput = document.getElementById('shortAnswer');
            if (initialShortAnswerInput) {
                initialShortAnswerInput.addEventListener('keypress', function(event) {
                    if (event.key === 'Enter') {
                        const selectedAnswer = event.target.value;
                        playAnswerSound(); // Play sound when an answer is submitted
                        storeAnswerAndLoadNext(selectedAnswer);
                        event.target.value = ''; // Clear input after submission
                    }
                });
            }
        });

        let currentQuestionId = <?php echo $question['question_id']; ?>;
        let selectedAnswers = JSON.parse(sessionStorage.getItem('selectedAnswers')) || {};
        let currentQuestionNumber = 1;

        document.querySelectorAll('.option-btn').forEach(button => {
            button.addEventListener('click', function() {
                const selectedAnswer = this.getAttribute('data-choice');
                playAnswerSound();
                storeAnswerAndLoadNext(selectedAnswer);
            });
        });

        document.getElementById('submitAnswer')?.addEventListener('click', function() {
            const selectedAnswer = document.getElementById('shortAnswer').value;
            storeAnswerAndLoadNext(selectedAnswer);
        });

        // Timer functionality
        const quizDifficulty = document.querySelector('#quiz_difficulty').value;
        const timeLimit = quizDifficulty === 'Easy' ? 10 : quizDifficulty === 'Moderate' ? 20 : 30;
        const TOTAL_TIME = timeLimit * 60; // 10 minutes in seconds
        let remainingTime = TOTAL_TIME;
        const timerContainer = document.querySelector('.timer-container');
        const timerBar = document.querySelector('.timer-bar');
        const timerText = document.querySelector('.timer-text');

        function formatTime(seconds) {
            const minutes = Math.floor(seconds / 60);
            const remainingSeconds = seconds % 60;
            return `${minutes}:${remainingSeconds < 10 ? '0' : ''}${remainingSeconds}`;
        }

        function updateTimer() {
            remainingTime--;

            // Update timer bar width
            const percentage = (remainingTime / TOTAL_TIME) * 100;
            timerBar.style.width = `${percentage}%`;

            // Update timer text
            timerText.textContent = formatTime(remainingTime);

            // Update container state based on remaining time
            if (remainingTime <= 60) {
                timerContainer.classList.remove('warning');
                timerContainer.classList.add('danger');
            } else if (remainingTime <= 180) {
                timerContainer.classList.add('warning');
            }

            // Vibration and sound warning for last 10 seconds
            if (remainingTime <= 10) {
                // Optional: Add vibration for mobile devices
                if ('vibrate' in navigator) {
                    navigator.vibrate(200);
                }
            }

            // When timer reaches 0
            if (remainingTime <= 0) {
                clearInterval(timerInterval);
                // Automatically submit the quiz with blank answers
                Object.keys(selectedAnswers).forEach(questionId => {
                    if (!selectedAnswers[questionId]) {
                        selectedAnswers[questionId] = ''; // Mark as blank
                    }
                });
                calculateScore();
            }
        }

        // Start the timer when the page loads
        const timerInterval = setInterval(updateTimer, 1000);



        function storeAnswerAndLoadNext(selectedAnswer) {
            selectedAnswers[currentQuestionId] = selectedAnswer;
            sessionStorage.setItem('selectedAnswers', JSON.stringify(selectedAnswers));
            console.log('Stored Selected Answers:', selectedAnswers);

            // Show the loading screen
            document.getElementById('loading-screen').style.display = 'flex';

            // Fetch the next question
            fetch(`process/fetch_next_question.php?current_id=${currentQuestionId}&quiz_id=<?php echo $quiz_id; ?>`)
                .then(response => response.json())
                .then(data => {
                    // Minimum display time for the loading screen
                    const minimumLoadingTime = 1000; // in milliseconds
                    const startTime = Date.now();

                    // Hide the loading screen after a delay
                    const hideLoadingScreen = () => {
                        const elapsedTime = Date.now() - startTime;
                        const delay = Math.max(0, minimumLoadingTime - elapsedTime);
                        setTimeout(() => {
                            document.getElementById('loading-screen').style.display = 'none';
                            if (data.success) {
                                displayNextQuestion(data.question);
                                currentQuestionId = data.question.question_id;
                                currentQuestionNumber++;
                                document.querySelector('.section').setAttribute('data-question-number', currentQuestionNumber);
                            } else {
                                calculateScore();
                            }
                        }, delay);
                    };

                    hideLoadingScreen();
                })
                .catch(error => {
                    // Ensure the loading screen is hidden on error
                    document.getElementById('loading-screen').style.display = 'none';
                    console.error('Error fetching next question:', error);
                });
        }

        function displayNextQuestion(question) {
            document.querySelector('.quiz-question h1').innerText = question.quiz_question;

            const answerOptionsDiv = document.getElementById('answer-options');
            answerOptionsDiv.innerHTML = ''; // Clear current options

            if (question.quiz_type === 'Multiple Choice') {
                answerOptionsDiv.innerHTML = `
                    <div class="col choice-A" data-label="A"><button class="btn option-btn" data-choice="A">${question.option_a}</button></div>
                    <div class="col choice-B" data-label="B"><button class="btn option-btn" data-choice="B">${question.option_b}</button></div>
                    <div class="col choice-C" data-label="C"><button class="btn option-btn" data-choice="C">${question.option_c}</button></div>
                    <div class="col choice-D" data-label="D"><button class="btn option-btn" data-choice="D">${question.option_d}</button></div>`;
            } else if (question.quiz_type === 'True/False') {
                answerOptionsDiv.innerHTML = `
                    <div class="col choice-True" data-label="True"><button class="btn option-btn" data-choice="True">True</button></div>
                    <div class="col choice-False" data-label="False"><button class="btn option-btn" data-choice="False">False</button></div>`;
            } else if (question.quiz_type === 'Short Answer') {
                answerOptionsDiv.innerHTML = `
                    <div class="col short-answer">
                        <input type="text" id="shortAnswer" class="short-form-control" placeholder="Type your answer here...">
                    </div>`;
            }

            document.querySelectorAll('.option-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const selectedAnswer = this.getAttribute('data-choice');
                    playAnswerSound(); // Play sound when an answer is selected
                    storeAnswerAndLoadNext(selectedAnswer);
                });
            });

            setTimeout(() => {
                const shortAnswerInput = document.getElementById('shortAnswer');
                if (shortAnswerInput) {
                    shortAnswerInput.addEventListener('keypress', function(event) {
                        if (event.key === 'Enter') {
                            const selectedAnswer = event.target.value;
                            playAnswerSound(); // Play sound when an answer is submitted
                            storeAnswerAndLoadNext(selectedAnswer);
                            event.target.value = ''; // Clear input after submission
                        }
                    });
                }
            }, 0);
        }

        function calculateScore() {
            fetch('process/fetch_correct_answers.php?quiz_id=<?php echo $quiz_id; ?>')
                .then(response => response.json())
                .then(data => {
                    const correctAnswers = data.correctAnswers;
                    const totalQuestions = Object.keys(correctAnswers).length;
                    const perfectPoints = data.pointsCriteria.perfect;
                    const consolationPoints = data.pointsCriteria.consolation;

                    let score = 0;

                    // Calculate the score by comparing selected answers with correct answers
                    for (const questionId in selectedAnswers) {
                        if (selectedAnswers[questionId] === correctAnswers[questionId]) {
                            score++;
                        }
                    }

                    // Determine points based on whether the score is perfect
                    const earnedPoints = score === totalQuestions ? perfectPoints : consolationPoints;

                    // Send score to PHP for recording
                    fetch('process/record_attempt.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                quiz_id: <?php echo $quiz_id; ?>,
                                user_id: <?php echo $user_id; ?>, // Replace with actual student_id variable if different
                                score: score,
                                classroom_id: <?php echo $classroom_id; ?>
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                document.getElementById('loading-screen').style.display = 'flex';
                                // Redirect to the result page with score and earned points in the URL
                                window.location.href = 'quiz_result.php?quiz_id=' + <?php echo $quiz_id; ?> +
                                    '&score=' + score + '&points=' + earnedPoints;
                            } else {
                                console.error('Error recording attempt:', data.error);
                            }
                        })
                        .catch(error => console.error('Error in recording attempt:', error));

                    sessionStorage.clear();
                });
        }
    </script>

</body>

</html>