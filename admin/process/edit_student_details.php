<?php
session_start();
require_once '../db/dbconn.php';
header('Content-Type: application/json');

if (isset($_POST['student_id'])) {
    $student_id = $_POST['student_id'];
    $student_fname = $_POST['modalfirst_name'];
    $student_lname = $_POST['modallast_name'];
    $student_year = $_POST['modalyear_level'];
    $student_dep = $_POST['modalstudent_department'];

    $studentDetails = $conn->prepare("UPDATE students SET lastname = ?, firstname = ?, year = ?, department = ? WHERE student_id = ?");
    $studentDetails->bind_param("ssssi", $student_lname, $student_fname, $student_year, $student_dep, $student_id);

    if ($studentDetails->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Student Details Successfully Updated!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update student details. Please try again.']);
    }

} else {
    echo json_encode(['status' => 'error', 'message' => 'Student ID is Required']);
}

?>