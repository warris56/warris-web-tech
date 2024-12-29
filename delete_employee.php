<?php
session_start();
ob_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$conn = new mysqli('localhost', 'root', '', 'SwiftPay');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])) {
    $id = $_GET['id'];

    if (empty($id)) {
        echo "Error: Employee ID is required.";
        exit();
    }

    // First, delete related records in the employee_logins table
    $sql = "DELETE FROM employee_logins WHERE employee_id=?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        echo "Error preparing employee_logins delete statement: " . $conn->error;
        exit();
    }
    $stmt->bind_param("i", $id);

    if (!$stmt->execute()) {
        echo "Error deleting employee login records: " . $stmt->error;
        exit();
    }
    $stmt->close();

    // Then, delete related records in the payslips table (if applicable)
    $sql = "DELETE FROM payslips WHERE employee_id=?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        echo "Error preparing payslips delete statement: " . $conn->error;
        exit();
    }
    $stmt->bind_param("i", $id);

    if (!$stmt->execute()) {
        echo "Error deleting payslip records: " . $stmt->error;
        exit();
    }
    $stmt->close();

    // Now delete the employee record
    $sql = "DELETE FROM employees WHERE id=?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        echo "Error preparing employee delete statement: " . $conn->error;
        exit();
    }
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // Log the delete action
        $admin_id = $_SESSION['admin_id'] ?? 'unknown';
        $action_type = 'delete_employee';
        $action_details = "Deleted employee ID: $id";
        
        $log_sql = "INSERT INTO audit_logs (user_type, user_id, action_type, action_details) VALUES ('admin', ?, ?, ?)";
        $log_stmt = $conn->prepare($log_sql);
        if ($log_stmt === false) {
            echo "Error preparing log statement: " . $conn->error;
            exit();
        }
        $log_stmt->bind_param("sss", $admin_id, $action_type, $action_details);
        $log_stmt->execute();

        // Redirect to view_employees.php
        header("Location: view_employees.php");
        exit();
    } else {
        echo "Error deleting employee record: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Invalid request. Employee ID is missing.";
}

// End and flush the buffer
ob_end_flush();

$conn->close();
?>