<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Login</title>
    <link rel="stylesheet" href="css/login-style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <div class="login-container">
        <div class="login-header">
            <img src="../assets/img/logo-quizfi.png" alt="Quizfi Logo" class="logo">
            <h1>Welcome Back</h1>
            <p>Login to continue to Quizfi</p>
        </div>
        <form action="login.php" method="post">
            <div class="input-group">
                <i class="fas fa-user input-icon"></i>
                <input type="text" class="login-input" id="username" name="username" placeholder="Username" required>
            </div>
            <div class="input-group">
                <i class="fas fa-lock input-icon"></i>
                <input type="password" class="login-input" id="password" name="password" placeholder="Password"
                    maxlength="15" minlength="8" required>
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
</body>

</html>