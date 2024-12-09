<?php
session_start();
include '../db/dbconn.php'; // Ensure your database connection is included

header('Content-Type: application/json');

if (isset($_POST['voucher_code'], $_POST['user_id'])) {
    $voucherCode = $_POST['voucher_code'];
    $userId = $_POST['user_id'];

    // Fetch voucher details using the code
    $getVoucherQuery = "SELECT * FROM purchased_vouchers WHERE wifi_code = ? AND user_id = ?";
    $getVoucherStmt = $conn->prepare($getVoucherQuery);
    $getVoucherStmt->bind_param('si', $voucherCode, $userId);
    $getVoucherStmt->execute();
    $voucherResult = $getVoucherStmt->get_result();
    $voucher = $voucherResult->fetch_assoc();

    // Check if voucher exists and is unused
    if (!$voucher) {
        echo json_encode(['status' => 'error', 'message' => 'Voucher not found or does not belong to user.']);
        exit;
    }

    if ($voucher['status'] !== 'unused') {
        echo json_encode(['status' => 'error', 'message' => 'Voucher is already used or expired.']);
        exit;
    }

    // Get the voucher duration and convert it to seconds
    $voucherDuration = $voucher['duration']; // Assuming duration is in TIME format (hh:mm:ss)
    list($vHours, $vMinutes, $vSeconds) = explode(':', $voucherDuration);
    $voucherDurationInSec = ($vHours * 3600) + ($vMinutes * 60) + $vSeconds;

    // Get the current duration of the user
    $getCurrentDurationQuery = "SELECT duration FROM students WHERE user_id = ?";
    $getCurrentDurationStmt = $conn->prepare($getCurrentDurationQuery);
    $getCurrentDurationStmt->bind_param('i', $userId);
    $getCurrentDurationStmt->execute();
    $currentDurationResult = $getCurrentDurationStmt->get_result();
    $currentDuration = $currentDurationResult->fetch_assoc();

    if (!$currentDuration) {
        echo json_encode(['status' => 'error', 'message' => 'User not found.']);
        exit;
    }

    // Convert the current duration to seconds (already stored as seconds)
    $currentDurationInSec = $currentDuration['duration'];

    // Calculate the new total time in seconds
    $newTotalDurationInSec = $currentDurationInSec + $voucherDurationInSec;

    // Begin a transaction to ensure atomicity
    $conn->begin_transaction();

    try {
        // Mark the voucher as used and set the start time
        $updateStatusQuery = "UPDATE purchased_vouchers SET status = 'used', start_time = NOW() WHERE wifi_code = ? AND user_id = ?";
        $updateStatusStmt = $conn->prepare($updateStatusQuery);
        $updateStatusStmt->bind_param('si', $voucherCode, $userId);
        $updateStatusStmt->execute();

        // Update the user's duration in the database
        $updateDurationQuery = "UPDATE students SET duration = ?, status = 'active' WHERE user_id = ?";
        $updateDurationStmt = $conn->prepare($updateDurationQuery);
        $updateDurationStmt->bind_param('ii', $newTotalDurationInSec, $userId);
        $updateDurationStmt->execute();

        // Commit transaction
        $conn->commit();

        echo json_encode([
            'status' => 'success',
            'message' => 'Voucher validated successfully.',
        ]);
    } catch (Exception $e) {
        // Roll back transaction if something goes wrong
        $conn->rollback();
        echo json_encode(['status' => 'error', 'message' => 'Failed to update voucher status.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
}
