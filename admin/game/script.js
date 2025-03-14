/**
 * Game Configuration
 * Contains all the static configuration values used throughout the game
 */
// Money ladder configuration
const moneyValues = [
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
  "$1,000,000",
];

// Sound effects configuration
const sounds = {
  intro: new Audio("https://assets.mixkit.co/active_storage/sfx/212/212-preview.mp3"),
  correct: new Audio("https://assets.mixkit.co/active_storage/sfx/270/270-preview.mp3"),
  wrong: new Audio("https://assets.mixkit.co/active_storage/sfx/277/277-preview.mp3"),
  lifeline: new Audio("https://assets.mixkit.co/active_storage/sfx/220/220-preview.mp3"),
  win: new Audio("https://assets.mixkit.co/active_storage/sfx/583/583-preview.mp3"),
  thinking: new Audio("https://assets.mixkit.co/active_storage/sfx/209/209-preview.mp3"),
};

/**
 * Game State Management
 * Variables that track the current state of the game
 */
let questions = [];
let currentQuestion = 0;
let moneyIndex = 0;
let lifelines = {
  fiftyFifty: true,
  hint: true,
  skip: true,
};
let thinkingSound = null;

/**
 * DOM Elements
 * Cached references to frequently used DOM elements
 */
const questionElement = document.getElementById("question");
const answerButtons = document.querySelectorAll(".answer-btn");
const moneyItems = document.querySelectorAll(".money-item");
const hintModal = document.getElementById("hintModal");
const hintText = document.getElementById("hintText");
const closeHintBtn = document.querySelector(".close-hint");

/**
 * Game Initialization
 * Functions responsible for setting up and starting the game
 */

// Load questions from server and initialize game
(async function loadQuestions() {
  try {
    if (!quizId) {
      throw new Error("Quiz ID is missing.");
    }

    const response = await fetch(`process/question/get_questions.php?quiz_id=${quizId}`);
    if (!response.ok) {
      throw new Error(`HTTP error! Status: ${response.status}`);
    }

    const data = await response.json();
    if (!data || data.length === 0) {
      console.log("Quiz not found.");
      throw new Error("Quiz not found.");
    }

    questions = data.map((q) => ({
      question: q.question_text,
      options: [q.option_a, q.option_b, q.option_c, q.option_d],
      correct: q.correct_answer,
      hint: q.hint,
    }));

    initGame(); // Start the game only if the quiz exists
  } catch (error) {
    console.error("Error fetching questions:", error);
    displayQuizNotFoundScreen();
  }
})();

/**
 * Game Core Functions
 * Core game logic and mechanics
 */

// Initialize game
function initGame() {
  createWelcomeScreen();
}

// Start the game
function startGame() {
  shuffleQuestions();
  updateMoneyLadder();
  setupLifelines();
  showQuestionWithAnimation();
}

// Shuffle questions array randomly
function shuffleQuestions() {
  for (let i = questions.length - 1; i > 0; i--) {
    const j = Math.floor(Math.random() * (i + 1));
    [questions[i], questions[j]] = [questions[j], questions[i]];
  }
}

/**
 * Question Display and Animation
 * Functions handling question display and animations
 */

// Show question with animation effects
function showQuestionWithAnimation() {
  // Hide question and answers first
  questionElement.style.opacity = "0";
  const currentAnswerButtons = document.querySelectorAll(".answer-btn");

  currentAnswerButtons.forEach((btn) => {
    btn.style.opacity = "0";
    btn.style.transform = "translateY(20px)";
  });

  // Show question with delay
  setTimeout(() => {
    showQuestion();
    questionElement.style.transition = "opacity 0.5s ease, transform 0.5s ease";
    questionElement.style.opacity = "1";

    // Play thinking sound
    thinkingSound = sounds.thinking;
    thinkingSound.loop = true;
    thinkingSound.volume = 0.3;
    thinkingSound.play();

    // Fade in answers one by one
    const updatedButtons = document.querySelectorAll(".answer-btn");
    updatedButtons.forEach((btn, index) => {
      setTimeout(() => {
        btn.style.transition = "opacity 0.5s ease, transform 0.5s ease";
        btn.style.opacity = "1";
        btn.style.transform = "translateY(0)";
      }, 300 + index * 200);
    });
  }, 500);
}

