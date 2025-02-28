<?php
include '../db/dbconn.php';
header('ContentType: application/json');

if (isset($_POST['voucherid'], $_POST['modalquantity'])) {
    $voucherid = $_POST['voucherid'];
    $modalquantity = $_POST['modalquantity'];

    $stmt = $conn->prepare("UPDATE vouchers SET quantity = ? WHERE voucher_id = ?");
    $stmt->bind_param("ii", $modalquantity, $voucherid);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo json_encode(['status' => 'success', 'message' => 'Voucher updated successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error occurred while updating the voucher.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
}
