<?php
require_once("../db/dbconn.php");
session_start();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $classroom_name = $_POST['classroom-name']; // Ensure the input name matches this
    $user_id = $_SESSION['user_id']; // Ensure the input name matches this

    try {
        // Start a transaction
        $conn->begin_transaction();

        // Check if the user already has 4 classrooms
        $checkStmt = $conn->prepare("SELECT COUNT(*) AS classroom_count FROM classroom WHERE user_id = ?");
        $checkStmt->bind_param('i', $user_id);
        $checkStmt->execute();
        $result = $checkStmt->get_result();
        $row = $result->fetch_assoc();

        if ($row['classroom_count'] >= 4) {
            throw new Exception('You have already created 4 classrooms.');
        }

        // Check if the classroom name is already taken by the user
        $nameCheckStmt = $conn->prepare("SELECT 1 FROM classroom WHERE user_id = ? AND classroom_name = ?");
        $nameCheckStmt->bind_param('is', $user_id, $classroom_name);
        $nameCheckStmt->execute();
        $nameCheckStmt->store_result();

        if ($nameCheckStmt->num_rows > 0) {
            throw new Exception('Classroom name is already taken.');
        }
        $nameCheckStmt->close();

        // Function to generate a unique random classroom code
        function generateUniqueCode($conn)
        {
            do {
                $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $classroomCode = '';
                for ($i = 0; $i < 10; $i++) {
                    $classroomCode .= $characters[rand(0, strlen($characters) - 1)];
                }

                // Check if the code already exists
                $codeCheckStmt = $conn->prepare("SELECT 1 FROM classroom WHERE classroom_code = ?");
                $codeCheckStmt->bind_param('s', $classroomCode);
                $codeCheckStmt->execute();
                $codeCheckStmt->store_result();
            } while ($codeCheckStmt->num_rows > 0); // Repeat until a unique code is found

            $codeCheckStmt->close();
            return $classroomCode;
        }

        // Generate a unique classroom code
        $classroomCode = generateUniqueCode($conn);

        // Prepare and bind parameters to insert the new classroom
        $stmt = $conn->prepare("INSERT INTO classroom (user_id, classroom_name, classroom_code) VALUES (?, ?, ?)");
        if ($stmt === false) {
            throw new Exception('Error preparing statement.');
        }

        $stmt->bind_param('iss', $user_id, $classroom_name, $classroomCode);

        // Execute and check if insert was successful
        if (!$stmt->execute()) {
            throw new Exception('Error creating classroom.');
        }

        // Commit the transaction
        $conn->commit();

        $response = array(
            'status' => 'success',
            'message' => 'Classroom created successfully',
            'classroom_code' => $classroomCode
        );
    } catch (Exception $e) {
        // Rollback the transaction in case of error
        $conn->rollback();
        $response = array('status' => 'error', 'message' => $e->getMessage());
    } finally {
        // Close the statement and connection
        if (isset($stmt)) $stmt->close();
        $conn->close();
    }

    echo json_encode($response);
} else {
    // Handle invalid request method
    $response = array('status' => 'error', 'message' => 'Invalid request method.');
    echo json_encode($response);
}