// Display current question and setup answer buttons
function showQuestion() {
  const question = questions[currentQuestion];
  questionElement.textContent = question.question;

  const currentAnswerButtons = document.querySelectorAll(".answer-btn");

  currentAnswerButtons.forEach((btn, index) => {
    // Update button content and state
    btn.querySelector("span").textContent = question.options[index];
    btn.className = "answer-btn";
    btn.disabled = false;
    btn.style.opacity = "1";
    btn.style.transform = "translateY(0)";
    btn.classList.remove("correct", "wrong", "disabled");
    btn.style.animation = "none";

    // Clear existing event listeners by cloning and replacing
    const newBtn = btn.cloneNode(true);
    btn.parentNode.replaceChild(newBtn, btn);

    // Add new event listener
    newBtn.addEventListener("click", () => checkAnswer(newBtn));
  });
}

/**
 * Answer Checking and Game Progress
 * Functions handling answer validation and game progression
 */

// Check selected answer and update game state
function checkAnswer(selectedButton) {
  const question = questions[currentQuestion];
  const selectedOption = selectedButton.dataset.option;

  // Stop thinking sound
  if (thinkingSound) {
    thinkingSound.pause();
    thinkingSound.currentTime = 0;
  }

  const currentAnswerButtons = document.querySelectorAll(".answer-btn");
  currentAnswerButtons.forEach((btn) => (btn.disabled = true));

  if (selectedOption === question.correct) {
    handleCorrectAnswer(selectedButton);
  } else {
    handleWrongAnswer(selectedButton, question.correct);
  }
}

// Handle correct answer scenario
function handleCorrectAnswer(selectedButton) {
  selectedButton.classList.add("correct");
  sounds.correct.play();

  setTimeout(() => {
    updateMoneyLadderWithAnimation();

    setTimeout(() => {
      currentQuestion++;
      if (currentQuestion < questions.length) {
        showQuestionWithAnimation();
      } else {
        sounds.win.play();
        showWinScreen();
      }
    }, 1500);
  }, 1500);
}

// Handle wrong answer scenario
function handleWrongAnswer(selectedButton, correctAnswer) {
  selectedButton.classList.add("wrong");
  sounds.wrong.play();

  const currentAnswerButtons = document.querySelectorAll(".answer-btn");
  currentAnswerButtons.forEach((btn) => {
    if (btn.dataset.option === correctAnswer) {
      btn.classList.add("correct");
    }
  });

  setTimeout(() => {
    showGameOverScreen();
  }, 2000);
}

/**
 * Money Ladder Management
 * Functions handling the money ladder display and updates
 */

// Update money ladder with animation
function updateMoneyLadderWithAnimation() {
  moneyItems.forEach((item) => item.classList.remove("active"));

  const currentMoneyItem = moneyItems[moneyItems.length - 1 - moneyIndex];
  currentMoneyItem.classList.add("active");
  currentMoneyItem.style.animation = "pulse 0.5s 3";

  const pulseStyle = document.createElement("style");
  pulseStyle.textContent = `
    @keyframes pulse {
      0%, 100% { transform: scale(1.05); }
      50% { transform: scale(1.15); }
    }
  `;
  document.head.appendChild(pulseStyle);

  moneyIndex++;
}

// Update money ladder display
function updateMoneyLadder() {
  moneyItems.forEach((item, index) => {
    item.textContent = moneyValues[moneyItems.length - 1 - index];
    if (index === moneyItems.length - 1) {
      item.classList.add("active");
    } else {
      item.classList.remove("active");
    }
  });
}

/**
 * Lifeline Management
 * Functions handling game lifelines and their effects
 */

// Setup lifeline functionality
function setupLifelines() {
  setupFiftyFiftyLifeline();
  setupHintLifeline();
  setupSkipLifeline();
  setupHintModalClose();
}

// Setup 50:50 lifeline
function setupFiftyFiftyLifeline() {
  document.getElementById("fifty-fifty").addEventListener("click", () => {
    if (!lifelines.fiftyFifty) return;

    sounds.lifeline.play();
    const question = questions[currentQuestion];
    const correctOption = question.correct;
    let wrongOptions = ["A", "B", "C", "D"].filter(opt => opt !== correctOption);
    wrongOptions = wrongOptions.sort(() => Math.random() - 0.5).slice(0, 2);

    const currentAnswerButtons = document.querySelectorAll(".answer-btn");
    currentAnswerButtons.forEach((btn) => {
      if (wrongOptions.includes(btn.dataset.option)) {
        btn.classList.add("disabled");
        btn.disabled = true;
        btn.style.animation = "fadeOut 0.5s forwards";
      }
    });

    lifelines.fiftyFifty = false;
    document.getElementById("fifty-fifty").classList.add("used");
  });
}

