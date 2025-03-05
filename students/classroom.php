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

                    <?php
                    // Pagination logic
                    $limit = 5; // Limit to 3 quizzes per page
                    $page = isset($_GET['page']) ? (int) $_GET['page'] : 1; // Get current page or default to 1
                    $offset = ($page - 1) * $limit; // Calculate offset for SQL query

                    // Fetch total number of classroom to calculate total pages
                    $totalClassrooms = "SELECT COUNT(*) as total FROM student_classroom WHERE student_id = ?";
                    $totalStmt = $conn->prepare($totalClassrooms);
                    if ($totalStmt === false) {
                        die('Error preparing the SQL statement: ' . $conn->error);
                    }
                    $totalStmt->bind_param("i", $studentId);
                    $totalStmt->execute();
                    $totalResult = $totalStmt->get_result();
                    $totalRow = $totalResult->fetch_assoc();
                    $totalrooms = $totalRow['total'];
                    $totalPages = ceil($totalrooms / $limit); // Calculate total pages

                    // Fetch quizzes for the current page based on the selected difficulty with LIMIT and OFFSET
                    $ClassroomQuery = "SELECT * FROM student_classroom LEFT JOIN classroom ON classroom.classroom_id = student_classroom.classroom_id WHERE student_id = ? LIMIT ? OFFSET ?";
                    $Cstmt = $conn->prepare($ClassroomQuery);
                    if ($Cstmt === false) {
                        die('Error preparing the SQL statement: ' . $conn->error);
                    }

                    $Cstmt->bind_param('iii', $studentId, $limit, $offset);
                    $Cstmt->execute();
                    $Cresult = $Cstmt->get_result();



                    $counter = 0; // Counter to track classroom per row


                    if ($Cresult->num_rows > 0):
                        while ($class = $Cresult->fetch_assoc()) {

                            $mainRoomId = $conn->prepare("SELECT profile_path FROM classroom WHERE classroom_id = ?");
                            $mainRoomId->bind_param("i", $class['classroom_id']);
                            $mainRoomId->execute();
                            $mainRoomIdResult = $mainRoomId->get_result();
                            $mainRoom = $mainRoomIdResult->fetch_assoc();
                            $imagePath = $mainRoom['profile_path'];

                            $educator_user_id = $class['user_id'];
                            $RoomCreator = "SELECT firstname, lastname FROM educators WHERE user_id = ?";
                            $RoomCreator = $conn->prepare($RoomCreator);
                            $RoomCreator->bind_param("i", $educator_user_id);
                            $RoomCreator->execute();
                            $RoomCreatorResult = $RoomCreator->get_result();
                            $creator = $RoomCreatorResult->fetch_assoc();
                            $creator_name = $creator['firstname'] . " " . $creator['lastname'];

                            // Start a new row after every 3 classroom
                            if ($counter % 3 == 0 && $counter > 0) {
                                echo '</div><div class="classroom-card-grid">'; // Close current row and start a new one
                            }
                            // Display classroom card
                    ?>
                            <div class="classroom-card" data-room-id="<?php echo $class['classroom_id']; ?>">
                                <div class="classroom-card-header"
                                    style="<?php echo !empty($imagePath) ? "background-image: url('$imagePath');" : 'background: var(--card-header-color)' ?>">
                                    <div class="menu">
                                        <div class="dots1"
                                            onclick="toggleDropdown(event, <?php echo $class['classroom_id']; ?>)">
                                            <div class="dot1"></div>
                                            <div class="dot1"></div>
                                            <div class="dot1"></div>
                                        </div>
                                        <div class="dropdown" id="dropdown-<?php echo $class['classroom_id']; ?>">
                                            <ul>
                                                <li onclick="deleteItem(<?php echo $class['classroom_id']; ?>, <?= $studentId; ?>)">Leave</li>
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="classroom-card-title">
                                        <h3><?= $class['classroom_name']; ?></h3>
                                        <div class="subtitle">
                                            <span><?= $class['classroom_code']; ?></span>
                                            <span><?= $creator_name; ?></span>
                                        </div>
                                    </div>
                                    <div class="classroom-card-profile">
                                        <img src="assets/3d-profiles/female-teacher-1.jpg" alt="Creator-Profile">
                                    </div>
                                </div>
                                <div class="classroom-card-body"
                                    onclick="confirmRoom('<?php echo htmlspecialchars($class['classroom_name']); ?>', <?php echo htmlspecialchars($class['classroom_id']); ?>)">
                                    <div class="text-body">
                                        <p><?= $class['classroom_desc']; ?></p>
                                    </div>
                                </div>
                            </div>


                    <?php
                            $counter++; // Increment counter after displaying a quiz
                        }
                    else:
                        echo '<div class="room-container no-room">
                    <img src="assets/img/no-room.svg" alt="No Room Joined">
                    <p>You have not joined any classroom.</p>
                    </div>';

                    endif;

                    // Close the last row if there are quizzes
                    if ($counter > 0) {
                        echo '</div>';
                    }

                    // Pagination controls
                    if ($totalPages > 1) {
                        echo '<div class="pagination">';

                        // Previous page link
                        if ($page > 1) {
                            echo '<a href="?page=' . ($page - 1) . '">&laquo; Previous</a>';
                        }

                        // Display page numbers
                        for ($i = 1; $i <= $totalPages; $i++) {
                            if ($i == $page) {
                                echo '<span class="current-page">' . $i . '</span>';
                            } else {
                                echo '<a href="?page=' . $i . '">' . $i . '</a>';
                            }
                        }

                        // Next page link
                        if ($page < $totalPages) {
                            echo '<a href="?page=' . ($page + 1) . '">Next &raquo;</a>';
                        }

                        echo '</div>';
                    }
                    ?>

                </div>
            </div>
        </main>

        <script>
            //join submit button
            $(document).ready(function() {
                $('#joinClassroom').on('submit', function(e) {
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
                        success: function(response) {
                            // Handle response from the server
                            if (response.status === 'success') {
                                toastr["success"]("Joined", "Success")
                                setTimeout(() => {
                                    window.location.href = "student_view_classroom.php?roomId=" + response.classroomId;
                                }, 1000);
                            } else {
                                alert("Failed to join Class!");
                            }
                        },
                        error: function(xhr, status, error) {
                            alert("An error Occured" + error);
                        }
                    })
                });
            });
        </script>
        <!-- =======Join to Click========= -->

        <script>
            // Function to show SweetAlert when the card is clicked
            function confirmRoom(classroomName, classroomId) {
                Swal.fire({
                    title: 'Enter Classroom?',
                    text: classroomName,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Enter!',
                    cancelButtonText: 'No, cancel!',
                    confirmButtonColor: '#2ecc71',
                    cancelButtonColor: '#d33',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Redirect or perform action to take the quiz
                        $.jGrowl("Entering the classroom...", {
                            sticky: false,
                            life: 1500
                        });
                        setTimeout(function() {
                            window.location.href = "student_view_classroom.php?roomId=" + classroomId;
                        }, 1500);
                        // Optionally, add the redirection or quiz start logic here
                    } else if (result.dismiss === Swal.DismissReason.cancel) {

                    }
                });
            }

            function deleteItem(roomId, studentId) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'Do you want to leave this classroom?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Confirm!',
                    cancelButtonText: 'No, cancel',
                    cancelButtonColor: '#3085d6',
                    reverseButtons: true,
                    customClass: {
                        confirmButton: 'gradient-button',
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Perform the delete operation via AJAX
                        $.ajax({
                            type: 'POST',
                            url: 'process/leave_class.php',
                            data: {
                                roomId: roomId,
                                studentId: studentId
                            },
                            success: function(response) {
                                if (response.status === 'success') {
                                    Swal.fire('Left!', 'The classroom has been successfully leaved.', 'success');
                                    setTimeout(function() {
                                        location.reload();
                                    }, 500)
                                } else {
                                    Swal.fire('Failed!', 'Failed to leave the classroom.', 'error');
                                }
                            },
                            error: function(xhr, status, error) {
                                Swal.fire('Error!', 'An error occurred while attempting to leave the classroom: ' + error, 'error');
                            }
                        });
                    }
                });
            }
        </script>
        <?php include('js/scripts.php'); ?>
</body>

</html>