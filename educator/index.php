<?php
session_start();
include_once 'includes/header.php';
include_once 'includes/session.php';
include_once 'db/dbconn.php';
?>

<body>
    <header id="header" class="header fixed-top d-flex align-items-center">
        <?php include_once 'includes/nav-top.php'; ?>
    </header><!-- End Header -->

    <main id="main" class="main">
        <?php include_once 'includes/mobile-nav.php'; ?>
        <input type="hidden" id="user-id" value="<?php echo $user_id; ?>">
        <div class="container mt-4">
            <!-- Your content here -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body p-4">
                            <div class="welcome d-flex align-items-center justify-content-start">
                                <div>
                                    <h2 class="mb-1">Welcome, Educator!</h2>
                                    <p class="mb-0">
                                        <?php echo date('l, F d, Y'); ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dashboard content -->
        </div>
    </main><!-- End #main -->

   

    <script src="js/main.js"></script>
</body>

</html>