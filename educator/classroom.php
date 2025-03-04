<?php
session_start();
include_once 'includes/header.php';
include_once 'includes/session.php';
$studentId = 2;
?>

<link rel="stylesheet" href="css/classroom.css">

<body>
    <header id="header" class="header">
        <?php include_once 'includes/nav-top.php' ?>
    </header>
    <?php include_once 'includes/bubble.php' ?>
    <main class="main" id="main">
        <?php include_once 'includes/mobile-nav.php' ?>
        <div class="card">
            <div class="classroom-container">
                <div class="classroom-card-grid">
                    <?php
                    $classQuery = "SELECT * FROM classroom WHERE user_id = ?";
                    $classStmt = $conn->prepare($classQuery);
                    $classStmt->bind_param('i', $user_id);
                    $classStmt->execute();
                    $classResult = $classStmt->get_result();
                    ?>
                    <?php
                    if ($classResult->num_rows > 0):
                        while ($class = $classResult->fetch_assoc()) {
                            $imagePath = $class['profile_path'];
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
                                                <li onclick="deleteItem(<?php echo $class['classroom_id']; ?>)">Delete</li>
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="classroom-card-title">
                                        <h3><?= $class['classroom_name']; ?></h3>
                                        <div class="subtitle">
                                            <span><?= $class['classroom_code']; ?></span>
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
                        <?php }
                    else: ?>
                        <div class="no-data-group">
                            <div class="no-data">
                                <img src="assets/img/no-data.svg" alt="">
                            </div>
                            <h2>It looks like you haven't created a classroom yet!</h2>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="action-btn-group">
            <button type="button" id="add-classroom" class="btn btn-primary"><i class="bx bx-plus"></i></button>
        </div>
    </main>
    <script src="js/main.js"></script>
    <script>
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
                    toastr.success('Redirecting to classroom...');
                    setTimeout(function() {
                        window.location.href = "view_classroom.php?roomId=" + classroomId;
                    }, 1500);
                    // Optionally, add the redirection or quiz start logic here
                } else if (result.dismiss === Swal.DismissReason.cancel) {

                }
            });
        }

        function deleteItem(classroom_id) {
            Swal.fire({
                title: 'Delete Classroom?',
                text: 'Are you sure you want to delete this classroom?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!',
                confirmButtonColor: '#2ecc71',
                cancelButtonColor: '#d33',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        url: "process/delete_classroom.php",
                        data: {
                            classroom_id: classroom_id
                        },
                        dataType: 'json',
                        success: function(data) {
                            toastr.success(data.message);
                            setTimeout(function() {
                                window.location.href = "classroom.php";
                            }, 1500);
                        }
                    });
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    toastr.error('Classroom not deleted' + data.message);
                }
            });
        }
    </script>
</body>