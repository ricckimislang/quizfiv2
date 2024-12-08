<?php
session_start();
include 'includes/header.php';
include 'includes/session.php';



// Assuming $time_duration contains the WiFi time in seconds (not in TIME format)
$totalDuration = $time_duration; // Directly using the total time in seconds
// Assuming $time_duration contains the WiFi time in seconds (not in TIME format)
$totalDuration = $time_duration; // Directly using the total time in seconds
?>

<link rel="stylesheet" href="css/user_profile.css">
<link rel="stylesheet" href="css/sidebar.css">

<body>
    <header id="header" class="header fixed-top d-flex align-items-center">
        <?php include 'includes/nav-top.php'; ?>
        <?php include 'includes/sidebar.php'; ?>

        <main id="main" class="main" height="100vh">
            <section class="section dashboard">
                <div class="row d-flex flex-column">
                    <div class="col">
                        <div class="profile-details leaderbox d-flex">
                            <div class="col-2">
                                <img src="assets/students/profile.png" alt="profile" class="profile-img">
                            </div>
                            <div class="col-4 mx-1">
                                <span><?php echo $row1['lastname'] . ', ' . $row1['firstname']; ?></span>
                                <span class="usertype">Student</span>
                                <p><?php echo $row1['department']; ?></p>
                            </div>
                            <div class="col-6 button-group">
                                <button class="btn btn-edit"><i class="bx bx-edit"></i> Edit profile</button>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="profile-details leaderbox d-flex justify-content-center align-items-center flex-column text-center p-4 ">
                            <div class="stats d-flex flex-column justify-content-center align-items-center mb-4">
                                <h3 class="ptsContainer text-primary">Points Available: <?php echo $student_score; ?></h3>

                                <!-- Store total duration time in seconds -->
                                <input type="hidden" id="totalDurationTime" value="<?php echo $totalDuration; ?>">
                                <input type="hidden" id="currentStatus" value="<?php echo $currentStatus; ?>">
                                <p><strong>IP Address:</strong> <?php echo htmlspecialchars($user_ip); ?></p> <!-- Display IP Address -->

                                <!-- Progress Bar -->
                                <div class="progress-container mt-3 bg-white shadow-sm rounded">
                                    <div id="progress-bar" class="progress-bar bg-primary rounded">
                                        <span id="time-remaining" class="text-dark position-absolute w-100"></span>
                                    </div>
                                </div>

                            </div>

                            <div class="btns my-4">
                                <button id="voucher-button" type="button" class="btn btn-success me-2 px-3 py-2">
                                    <span class="fas fa-wifi icon-inline"></span> Use Wifi Voucher
                                </button>
                                <a href="convert_voucher.php" class="btn btn-warning px-3 py-2">
                                    <i class="fas fa-ticket-alt icon-inline"></i> Convert Points to Voucher
                                </a>
                            </div>
                            <button type="button" class="btn btn-secondary px-3 py-2 me-2" id="pause-btn" style="display: none;">
                                <i class="fas fa-pause"></i> Pause
                            </button>
                            <button type="button" class="btn btn-success px-3 py-2" id="continue-btn" style="display: none;">
                                <i class="fas fa-play"></i> Continue
                            </button>

                            <div class="tipPts mt-3 p-2 rounded bg-light text-secondary">
                                <strong>Tip:</strong> Not enough points? Gain more by participating in quizzes!
                                <a href="take_quiz.php" class="text-decoration-none fw-bold text-primary">Play Now</a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <div class="modal fade" id="codeModal" tabindex="-1" aria-labelledby="codeModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-dark" id="codeModalLabel">Insert WIFI Code</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="text" id="voucher-code" name="voucher-code" class="form-control" placeholder="Insert your generated WIFI code here">
                        <input type="hidden" id="user-id" name="user-id" class="form-control" value="<?php echo $user_id; ?>">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-success" id="voucher-submit">Use</button>
                    </div>
                </div>
            </div>
        </div>

        <?php include 'js/scripts.php'; ?>

        <script>
            document.getElementById('voucher-button').addEventListener('click', () => {
                new bootstrap.Modal(document.getElementById('codeModal')).show();
            });
            document.getElementById('voucher-submit').addEventListener('click', function() {
                const voucherCode = document.getElementById('voucher-code').value;
                const userId = document.getElementById('user-id').value;

                // Disable the button to prevent multiple submissions
                const submitButton = document.getElementById('voucher-submit');
                submitButton.disabled = true;

                $.ajax({
                    url: 'process/validate_voucher.php',
                    type: 'POST',
                    data: {
                        voucher_code: voucherCode,
                        user_id: userId,
                    },
                    dataType: 'json', // Expecting JSON response
                    success: function(data) {
                        if (data.status === 'success') {
                            $.jGrowl('Voucher validated successfully. Total time updated.', {
                                life: 1000,
                                theme: 'alert alert-success',
                                position: 'top-right'
                            });
                            setTimeout(function() {
                                // Your code to execute after a timeout
                                location.reload();
                            }, 1000); // 1 second timeout
                            // Close the modal
                            bootstrap.Modal.getInstance(document.getElementById('codeModal')).hide();
                        } else {
                            alert(data.message);
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        alert('An error occurred: ' + textStatus);
                    },
                    complete: function() {
                        // Re-enable the submit button after the AJAX request completes
                        submitButton.disabled = false;
                    }
                });
            });

            document.addEventListener("DOMContentLoaded", function() {
                const timeRemainingElement = document.getElementById("time-remaining");
                const progressBarElement = document.getElementById("progress-bar");
                const pauseBtn = document.getElementById("pause-btn");
                const continueBtn = document.getElementById("continue-btn");

                let totalDuration = parseInt(document.getElementById('totalDurationTime').value, 10);
                let currentStatus = document.getElementById('currentStatus').value;
                let interval, updateInterval;
                let elapsedTime = 0;

                updateProgressBar(totalDuration);

                // Function to start the timer
                function startTimer() {
                    let startTime = Date.now();
                    interval = setInterval(function() {
                        if (currentStatus !== 'inactive') {
                            elapsedTime = Math.floor((Date.now() - startTime) / 1000);
                            let remainingTime = totalDuration - elapsedTime;

                            if (remainingTime <= 0) {
                                clearInterval(interval);
                                clearInterval(updateInterval);
                                updateDatabaseTimer(0);
                                timeRemainingElement.innerText = "Time's up!";
                                pauseBtn.style.display = "none";
                                continueBtn.style.display = "none";
                            } else {
                                updateProgressBar(remainingTime);
                            }
                        }
                    }, 1000);


                }

                // Update the progress bar
                function updateProgressBar(duration) {
                    let hours = Math.floor(duration / 3600);
                    let minutes = Math.floor((duration % 3600) / 60);
                    let seconds = duration % 60;

                    let timeString =
                        (hours < 10 ? '0' : '') + hours + ':' +
                        (minutes < 10 ? '0' : '') + minutes + ':' +
                        (seconds < 10 ? '0' : '') + seconds;

                    timeRemainingElement.innerText = timeString;

                    let percentage = (duration / totalDuration) * 100;
                    progressBarElement.style.width = percentage + "%";
                }

                // Update the timer status in the database
                function updateTimerStatus(status) {
                    const xhr = new XMLHttpRequest();
                    xhr.open("POST", "process/update_status.php", true);
                    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                    xhr.onload = function() {
                        if (xhr.status === 200) {
                            $(document).ready(function() {
                                // Example of showing a jGrowl notification
                                $.jGrowl(currentStatus === 'inactive' ? "You are now disconnected" : "You are now connected", {
                                    life: 5000, // Display for 5 seconds
                                    theme: 'alert alert-success', // Optional: specify a theme
                                    position: 'top-right' // Optional: specify position
                                });
                                setTimeout(function() {
                                    // Your code to execute after a timeout
                                    console.log("Timer Status Changed");
                                    location.reload();
                                }, 1000); // 3 seconds timeout
                            });
                        }
                    };
                    xhr.send("user_id=<?php echo $user_id; ?>&timer_status=" + status);
                }

                // Set button visibility based on current status
                if (totalDuration <= 0) {
                    pauseBtn.style.display = "none";
                    continueBtn.style.display = "none";
                } else if (currentStatus === 'inactive') {
                    pauseBtn.style.display = "none";
                    continueBtn.style.display = "inline";
                } else {
                    pauseBtn.style.display = "inline";
                    continueBtn.style.display = "none";
                }

                // Pause button functionality
                pauseBtn.addEventListener("click", function() {
                    updateTimerStatus('inactive');
                    currentStatus = 'inactive';
                    clearInterval(interval);
                    pauseBtn.style.display = "none";
                    continueBtn.style.display = "inline";
                });

                // Continue button functionality
                continueBtn.addEventListener("click", function() {
                    updateTimerStatus('active');
                    currentStatus = 'active';
                    startTimer();
                    pauseBtn.style.display = "inline";
                    continueBtn.style.display = "none";
                });

                startTimer();
            });
        </script>
</body>