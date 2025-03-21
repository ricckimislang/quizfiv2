<div class="quizTitle">
    <div class="logo-container">
        <a href="#" class="logo d-flex align-items-center">
            <img src="assets/img/logo-quizfi.png" alt="quizfi logo">
        </a>
        <i class="bi bi-list toggle-sidebar-btn"></i>
    </div>


    <div class="nav-menu">
        <ul>
            <li class="<?= getActivePage('manage_quiz.php') ?>"><a href="manage_quiz.php"><img
                        src="assets/font-icons/quiz.png" alt="">&nbsp;&nbsp;Quiz</a></li>
            <li class="<?= getActivePage('classroom.php') ?>"><a href="classroom.php"><i class="bx bx-chalkboard" style="color:rgb(34, 255, 0)"></i>&nbsp;Classroom</a></li>
            <li>
                <a href="#" id="settings-icon" onclick="toggleSettings(event)">
                    <i class="bx bx-cog" style="color: #4154f1"></i>
                </a>
                <div class="settings-dropdown" id="settings-dropdown">
                    <ul>
                        <li>
                            <a href="#" onclick="changePassword()">
                                <i class="bx bx-key me-2"></i>Change Password
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li><a href="logout.php"><i class='bx bx-log-out-circle' style="color: #4154f1"></i></a></li>

        </ul>
    </div>
</div><!-- End Logo -->
<script>
    function toggleSettings(event) {
        event.preventDefault();
        const dropdown = document.getElementById('settings-dropdown');
        dropdown.classList.toggle('show');
    }

    // Close the dropdown if the user clicks outside of it
    window.onclick = function(event) {
        const dropdown = document.getElementById('settings-dropdown');
        if (!event.target.matches('#settings-icon') && !dropdown.contains(event.target)) {
            dropdown.classList.remove('show');
        }
    }
</script>
<style>
    .settings-dropdown {
        border-radius: 5px;
        background: linear-gradient(135deg, #1a1a1d 0%, #4e4e50 100%);
        display: none;
        position: absolute;
        left: -20px;
        background-color: black;
        box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
        z-index: 1;
    }

    .settings-dropdown.show {
        display: block;
    }
</style>