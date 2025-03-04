<?php
session_start();
include '../db/dbconn.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get and sanitize the form data
    $voucher_name = isset($_POST['voucher_name']) ? trim(mysqli_real_escape_string($conn, $_POST['voucher_name'])) : '';
    $voucher_duration = isset($_POST['voucher_duration']) ? trim(mysqli_real_escape_string($conn, $_POST['voucher_duration'])) : '';
    $voucher_description = isset($_POST['voucher_description']) ? trim(mysqli_real_escape_string($conn, $_POST['voucher_description'])) : '';
    $voucher_quantity = isset($_POST['voucher_quantity']) ? (int)$_POST['voucher_quantity'] : 0;
    $voucher_points = isset($_POST['voucher_price']) ? (int)$_POST['voucher_price'] : 0; // Changed to points to match your table

    // Validate input
    if (empty($voucher_name) || empty($voucher_duration) || empty($voucher_description) || $voucher_quantity <= 0 || $voucher_points <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'Please fill in all required fields correctly.']);
        exit;
    }

    // Check for duplicate voucher
    $stmt = $conn->prepare("SELECT * FROM vouchers WHERE voucher_name = ?");
    if (!$stmt) {
        echo json_encode(['status' => 'error', 'message' => 'Prepare statement failed: ' . $conn->error]);
        exit;
    }

    $stmt->bind_param("s", $voucher_name);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode(['status' => 'duplicate']);
        exit;
    }
    $stmt->close();

    // Insert the new voucher into the database
    $stmt = $conn->prepare("INSERT INTO vouchers (voucher_name, voucher_description, points, quantity, duration) VALUES (?, ?, ?, ?, ?)");
    if (!$stmt) {
        echo json_encode(['status' => 'error', 'message' => 'Prepare statement failed: ' . $conn->error]);
        exit;
    }

    // Bind parameters: "ssiis" for string, string, integer, integer, string
    $stmt->bind_param("ssiis", $voucher_name, $voucher_description, $voucher_points, $voucher_quantity, $voucher_duration);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to add voucher. Error: ' . $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}

$conn->close();
