<!DOCTYPE html>
<html lang="en">

<?php include("includes/header.php"); ?>
<title>Quiz Registration</title>
<link rel="stylesheet" href="css/login-style.css">
<link rel="stylesheet" href="css/loading-screen.css">
<link href='assets/vendor/boxicons/css/boxicons.min.css' rel='stylesheet'>
<link rel="stylesheet" href="css/toastr.css">
</head>

<body style="overflow-y: auto !important;">
    <!-- Loading Screen -->
    <?php include("includes/loading-screen.php"); ?>

    <!-- Audio Control -->
    <div class="audio-control-container" style="position: fixed; top: 20px; right: 20px; z-index: 100;">
        <button id="audio-control" class="btn btn-link" aria-label="Toggle background music">
            <i class='bx bx-volume-mute' style="font-size: 24px; color: #007bff;"></i>
        </button>
    </div>

    <audio id="background-audio" loop>
        <source src="assets/img/login-bg-music.mp3" type="audio/mpeg">
        Your browser does not support the audio element.
    </audio>

    <!-- Registration Container -->
    <div class="login-container" style="top: 150px;">
        <div class="login-header">
            <img src="../assets/img/logo-quizfi.png" alt="Quizfi Logo" class="logo">
            <h1 class="login-title">Create Account</h1>
            <p class="login-subtitle">Register to continue to Quizfi</p>
        </div>

        <!-- Registration Form -->
        <form id="registerForm" enctype="multipart/form-data">
            <div class="input-group">
                <i class='bx bx-envelope input-icon'></i>
                <input type="email" class="login-input" id="email" name="email" placeholder="Email" required>
            </div>
            <div class="input-group">
                <i class='bx bx-user input-icon'></i>
                <input type="text" class="login-input" id="firstName" name="firstName" placeholder="First Name" required>
            </div>
            <div class="input-group">
                <i class='bx bx-user input-icon'></i>
                <input type="text" class="login-input" id="lastName" name="lastName" placeholder="Last Name" required>
            </div>
            <div class="input-group">
                <i class='bx bx-calendar input-icon'></i>
                <select class="login-input" id="year" name="year" required>
                    <option value="" disabled selected>Select Year</option>
                    <option value="1">1st Year</option>
                    <option value="2">2nd Year</option>
                    <option value="3">3rd Year</option>
                    <option value="4">4th Year</option>
                </select>
            </div>
            <div class="input-group">
                <i class='bx bx-building input-icon'></i>
                <input type="text" class="login-input" id="department" name="department" placeholder="Department" required>
            </div>

            <label for="idPhoto" class="input-label">ID Photo</label>

            <div class="input-group">
                <p style="font-size: 12px; color: #6c757d;">Please use your school ID for validation.</p>
                <i class='bx bx-id-card input-icon'></i>
                <input type="file" class="login-input" id="idPhoto" name="idPhoto" accept="image/*" required>
            </div>

            <button type="submit" class="login-button">Register</button>

            <div class="form-links">
                <a href="index.php" class="forgot-password">Already have an account? Login</a>
            </div>
        </form>
    </div>
    <?php include("js/scripts.php"); ?>
    <script src="js/toastr.min.js"></script>
    <script>
        document.getElementById('registerForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const submitButton = this.querySelector('button[type="submit"]');
            submitButton.disabled = true;

            // Collect form data
            const formData = new FormData(this);

            try {
                const response = await fetch("process/register.php", {
                    method: "POST",
                    body: formData,
                });

                const data = await response.json();

                if (data.success) {
                    toastr.success("Registered successfully", "Success");
                    setTimeout(() => window.location.href = "index.php", 2000);
                } else {
                    toastr.error(data.message || "An error occurred. Please try again.", "Error");
                }
            } catch (error) {
                toastr.error("An unexpected error occurred.", "Error");
                console.error("Registration error:", error);
            } finally {
                submitButton.disabled = false;
            }
        });
    </script>

</body>

</html>