// Setup hint lifeline
function setupHintLifeline() {
  document.getElementById("hint").addEventListener("click", () => {
    if (!lifelines.hint) return;

    sounds.lifeline.play();
    hintText.textContent = questions[currentQuestion].hint;
    hintModal.classList.add("active");

    lifelines.hint = false;
    document.getElementById("hint").classList.add("used");
  });
}

// Setup skip lifeline
function setupSkipLifeline() {
  document.getElementById("skip").addEventListener("click", () => {
    if (!lifelines.skip) return;

    sounds.lifeline.play();
    if (thinkingSound) {
      thinkingSound.pause();
      thinkingSound.currentTime = 0;
    }

    const skipAnimation = document.createElement("div");
    skipAnimation.className = "skip-animation";
    skipAnimation.textContent = "SKIPPING...";
    document.body.appendChild(skipAnimation);

    const skipStyle = document.createElement("style");
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
      handleSkipQuestion();
    }, 1500);
  });
}

// Handle skip question logic
function handleSkipQuestion() {
  currentQuestion++;
  if (currentQuestion < questions.length) {
    showQuestionWithAnimation();
    updateMoneyLadderWithAnimation();
  } else {
    sounds.win.play();
    showWinScreen();
  }

  lifelines.skip = false;
  document.getElementById("skip").classList.add("used");
}

// Setup hint modal close functionality
function setupHintModalClose() {
  closeHintBtn.addEventListener("click", () => {
    hintModal.classList.remove("active");
  });
}

/**
 * Screen Management
 * Functions handling different game screens (welcome, win, game over)
 */

// Create welcome screen
function createWelcomeScreen() {
  const welcomeScreen = document.createElement("div");
  welcomeScreen.className = "welcome-screen";
  welcomeScreen.innerHTML = `
    <div class="welcome-content">
      <h1>${quizTitle}</h1>
      <p>Test your knowledge and win big!</p>
      <button id="start-game">Start Game</button>
    </div>
  `;
  document.body.appendChild(welcomeScreen);

  addWelcomeScreenStyles();
  setupWelcomeScreenEvents(welcomeScreen);
}

// Add welcome screen styles
function addWelcomeScreenStyles() {
  const style = document.createElement("style");
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
}

// Setup welcome screen event listeners
function setupWelcomeScreenEvents(welcomeScreen) {
  document.getElementById("start-game").addEventListener("click", () => {
    sounds.intro.play();
    welcomeScreen.style.animation = "fadeOut 1s forwards";
    setTimeout(() => {
      welcomeScreen.remove();
      startGame();
    }, 1000);
  });

  const fadeOutStyle = document.createElement("style");
  fadeOutStyle.textContent = `
    @keyframes fadeOut {
      from { opacity: 1; }
      to { opacity: 0; }
    }
  `;
  document.head.appendChild(fadeOutStyle);
}

// Show win screen
function showWinScreen() {
  const winScreen = document.createElement("div");
  winScreen.className = "result-screen win-screen";
  winScreen.innerHTML = `
    <div class="result-content">
      <h1>Congratulations!</h1>
      <p>You've won $1,000,000!</p>
      <button id="play-again">Play Again</button>
    </div>
  `;
  document.body.appendChild(winScreen);

  createConfetti();
  addResultScreenStyles("win");

  document.getElementById("play-again").addEventListener("click", () => {
    winScreen.remove();
    resetGame();
  });
}

// Show game over screen
function showGameOverScreen() {
  const gameOverScreen = document.createElement("div");
  gameOverScreen.className = "result-screen game-over-screen";
  gameOverScreen.innerHTML = `
    <div class="result-content">
      <h1>Game Over!</h1>
      <p>You won: ${moneyIndex > 0 ? moneyValues[moneyIndex - 1] : "$0"}</p>
      <button id="try-again">Try Again</button>
    </div>
  `;
  document.body.appendChild(gameOverScreen);

  addResultScreenStyles("game-over");

  document.getElementById("try-again").addEventListener("click", () => {
    gameOverScreen.remove();
    resetGame();
  });
}

