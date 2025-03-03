<?php
session_start();
include_once '../db/dbconn.php';
header('Content-Type: application/json');

if (isset($_POST['educator_id'])) {
    $educator_id = $_POST['educator_id'];
    $educator_firstname = $_POST['educator_firstname'];
    $educator_lastname = $_POST['educator_lastname'];
    $educator_department = $_POST['educator_department'];

    $educatorProfile = $conn->prepare("UPDATE educators SET firstname = ?, lastname = ?, department = ? WHERE educator_id = ?");
    $educatorProfile->bind_param("sssi", $educator_firstname, $educator_lastname, $educator_department, $educator_id);
    $educatorProfile->execute();
    $educatorProfile->close();

    if ($educatorProfile) {
        $response = array("status" => "success", "message" => "Educator Profile updated successfully.");
        echo json_encode($response);
    } else {
        $response = array("status" => "error", "message" => "Failed to update educator profile. Please try again.");
        echo json_encode($response);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Educator ID is required']);
}

?>