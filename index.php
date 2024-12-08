<?php include("includes/head.php"); ?>
<!DOCTYPE html>
<html lang="en">

<body id="page-top" class="quiz-platform">
    <!-- Navigation-->
    <?php include("includes/nav.php"); ?>

    <!-- Masthead-->
    <?php include("includes/header.php"); ?>

    <!-- New Quiz Categories Section -->
    <section class="quiz-categories container my-5">
        <div class="row">
            <div class="col-12 text-center mb-4">
                <h2 class="section-title">Explore Our Quiz Categories</h2>
            </div>
        </div>
        <div class="row g-4">
            <!-- Category Cards -->
            <div class="col-md-4">
                <div class="category-card card h-100 shadow-sm">
                    <div class="card-body text-center">
                        <i class="fas fa-flask category-icon mb-3"></i>
                        <h5 class="card-title">Science Quizzes</h5>
                        <p class="card-text text-muted">Challenge your scientific knowledge</p>
                        <a href="#" class="btn btn-primary mt-3">Start Quiz</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="category-card card h-100 shadow-sm">
                    <div class="card-body text-center">
                        <i class="fas fa-globe category-icon mb-3"></i>
                        <h5 class="card-title">Geography Quizzes</h5>
                        <p class="card-text text-muted">Explore the world through quizzes</p>
                        <a href="#" class="btn btn-primary mt-3">Start Quiz</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="category-card card h-100 shadow-sm">
                    <div class="card-body text-center">
                        <i class="fas fa-history category-icon mb-3"></i>
                        <h5 class="card-title">History Quizzes</h5>
                        <p class="card-text text-muted">Discover historical facts</p>
                        <a href="#" class="btn btn-primary mt-3">Start Quiz</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer-->
    <footer class="footer bg-dark text-white text-center py-3">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <p class="mb-0">
                        &copy; <?php echo date('Y'); ?> Rek Bords Quiz Platform
                        | <a href="#" class="text-white">Privacy Policy</a>
                        | <a href="#" class="text-white">Terms of Service</a>
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="js/main.js"></script>
</body>

</html>