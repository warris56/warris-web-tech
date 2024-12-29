<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'SwiftPay');

if ($conn->connect_error) {
    die(json_encode(['error' => "Connection failed: " . $conn->connect_error]));
}

$response = ['success' => false, 'error' => 'Invalid employee ID.'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $employee_id = $_POST['employee_id'];
    $session_employee_id = $_SESSION['employee_id'];

    // Verify the employee ID
    if ($employee_id == $session_employee_id) {
        $_SESSION['verified'] = true;
        $response = ['success' => true];
    } else {
        $response['error'] = 'Employee ID does not match.';
    }
}

echo json_encode($response);
$conn->close();
?>
