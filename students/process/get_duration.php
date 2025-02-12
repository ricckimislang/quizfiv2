<?php
session_start();
include '../includes/session.php'; // Include your session management
include '../../db/dbconn.php'; // Include your database connection

if (isset($_POST['voucher_code'])) {
    $voucher_code = $_POST['voucher_code'];

    // Prepare SQL query to retrieve the duration
    $stmt = $conn->prepare("SELECT duration FROM vouchers WHERE voucher_code = ? LIMIT 1");
    $stmt->bind_param("s", $voucher_code);
    $stmt->execute();
    $stmt->bind_result($duration);
    $stmt->fetch();
    $stmt->close();

    // Return the duration in a JSON format
    echo json_encode(['status' => 'success', 'duration' => $duration]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'No voucher code provided.']);
}
