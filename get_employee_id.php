<?php
session_start();
$response = ['success' => false, 'employee_id' => null];

if (isset($_SESSION['employee_id'])) {
    $response = ['success' => true, 'employee_id' => $_SESSION['employee_id']];
} else {
    $response['error'] = 'Employee ID not found in session.';
}

echo json_encode($response);
?>
