<!DOCTYPE html>
<html lang="en">

<?php include("includes/header.php"); ?>
<title>Quiz Login</title>
<link rel="stylesheet" href="css/login-style.css">
<link rel="stylesheet" href="css/loading-screen.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link rel="stylesheet" href="css/toastr.css">
</head>

<body>
    <!-- Loading Screen -->
    <?php include("includes/loading-screen.php"); ?>

    <!-- Audio Control -->
    <div class="audio-control-container" style="position: fixed; top: 20px; right: 20px; z-index: 100;">
        <button id="audio-control" class="btn btn-link" aria-label="Toggle background music">
            <i class="fas fa-volume-mute" style="font-size: 24px; color: #007bff;"></i>
        </button>
    </div>

    <audio id="background-audio" loop>
        <source src="assets/img/login-mario.mp3" type="audio/mpeg">
        Your browser does not support the audio element.
    </audio>

    <!-- Login Container -->
    <div class="login-container">
        <div class="login-header">
            <img src="../assets/img/logo-quizfi.png" alt="Quizfi Logo" class="logo">
            <h1 class="login-title">Welcome Back</h1>
            <p class="login-subtitle">Login to continue to Quizfi</p>
        </div>

        <!-- Login Form -->
        <form id="loginForm">
            <div class="input-group">
                <i class="fas fa-user input-icon"></i>
                <input type="text" class="login-input" id="username" name="username" placeholder="Username" required>
            </div>
            <div class="input-group">
                <i class="fas fa-lock input-icon"></i>
                <input type="password" class="login-input" id="password" name="password" placeholder="Password"
                    required>
                <span class="password-toggle">
                    <i class="fas fa-eye-slash" id="togglePassword"></i>
                </span>
            </div>
            <button type="submit" class="login-button">Login</button>
            <div class="form-links">
                <a href="forgot-password.php" class="forgot-password">Forgot Password?</a>
                <a href="signup.php" class="signup-link">Create Account</a>
            </div>
        </form>
    </div>
    <?php include("js/scripts.php"); ?>
    <script src="js/toastr.min.js"></script>
    <script>
        // Initialize DOM elements
        const elements = {
            audio: document.getElementById('background-audio'),
            audioControl: document.getElementById('audio-control'),
            passwordInput: document.getElementById('password'),
            togglePassword: document.getElementById('togglePassword'),
            loginForm: document.getElementById('loginForm'),
            loadingScreen: document.getElementById('loading-screen'),
            loadingDots: document.querySelectorAll('.loading-dots .dot')
        };

        // Audio Control
        let isPlaying = false;

        function updateAudioIcon(playing) {
            const icon = elements.audioControl.querySelector('i');
            icon.className = playing ? 'fas fa-volume-up' : 'fas fa-volume-mute';
        }

        function toggleAudio() {
            if (isPlaying) {
                elements.audio.pause();
            } else {
                elements.audio.play().catch(console.error);
            }
            isPlaying = !isPlaying;
            updateAudioIcon(isPlaying);
            localStorage.setItem('audioPlayed', isPlaying);
        }

        elements.audioControl.addEventListener('click', toggleAudio);

        // Initialize audio state from localStorage
        if (localStorage.getItem('audioPlayed') === 'true') {
            elements.audio.play().then(() => {
                isPlaying = true;
                updateAudioIcon(true);
            }).catch(console.error);
        }

        // Password Toggle
        elements.togglePassword.addEventListener('click', function () {
            const type = elements.passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            elements.passwordInput.setAttribute('type', type);
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });

        // Loading Screen Animation
        elements.loadingDots.forEach((dot, index) => {
            dot.style.animationDelay = `${index * 0.2}s`;
        });

        window.addEventListener('load', () => {
            elements.loadingScreen.style.opacity = '0';
            elements.loadingScreen.style.transition = 'opacity 0.5s ease-out';
            setTimeout(() => {
                elements.loadingScreen.style.display = 'none';
            }, 500);
        });

        // Form Submission
        elements.loginForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const submitButton = this.querySelector('button[type="submit"]');
            submitButton.disabled = true;

            // Form validation
            const username = document.getElementById('username').value.trim();
            const password = document.getElementById('password').value.trim();

            if (!username || !password) {
                showAlert('danger', 'Please fill in all fields');
                submitButton.disabled = false;
                return;
            }

            $.ajax({
                url: "process/login.php",
                type: "POST",
                data: {
                    username,
                    password
                },
                dataType: "json",
                success: function (data) {
                    const redirects = {
                        'student': "user_profile.php",
                        'educator': "../educator/manage_quiz.php",
                        'admin': "../admin/manage_quiz.php"
                    };

                    if (data.userType && redirects[data.userType]) {
                        toastr["success"]("Logged in successfully", "Success")
                        setTimeout(() => {
                            window.location.href = redirects[data.userType];
                        }, 2000);
                    } else {
                        toastr["error"]("Incorrect Username or Password", "Error")
                    }
                },
                error: function (jqXHR, textStatus) {
                    showAlert('danger', 'An error occurred. Please try again.');
                    console.error('Login error:', textStatus);
                },
                complete: function () {
                    submitButton.disabled = false;
                }
            });
        });
    </script>
</body>

</html>