<?php
session_start();
include('includes/header.php');
include('includes/session.php');
?>

<!-- Links -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="css/loading-screen.css">
<link rel="stylesheet" href="css/student_view_classroom.css">

<?php
$classroom_id = $_GET['roomId'];
?>

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

                <?php
                $classroom_id = isset($_GET['roomId']) ? (int) $_GET['roomId'] : 0;
                //get room name
                $rQuery = $conn->prepare("SELECT profile_path, classroom_name, classroom_code FROM classroom WHERE classroom_id = ?");
                $rQuery->bind_param("i", $classroom_id);
                $rQuery->execute();
                $rResult = $rQuery->get_result();
                $rRow = $rResult->fetch_assoc();
                $roomName = $rRow['classroom_name'];
                $roomCode = $rRow['classroom_code'];
                $roomBg = $rRow['profile_path'];
                ?>

                <!-- classroom content -->
                <div class="classroom-content">
                    <div class="info-container" style="background-image: url('<?php echo ($roomBg) ? $roomBg : 'assets/classroom-bg/general-bg.png'; ?>')">
                        <div class="room-title">
                            <h1><?= htmlspecialchars($roomName) ?></h1>
                            <div class="subtitle">
                                <span><?= htmlspecialchars($roomCode) ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="student-container">
                        <div class="student-title">
                            <h1>Students</h1>
                        </div>

                        <?php
                        $classroom_id = isset($_GET['roomId']) ? (int) $_GET['roomId'] : 0;
                        // Get student list
                        $listQuery = $conn->prepare("
                                            SELECT sc.student_id AS student_id, s.profile_path AS profile_path, s.firstname AS firstname, s.lastname AS lastname 
                                            FROM student_classroom sc
                                            LEFT JOIN students s ON sc.student_id = s.student_id 
                                            WHERE sc.classroom_id = ?
                                        ");
                        $listQuery->bind_param("i", $classroom_id);
                        $listQuery->execute();
                        $listResult = $listQuery->get_result();
                        ?>

                        <div class="student-list">

                            <?php while ($listrow = $listResult->fetch_assoc()): ?>

                                <div class="student-item">
                                    <div class="student-info">
                                        <img src="<?php echo $listrow ? ($listrow['profile_path'] ?? 'assets/avatars/no-profile.jpg') : 'assets/avatars/no-profile.jpg'; ?>"
                                            alt="Student Profile" class="student-profile">
                                        <span
                                            class="student-name"><?= htmlspecialchars($listrow['firstname'] . ' ' . $listrow['lastname']); ?></span>
                                    </div>
                                </div>

                            <?php endwhile; ?>

                        </div>
                    </div>
                </div>

                <div class="quiz-content">
                    <div class="quiz-container">
                        <div class="quiz-title">
                            <h1>Quizzes</h1>
                        </div>
                    </div>
                    <div class="quiz-list">

                        <?php
                        //get quizzes assigned to classroom
                        $classroom_id = isset($_GET['roomId']) ? (int) $_GET['roomId'] : 0;
                        $quizQuery = $conn->prepare("SELECT * FROM quiz WHERE classroom_id = ?");
                        $quizQuery->bind_param("i", $classroom_id);
                        $quizQuery->execute();
                        $quizResult = $quizQuery->get_result();
                        ?>

                        <?php while ($quizRow = $quizResult->fetch_assoc()): ?>

                            <div class="quiz-item">
                                <div class="quiz-info">
                                    <i class="fa-solid fa-clipboard-list quiz-icon"></i>
                                    <span class="quiz-name"><?php echo htmlspecialchars($quizRow['quiz_title']); ?></span>
                                </div>

                                <!-- play link -->
                                <a href="javascript: void(0);"
                                    onclick="confirmQuiz('<?php echo htmlspecialchars($quizRow['quiz_title']) ?>', '<?php echo htmlspecialchars($quizRow['quiz_id']); ?>', '<?php echo $classroom_id; ?>')"
                                    class="btn play-button"><i class="fa fa-play"></i>
                                </a>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>

            </div>
        </main>


        <!-- Scripts -->

        <!-- Nav Link Active -->
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                // Select the navigation links
                const menuLinks = document.querySelectorAll('.menu-nav a');

                // Select the two content sections
                const classroomDiv = document.querySelector('.classroom-content');
                const quizContentDiv = document.querySelector('.quiz-content');

                // Initially, ensure that only the classroom content is active
                classroomDiv.classList.add('active');
                quizContentDiv.classList.remove('active');

                // Add click event listeners for each menu link
                menuLinks.forEach(link => {
                    link.addEventListener('click', function(event) {
                        event.preventDefault();

                        // Remove 'active' class from all menu links
                        menuLinks.forEach(link => link.classList.remove('active'));
                        // Add 'active' to the clicked link
                        this.classList.add('active');

                        // Toggle the active class on the content sections based on the clicked link's id
                        if (this.id === 'room') {
                            classroomDiv.classList.add('active');
                            quizContentDiv.classList.remove('active');
                        } else if (this.id === 'quizzes') {
                            classroomDiv.classList.remove('active');
                            quizContentDiv.classList.add('active');
                        }
                    });
                });


            });

            function confirmQuiz(quizTitle, quizId, classroomId) {
                Swal.fire({
                    title: quizTitle,
                    text: "Are you sure you want to take this quiz ?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, start quiz!',
                    cancelButtonText: 'No, cancel!',
                    confirmButtonColor: '#2ecc71',
                    cancelButtonColor: '#d33',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Redirect or perform action to take the quiz
                        window.location.href = "quiz_time.php?quiz_id=" + quizId + "&roomId=" + classroomId;
                        // Optionally, add the redirection or quiz start logic here
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        Swal.fire(
                            'Cancelled',
                            'Action Cancelled',
                            'error'
                        );
                    }
                });
            }
        </script>
        <?php include('js/scripts.php'); ?>
</body>