<div class="toggle-sidebar bottom-nav">
    <ul>
        <li class="<?= getActivePage('index.php') ?>">
            <a href="index.php">
                <img src="assets/font-icons/home.png" alt="Dashboard">
                
            </a>
        </li>
        <li class="<?= getActivePage('manage_quiz.php') ?>">
            <a href="manage_quiz.php">
                <img src="assets/font-icons/quiz.png" alt="Manage Quiz">
                
            </a>
        </li>
        <li class="<?= getActivePage('manage_rewards.php') ?>">
            <a href="manage_rewards.php">
                <img src="assets/font-icons/reward.png" alt="Manage Rewards">
                
            </a>
        </li>
        <li class="<?= getActivePage('manage_users.php') ?>">
            <a href="manage_users.php">
                <img src="assets/font-icons/users.png" alt="Manage Users">
                
            </a>
        </li>
        <li class="<?= getActivePage('manage_voucher.php') ?>">
            <a href="manage_voucher.php">
                <img src="assets/font-icons/voucher.png" alt="Manage Vouchers">
                
            </a>
        </li>
        <li class="<?= getActivePage('game/create_quiz.php') ?>">
            <a href="game/create_quiz.php">
                <i class="bx bxs-game"></i>
                
            </a>
        </li>
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
        <li class="<?= getActivePage('logout.php') ?>">
            <a href="logout.php">
                <i class="bx bx-log-out-circle"></i>
                
            </a>
        </li>
    </ul>
</div>