<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>QuizFi</title>
    <meta content="QuizFi - Your ultimate quiz platform" name="description">
    <meta content="QuizFi, quiz, platform, online quiz, quiz questions" name="keywords">

    <!-- Favicons -->
    <link href="../assets/img/logo-quizfi.png" rel="icon" type="image/jpg">

    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700&family=Nunito:wght@300;400;600;700&family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">

    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="js/jquery-3.6.0.min.js"></script>
    <script src="js/jquery.jgrowl.min.js"></script>
    <script src="js/sweetalert2@11.js"></script>
    <script src="js/toastr.min.js"></script>



    <link href="css/style.css" rel="stylesheet">
    <link href="css/nav-top.css" rel="stylesheet">
    <link rel="stylesheet" href="css/toastr.css">
    <link rel="stylesheet" href="css/bubble.css">


</head>
<?php
function getActivePage($pageName)
{
    return (basename($_SERVER['PHP_SELF']) == $pageName) ? 'active' : '';
}
?>