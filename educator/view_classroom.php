<?php
session_start();
include_once 'includes/header.php';
include_once 'includes/session.php';
?>

<link rel="stylesheet" href="css/view_classroom.css">

<body>
    <header id="header" class="header">
        <?php include_once 'includes/nav-top.php' ?>
    </header>
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
                <div class="info-container" style="background-image: url('<?php echo ($roomBg) ? $roomBg : 'assets/classroom-bg/general-bg.png'; ?>');">
                    <div class="room-title">
                        <h1><?= htmlspecialchars($roomName) ?></h1>
                        <div class="subtitle">
                            <span><?= htmlspecialchars($roomCode) ?></span>
                        </div>
                    </div>
                    <button type="button" id="change_background_wallpaper" class="btn btn-primary change-background">
                        <i class="bx bx-edit"></i>
                        Customize
                    </button>
                </div>

                <div class="student-container">
                    <div class="student-title">
                        <h1>Students</h1>
                    </div>
                    <div class="button-group">
                        <div class="form-group">
                            <input type="checkbox" id="select-all-checkbox">
                            <label for="select-all-checkbox">Select All</label>
                        </div>
                        <button class="btn btn-primary" id="remove-button" style="display: none;">Remove</button>
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

                            <div class="student-item" data-student-id="<?= htmlspecialchars($listrow['student_id']); ?>">
                                <div class="student-info">
                                    <input type="checkbox" class="student-checkbox">
                                    <img src="<?php echo $listrow ? ($listrow['profile_path'] ?? 'assets/avatars/no-profile.jpg') : 'assets/avatars/no-profile.jpg'; ?>"
                                        alt="Student Profile" class="student-profile">
                                    <span class="student-name"><?= htmlspecialchars($listrow['firstname'] . ' ' . $listrow['lastname']); ?></span>
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
                                <i class="bx bx-clipboard quiz-icon"></i>
                                <span class="quiz-name"><?php echo htmlspecialchars($quizRow['quiz_title']); ?></span>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>

        </div>

        <!-- modals -->
        <?php include_once 'modal/change_wallpaper.php' ?>
    </main>
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

            const selectAllCheckbox = document.getElementById('select-all-checkbox');
            const removeButton = document.getElementById('remove-button');
            const studentCheckboxes = document.querySelectorAll('.student-checkbox');

            // Select All functionality
            selectAllCheckbox.addEventListener('change', function() {
                studentCheckboxes.forEach(checkbox => {
                    checkbox.checked = selectAllCheckbox.checked;
                });
                toggleRemoveButton();
            });

            // Toggle Remove Button visibility
            studentCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', toggleRemoveButton);
            });

            // Remove functionality with AJAX and SweetAlert
            removeButton.addEventListener('click', function() {
                const selectedStudents = [];
                const studentItems = [];

                // Collect student IDs to remove
                studentCheckboxes.forEach(checkbox => {
                    if (checkbox.checked) {
                        const studentItem = checkbox.closest('.student-item');
                        if (studentItem) {
                            // Find the student ID (we'll need to add this attribute to the HTML)
                            const studentId = studentItem.getAttribute('data-student-id');
                            if (studentId) {
                                selectedStudents.push(studentId);
                                studentItems.push(studentItem);
                            }
                        }
                    }
                });

                if (selectedStudents.length > 0) {
                    // Use SweetAlert for confirmation
                    Swal.fire({
                        title: 'Remove Students',
                        text: `Are you sure you want to remove ${selectedStudents.length} student(s) from this classroom?`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, remove them!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Get classroom ID from URL
                            const urlParams = new URLSearchParams(window.location.search);
                            const classroomId = urlParams.get('roomId');

                            // Perform AJAX request
                            $.ajax({
                                url: 'process/remove_student_classroom.php',
                                type: 'POST',
                                data: {
                                    classroom_id: classroomId,
                                    student_ids: selectedStudents
                                },
                                success: function(response) {
                                    try {
                                        const result = JSON.parse(response);

                                        if (result.status === 'success') {
                                            // Remove the student items from the DOM
                                            studentItems.forEach(item => {
                                                item.remove();
                                            });

                                            // Show success message
                                            Swal.fire(
                                                'Removed!',
                                                'The selected students have been removed from the classroom.',
                                                'success'
                                            );

                                            // Reset checkboxes and button
                                            selectAllCheckbox.checked = false;
                                            toggleRemoveButton();
                                        } else {
                                            // Show error message
                                            Swal.fire(
                                                'Error!',
                                                result.message || 'There was a problem removing the students.',
                                                'error'
                                            );
                                        }
                                    } catch (e) {
                                        // Handle parsing error
                                        Swal.fire(
                                            'Error!',
                                            'Invalid response from the server.',
                                            'error'
                                        );
                                    }
                                },
                                error: function() {
                                    // Handle AJAX error
                                    Swal.fire(
                                        'Error!',
                                        'There was a problem connecting to the server.',
                                        'error'
                                    );
                                }
                            });
                        }
                    });
                }
            });

            function toggleRemoveButton() {
                const anyChecked = Array.from(studentCheckboxes).some(checkbox => checkbox.checked);
                removeButton.style.display = anyChecked ? 'inline-block' : 'none';
            }

            // change change_background_wallpaper
            const changeBackgroundWallpaper = document.getElementById('change_background_wallpaper');

            $(changeBackgroundWallpaper).click(function() {
                // Show background image selection dialog
                $('#changeWallpaperModal').modal('show');
            });

            $('#ChangeWallpaperForm').submit(async function(event) {
                event.preventDefault();
                const selectedAvatar = document.querySelector('input[name="selected_avatar"]:checked');

                if (!selectedAvatar) {
                    toastr["warning"]("Please select a wallpaper");
                    return;
                }

                const formData = new FormData(this);
                formData.append('selected_avatar', selectedAvatar.value);
                formData.append('classroom_id', document.querySelector('input[name="classroom_id"]').value);

                try {


                    const response = await fetch('process/update_room_bg.php', {
                        method: 'POST',
                        body: formData
                    });

                    const data = await response.json();

                    if (data.status === 'success') {
                        toastr["success"]("Wallpaper updated successfully!");
                        setTimeout(() => location.reload(), 1500);
                        bootstrap.Modal.getInstance(document.getElementById('changeWallpaperModal')).hide();
                    } else {
                        toastr["error"](data.message || "Error updating Wallpaper");
                    }
                } catch (error) {
                    console.error('Error updating Wallpaper:', error);
                    toastr["error"]("Error updating Wallpaper");
                }

            });
        });
    </script>
    <script src="js/main.js"></script>

</body>