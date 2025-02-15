<?php
session_start();
include('includes/header.php');
include('includes/session.php');
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="css/loading-screen.css">
<link rel="stylesheet" href="css/convert_voucher.css">
<link rel="stylesheet" href="css/style.css">

<body>
    <!-- Loading Screen -->
    <?php include("includes/loading-screen.php"); ?>

    <header id="header" class="header fixed-top d-flex align-items-center">
        <?php include 'includes/nav-top.php'; ?>
    </header>

    <?php include 'includes/sidebar.php'; ?>

    <main id="main" class="main">
        <div class="top-bar">
            <div class="menu-nav">
                <ul>
                    <li><a href="#" class="active" id="VoucherList">Voucher</a></li>
                    <li><a href="#" id="HistoryList">History</a></li>
                </ul>
            </div>
        </div>

        <div class="content container-fluid m-0">
            <!-- voucher content -->
            <div class="voucher-content">
                <div class="points-container">
                    <img src="assets/img/coin.png" alt="" class="coin-image">
                    <span><?php echo $student_score; ?></span>
                </div>
                <div class="card-grid">
                    <?php
                    $limit = 8;
                    $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
                    $offset = ($page - 1) * $limit;

                    $totalVouchers = "SELECT COUNT(*) as total FROM vouchers";
                    $totalStmt = $conn->prepare($totalVouchers);
                    if ($totalStmt === false) {
                        die('Error preparing the SQL statement: ' . $conn->error);
                    }
                    $totalStmt->execute();
                    $totalResult = $totalStmt->get_result();
                    $totalRow = $totalResult->fetch_assoc();
                    $totalVouchersCount = $totalRow['total'];
                    $totalPages = ceil($totalVouchersCount / $limit);

                    $vouchersQuery = "SELECT * FROM vouchers LIMIT ? OFFSET ?";
                    $vstmt = $conn->prepare($vouchersQuery);
                    if ($vstmt === false) {
                        die('Error preparing the SQL statement: ' . $conn->error);
                    }

                    $vstmt->bind_param('ii', $limit, $offset);
                    $vstmt->execute();
                    $vresult = $vstmt->get_result();

                    while ($vouch = $vresult->fetch_assoc()) {
                        ?>
                        <div class="voucher-container" data-user-id="<?php echo $user_id; ?>"
                            data-voucher-id="<?php echo $vouch['voucher_id']; ?>"
                            data-voucher-name="<?php echo $vouch['voucher_name']; ?>">
                            <div class="voucher-header">
                                <img src="assets/img/logo-quizfi.png" alt="logo.png">
                            </div>
                            <div class="voucher-body">
                                <div class="voucher-title">
                                    <h5><?php echo htmlspecialchars($vouch['points']); ?> points</h5>
                                    <span class="voucher-hour">
                                        <i class="fa fa-clock"></i>
                                        <?php echo htmlspecialchars($vouch['voucher_name']); ?>
                                        <p>Qty: <?php echo htmlspecialchars($vouch['quantity']); ?></p>
                                    </span>
                                </div>
                                <div class="voucher-instruction">
                                    <span class="subtitle">HOW TO USE THE VOUCHER</span>
                                    <p>1. Copy the code and go to profile page</p>
                                    <p>2. Click the redeem voucher button</p>
                                    <p>3. Enter the voucher code and submit</p>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>

                <?php if ($totalPages > 1): ?>
                    <div class="pagination-container">
                        <div class="pagination">
                            <?php if ($page > 1): ?>
                                <a href="?page=<?php echo ($page - 1); ?>">&laquo; Previous</a>
                            <?php endif; ?>

                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <?php if ($i == $page): ?>
                                    <span class="current-page"><?php echo $i; ?></span>
                                <?php else: ?>
                                    <a href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                <?php endif; ?>
                            <?php endfor; ?>

                            <?php if ($page < $totalPages): ?>
                                <a href="?page=<?php echo ($page + 1); ?>">Next &raquo;</a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- history content -->
            <div class="history-content">
            </div>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const loadingScreen = document.getElementById('loading-screen');
            if (loadingScreen) {
                loadingScreen.style.display = 'none';
            }
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const menuLinks = document.querySelectorAll('.menu-nav a');
            const voucherContent = document.querySelector('.voucher-content');
            const historyContent = document.querySelector('.history-content');

            voucherContent.classList.add('active');
            historyContent.classList.remove('active');

            menuLinks.forEach(link => {
                link.addEventListener('click', function (event) {
                    event.preventDefault();
                    menuLinks.forEach(link => link.classList.remove('active'));
                    this.classList.add('active');
                    if (this.id === 'VoucherList') {
                        voucherContent.classList.add('active');
                        historyContent.classList.remove('active');
                    } else if (this.id === 'HistoryList') {
                        voucherContent.classList.remove('active');
                        historyContent.classList.add('active');
                    }
                });
            });

            //buy voucher
            $(document).ready(function () {
                $('.voucher-container').on('click', function () {
                    var voucherId = $(this).data('voucher-id');
                    var voucherName = $(this).data('voucher-name');
                    var userId = $(this).data('user-id');

                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You want to buy " + voucherName + "?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#2ecc71',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, buy it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: 'process/buy_voucher.php',
                                type: 'POST',
                                dataType: 'json',
                                data: {
                                    voucher_id: voucherId,
                                    user_id: userId
                                },
                                success: function (response) {
                                    if (response.status === 'success') {
                                        Swal.fire({
                                            title: 'Purchased!',
                                            html: '<p>Voucher: ' + voucherName + '</p>' +
                                                '<p style="margin-top: 20px;">Please copy the generated code for the voucher: <strong>' + response.voucher_code + '</strong></p>',
                                            icon: 'success',
                                            timer: 10000,
                                            timerProgressBar: true,
                                            showConfirmButton: false,
                                            didOpen: () => {
                                                Swal.showLoading();
                                            },
                                            willClose: () => {
                                                Swal.fire({
                                                    title: 'Purchased!',
                                                    html: '<p>Voucher: ' + voucherName + '</p>' +
                                                        '<p style="margin-top: 20px;">Please copy the generated code for the voucher: <strong>' + response.voucher_code + '</strong></p>',
                                                    icon: 'success',
                                                    confirmButtonText: 'OK'
                                                }).then((result) => {
                                                    if (result.isConfirmed) {
                                                        location.reload();
                                                    }
                                                });
                                            }
                                        });
                                    } else {
                                        Swal.fire(
                                            'Error!',
                                            response.message,
                                            'error'
                                        );
                                    }
                                },
                                error: function () {
                                    Swal.fire(
                                        'Error!',
                                        'Error connecting to the server.',
                                        'error'
                                    );
                                }
                            });
                        }
                    });
                });
            });
        });
    </script>
    <?php include('js/scripts.php'); ?>
</body>