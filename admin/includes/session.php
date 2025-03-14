<?php

// Ensure user_id is set in the session
if (!isset($_SESSION['user_id'])) {
    header("Location: ../students/index.php"); // Redirect to login if user is not logged in
    exit();
}

$user_id = $_SESSION['user_id'];
// Function to get user IP address
function getUserIP()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    return $_SERVER['REMOTE_ADDR'];
}

$user_ip = getUserIP();

?>
<script>
    console.log("User ID: <?php echo $user_id; ?>");
</script>