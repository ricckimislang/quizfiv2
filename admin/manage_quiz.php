<?php
include_once 'includes/header.php';
?>

<!-- links -->
<link rel="stylesheet" href="css/manage_quiz.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">



<body>
    <header id="header" class="header fixed-top d-flex align-items-center">
        <?php include_once 'includes/nav-top.php'; ?>
    </header>
    <main id="main" class="main">
        <?php include_once 'includes/mobile-nav.php'; ?>
        <div class="container">
            <!-- Create Quiz Section -->
            <form class="quiz-form">
                <div class="card">
                    <div class="card-header">
                        <h2>Create New Quiz</h2>
                        <p class="subtitle">Build your quiz by adding questions and setting parameters</p>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="quiz_title">Quiz Title</label>
                            <input type="text" id="quiz_title" name="quiz_title" placeholder="Enter quiz title"
                                class="form-control" required>
                            <br>
                            <label for="quiz_description">Quiz Description</label>
                            <input type="text" id="quiz_description" name="quiz_description"
                                placeholder="Enter Description" class="form-control" required>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="difficulty">Difficulty Level</label>
                                <select id="difficulty" name="difficulty" required class="form-control">
                                    <option value="" disabled selected>Select difficulty</option>
                                    <option value="Easy">Easy (10 questions limit)</option>
                                    <option value="Moderate">Moderate (20 questions limit)</option>
                                    <option value="Hard">Hard (50 questions limit)</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- new card for question -->
                <div class="card">
                    <div class="card-body">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="question_type">Question Type</label>
                                <select id="question_type" name="question_type" onchange="showOptions(this.value)"
                                    required class="form-control">
                                    <option value="">Select question type</option>
                                    <option value="multiple_choice">Multiple Choice</option>
                                    <option value="true_false">True/False</option>
                                    <option value="short_answer">Short Answer</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="question_text">Question Text</label>
                            <textarea id="question_text" name="question_text" rows="3" required
                                placeholder="Enter your question here" class="form-control"></textarea>
                        </div>

                        <!-- Dynamic Options Section -->
                        <div id="options-container" class="options-container">
                            <div id="multipleChoiceOptions" class="question-type-options">
                                <div class="option-input">
                                    <input type="text" name="option_a" id="option_a" placeholder="Option A" required
                                        class="form-control">
                                </div>
                                <div class="option-input">
                                    <input type="text" name="option_b" id="option_b" placeholder="Option B" required
                                        class="form-control">
                                </div>
                                <div class="option-input">
                                    <input type="text" name="option_c" id="option_c" placeholder="Option C" required
                                        class="form-control">
                                </div>
                                <div class="option-input">
                                    <input type="text" name="option_d" id="option_d" placeholder="Option D" required
                                        class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>Correct Answer:</label>
                                    <div class="form-check">
                                        <input type="radio" id="correct_option_a" name="correct_answer" value="A">
                                        <label for="correct_option_a">A</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="radio" id="correct_option_b" name="correct_answer" value="B">
                                        <label for="correct_option_b">B</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="radio" id="correct_option_c" name="correct_answer" value="C">
                                        <label for="correct_option_c">C</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="radio" id="correct_option_d" name="correct_answer" value="D">
                                        <label for="correct_option_d">D</label>
                                    </div>
                                </div>
                            </div>

                            <div id="trueFalseOptions" class="question-type-options" style="display: none;">
                                <div class="form-group">
                                    <label>True/False:</label>
                                    <div class="form-check">
                                        <input type="radio" id="true" name="correct_answer" value="True">
                                        <label for="true">True</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="radio" id="false" name="correct_answer" value="False">
                                        <label for="false">False</label>
                                    </div>
                                </div>
                            </div>

                            <div id="shortAnswerOptions" class="question-type-options" style="display: none;">
                                <div class="form-group">
                                    <label for="short_answer">Short Answer:</label>
                                    <input class="form-control" placeholder="Add here your short answer..."
                                        id="short_answer" name="short_answer">
                                </div>
                            </div>
                        </div>

                        <div class="button-group text-right">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-plus"></i> Add Question
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <script>
            function showOptions(questionType) {
                // Hide all option sections first
                document.getElementById('multipleChoiceOptions').style.display = 'none';
                document.getElementById('trueFalseOptions').style.display = 'none';
                document.getElementById('shortAnswerOptions').style.display = 'none';

                // Show the relevant section based on selection
                switch (questionType) {
                    case 'multiple_choice':
                        document.getElementById('multipleChoiceOptions').style.display = 'block';
                        break;
                    case 'true_false':
                        document.getElementById('trueFalseOptions').style.display = 'block';
                        break;
                    case 'short_answer':
                        document.getElementById('shortAnswerOptions').style.display = 'block';
                        break;
                }
            }
        </script>
    </main>
    <script src="js/main.js"></script>
</body>