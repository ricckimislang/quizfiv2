<?php
session_start();
include '../../db/dbconn.php'; // Ensure your database connection is included

header('Content-Type: application/json');

if (isset($_POST['voucher_id'], $_POST['user_id'])) {
    $voucherId = $_POST['voucher_id'];
    $userId = $_POST['user_id'];

    // Fetch voucher details
    $getVoucherQuery = "SELECT points, duration, quantity FROM vouchers WHERE voucher_id = ?";
    $getVoucherStmt = $conn->prepare($getVoucherQuery);
    $getVoucherStmt->bind_param('i', $voucherId);
    $getVoucherStmt->execute();
    $voucherResult = $getVoucherStmt->get_result();
    $voucher = $voucherResult->fetch_assoc();

    if (!$voucher) {
        echo json_encode(['status' => 'error', 'message' => 'Voucher not found.']);
        exit;
    }

    $requiredPoints = $voucher['points'];
    $duration = $voucher['duration'];
    $quantity = $voucher['quantity'];

    // Fetch user's current score
    $getUserScoreQuery = "SELECT score FROM students WHERE user_id = ?";
    $getUserScoreStmt = $conn->prepare($getUserScoreQuery);
    $getUserScoreStmt->bind_param('i', $userId);
    $getUserScoreStmt->execute();
    $userResult = $getUserScoreStmt->get_result();
    $user = $userResult->fetch_assoc();

    if (!$user) {
        echo json_encode(['status' => 'error', 'message' => 'User not found.']);
        exit;
    }

    $userScore = $user['score'];

    // Check if user has enough points
    if ($userScore < $requiredPoints) {
        echo json_encode(['status' => 'error', 'message' => 'Insufficient points to purchase this voucher.']);
        exit;
    }

    // Check voucher quantity before deducting
    if ($quantity <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'Voucher is out of stock.']);
        exit;
    }

    // Deduct voucher quantity
    $updateQuantityQuery = "UPDATE vouchers SET quantity = quantity - 1 WHERE voucher_id = ?";
    $updateStmt = $conn->prepare($updateQuantityQuery);
    $updateStmt->bind_param('i', $voucherId);

    if ($updateStmt->execute() && $updateStmt->affected_rows > 0) {
        // Deduct user score after successful quantity deduction
        $newScore = $userScore - $requiredPoints;
        $updateScoreQuery = "UPDATE students SET score = ? WHERE user_id = ?";
        $updateScoreStmt = $conn->prepare($updateScoreQuery);
        $updateScoreStmt->bind_param('ii', $newScore, $userId);
        $updateScoreStmt->execute();

        // Generate a random code for the Wi-Fi voucher
        $voucherCode = strtoupper(bin2hex(random_bytes(10)));

        // Insert the generated code into purchased_vouchers table
        $insertCodeQuery = "INSERT INTO purchased_vouchers (user_id, voucher_id, wifi_code, duration, status) VALUES (?, ?, ?, ?, 'unused')";
        $insertStmt = $conn->prepare($insertCodeQuery);
        $insertStmt->bind_param('iiss', $userId, $voucherId, $voucherCode, $duration);
        $insertStmt->execute();

        echo json_encode(['status' => 'success', 'voucher_code' => $voucherCode]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error occurred while processing the voucher purchase.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
}
