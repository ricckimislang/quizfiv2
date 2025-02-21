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
                                            <button class="btn-edit">
                                                <i class="bx bx-edit"></i>
                                                Edit
                                            </button>
                                            <button class="btn-delete">
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
        });
    </script>
</body>