<?php
session_start();
include_once '../db/dbconn.php';
header('Content-Type: application/json');

if (isset($_POST['educator_id2'])) {
    $educator_id = $_POST['educator_id2'];
    $educator_account_email = $_POST['educator_account_email'];
    $educator_account_user = $_POST['educator_account_user'];

    $educatorUser = $conn->prepare("SELECT user_id FROM educators WHERE educator_id = ?");
    $educatorUser->bind_param("i", $educator_id);
    $educatorUser->execute();
    $educatorUserResult = $educatorUser->get_result();
    $educatorUserRow = $educatorUserResult->fetch_assoc();
    $educator_user_id = $educatorUserRow['user_id'];

    $educatorAccount = $conn->prepare("UPDATE user SET username = ?, user_email = ? WHERE user_id = ?");
    $educatorAccount->bind_param("ssi", $educator_account_user, $educator_account_email, $educator_user_id);
    $educatorAccount->execute();
    $educatorAccount->close();

    if ($educatorAccount) {
        $response = array("status" => "success", "message" => "Educator Profile updated successfully.");
        echo json_encode($response);
    } else {
        $response = array("status" => "error", "message" => "Failed to update educator profile. Please try again.");
        echo json_encode($response);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Educator ID is required']);
}
