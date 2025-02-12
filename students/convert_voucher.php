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
                <div class="card-grid">
                    <div class="voucher-container">
                        <div class="voucher-header">
                            <img src="assets/img/logo-quizfi.png" alt="logo.png">
                        </div>
                        <div class="voucher-body">
                            <div class="voucher-title">
                                <h5>1000 points</h5>
                                <span class="voucher-hour">
                                    <i class="fa fa-clock"></i>
                                    1hr
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
                </div>
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
            // Select the navigation links
            const menuLinks = document.querySelectorAll('.menu-nav a');

            // Select the two content sections
            const voucherContent = document.querySelector('.voucher-content');
            const historyContent = document.querySelector('.history-content');

            // Initially, ensure that only the classroom content is active
            voucherContent.classList.add('active');
            historyContent.classList.remove('active');

            // Add click event listeners for each menu link
            menuLinks.forEach(link => {
                link.addEventListener('click', function (event) {
                    event.preventDefault();

                    // Remove 'active' class from all menu links
                    menuLinks.forEach(link => link.classList.remove('active'));
                    // Add 'active' to the clicked link
                    this.classList.add('active');

                    // Toggle the active class on the content sections based on the clicked link's id
                    if (this.id === 'VoucherList') {
                        voucherContent.classList.add('active');
                        historyContent.classList.remove('active');
                    } else if (this.id === 'HistoryList') {
                        voucherContent.classList.remove('active');
                        historyContent.classList.add('active');
                    }
                });
            });
        });

    </script>
    <?php include('js/scripts.php'); ?>
</body>