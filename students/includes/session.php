<?php
include 'db/dbconn.php';

// Ensure user_id is set in the session
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php"); // Redirect to login if user is not logged in
    exit();
}

$user_id = $_SESSION['user_id'];

// Use a prepared statement to prevent SQL injection
$stmt = $conn->prepare("SELECT * FROM students WHERE user_id = ?");
if ($stmt === false) {
    die('Prepare failed: ' . htmlspecialchars($conn->error)); // Error handling
}

$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die('No student found for this user.'); // Error handling for no results
}

$row1 = $result->fetch_assoc();

// Use null coalescing operator to handle potential null values
$studentName = ($row1['firstname'] ?? '') . ' ' . ($row1['lastname'] ?? '');
$student_score = $row1['score'] ?? 0;
$studentId = $row1['student_id'] ?? null;
$user_id = $row1['user_id'] ?? null;
$time_duration = $row1['duration'] ?? 0;
$currentStatus = $row1['status'] ?? 'unknown';
$profile_pic = $row1['profile_path'] ?? 'none';

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

// Update the IP address in the database
$addIp = $conn->prepare("UPDATE students SET ip_address = ? WHERE user_id = ?");
if ($addIp === false) {
    die('Prepare failed: ' . htmlspecialchars($conn->error)); // Error handling
}

$addIp->bind_param('si', $user_ip, $user_id);
if (!$addIp->execute()) {
    die('Execute failed: ' . htmlspecialchars($addIp->error)); // Error handling
}

// Optionally, you can close the statement and connection
$stmt->close();
$addIp->close();
