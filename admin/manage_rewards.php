<?php
session_start();
include_once 'includes/header.php';
include_once 'includes/session.php';
?>

<link rel="stylesheet" href="css/manage_rewards.css">
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
                    <h2>✨Configure Rewards</h2>
                    <p class="subtitle">Configure the Correct Amount of Rewards from Quizzes</p>
                </div>
                <table class="rewardTable" id="rewardTable">
                    <thead>
                        <tr>
                            <th>Difficulty</th>
                            <th>Perfect</th>
                            <th>Consolation</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Easy</td>
                            <td>10</td>
                            <td>5</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </main>

    <script>
        $(function () {
            $('#rewardTable').DataTable({
                responsive: true,  // Keeps the table mobile-friendly
                autoWidth: false,  // Prevents column misalignment
                deferRender: true, // Improves performance for large datasets
                dom: 'rt', // Custom layout

                language: {
                    searchPlaceholder: "Search records...",
                    search: "", // Removes default label
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
                    paginate: {
                        first: "First",
                        last: "Last",
                        next: "Next",
                        previous: "Previous"
                    }
                }
            });
        });
    </script>
    <script src="js/main.js"></script>
</body>