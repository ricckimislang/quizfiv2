<?php
session_start();
include('includes/header.php');
include('includes/session.php');
?>

<!-- Links -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="css/loading-screen.css">
<link rel="stylesheet" href="css/student_view_classroom.css">


<!-- Main Section -->

<body>
    <!-- Loading Screen -->
    <?php include("includes/loading-screen.php"); ?>

    <header id="header" class="header fixed-top d-flex align-items-center">
        <?php include 'includes/nav-top.php'; ?>
        <?php include 'includes/sidebar.php'; ?>

        <main id="main" class="main">
            <!--navigation bar sa classroom-->
            <div class="top-bar">
                <div class="menu-nav">
                    <ul>
                        <li><a href="#" class="active" id="room">Classroom</a></li>
                        <li><a href="#" id="quizzes">Quizzes</a></li>
                    </ul>
                </div>
            </div>

            <!-- Content -->
            <div class="content">
                <div class="info-container">

                </div>

                <div class="student-container">
                    <div class="student-title">
                        <h1>Students</h1>
                    </div>

                    <div class="student-list">
                        <div class="student-item">
                            <div class="student-info">
                                <input type="checkbox" class="student-checkbox">
                                <img src="assets/3d-profiles/male-1.jpg" alt="Student Profile" class="student-profile">
                                <span class="student-name">John Doe</span>
                            </div>
                            
                            <!-- menu option -->
                            <button class="kebab-button">
                                <i class="fa fa-ellipsis-v" onclick="toggleDropdown(event)"></i>
                                <div class="dropdown" id="dropdown">
                                    <ul>
                                        <li onclick="deleteItem()">Delete</li>
                                    </ul>
                                </div>
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </main>











        <!-- Scripts -->
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

        <!-- Nav Link Active -->
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                const menuLinks = document.querySelectorAll('.menu-nav a');

                menuLinks.forEach(link => {
                    link.addEventListener('click', function (event) {
                        // Prevent default link behavior for demonstration
                        event.preventDefault();

                        // Remove 'active' class from all links
                        menuLinks.forEach(link => link.classList.remove('active'));

                        // Add 'active' class to the clicked link
                        this.classList.add('active');

                    });
                });
            });
        </script>
        <?php include('js/scripts.php'); ?>
</body>