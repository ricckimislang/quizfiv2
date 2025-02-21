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
                <table class="rewardTable stripe " id="rewardTable">
                    <thead>
                        <tr>
                            <th>Difficulty</th>
                            <th>Perfect</th>
                            <th>Consolation</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $categories = $conn->prepare("SELECT * FROM categories");
                        $categories->execute();
                        $categories = $categories->get_result();
                        while ($row = $categories->fetch_assoc()) {
                            ?>
                            <tr >
                                <td data-label="Difficulty"><?= $row['difficulty']; ?></td>
                                <td data-label="Perfect"><input class="form-control" type="text" id="perfect" name="perfect"
                                        value="<?= $row['perfect'] ?>"></td>
                                <td data-label="Consolation"><input class="form-control" type="text" id="consolation"
                                        name="consolation" value="<?= $row['consolation'] ?>"></td>
                                <td data-label="Action" class="table-action"><button type="button"
                                        class="action-btn">Save</button></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>

    </main>

    <script>
        $(function () {
            $('#rewardTable').DataTable({
                responsive: true,  // Keeps the table mobile-friendly
                autoWidth: true,  // Prevents column misalignment
                deferRender: true, // Improves performance for large datasets
                dom: 'rt', // Custom layout
            });
        });
    </script>
    <script>
        $(document).ready(function () {
            $('.action-btn').click(function () {
                const row = $(this).closest('tr');
                const difficulty = row.find('td:first').text();
                const perfect = row.find('input[name="perfect"]').val();
                const consolation = row.find('input[name="consolation"]').val();

                // Show loading state
                const button = $(this);
                const originalText = button.text();
                button.prop('disabled', true).text('Saving...');

                $.ajax({
                    url: 'process/configure_rewards.php',
                    method: 'POST',
                    data: {
                        difficulty: difficulty,
                        perfect: perfect,
                        consolation: consolation
                    },
                    dataType: 'json',
                    success: function (response) {
                        if (response.status === 'success') {
                            // Show success feedback
                            button.removeClass('btn-primary').addClass('btn-success');
                            button.text('Saved!');
                            toastr.success('Rewards updated successfully.');

                            // Reset button after 2 seconds
                            setTimeout(function () {
                                button.removeClass('btn-success').addClass('btn-primary');
                                button.text(originalText);
                                button.prop('disabled', false);
                            }, 2000);
                        } else {
                            // Show error feedback
                            alert('Error: ' + response.message);
                            button.text(originalText);
                            button.prop('disabled', false);
                            toastr.error('Error.');
                        }
                    },
                    error: function () {
                        // Handle network/server errors
                        toastr.error('Failed, Please Try Again.');
                        button.text(originalText);
                        button.prop('disabled', false);
                    }
                });
            });
        });
    </script>
    <script src="js/main.js"></script>
</body>