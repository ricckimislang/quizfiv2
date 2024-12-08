<?php
include 'db/dbconn.php';
// Ensure user_id is set in the session
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php"); // Redirect to login if user is not logged in
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = "SELECT * FROM students WHERE user_id = ?";
$stmt = $conn->prepare($stmt);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row1 = $result->fetch_assoc();

$studentName = $row1['firstname'] . ' ' . $row1['lastname'];
$student_score = $row1['score'];
$studentId = $row1['student_id'];
$user_id = $row1['user_id'];
$time_duration = $row1['duration'];
$currentStatus = $row1['status'];
$ip_address = $_SERVER['REMOTE_ADDR'];

function getUserIP()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        return $_SERVER['REMOTE_ADDR'];
    }
}

$user_ip = getUserIP();


$addIp = $conn->prepare("UPDATE students SET ip_address = ? WHERE user_id = ?");
$addIp->bind_param('si', $user_ip, $user_id);
$addIp->execute();
