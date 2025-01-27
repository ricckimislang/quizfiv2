<?php
session_start();
include('includes/header.php');
include('includes/session.php');
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="css/loading-screen.css">
<link rel="stylesheet" href="css/classroom.css">

<body>
    <!-- Loading Screen -->
    <?php include("includes/loading-screen.php"); ?>
    <header id="header" class="header fixed-top d-flex align-items-center">
        <?php include 'includes/nav-top.php'; ?>
        <?php include 'includes/sidebar.php'; ?>

        <main id="main" class="main">
            <!-- join input container -->
            <div class="join-classroom-container">
                <h2>Join a Classroom</h2>
                <div class="classroom-code-group">
                    <input type="text" id="classroom-code" class="classroom-code" placeholder="Enter classroom code" />
                    <button class="join-button" id="join-button">Join Classroom</button>
                </div>
            </div>
            <!-- classroom container -->
            <div class="classroom-container">
                <div class="card-grid">
                    <div class="classroom-card">

                    </div>
                    <div class="classroom-card">

                    </div>
                </div>
                <div class="card-grid">
                    <div class="classroom-card">

                    </div>
                    <div class="classroom-card">

                    </div>
                </div>

            </div>
        </main>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const loadingScreen = document.getElementById('loading-screen');

                window.addEventListener('load', () => {
                    loadingScreen.style.opacity = '0';
                    setTimeout(() => {
                        loadingScreen.style.display = 'none';
                    }, 500);
                });
            });
        </script>
        <?php include('js/scripts.php'); ?>
</body>

</html>