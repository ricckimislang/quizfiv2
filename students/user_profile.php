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
            <section class="section dashboard">
                <div class="row d-flex flex-column">
                    <!-- Profile Section -->
                    <div class="col">
                        <div class="profile-details leaderbox p-4">
                            <div class="col-2 text-center">
                                <img src="assets/students/profile.png" alt="Student Profile" class="profile-img">
                            </div>
                            <div class="col-4 mx-1">
                                <span
                                    class="student-name fw-bold fs-5"><?php echo htmlspecialchars($row1['firstname'] . ' ' . $row1['lastname']); ?></span>
                                <p class="department text-muted"><?php echo htmlspecialchars($row1['department']); ?>
                                </p>
                            </div>
                            <div class="col-6 button-group text-end">
                                <button class="btn btn-edit" id="edit-profile-btn">
                                    <i class="bx bx-edit"></i> Edit Profile
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Stats Section -->
                    <div class="col">
                        <div
                            class="profile-details leaderbox d-flex justify-content-center align-items-center flex-column text-center p-4">
                            <div class="stats d-flex flex-column justify-content-center align-items-center mb-1">
                                <h3 class="ptsContainer text-primary">Available Points: <?php echo $student_score; ?>
                                </h3>

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

                            <div class="btns my-4">
                                <button id="voucher-button" type="button" class="btn btn-success me-2 px-3 py-2"
                                    style="border-radius: 0;">
                                    <span class="fas fa-wifi icon-inline"></span> Redeem Voucher
                                </button>
                                <a href="convert_voucher.php" class="btn btn-warning px-3 py-2"
                                    style="border-radius: 0;">
                                    <i class="fas fa-ticket-alt icon-inline"></i> Exchange Points for Voucher
                                </a>
                            </div>

                            <button type="button" class="btn btn-secondary px-3 py-2 me-2" id="pause-btn"
                                style="display: none; border-radius: 0;">
                                <i class="fas fa-pause"></i> Pause
                            </button>
                            <button type="button" class="btn btn-success px-3 py-2" id="continue-btn"
                                style="display: none; border-radius: 0;">
                                <i class="fas fa-play"></i> Continue
                            </button>

                            <div class="tipPts mt-3 p-2 rounded bg-light">
                                <strong>Tip:</strong> Not enough points? Gain more by participating in quizzes!
                                <a href="take_quiz.php" class="text-decoration-none fw-bold text-primary">Play Now</a>
                            </div>
                        </div>
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
                        <!-- Alerts -->
                        <div class="alert alert-success alert-dismissible fade show" role="alert"
                            style="display: none;">
                            <i class="bi bi-check-circle me-1"></i>
                            <span class="alert-text"></span>
                        </div>

                        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="display: none;">
                            <i class="bi bi-exclamation-octagon me-1"></i>
                            <span class="alert-text"></span>
                        </div>
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
            // Alert Handling
            function showAlert(type, message) {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(alert => alert.style.display = 'none');

                const alert = document.querySelector(`.alert-${type}`);
                if (alert) {
                    alert.querySelector('.alert-text').textContent = message;
                    alert.style.display = 'block';

                    if (type === 'success') {
                        setTimeout(() => alert.style.display = 'none', 5000);
                    } else {
                        setTimeout(() => alert.style.display = 'none', 3000);
                    }
                }
            }

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
                            $.jGrowl(
                                status === 'inactive' ? "You are now disconnected" : "You are now connected", {
                                life: 3000,
                                theme: 'alert alert-success',
                                position: 'top-right'
                            }
                            );
                            setTimeout(() => location.reload(), 1000);
                        }
                    } catch (error) {
                        console.error('Error updating timer status:', error);
                        $.jGrowl("Error updating status", {
                            theme: 'alert alert-danger',
                            position: 'top-right'
                        });
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
                            showAlert('success', 'Voucher validated successfully.');
                            setTimeout(() => location.reload(), 2000);
                            bootstrap.Modal.getInstance(document.getElementById('codeModal')).hide();
                        } else {
                            showAlert('danger', data.message);
                        }
                    } catch (error) {
                        console.error('Error validating voucher:', error);
                        $.jGrowl('An error occurred while validating the voucher', {
                            theme: 'alert alert-danger',
                            position: 'top-right'
                        });
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