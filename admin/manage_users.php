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
    <header id="header" class="header fixed-top d-flex align-items-center">
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
                                    <td data-label="Name"><?php echo $row['firstname'] . ' ' . $row['lastname']; ?></td>
                                    <td data-label="Year Level"><?php echo $row['year']; ?></td>
                                    <td data-label="Department"><?php echo $row['department']; ?></td>
                                    <td data-label="Action">
                                        <div class="action-btn-group">
                                            <button data-toggle="tooltip" data-placement="top" title="Edit Student"
                                                onclick="editStudent('<?php echo $row['student_id'] ?>')" class="btn-edit">
                                                <i class="bx bx-edit"></i>
                                                Edit
                                            </button>
                                            <button data-toggle="tooltip" data-placement="top" title="Delete Student"
                                                class="btn-delete">
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
                                            <button data-toggle="tooltip" data-placement="top" title="Edit Educator"
                                                onclick="editEducator('<?php echo $row['educator_id'] ?>')"
                                                class="btn-edit">
                                                <i class="bx bx-edit"></i>
                                                Edit
                                            </button>
                                            <button data-toggle="tooltip" data-placement="top" title="Delete Educator"
                                                class="btn-delete">
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
    </main>
    <!-- Modals -->
    <?php include_once 'modal/student-form.php'; ?>

    <script src="js/main.js"></script>
    <script>
        $(document).ready(function () {
            $('#studentTable').DataTable({
                responsive: true,
                autoWidth: false,
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search students...",
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
                drawCallback: function () {
                    // Reinitialize tooltips after table redraw
                    $('[data-toggle="tooltip"]').tooltip();
                }
            });

            $('#educatorTable').DataTable({
                responsive: true,
                autoWidth: false,
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search educators...",
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
                drawCallback: function () {
                    // Reinitialize tooltips after table redraw
                    $('[data-toggle="tooltip"]').tooltip();
                }
            });
        });
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
                success: function (data) {
                    if (data.error) {
                        $.jGrowl(data.error, {
                            theme: "alert alert-danger",
                            life: 3000
                        });
                    } else {
                        $('#student-form').modal('show');
                        $('#modalfirst_name').val(data.firstname);
                        $('#modallast_name').val(data.lastname);
                        $('#modalyear_level').val(data.year);
                        $('#modalstudent_department').val(data.department);
                        $('#student_id').val(data.student_id);
                    }
                },
                error: function (xhr, status, error) {
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
                success: function (data) {
                    if (data.error) {
                        $.jGrowl(data.error, {
                            theme: "alert alert-danger",
                            life: 3000
                        });
                    } else {
                        $('#educator-form').modal('show');
                        $('#modalfirst_name').val(data.firstname);
                        $('#modallast_name').val(data.lastname);
                        $('#modaldepartment').val(data.department);
                        $('#educator_id').val(data.educator_id);
                    }
                },
                error: function (xhr, status, error) {
                    console.log(error);
                }
            });
        }
    </script>
</body>