// Add result screen styles
function addResultScreenStyles(type) {
  const bgColor = type === "win" ? "rgba(0, 100, 0, 0.9)" : "rgba(150, 0, 0, 0.9)";
  const borderColor = type === "win" ? "gold" : "#ff6b6b";

  const style = document.createElement("style");
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
      color: ${type === "win" ? "gold" : "white"};
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

/**
 * Visual Effects
 * Functions handling visual effects and animations
 */

// Create confetti effect for win screen
function createConfetti() {
  const confettiContainer = document.createElement("div");
  confettiContainer.className = "confetti-container";
  document.body.appendChild(confettiContainer);

  const confettiStyle = document.createElement("style");
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

  const colors = ["#f00", "#0f0", "#00f", "#ff0", "#0ff", "#f0f", "#fff"];
  for (let i = 0; i < 100; i++) {
    const confetti = document.createElement("div");
    confetti.className = "confetti";
    confetti.style.left = Math.random() * 100 + "vw";
    confetti.style.width = Math.random() * 10 + 5 + "px";
    confetti.style.height = Math.random() * 10 + 5 + "px";
    confetti.style.background = colors[Math.floor(Math.random() * colors.length)];
    confetti.style.animationDuration = Math.random() * 3 + 2 + "s";
    confetti.style.animationDelay = Math.random() * 2 + "s";
    confettiContainer.appendChild(confetti);
  }

  setTimeout(() => {
    confettiContainer.remove();
  }, 5000);
}

/**
 * Game Reset
 * Functions handling game reset functionality
 */

// Reset game state
function resetGame() {
  currentQuestion = 0;
  moneyIndex = 0;
  lifelines = {
    fiftyFifty: true,
    hint: true,
    skip: true,
  };

  // Reset lifeline buttons
  document.querySelectorAll(".lifeline-btn").forEach((btn) => {
    btn.classList.remove("used");
  });

  // Reset answer buttons
  document.querySelectorAll(".answer-btn").forEach((btn) => {
    btn.className = "answer-btn";
    btn.disabled = false;
    btn.style.opacity = "1";
    btn.style.transform = "translateY(0)";
    btn.style.animation = "none";
  });

  setTimeout(() => {
    window.location.href = "create_quiz.php";
  }, 2000);
}

/**
 * Error Handling
 * Functions handling error scenarios
 */

// Display quiz not found screen
function displayQuizNotFoundScreen() {
  const notFoundScreen = document.createElement("div");
  notFoundScreen.className = "welcome-screen";
  notFoundScreen.innerHTML = `
    <div class="welcome-content">
      <h1>Quiz Not Found</h1>
      <p>Sorry, the requested quiz could not be found.</p>
      <button class="return-button" onclick="window.location.href='create_quiz.php'">Return to Quiz Selection</button>
    </div>
  `;
  document.body.appendChild(notFoundScreen);

  const style = document.createElement("style");
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
      background: rgba(80, 0, 0, 0.8);
      padding: 3rem;
      border-radius: 20px;
      box-shadow: 0 0 50px rgba(255, 0, 0, 0.5);
      border: 2px solid rgba(255, 100, 100, 0.3);
      animation: pulse 2s infinite alternate;
    }
    @keyframes pulse {
      0% { transform: scale(1); }
      100% { transform: scale(1.05); }
    }
    .welcome-content h1 {
      font-size: 2.5rem;
      margin-bottom: 1rem;
      color: #ff3333;
      text-shadow: 0 0 10px rgba(255, 0, 0, 0.5);
    }
    .welcome-content p {
      font-size: 1.2rem;
      margin-bottom: 2rem;
      color: white;
    }
    .return-button {
      padding: 1rem 2rem;
      font-size: 1.2rem;
      background: linear-gradient(135deg,rgb(181, 45, 92), rgb(138, 22, 86));
      color: white;
      border: 1px solid white;
      border-radius: 50px;
      cursor: pointer;
      transition: all 0.3s ease;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
    }
    .return-button:hover {
      transform: translateY(-3px);
      box-shadow: 0 7px 20px rgba(0, 0, 0, 0.4);
    }
  `;
  document.head.appendChild(style);
}
