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
                <form id="joinClassroom">
                    <div class="classroom-code-group">
                        <input type="hidden" id="user_id" name="user_id" value="<?php echo $user_id; ?>">
                        <input type="text" id="classroom-code" class="classroom-code" placeholder="Enter classroom code"
                            required />
                        <button type="submit" class="join-button">Join Classroom</button>
                    </div>
                </form>
            </div>
            <!-- classroom container -->
            <div class="classroom-container">
                <div class="classroom-card-grid">
                    <div class="classroom-card">

                        <div class="classroom-card-header">
                            <div class="menu">
                                <div class="dots1" onclick="toggleDropdown(event)">
                                    <div class="dot1"></div>
                                    <div class="dot1"></div>
                                    <div class="dot1"></div>
                                </div>
                                <div class="dropdown" id="dropdown">
                                    <ul>
                                        <li onclick="deleteItem()">Delete</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="classroom-card-title">
                                <h3>PE111</h3>
                                <div class="subtitle">
                                    <span>12312312315</span>
                                    <span>Buante</span>
                                </div>
                            </div>
                            <div class="classroom-card-profile">
                                <img src="assets/3d-profiles/female-teacher-1.jpg" alt="Creator-Profile">
                            </div>
                        </div>
                        <div class="classroom-card-body">
                            <div class="text-body">
                                <p>Classroom Description</p>
                            </div>
                        </div>
                    </div>

                    <div class="classroom-card">
                        <div class="classroom-card-header">
                            <div class="menu">
                                <div class="dots1" onclick="toggleDropdown(event)">
                                    <div class="dot1"></div>
                                    <div class="dot1"></div>
                                    <div class="dot1"></div>
                                </div>
                                <div class="dropdown" id="dropdown">
                                    <ul>
                                        <li onclick="deleteItem()">Delete</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="classroom-card-title">
                                <h3>IT ELEC II</h3>
                                <div class="subtitle">
                                    <span>ABCCDDAS</span>
                                    <span>Mislang</span>
                                </div>
                            </div>
                            <div class="classroom-card-profile">
                                <img src="assets/3d-profiles/male-teacher-2.jpg" alt="classroom-profile">
                            </div>
                        </div>
                        <div class="classroom-card-body">
                            <div class="text-body">
                                <p>Classroom Description</p>
                            </div>
                        </div>
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

            //join submit button
            $(document).ready(function () {
                $('#joinClassroom').on('submit', function (e) {
                    e.preventDefault();
                    var user_id = $('#user_id').val();
                    var classroomCode = $('#classroom-code').val();
                    $.ajax({
                        type: 'POST',
                        url: 'process/join-classroom.php',
                        data: {
                            user_id: user_id,
                            classroomCode: classroomCode
                        },
                        success: function (response) {
                            // Handle response from the server
                            if (response.status === 'success') {
                                alert("Classroom joined successfully!");
                            } else {
                                alert("Failed to join Class!");
                            }
                        },
                        error: function (xhr, status, error) {
                            alert("An error Occured" + error);
                        }
                    })
                });
            });
        </script>
        <?php include('js/scripts.php'); ?>
</body>

</html>