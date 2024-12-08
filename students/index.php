<!DOCTYPE html>
<html lang="en">

<?php include("includes/header.php"); ?>
<title>Quiz Login</title>
<link rel="stylesheet" href="css/login-style.css">
<link rel="stylesheet" href="css/loading-screen.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <div id="loading-screen">
        <img src="../assets/img/logo-quizfi.png" alt="Loading">
    </div>
    <audio id="background-audio" loop muted>
        <source src="assets/img/login-mario.mp3" type="audio/mpeg">
        Your browser does not support the audio element.
    </audio>
    <div class="login-container">
        <div class="login-header">
            <img src="../assets/img/logo-quizfi.png" alt="Quizfi Logo" class="logo">
            <h1 class="login-title">Welcome Back</h1>
            <p class="login-subtitle">Login to continue to Quizfi</p>
            <!-- login notification -->
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-1"></i>
                <span class="alert-text">You are Logged In!</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>

            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-octagon me-1"></i>
                <span class="alert-text">You have entered an incorrect username or password!</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <!-- end of notification -->
        </div>
        <form id="loginForm">
            <div class="input-group">
                <i class="fas fa-user input-icon"></i>
                <input type="text" class="login-input" id="username" name="username" placeholder="Username" required>
            </div>
            <div class="input-group">
                <i class="fas fa-lock input-icon"></i>
                <input type="password" class="login-input" id="password" name="password" placeholder="Password" required>
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

    <script>
        // Password toggle visibility
        const passwordInput = document.getElementById('password');
        const togglePassword = document.getElementById('togglePassword');

        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);

            // Toggle eye icon
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });
    </script>

    <script>
        const audio = document.getElementById('background-audio');

        // Function to play audio
        function playAudio() {
            audio.muted = false; // Unmute the audio
            audio.play().catch(function(error) {
                console.log("Audio playback failed: ", error);
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

        $("#loginForm").submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: "process/login.php",
                type: "POST",
                data: {
                    username: $("#username").val(),
                    password: $("#password").val()
                },
                dataType: "json", // Expecting JSON response
                success: function(data) {
                    if (data.userType === 'student') {
                        $('.alert-danger').hide();
                        $('.alert-success').find('.alert-text').text('Logged In successfully!');
                        $('.alert-success').show();
                        setTimeout(function() {
                            window.location.href = "user_profile.php";
                        }, 2000);

                    } else if (data.userType === 'educator') {
                        $('.alert-danger').hide();
                        $('.alert-success').find('.alert-text').text('Logged In successfully!');
                        $('.alert-success').show();
                        setTimeout(function() {
                            window.location.href = "../educator/manage_quiz.php";
                        }, 2000);

                    } else if (data.userType === 'admin') {
                        $('.alert-danger').hide();
                        $('.alert-success').find('.alert-text').text('Logged In successfully!');
                        $('.alert-success').show();
                        setTimeout(function() {
                            window.location.href = "../admin/manage_quiz.php";
                        }, 2000);

                    } else {
                        $('.alert-success').hide();
                        $('.alert-danger').find('.alert-text').text('Error: Incorrect username or password.');
                        $('.alert-danger').show();
                        // Enable the button again if there is an error
                        $("button[type='submit']").attr("disabled", false);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    $('.alert-success').hide();
                    $('.alert-danger').find('.alert-text').text('An error occurred: ' + textStatus);
                    $('.alert-danger').show();
                }
            });
        });

        // Ensure alerts are hidden on page load
        $(document).ready(function() {
            $('.alert-success, .alert-danger').hide();
        });
    </script>
</body>

</html>
