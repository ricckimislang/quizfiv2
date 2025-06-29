<?php
session_start();
include_once 'includes/header.php';
include_once 'includes/session.php';
?>
<link rel="stylesheet" href="css/manage_users.css">
<!-- Add these lines after your existing CSS links -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">

<!-- Add these lines before your closing </body> tag -->
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

<body>
    <header id="header" class="header">
        <?php include_once 'includes/nav-top.php'; ?>
    </header>

    <?php include_once 'includes/bubble.php'; ?>

    <main id="main" class="main">
        <?php include_once 'includes/mobile-nav.php'; ?>
        <div class="container">
            <div class="card">
                <div class="card-header">
                    <h2>Student</h2>
                </div>
                <div class="card-body">
                    <table id="studentTable" class="studentTable stripe">
                        <thead>
                            <tr>
                                <th>ID</th>
                                </th>
                                <th>Name</th>
                                <th>Year Level</th>
                                <th>Department</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $students = $conn->prepare("SELECT * FROM students ORDER BY firstname ASC, lastname ASC");
                            $students->execute();
                            $students = $students->get_result();
                            while ($row = $students->fetch_assoc()):
                            ?>
                                <tr>
                                    <td data-label="ID">
                                        <img src="../students/<?php echo $row['id_photo']; ?>"
                                            alt="Photo ID"
                                            class="img-fluid img-thumbnail"
                                            style="width: 50px; height: 50px; cursor: pointer;"
                                            onclick="viewFullScreen(this);">
                                    </td>
                                    <td data-label="Name"><?php echo $row['firstname'] . ' ' . $row['lastname']; ?></td>
                                    <td data-label="Year Level"><?php echo $row['year']; ?></td>
                                    <td data-label="Department"><?php echo $row['department']; ?></td>
                                    <td data-label="Action">
                                        <div class="action-btn-group">
                                            <button data-bs-toggle="tooltip" data-bs-placement="bottom" title="Edit Student"
                                                onclick="editStudent('<?php echo $row['student_id'] ?>')" class="btn-edit">
                                                <i class="bx bx-edit"></i>
                                                Edit
                                            </button>
                                            <button data-bs-toggle="tooltip" data-bs-placement="bottom"
                                                title="Delete Student" onclick="deleteStudent('<?php echo $row['student_id'] ?>')" class="btn-delete">
                                                <i class="bx bx-trash"></i>
                                                Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h2>Educators</h2>
                </div>
                <div class="card-body">
                    <table id="educatorTable" class="educatorTable stripe">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Department</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $educators = $conn->prepare("SELECT * FROM educators ORDER BY firstname ASC, lastname ASC");
                            $educators->execute();
                            $educators = $educators->get_result();
                            while ($row = $educators->fetch_assoc()):
                            ?>
                                <tr>
                                    <td data-label="Name"><?php echo $row['firstname'] . ' ' . $row['lastname']; ?></td>
                                    <td data-label="Department"><?php echo $row['department']; ?></td>
                                    <td data-label="Action">
                                        <div class="action-btn-group">
                                            <button data-bs-toggle="tooltip" data-bs-placement="bottom"
                                                title="Edit Educator"
                                                onclick="editEducator('<?php echo $row['educator_id'] ?>')"
                                                class="btn-edit">
                                                <i class="bx bx-edit"></i>
                                                Edit
                                            </button>
                                            <button data-bs-toggle="tooltip" data-bs-placement="bottom"
                                                title="Delete Educator" onclick="deleteEducator('<?php echo $row['educator_id'] ?>')" class="btn-delete">
                                                <i class="bx bx-trash"></i>
                                                Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="button-group">
            <!-- SOON FUNCTION!! <button data-bs-tooltip="tooltip" data-bs-placement="left" title="Verify User" type="button"
                id="verify-modal-btn" class="btn-add"><i class="bx bx-user-check"></i></button> -->
            <button data-bs-tooltip="tooltip" data-bs-placement="left" title="Add User" type="button"
                id="register-modal-btn" class="btn-add"><i class="bx bx-plus"></i></button>
        </div>
        <div id="fullscreenOverlay" style="display: none; position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: rgba(0, 0, 0, 0.8); display: none; justify-content: center; align-items: center; z-index: 1000;">
            <img id="fullscreenImage" src="" style="max-width: 90%; max-height: 90%; border: 5px solid white; border-radius: 10px; cursor: pointer;" onclick="closeFullscreen();">
        </div>
    </main>
    <!-- Modals -->
    <?php include_once 'modal/student-form.php'; ?>
    <?php include_once 'modal/educator-form.php'; ?>
    <?php include_once 'modal/register-user-form.php'; ?>
    <?php include_once 'modal/verify-user-form.php'; ?>
    <?php include_once 'modal/delete_user_modal.php'; ?>

    <script src="js/main.js"></script>
    <script>
        $(document).ready(function() {
            // Define an array of table IDs
            let tableIDs = ['#studentTable', '#educatorTable', '#verifyTable', '#historyTable'];

            // Loop through each ID and initialize DataTables
            tableIDs.forEach(function(tableID) {
                $(tableID).DataTable({
                    responsive: true,
                    autoWidth: false,
                    language: {
                        search: "_INPUT_",
                        searchPlaceholder: "Search...",
                        lengthMenu: "Show _MENU_ entries",
                        info: "Showing _START_ to _END_ of _TOTAL_ entries",
                        paginate: {
                            first: "First",
                            last: "Last",
                            next: "Next",
                            previous: "Prev"
                        }
                    },
                    pageLength: 5,
                    lengthMenu: [5, 10, 15, 25],
                    ordering: true,
                    dom: '<"top"lf>rt<"bottom"ip><"clear">',
                });
            });
        });


        function viewFullScreen(imgElement) {
            const fullscreenOverlay = document.getElementById('fullscreenOverlay');
            const fullscreenImage = document.getElementById('fullscreenImage');

            fullscreenImage.src = imgElement.src; // Assign the clicked image source
            fullscreenOverlay.style.display = 'flex'; // Show overlay
        }
        // Close fullscreen when clicking outside the image
        document.getElementById('fullscreenOverlay').addEventListener('click', function(event) {
            if (event.target === this) { // Ensure only background clicks close it
                closeFullscreen();
            }
        });

        function closeFullscreen() {
            document.getElementById('fullscreenOverlay').style.display = 'none';
        }
    </script>

    <script>
        function editStudent(student_id) {
            $.ajax({
                type: 'POST',
                url: 'process/get_student.php',
                data: {
                    student_id: student_id
                },
                dataType: 'json',
                success: function(data) {
                    if (data.error) {
                        toastr.error(data.error)
                    } else {
                        $('#student-form').modal('show');
                        $('#modalfirst_name').val(data.firstname);
                        $('#modallast_name').val(data.lastname);
                        $('#modalyear_level').val(data.year);
                        $('#modalstudent_department').val(data.department);
                        $('#student_id').val(data.student_id);
                        $('#student_id2').val(data.student_id);

                        $('#account_email').val(data.user_email);
                        $('#account_user').val(data.username);
                    }
                },
                error: function(xhr, status, error) {
                    console.log(error);
                }
            });
        }

        function editEducator(educator_id) {
            $.ajax({
                type: 'POST',
                url: 'process/get_educator.php',
                data: {
                    educator_id: educator_id
                },
                dataType: 'json',
                success: function(data) {
                    if (data.error) {
                        toastr.error(data.error)
                    } else {
                        $('#educator-form').modal('show');
                        $('#educator_firstname').val(data.firstname);
                        $('#educator_lastname').val(data.lastname);
                        $('#educator_department').val(data.department);
                        $('#educator_email').val(data.educator_id);
                        $('#educator_account_user').val(data.username);
                        $('#educator_account_email').val(data.user_email);

                        $('#educator_id').val(data.educator_id);
                        $('#educator_id2').val(data.educator_id);
                    }
                },
                error: function(xhr, status, error) {
                    console.log(error);
                }
            });
        }

        function deleteStudent(student_id) {
            $('#deleteConfirmationModal').modal('show');
            const deleteUserBtn = document.getElementById('delete-user-btn');

            $(deleteUserBtn).click(function() {
                $('#deleteConfirmationModal').modal('hide');
                $.ajax({
                    type: 'POST',
                    url: 'process/delete_student_educator.php',
                    data: {
                        student_id: student_id
                    },
                    dataType: 'json',
                    success: function(data) {
                        if (data.error) {
                            toastr.error(data.error)
                        } else {
                            toastr.success(data.success)
                            setTimeout(function() {
                                location.reload()
                            }, 0);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log(error);
                    }
                });
            });
        }

        function deleteEducator(educator_id) {
            $('#deleteConfirmationModal').modal('show');
            const deleteUserBtn = document.getElementById('delete-user-btn');

            $(deleteUserBtn).click(function() {
                $('#deleteConfirmationModal').modal('hide');
                $.ajax({
                    type: 'POST',
                    url: 'process/delete_student_educator.php',
                    data: {
                        educator_id: educator_id
                    },
                    dataType: 'json',
                    success: function(data) {
                        if (data.error) {
                            toastr.error(data.error)
                        } else {
                            toastr.success(data.success)
                            setTimeout(function() {
                                location.reload()
                            }, 0);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log(error);
                    }
                });
            });
        }
    </script>
    <script>
        // user registration form and verification
        $(document).ready(function() {
            const registrationBtn = document.getElementById('register-modal-btn');
            const verificationBtn = document.getElementById('verify-modal-btn')

            $(registrationBtn).click(function() {
                $('#registration-form').modal('show');
            });

            $(verificationBtn).click(function() {
                $('#verification-form').modal('show');
            });

            $('#student-reg-form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: 'process/add_student_user.php',
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function(data) {
                        if (data.status == 'duplicate') {
                            toastr.error(data.message)
                        } else if (data.status == 'error') {
                            toastr.error(data.message)
                        } else if (data.status == 'success') {
                            toastr.success(data.message)
                            $('#registration-form').modal('hide');
                            setTimeout(function() {
                                location.reload()
                            }, 1500);
                        } else {
                            toastr.error(data.message)
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log(error);
                    }
                });
            });

            $('#educator-reg-form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: 'process/add_educator_user.php',
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function(data) {
                        if (data.status == 'duplicate') {
                            toastr.error(data.message)
                        } else if (data.status == 'error') {
                            toastr.error(data.message)
                        } else if (data.status == 'success') {
                            toastr.success(data.message)
                            $('#registration-form').modal('hide');
                            setTimeout(function() {
                                location.reload()
                            }, 1500);
                        } else {
                            toastr.error(data.message)
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log(error);
                    }
                });
            });
        });

        // submitting edited form student
        $(document).ready(function() {
            // profile
            $('#student-edit-form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: 'process/edit_student_details.php',
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function(data) {
                        if (data.error) {
                            toastr.error(data.message);
                        } else {
                            try {
                                $('#student-form').modal('hide');
                            } catch (error) {
                                console.error('Error hiding modal:', error);
                            }
                            toastr.success(data.message);
                            setTimeout(function() {
                                location.reload();
                            }, 0);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log(error);
                    }
                });
            });
            // account
            $('#account-edit-form').submit(function(e) {
                e.preventDefault();
                const formData = $(this).serialize();

                $.ajax({
                    type: 'POST',
                    url: 'process/edit_student_account.php',
                    data: formData,
                    dataType: 'json',
                    success: function(data) {
                        if (data.error) {
                            toastr.error(data.message);
                        } else {
                            try {
                                $('#student-form').modal('hide');
                            } catch (error) {
                                console.error('Error hiding modal:', error);
                            }
                            toastr.success(data.message);
                            setTimeout(function() {
                                location.reload();
                            }, 0);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log(error);
                    }
                });
            });

            // educator
            // Educator form
            $('#educator-profile-edit-form').submit(function(e) {
                e.preventDefault();

                $.ajax({
                    type: 'POST',
                    url: 'process/edit_educator_profile.php',
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function(data) {
                        if (data.error) {
                            toastr.error(data.message);
                        } else {
                            try {
                                $('#educator-form').modal('hide');
                            } catch (error) {
                                console.error('Error hiding modal:', error);
                            }
                            toastr.success(data.message);
                            setTimeout(function() {
                                location.reload();
                            }, 0);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log(error);
                    }
                });
            });

            $('#educator-account-edit-form').submit(function(e) {
                e.preventDefault();

                $.ajax({
                    type: 'POST',
                    url: 'process/edit_educator_account.php',
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function(data) {
                        if (data.error) {
                            toastr.error(data.message);
                        } else {
                            try {
                                $('#educator-form').modal('hide');
                            } catch (error) {
                                console.error('Error hiding modal:', error);
                            }
                            toastr.success(data.message);
                            setTimeout(function() {
                                location.reload();
                            }, 0);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log(error);
                    }
                });
            });
        });
    </script>
</body>