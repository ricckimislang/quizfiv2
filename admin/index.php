<?php
session_start();
include_once 'includes/header.php';
include_once 'includes/session.php';
include_once 'db/dbconn.php';


$voucherGraph = $conn->prepare("SELECT status, COUNT(*) as count FROM purchased_vouchers GROUP BY status");
$voucherGraph->execute();
$result = $voucherGraph->get_result();

// Fetch results
while ($row = $result->fetch_assoc()) {
    switch (strtolower($row['status'])) {
        case 'used':
            $used = $row['count'];
            break;
        case 'unused':
            $unused = $row['count'];
            break;
    }
}


// Count the total number of users, educator and student po
$totalUsers = $conn->prepare("SELECT COUNT(*) as total, SUM(usertype = 'educator') as educators, SUM(usertype = 'student') as students FROM user");
$totalUsers->execute();
$totalUsersResult = $totalUsers->get_result();
$totalUsersRow = $totalUsersResult->fetch_assoc();
$totalUsersCount = $totalUsersRow['total'] - 1;
$totalEducatorCount = $totalUsersRow['educators'];
$totalStudentCount = $totalUsersRow['students'];

// Count the total number of vouchers
$availVoucher = $conn->prepare("SELECT SUM(quantity) as quantity FROM vouchers");
$availVoucher->execute();
$availVoucherResult = $availVoucher->get_result();
$availVoucherRow = $availVoucherResult->fetch_assoc();
$availVoucherCount = $availVoucherRow['quantity'];

// for active users table
$usersActiveData = [];
$usersActive = $conn->prepare("SELECT * FROM students GROUP BY lastname, firstname, status ORDER BY status");
$usersActive->execute();
$usersActiveResult = $usersActive->get_result();

while ($usersActiveRow = $usersActiveResult->fetch_assoc()) {

    // query para sa quiz attempts
    $quizAttempts = $conn->prepare("SELECT COUNT(*) as quizAttempt FROM quiz_attempt WHERE user_id = ?");
    $quizAttempts->bind_param("i", $usersActiveRow['user_id']);
    $quizAttempts->execute();
    $quizAttemptsResult = $quizAttempts->get_result();
    $quizAttemptsRow = $quizAttemptsResult->fetch_assoc();

    $usersActiveData[] = [
        'fullname' => $usersActiveRow['firstname'] . ' ' . $usersActiveRow['lastname'],
        'status' => $usersActiveRow['status'],
        'quizAttempt' => $quizAttemptsRow['quizAttempt'],
    ];
}


// Ensure $used and $unused are defined
$used = isset($used) ? $used : 0;
$unused = isset($unused) ? $unused : 0;

// Calculate total vouchers correctly
$totalVouchers = $used + $unused;

// Calculate percentages
$usedPercentage = $totalVouchers > 0 ? ($used / $totalVouchers) * 100 : 0;
$activePercentage = $totalVouchers > 0 ? ($unused / $totalVouchers) * 100 : 0;


?>
<script>
    var unusedVouchers = <?php echo isset($unused) ? $unused : 0; ?>;
    var usedVouchers = <?php echo isset($used) ? $used : 0; ?>;
    var totalVouchers = unusedVouchers + usedVouchers;

    var activePercentage = totalVouchers > 0 ? (unusedVouchers / totalVouchers) * 100 : 0;
    var usedPercentage = totalVouchers > 0 ? (usedVouchers / totalVouchers) * 100 : 0;
    var totalVoucherPercentage = totalVouchers > 0 ? 100 : 0;
</script>


<link rel="stylesheet" href="css/index.css">

