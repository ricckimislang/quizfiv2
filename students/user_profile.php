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
            <section class="section">
                <div class="profile-card">
                    <!-- Card Header -->
                    <div class="card-header">
                        <div class="menu">
                            <div class="dots1" onclick="changeProfile(event)">
                                <div class="dot1"></div>
                                <div class="dot1"></div>
                                <div class="dot1"></div>
                            </div>
                            <div class="dropdown" id="dropdown">
                                <ul>
                                    <li id="change-profile" onclick="showChangeProfileModal()">Change Profile Picture
                                    </li>
                                    <li id="change-password" onclick="changePassword()">Change Password
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="profile-img-wrapper">
                            <img class="profile-img" src="<?php echo $row1['profile_path']; ?>" alt="Profile Picture">
                        </div>
                    </div>

                    <!-- Card Content -->
                    <div class="card-content">
                        <h2 class="profile-name">
                            <?php echo htmlspecialchars($row1['firstname'] . ' ' . $row1['lastname']); ?>
                        </h2>
                        <p class="profile-role">
                            <?php echo htmlspecialchars($row1['department']); ?>
                        </p>

                        <!-- Stats Section -->
                        <div class="profile-stats">
                            <div class="stat-item">
                                <div class="stat-value">
                                    <img src="assets/img/coin.png" alt="Coins" style="width: 20px; height: 20px;">
                                    <?php echo htmlspecialchars($student_score); ?>
                                </div>
                                <div class="stat-label">Coins</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-value"><?php echo htmlspecialchars("1"); ?></div>
                                <div class="stat-label">Rank</div>
                            </div>
                        </div>

                        <!-- Timer Section -->
                        <div class="timer-section">
                            <input type="hidden" id="totalDurationTime" value="<?php echo $totalDuration; ?>">
                            <input type="hidden" id="currentStatus" value="<?php echo $currentStatus; ?>">
                            <p class="ip-address"><strong>IP:</strong> <?php echo htmlspecialchars($user_ip); ?></p>

                            <!-- Progress Bar -->
                            <div class="progress-container mt-3 bg-white shadow-sm rounded">
                                <div id="progress-bar" class="progress-bar bg-primary rounded">
                                    <span id="time-remaining" class="text-dark position-absolute w-100"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="profile-actions">
                            <button type="button" class="action-btn primary-btn" id="pause-btn" style="display: none;">
                                <i class='bx bx-pause'></i> Pause
                            </button>
                            <button type="button" class="action-btn primary-btn" id="continue-btn"
                                style="display: none;">
                                <i class='bx bx-play'></i> Continue
                            </button>
                            <button id="voucher-button" type="button" class="action-btn primary-btn">
                                <i class="bx bxs-coupon"></i> Redeem
                            </button>
                            <a href="convert_voucher.php" class="action-btn primary-btn">
                                <i class="bi bi-currency-exchange"></i> Exchange
                            </a>
                        </div>

                        <!-- Tip Section -->
                        <div class="profile-bio">
                            <strong>Tip:</strong> Not enough points? Gain more by participating in quizzes!
                            <a href="take_quiz.php" class="text-primary">Play Now</a>
                        </div>

                        <!-- Social Links -->
                        <div class="social-links">
                            <a href="javascript:void(0);" class="social-icon badge" data-tooltip="Coming Soon!..">
                                <i class='bx bxl-facebook'></i>
                            </a>
                            <a href="javascript:void(0);" class="social-icon badge" data-tooltip="Coming Soon!..">
                                <i class='bx bxl-twitter'></i>
                            </a>
                            <a href="javascript:void(0);" class="social-icon badge" data-tooltip="Coming Soon!..">
                                <i class='bx bxl-instagram'></i>
                            </a>
                        </div>

                        <!-- Achievements -->
                        <div class="achievements">
                            <div class="badge" data-tooltip="Quiz Master">
                                <i class='bx bxs-trophy' style="color: #FFD700;"></i>
                            </div>
                            <div class="badge" data-tooltip="Fast Learner">
                                <i class='bx bxs-bolt' style="color: #00BFFF;"></i>
                            </div>
                            <div class="badge" data-tooltip="Top Performer">
                                <i class='bx bxs-star' style="color: #FF6B6B;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
        <?php include_once('modal/change-profile.php'); ?>
        <?php include_once('modal/change-password.php'); ?>
        <?php include_once('modal/change_password_message.php'); ?>

        <!-- Voucher Modal -->
        <div class="modal fade" id="codeModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="codeModalLabel">Redeem Code</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="text" id="voucher-code" name="voucher-code" class="form-control"
                            placeholder="Insert your generated voucher code here">
                        <input type="hidden" id="user-id" name="user-id" value="<?php echo $user_id; ?>">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn primary-btn" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn primary-btn" id="voucher-submit">Use</button>
                    </div>
                </div>
            </div>
        </div>

        <?php include("js/scripts.php"); ?>

        <script>
            //change password message
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
        </script>
        <script>
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
                                setTimeout(function() {
                                    location.reload();
                                }, 1000);
                            } else {
                                toastr["warning"]("You have Disconnected!");
                                setTimeout(function() {
                                    location.reload();
                                }, 1000);
                            }
                        }
                    } catch (error) {
                        console.error('Error updating timer status:', error);
                        toastr["error"]("Error updating timer status");
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
                showChangePasswordMessage();
                const timerController = new TimerController();
                timerController.startTimer();
            });
        </script>
        <script>
            // Function to show the avatar selection modal
            function showChangeProfileModal() {
                const modal = new bootstrap.Modal(document.getElementById('change-profile-modal'));
                modal.show();
            }

            // Avatar Selection
            document.addEventListener("DOMContentLoaded", function() {
                const saveProfileBtn = document.getElementById('save-profile-picture');

                // Handle avatar selection
                saveProfileBtn.addEventListener('click', async function() {
                    const selectedAvatar = document.querySelector('input[name="selected_avatar"]:checked');

                    if (!selectedAvatar) {
                        toastr["warning"]("Please select an avatar");
                        return;
                    }

                    const formData = new FormData();
                    formData.append('selected_avatar', selectedAvatar.value);
                    formData.append('user_id', document.querySelector('input[name="user_id"]').value);

                    try {
                        saveProfileBtn.disabled = true;
                        saveProfileBtn.innerHTML = 'Saving...';

                        const response = await fetch('process/update_avatar.php', {
                            method: 'POST',
                            body: formData
                        });

                        const data = await response.json();

                        if (data.status === 'success') {
                            toastr["success"]("Avatar updated successfully!");
                            setTimeout(() => location.reload(), 1500);
                            bootstrap.Modal.getInstance(document.getElementById('change-profile-modal')).hide();
                        } else {
                            toastr["error"](data.message || "Error updating avatar");
                        }
                    } catch (error) {
                        console.error('Error updating avatar:', error);
                        toastr["error"]("Error updating avatar");
                    } finally {
                        saveProfileBtn.disabled = false;
                        saveProfileBtn.innerHTML = 'Save Selection';
                    }
                });
            });

            //change password
            function changePassword() {
                const modal = new bootstrap.Modal(document.getElementById('change-password-modal'));
                modal.show();
                const modal1 = bootstrap.Modal.getInstance(document.getElementById('change-password-warning-modal'));
                modal1.hide();
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
</body>

</html>