<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'SwiftPay');

if ($conn->connect_error) {
    die(json_encode(['error' => "Connection failed: " . $conn->connect_error]));
}

$response = ['success' => false, 'error' => 'Invalid username or password.'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if the user is an admin
    $sql = "SELECT * FROM admins WHERE username='$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            session_regenerate_id(true);
            $_SESSION['user'] = $username;
            $_SESSION['role'] = 'admin';
            $response = ['success' => true, 'role' => 'admin'];
        }
    }

    // Check if the user is an employee
    if (!$response['success']) { // Only check if not already successful
        $sql = "SELECT * FROM employee_logins WHERE username='$username'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                session_regenerate_id(true);
                $_SESSION['user'] = $username;
                $_SESSION['role'] = 'employee';
                $_SESSION['employee_id'] = $row['employee_id'];

                // Fetch the employee details
                $employee_sql = "SELECT * FROM employees WHERE id='" . $row['employee_id'] . "'";
                $employee_result = $conn->query($employee_sql);
                if ($employee_result->num_rows > 0) {
                    $_SESSION['employee'] = $employee_result->fetch_assoc();
                    $response = ['success' => true, 'role' => 'employee'];
                }
            }
        }
    }

    if (!$response['success']) {
        $_SESSION['error'] = "Invalid username or password.";
    }
}

echo json_encode($response);
$conn->close();
?>
