<?php
session_start();
include_once 'includes/header.php';
include_once 'includes/session.php';
?>

<!-- links -->
<link rel="stylesheet" href="css/manage_quiz.css">



<body>
    <header id="header" class="header fixed-top d-flex align-items-center">
        <?php include_once 'includes/nav-top.php'; ?>
    </header>
    <!-- Add this right after your <body> tag but before the main content -->
    <?php include_once 'includes/bubble.php'; ?>
    <main id="main" class="main">
        <?php include_once 'includes/mobile-nav.php'; ?>
        <div class="container">
            <!-- Create Quiz Section -->
            <div class="alert alert-primary alert-dismissible fade show" role="alert">
                Please Complete All The Forms To Avoid Errors!😊
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <form id="create-quiz-form" class="quiz-form">
                <input type="hidden" id="user_id" name="user_id" value="<?php echo $user_id; ?>">
                <div class="card">
                    <div class="card-header">
                        <h2>📖Create New Quiz</h2>
                        <p class="subtitle">Build your quiz by adding questions and selecting the question type!</p>
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
            </form>
        </div>
        <div class="button-group">
            <button type="button" id="submit-quiz" class="btn btn-primary"><i class="bx bx-plus"></i></button>
        </div>

        <script>
            // character count #quiz_description
            $(document).ready(function () {
                const MAX_CHARACTERS = 45; // Set your desired character limit
                const descriptionField = $('#quiz_description');
                const charCountDisplay = $('<small class="text-light">').insertAfter(descriptionField);

                descriptionField.on('input', function () {
                    // Get the current input value and its length
                    let currentText = descriptionField.val();
                    let currentLength = currentText.length;

                    // Display the current character count
                    charCountDisplay.text(`${currentLength}/${MAX_CHARACTERS} characters`);

                    // Check if the character limit is reached
                    if (currentLength > MAX_CHARACTERS) {
                        // Trim the text to the max character limit and update the textarea
                        descriptionField.val(currentText.substring(0, MAX_CHARACTERS));
                        charCountDisplay.text(`You have reached the character limit of ${MAX_CHARACTERS} characters.`);
                    }
                });
            });

            //changing difficulty
            document.addEventListener("DOMContentLoaded", function () {
                const alertForm = document.querySelector(".alert-primary");
                setTimeout(function () {
                    alertForm.style.display = "none";
                }, 10000);

                const difficultySelect = document.getElementById("difficulty");
                const questionContainer = document.createElement("div");
                questionContainer.id = "question-container";
                document.querySelector(".quiz-form").appendChild(questionContainer);

                difficultySelect.addEventListener("change", function () {
                    const difficulty = difficultySelect.value;
                    let questionCount = 0;

                    // Define question count based on difficulty
                    if (difficulty === "Easy") questionCount = 10;
                    else if (difficulty === "Moderate") questionCount = 20;
                    else if (difficulty === "Hard") questionCount = 50;

                    generateQuestions(questionCount);
                });

                function generateQuestions(count) {
                    questionContainer.innerHTML = ""; // Clear previous questions

                    for (let i = 1; i <= count; i++) {
                        let questionHTML = `
                <div class="card">
                    <div class="card-body">
                        <h2>Question ${i}</h2>
                        <div class="form-group">
                            <label for="question_text_${i}">Question Text</label>
                            <textarea id="question_text_${i}" name="question_text_${i}" rows="3" required
                                placeholder="Enter your question here" class="form-control"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="question_type_${i}">Question Type</label>
                            <select id="question_type_${i}" name="question_type_${i}" onchange="showOptions(this.value, ${i})"
                                required class="form-control">
                                <option value="">Select question type</option>
                                <option value="Multiple Choice">Multiple Choice</option>
                                <option value="True/False">True/False</option>
                                <option value="Short Answer">Short Answer</option>
                            </select>
                        </div>

                        <div id="options-container-${i}" class="options-container"></div>
                    </div>
                </div>
            `;

                        questionContainer.insertAdjacentHTML("beforeend", questionHTML);
                    }
                }
            });

            // Function to show options based on selected question type
            function showOptions(questionType, index) {
                const container = document.getElementById(`options-container-${index}`);
                container.innerHTML = ""; // Clear previous options

                if (questionType === "Multiple Choice") {
                    container.innerHTML = `
            <div class="form-group">
                <label>Options:</label>
                <input type="text" name="option_a_${index}" placeholder="Option A" required class="form-control">
                <input type="text" name="option_b_${index}" placeholder="Option B" required class="form-control">
                <input type="text" name="option_c_${index}" placeholder="Option C" required class="form-control">
                <input type="text" name="option_d_${index}" placeholder="Option D" required class="form-control">
                <label>Correct Answer:</label>
                <select name="correct_answer_${index}" class="form-control">
                    <option value="A">A</option>
                    <option value="B">B</option>
                    <option value="C">C</option>
                    <option value="D">D</option>
                </select>
            </div>`;
                } else if (questionType === "True/False") {
                    container.innerHTML = `
            <div class="form-group">
                <label>True/False:</label>
                <div class="form-check">
                    <input type="radio" id="true_${index}" name="correct_answer_${index}" value="True">
                    <label for="true_${index}" class="radio-label">True</label>

                    <input type="radio" id="false_${index}" name="correct_answer_${index}" value="False">
                    <label for="false_${index}" class="radio-label">False</label>
                </div>
            </div>`;
                } else if (questionType === "Short Answer") {
                    container.innerHTML = `
            <div class="form-group">
                <label>Short Answer:</label>
                <input type="text" name="short_answer_${index}" class="form-control" placeholder="Enter answer">
            </div>`;
                }
            }

        </script>
        <script>
            $(document).ready(function () {
                $("#submit-quiz").click(function () {
                    $("#create-quiz-form").submit(); // Trigger form submission
                });

                $("#create-quiz-form").submit(function (event) {
                    event.preventDefault();

                    if (!validateForm()) return;  // Stops AJAX if validation fails

                    // If all valid, submit form via AJAX
                    const formData = $(this).serialize();
                    $.ajax({
                        url: 'process/add_quiz.php',
                        type: 'POST',
                        data: formData,
                        beforeSend: function () {
                            Swal.fire({
                                title: 'Saving Quiz',
                                text: 'Please wait...',
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });
                        },
                        success: function (response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Quiz Saved',
                                text: 'Your quiz has been successfully saved!'
                            }).then(() => {
                                location.reload();
                            });
                        },
                        error: function (xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Failed to save quiz. Please try again.'
                            });
                        }
                    });
                });

                // Function to display warning messages
                function showWarning(selector, message) {
                    $(selector).addClass("is-invalid").after(`<small class="warning-message text-danger">${message}</small>`);
                }


                function validateForm() {
                    $(".warning-message").remove();
                    $(".is-invalid").removeClass("is-invalid");

                    let isValid = true;

                    // Validate quiz title
                    if (!$("#quiz_title").val().trim()) {
                        isValid = false;
                        showWarning("#quiz_title", "Quiz title is required.");
                    }

                    // Validate quiz description
                    if (!$("#quiz_description").val().trim()) {
                        isValid = false;
                        showWarning("#quiz_description", "Quiz description is required.");
                    }

                    // Validate difficulty selection
                    if (!$("#difficulty").val()) {
                        isValid = false;
                        showWarning("#difficulty", "Please select a difficulty level.");
                    }

                    // Validate questions
                    const questionCount = $("#question-container .card").length;

                    for (let i = 1; i <= questionCount; i++) {
                        const questionText = $(`#question_text_${i}`).val().trim();
                        const questionType = $(`#question_type_${i}`).val();

                        if (!questionText) {
                            isValid = false;
                            showWarning(`#question_text_${i}`, "Question text is required.");
                        }

                        if (!questionType) {
                            isValid = false;
                            showWarning(`#question_type_${i}`, "Please select a question type.");
                        }

                        if (questionType === "Multiple Choice") {
                            const options = [
                                { id: `option_a_${i}`, name: "Option A" },
                                { id: `option_b_${i}`, name: "Option B" },
                                { id: `option_c_${i}`, name: "Option C" },
                                { id: `option_d_${i}`, name: "Option D" }
                            ];

                            let filledOptions = 0;
                            options.forEach(opt => {
                                const inputVal = $(`input[name="${opt.id}"]`).val().trim();
                                if (inputVal) {
                                    filledOptions++;
                                } else {
                                    showWarning(`input[name="${opt.id}"]`, `${opt.name} is required.`);
                                }
                            });

                            // Require at least two options to be filled
                            if (filledOptions < 4) {
                                isValid = false;
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Validation Error',
                                    text: 'At least 4 options must be provided for multiple-choice questions.'
                                });
                            }

                            // Ensure at least one correct answer is selected
                            if (!$(`select[name="correct_answer_${i}"]`).val()) {
                                isValid = false;
                                showWarning(`select[name="correct_answer_${i}"]`, "Please select a correct answer.");
                            }
                        }

                        else if (questionType === "True/False") {
                            if (!$(`input[name="correct_answer_${i}"]:checked`).val()) {
                                isValid = false;
                                showWarning(`input[name="correct_answer_${i}"]`, "Please select True or False.");
                            }
                        }

                        else if (questionType === "Short Answer") {
                            if (!$(`input[name="short_answer_${i}"]`).val().trim()) {
                                isValid = false;
                                showWarning(`input[name="short_answer_${i}"]`, "Short answer is required.");
                            }
                        }
                    }

                    // Prevent form submission if validation fails
                    if (!isValid) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Validation Error',
                            text: 'Please complete all required fields before submitting.'
                        });
                        return false;  // Prevents form from proceeding
                    }

                    return true; // Allows form submission if everything is valid
                }

            });

        </script>
    </main>
    <script src="js/main.js"></script>
</body>