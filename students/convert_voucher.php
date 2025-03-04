<?php
session_start();
include('includes/header.php');
include('includes/session.php');
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="css/loading-screen.css">
<link rel="stylesheet" href="css/convert_voucher.css">
<link rel="stylesheet" href="css/style.css">
<!-- Add these lines after your existing CSS links -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">

<!-- Add these lines before your closing </body> tag -->
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

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

        <div class="content">
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
                <div class="history-container">
                    <div class="history-title">
                        <h1>History</h1>
                    </div>
                    <div class="history-table-container">
                        <?php
                        // Fetch history data from the database
                        $history = $conn->prepare("SELECT ps.user_id, ps.voucher_id, ps.wifi_code, ps.duration, ps.status FROM purchased_vouchers ps WHERE user_id = ?");
                        $history->bind_param("i", $user_id);
                        $history->execute();
                        $historyResult = $history->get_result();
                        ?>
                        <table class="history-table" id="historyTable">
                            <thead>
                                <tr>
                                    <th>Duration</th>
                                    <th>Code</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($historyRow = $historyResult->fetch_assoc()): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($historyRow['duration']); ?></td>
                                        <td>
                                            <div class="copy-code-container">
                                                <span
                                                    class="voucher-code"><?= htmlspecialchars($historyRow['wifi_code']); ?></span>
                                            </div>
                                        </td>
                                        <td>
                                            <input type="hidden" id="wifi-code-copy"
                                                value="<?= htmlspecialchars($historyRow['wifi_code']); ?>">
                                            <button class="copy-button">
                                                <i class="bx bxs-copy-alt"></i>
                                                <span>Copy</span>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


        </div>
        </div>
    </main>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const menuLinks = document.querySelectorAll('.menu-nav a');
            const voucherContent = document.querySelector('.voucher-content');
            const historyContent = document.querySelector('.history-content');

            voucherContent.classList.add('active');
            historyContent.classList.remove('active');

            menuLinks.forEach(link => {
                link.addEventListener('click', function(event) {
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
            $(document).ready(function() {
                $('.voucher-container').on('click', function() {
                    var voucherId = $(this).data('voucher-id');
                    var voucherName = $(this).data('voucher-name');
                    var userId = $(this).data('user-id');

                    Swal.fire({
                        title: '⚠️ Confirm Purchase?',
                        html: `<p style="font-size: 18px; color: #333;">You are about to buy this Voucher:<strong> ${voucherName}</strong></p>`,
                        text: "You want to buy " + voucherName + "?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#2ecc71',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, buy it!',
                        reverseButtons: true
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
                                success: function(response) {
                                    if (response.status === 'success') {
                                        Swal.fire({
                                            title: '🎉 Purchased Successfully!',
                                            html: `
        <p style="font-size: 18px; color: #333;">Voucher: <strong>${voucherName}</strong></p>
        <p style="margin-top: 20px; font-weight: bold;">🔑 Your voucher code:</p>
        <div style="display: flex; align-items: center; justify-content: space-between; 
                    background: #f8f9fa; padding: 10px; font-size: 20px; 
                    font-weight: bold; border-radius: 5px; color: #28a745;">
            <span id="voucherCode">${response.voucher_code}</span>
            <button id="copyButton" style="background: none; border: none; cursor: pointer; padding: 5px;">
                <i class="fas fa-copy" style="color: #28a745; font-size: 18px;"></i>
            </button>
        </div>
        <p style="margin-top: 10px; font-size: 14px; color: #666;">(Click the copy icon to copy)</p>`,
                                            icon: 'success',
                                            background: '#fff',
                                            width: '500px',
                                            showConfirmButton: true,
                                            confirmButtonText: 'Done',
                                            confirmButtonColor: '#28a745',
                                            didOpen: () => {
                                                document.getElementById('copyButton').addEventListener('click', () => {
                                                    let codeText = document.getElementById('voucherCode').innerText;
                                                    navigator.clipboard.writeText(codeText);

                                                    // Change icon to checkmark after copying
                                                    document.getElementById('copyButton').innerHTML = '<i class="fas fa-check" style="color: #28a745; font-size: 18px;"></i>';

                                                    Swal.fire({
                                                        title: 'Copied!',
                                                        text: 'Voucher code copied to clipboard.',
                                                        icon: 'success',
                                                        timer: 1500,
                                                        showConfirmButton: false
                                                    });
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
                                error: function() {
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const copyButtons = document.querySelectorAll('.copy-button');

            copyButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Find the closest row and get the hidden input inside it
                    const row = this.closest('tr');
                    const codeInput = row.querySelector('input[type="hidden"]');

                    if (codeInput) {
                        const codeText = codeInput.value;

                        navigator.clipboard.writeText(codeText).then(() => {
                            const icon = this.querySelector('i');
                            icon.classList.remove('fa-copy');
                            icon.classList.add('fa-check');
                            toastr.success('Voucher code copied to clipboard.');

                            setTimeout(() => {
                                icon.classList.remove('fa-check');
                                icon.classList.add('fa-copy');
                            }, 1500);
                        }).catch(err => {
                            toastr.error('Voucher code failed to copied to clipboard.');
                            console.error('Failed to copy:', err);
                        });
                    }
                });
            });
        });


        // Add this after your existing scripts
        $(function() {
            $('#historyTable').DataTable({
                responsive: true, // Keeps the table mobile-friendly
                pageLength: 5, // Increased initial display length for better UX
                lengthMenu: [5, 10, 15, 25],
                autoWidth: false, // Prevents column misalignment
                deferRender: true, // Improves performance for large datasets
                dom: '<"top"l>rt<"bottom"ip>', // Custom layout

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
    <?php include('js/scripts.php'); ?>
</body>