<body>
    <header id="header" class="header fixed-top d-flex align-items-center">
        <?php include_once 'includes/nav-top.php'; ?>
    </header><!-- End Header -->

    <?php include_once 'includes/bubble.php'; ?>

    <main id="main" class="main">
        <?php include_once 'includes/mobile-nav.php'; ?>
        <input type="hidden" id="user-id" value="<?php echo $user_id; ?>">
        <div class="container mt-4">
            <!-- Dashboard Header -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body p-4">
                            <div class="welcome d-flex align-items-center justify-content-start">
                                <img src="assets/img/welcome.svg" alt="welcome image" width="200">
                                <div>
                                    <h2 class="mb-1">Welcome back, Admin!</h2>
                                    <p class=" mb-0">
                                        <?php echo date('l, F d, Y'); ?> | Last login: Today at 9:30 AM
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="row mb-4">
                <div class="col-md-6 col-lg-3 mb-3">
                    <div class="card info-card sales-card">
                        <div class="card-body">
                            <h5 class="card-title">Total Users <span>| registered</span></h5>
                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-people"></i>
                                </div>
                                <div class="ps-3">
                                    <h6><?= $totalUsersCount; ?></h6>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3 mb-3">
                    <div class="card info-card revenue-card">
                        <div class="card-body">
                            <h5 class="card-title">Educators <span>| Total</span></h5>
                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-person-workspace"></i>
                                </div>
                                <div class="ps-3">
                                    <h6><?= $totalEducatorCount; ?></h6>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3 mb-3">
                    <div class="card info-card">
                        <div class="card-body">
                            <h5 class="card-title">Students <span>| Total</span></h5>
                            <div class="d-flex align-items-center">
                                <div class="card-icon">
                                    <i class="bi bi-people"></i>
                                </div>
                                <div class="ps-3">
                                    <h6><?= $totalStudentCount; ?></h6>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3 mb-3">
                    <div class="card info-card customers-card">
                        <div class="card-body">
                            <h5 class="card-title">Vouchers <span>| Available</span></h5>
                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-ticket-perforated"></i>
                                </div>
                                <div class="ps-3">
                                    <h6><?= isset($availVoucherCount) ? $availVoucherCount : 0; ?></h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>

            <!-- Top Users & Voucher Status -->
            <div class="row">
                <div class="col-md-8 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Top Users <span>| Active</span></h5>
                            <table class="table table-borderless datatable">
                                <thead>
                                    <tr>
                                        <th scope="col">User</th>
                                        <th scope="col">Quiz Attempts</th>
                                        <th scope="col">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($usersActiveData as $dataStudent) { ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-primary rounded-circle me-2"
                                                        style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;">
                                                        <?php
                                                        $fullname = $dataStudent['fullname'];
                                                        $names = explode(' ', $fullname); // Split the full name into an array of words

                                                        $firstInitial = strtoupper(substr($names[0], 0, 1)); // First letter of the first word
                                                        $secondInitial = isset($names[1]) ? strtoupper(substr($names[1], 0, 1)) : ''; // First letter of the second word (if it exists)

                                                        $initials = $firstInitial . $secondInitial; // Concatenate the initials
                                                        ?>
                                                        <?= $initials ?>
                                                    </div>
                                                    <span><?= htmlspecialchars($dataStudent['fullname']); ?></span>
                                                </div>
                                            </td>
                                            <td><?= $dataStudent['quizAttempt']; ?></td>
                                            <td><span
                                                    class="badge <?php echo ($dataStudent['status'] == 'active') ? 'bg-success' : 'bg-danger' ?>"><?= htmlspecialchars($dataStudent['status']); ?></span>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Voucher Status</h5>
                            <div style="height: 220px">
                                <canvas id="voucherStatusChart"></canvas>
                            </div>
                            <div class="mt-3">
                                <div id="noDataMessage" style="display: none; text-align: center; margin-top: 20px;">
                                    <h5>No data available for the pie chart.</h5>
                                </div>
                                <!-- <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span>Available Vouchers</span>
                                    <span class="badge bg-info"><?php echo isset($availVoucherCount) ? $availVoucherCount : 0; ?></span>
                                </div>
                                <div class="progress mb-3" style="height: 8px;">
                                    <div id="totalVoucherPercentage" class="progress-bar bg-info" role="progressbar"
                                        style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                </div> -->
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span>Used Vouchers</span>
                                    <span class="badge bg-danger"><?php echo isset($used) ? $used : 0; ?></span>
                                </div>
                                <div class="progress mb-3" style="height: 8px;">
                                    <div id="usedPercentage" class="progress-bar bg-danger" role="progressbar"
                                        style="width: <?= $usedPercentage; ?>%" aria-valuenow="<?= $usedPercentage; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span>Unused Vouchers</span>
                                    <span class="badge bg-success"><?php echo isset($unused) ? $unused : 0; ?></span>
                                </div>
                                <div class="progress mb-3" style="height: 8px;">
                                    <div id="activePercentage" class="progress-bar bg-success" role="progressbar"
                                        style="width: <?= $activePercentage; ?>%" aria-valuenow="<?= $activePercentage; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </main><!-- End #main -->
    <!-- Include Change Password Modal -->
    <?php include_once 'modal/change-password.php'; ?>
    <?php include_once 'modal/change_password_message.php'; ?>


    <!-- Chart.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            showChangePasswordMessage();
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const voucherStatusChart = document.getElementById('voucherStatusChart');
            new Chart(voucherStatusChart, {
                type: 'pie',
                data: {
                    labels: ['Unused', 'Used'],
                    datasets: [{
                        data: [unusedVouchers, usedVouchers],
                        backgroundColor: ['#2eca6a', '#dc3545'],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });

            document.getElementById('totalVoucherPercentage').style.width = totalVoucherPercentage + "%";
            document.getElementById('usedPercentage').style.width = usedPercentage + "%";
            document.getElementById('activePercentage').style.width = activePercentage + "%";

            document.getElementById('totalVoucherPercentage').setAttribute("aria-valuenow", totalVouchers);
            document.getElementById('usedPercentage').setAttribute("aria-valuenow", usedPercentage);
            document.getElementById('activePercentage').setAttribute("aria-valuenow", activePercentage);


            // Show no data message if both values are zero
            if (unusedVouchers === 0 && usedVouchers === 0) {
                console.log("No data available, showing message.");
                document.getElementById('noDataMessage').style.display = 'block';
            } else {
                console.log("Data available, hiding message.");
                document.getElementById('noDataMessage').style.display = 'none';
            }
        });
    </script>


    <!-- Change Password JavaScript -->
    <script>
        async function showChangePasswordMessage() {
            try {
                const userId = document.getElementById('user-id').value;

                if (!userId) {
                    console.error('User ID not found');
                    return;
                }

                const checkPassword = await fetch('process/check_password.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `user_id=${userId}`
                });

                if (!checkPassword.ok) {
                    throw new Error('Network response was not ok');
                }

                const data = await checkPassword.json();

                if (data.success) {
                    const modalElement = document.getElementById('change-password-warning-modal');
                    if (modalElement) {
                        const modal = new bootstrap.Modal(modalElement);
                        modal.show();
                    } else {
                        console.error('Modal element not found');
                    }
                }
            } catch (error) {
                console.error('Error checking password:', error);
            }
        }

        function changePassword() {
            const modal = new bootstrap.Modal(document.getElementById('change-password-modal'));
            modal.show();
            const modal1 = bootstrap.Modal.getInstance(document.getElementById('change-password-warning-modal'));
            if (modal1) {
                modal1.hide();
            }
        }

        $(document).ready(function() {
            $('#changePasswordForm').submit(function(e) {
                e.preventDefault();

                let submitBtn = $('#changePasswordBtn');
                submitBtn.prop('disabled', true).text('Changing...');

                fetch('process/change-user-password.php', {
                        method: 'POST',
                        body: new FormData(this)
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            toastr["success"](data.message);
                            $('#changePasswordForm')[0].reset();
                            const modal = bootstrap.Modal.getInstance(document.getElementById('change-password-modal'));
                            modal.hide();
                        } else {
                            toastr["error"](data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error changing password:', error);
                        toastr["error"]("An error occurred while changing password");
                    })
                    .finally(() => {
                        submitBtn.prop('disabled', false).text('Save Changes');
                    });
            });
        });
    </script>

    <script src="js/main.js"></script>
</body>