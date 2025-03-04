<?php
require_once '../db/dbconn.php';
header('Content-Type: application/json');

if (isset($_POST['voucher_id'])) {
    $voucher_id = $_POST['voucher_id'];

    $conn->begin_transaction();
    $stmt = $conn->prepare("DELETE FROM vouchers WHERE voucher_id = ?");
    $stmt->bind_param("i", $voucher_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $conn->commit();
        echo json_encode(['status' => 'success', 'message' => 'Voucher deleted successfully.']);
        $stmt->close();
    } else {
        $conn->rollback();
        echo json_encode(['status' => 'error', 'message' => 'Failed to delete voucher.']);
        $stmt->close();
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
    $stmt->close();
}
