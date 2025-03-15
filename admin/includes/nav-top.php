<div class="quizTitle">
    <div class="logo-container">
        <a href="#" class="logo d-flex align-items-center">
            <img src="assets/img/logo-quizfi.png" alt="quizfi logo">
        </a>
        <i class="bi bi-list toggle-sidebar-btn"></i>
    </div>

    <div class="nav-menu">
        <ul>
            <li class="<?= getActivePage('index.php') ?>">
                <a href="index.php">
                    <img src="assets/font-icons/home.png" alt="Home icon">
                    <span>Home</span>
                </a>
            </li>
            <li class="<?= getActivePage('manage_quiz.php') ?>">
                <a href="manage_quiz.php">
                    <img src="assets/font-icons/quiz.png" alt="Quiz icon">
                    <span>Quiz</span>
                </a>
            </li>
            <li class="<?= getActivePage('manage_rewards.php') ?>">
                <a href="manage_rewards.php">
                    <img src="assets/font-icons/reward.png" alt="Rewards icon">
                    <span>Rewards</span>
                </a>
            </li>
            <li class="<?= getActivePage('manage_users.php') ?>">
                <a href="manage_users.php">
                    <img src="assets/font-icons/users.png" alt="Users icon">
                    <span>Users</span>
                </a>
            </li>
            <li class="<?= getActivePage('manage_voucher.php') ?>">
                <a href="manage_voucher.php">
                    <img src="assets/font-icons/voucher.png" alt="Voucher icon">
                    <span>Voucher</span>
                </a>
            </li>
            <li class="<?= getActivePage('game/create_quiz.php') ?>">
                <a href="game/create_quiz.php">
                    <i class="bx bxs-game"></i>
                    <span>Game</span>
                </a>
            </li>
            <li>
                <a href="logout.php">
                    <i class="bx bx-log-out-circle" style="color: #4154f1"></i>
                </a>
            </li>
        </ul>
    </div>
</div>