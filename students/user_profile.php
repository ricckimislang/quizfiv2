<?php
session_start();
include 'includes/header.php';
include 'includes/session.php';

$totalDuration = $time_duration;
?>

<link rel="stylesheet" href="css/user_profile.css">
<link rel="stylesheet" href="css/loading-screen.css">

<body>
    <!-- Loading Screen -->
    <?php include("includes/loading-screen.php"); ?>
    <header id="header" class="header fixed-top d-flex align-items-center">
        <?php include 'includes/nav-top.php'; ?>
        <?php include 'includes/sidebar.php'; ?>

        <main id="main" class="main" height="100vh">
            <section class="section ">
                <div class="profile-details leaderbox">
                    <div class="container text-center p-0">
                        <div class="logo-header">
                            <div class="background-header">
                            </div>
                            <div class="profile-avatar">
                                <img class="profile-img" src="assets/students/profile.jpg" alt="Logo">
                            </div>

                            <h2>
                                <?php echo htmlspecialchars($row1['firstname'] . ' ' . $row1['lastname']); ?>
                            </h2>

                            <p>
                                <?php echo htmlspecialchars($row1['department']); ?>
                            </p>

                            <div class="coins-container">
                                <span class="ranking-badge bg-success text-white px-2 py-1">
                                    Rank <?php echo htmlspecialchars("1"); ?>
                                </span>
                                <img src="assets/img/coin.png" alt="Coins" class="coins-icon"
                                    style="width: 26px; height: 26px;">
                                <span class="coins-amount fw-bold">
                                    <?php echo htmlspecialchars($student_score); ?> Coins
                                </span>
                            </div>
                        </div>
                        <div class="container">
                            <div class="stats d-flex flex-column justify-content-center align-items-center mb-1">

                                <input type="hidden" id="totalDurationTime" value="<?php echo $totalDuration; ?>">
                                <input type="hidden" id="currentStatus" value="<?php echo $currentStatus; ?>">
                                <p><strong>IP Address:</strong> <?php echo htmlspecialchars($user_ip); ?></p>


                                <!-- Progress Bar -->
                                <div class="progress-container mt-3 bg-white shadow-sm rounded">
                                    <div id="progress-bar" class="progress-bar bg-primary rounded">
                                        <span id="time-remaining" class="text-dark position-absolute w-100"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="btns">
                                <button type="button" class="btn btn-secondary px-3 py-2 rounded" id="pause-btn"
                                    style="display: none; border-radius: 0;">
                                    <i class="fas fa-pause"></i> Pause
                                </button>
                                <button type="button" class="btn btn-success px-3 py-2 rounded" id="continue-btn"
                                    style="display: none; border-radius: 0;">
                                    <i class="fas fa-play"></i> Continue
                                </button>
                                <button id="voucher-button" type="button" class="btn btn-success px-3 py-2">
                                    <i class="bx bxs-coupon"></i> Redeem
                                </button>
                                <a href="convert_voucher.php" class="btn btn-warning px-3 py-2">
                                    <i class="bi bi-currency-exchange"></i> Exchange
                                </a>
                            </div>

                            <div class="tipPts mt-3 p-2 rounded bg-light">
                                <strong>Tip:</strong> Not enough points? Gain more by participating in quizzes!
                                <a href="take_quiz.php" class="text-decoration-none fw-bold text-primary">Play
                                    Now</a>
                            </div>
                            </>
                        </div>
                    </div>
            </section>
        </main>

        <!-- Voucher Modal -->
        <div class="modal fade" id="codeModal" tabindex="-1" aria-labelledby="codeModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-light" id="codeModalLabel">Redeem Code</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="text" id="voucher-code" name="voucher-code" class="form-control"
                            placeholder="Insert your generated voucher code here" aria-label="WIFI Code">
                        <input type="hidden" id="user-id" name="user-id" value="<?php echo $user_id; ?>">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success" id="voucher-submit">Use</button>
                    </div>
                </div>
            </div>
        </div>



        <?php include("js/scripts.php"); ?>
        <script>
            // Replace existing loading screen script with this
            document.addEventListener('DOMContentLoaded', () => {
                const loadingScreen = document.getElementById('loading-screen');

                window.addEventListener('load', () => {
                    loadingScreen.style.opacity = '0';
                    setTimeout(() => {
                        loadingScreen.style.display = 'none';
                    }, 500);
                });
            });

            // Timer and UI Controller
            class TimerController {
                constructor() {
                    this.elements = {
                        timeRemaining: document.getElementById("time-remaining"),
                        progressBar: document.getElementById("progress-bar"),
                        pauseBtn: document.getElementById("pause-btn"),
                        continueBtn: document.getElementById("continue-btn"),
                        voucherBtn: document.getElementById("voucher-button"),
                        voucherSubmit: document.getElementById("voucher-submit"),
                        voucherCode: document.getElementById("voucher-code"),
                        userId: document.getElementById("user-id")
                    };

                    this.totalDuration = parseInt(document.getElementById('totalDurationTime').value, 10);
                    this.currentStatus = document.getElementById('currentStatus').value;
                    this.interval = null;
                    this.elapsedTime = 0;

                    this.initializeUI();
                    this.setupEventListeners();
                }

                initializeUI() {
                    this.updateProgressBar(this.totalDuration);
                    this.updateButtonVisibility();
                }

                setupEventListeners() {
                    this.elements.voucherBtn.addEventListener('click', () => {
                        new bootstrap.Modal(document.getElementById('codeModal')).show();
                    });

                    this.elements.voucherSubmit.addEventListener('click', () => this.handleVoucherSubmit());
                    this.elements.pauseBtn.addEventListener('click', () => this.handlePause());
                    this.elements.continueBtn.addEventListener('click', () => this.handleContinue());
                }

                startTimer() {
                    const startTime = Date.now();
                    this.interval = setInterval(() => {
                        if (this.currentStatus !== 'inactive') {
                            this.elapsedTime = Math.floor((Date.now() - startTime) / 1000);
                            const remainingTime = this.totalDuration - this.elapsedTime;

                            if (remainingTime <= 0) {
                                this.handleTimeUp();
                            } else {
                                this.updateProgressBar(remainingTime);
                            }
                        }
                    }, 1000);
                }

                updateProgressBar(duration) {
                    const hours = Math.floor(duration / 3600);
                    const minutes = Math.floor((duration % 3600) / 60);
                    const seconds = duration % 60;

                    const timeString = [hours, minutes, seconds]
                        .map(unit => unit.toString().padStart(2, '0'))
                        .join(':');

                    this.elements.timeRemaining.innerText = timeString;
                    this.elements.progressBar.style.width = `${(duration / this.totalDuration) * 100}%`;
                }

                async updateTimerStatus(status) {
                    try {
                        const formData = new URLSearchParams();
                        formData.append('user_id', '<?php echo $user_id; ?>');
                        formData.append('timer_status', status);

                        const response = await fetch('process/update_status.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: formData
                        });

                        if (response.ok) {
                            const data = await response.json();
                            if (status === 'active') {
                                toastr["success"](data.message);
                                setTimeout(function () {
                                    location.reload();
                                }, 1000);
                            }
                            else {
                                toastr["warning"]("You have Disconnected!");
                                setTimeout(function () {
                                    location.reload();
                                }, 1000);
                            }

                        }
                    } catch (error) {
                        console.error('Error updating timer status:', error);
                        toastr["error"]("Error updating timer status".error);
                    }
                }

                updateButtonVisibility() {
                    if (this.totalDuration <= 0) {
                        this.elements.pauseBtn.style.display = "none";
                        this.elements.continueBtn.style.display = "none";
                    } else if (this.currentStatus === 'inactive') {
                        this.elements.pauseBtn.style.display = "none";
                        this.elements.continueBtn.style.display = "inline";
                    } else {
                        this.elements.pauseBtn.style.display = "inline";
                        this.elements.continueBtn.style.display = "none";
                    }
                }

                async handleVoucherSubmit() {
                    const submitBtn = this.elements.voucherSubmit;
                    const voucherCode = this.elements.voucherCode.value;
                    const userId = this.elements.userId.value;

                    try {
                        submitBtn.disabled = true;
                        submitBtn.classList.add('loading');

                        const formData = new FormData();
                        formData.append('voucher_code', voucherCode);
                        formData.append('user_id', userId);

                        const response = await fetch('process/validate_voucher.php', {
                            method: 'POST',
                            body: formData
                        });

                        const data = await response.json();

                        if (data.status === 'success') {
                            toastr["success"]("Voucher validated successfully!")
                            setTimeout(() => location.reload(), 2000);
                            bootstrap.Modal.getInstance(document.getElementById('codeModal')).hide();
                        } else {
                            toastr["error"](data.message);
                        }
                    } catch (error) {
                        console.error('Error validating voucher:', error);
                        toastr["error"]("Error validating voucher");
                    } finally {
                        submitBtn.disabled = false;
                        submitBtn.classList.remove('loading');
                    }
                }

                handleTimeUp() {
                    clearInterval(this.interval);
                    this.elements.timeRemaining.innerText = "Time's up!";
                    this.elements.pauseBtn.style.display = "none";
                    this.elements.continueBtn.style.display = "none";
                }

                handlePause() {
                    this.updateTimerStatus('inactive');
                    this.currentStatus = 'inactive';
                    clearInterval(this.interval);
                    this.elements.pauseBtn.style.display = "none";
                    this.elements.continueBtn.style.display = "inline";
                }

                handleContinue() {
                    this.updateTimerStatus('active');
                    this.currentStatus = 'active';
                    this.startTimer();
                    this.elements.pauseBtn.style.display = "inline";
                    this.elements.continueBtn.style.display = "none";
                }
            }

            // Initialize Timer Controller when DOM is loaded
            document.addEventListener("DOMContentLoaded", () => {
                const timerController = new TimerController();
                timerController.startTimer();
            });
        </script>
</body>

</html>