<?php
session_start();

// Establish database connection
$conn = new mysqli('localhost', 'root', '', 'SwiftPay');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve and sanitize input data
    $id = $_POST['id'];
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $department = trim($_POST['department']);
    $salary = $_POST['salary'];

    // Validate if all fields are received correctly
    if (empty($id) || empty($name) || empty($email) || empty($department) || empty($salary)) {
        echo "All fields are required.";
        exit();
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format.";
        exit();
    }

    // Prepare SQL statement for updating employee details
    $sql = "UPDATE employees SET name=?, email=?, department=?, salary=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssdi", $name, $email, $department, $salary, $id); // Note: salary should be a double or decimal

    // Execute the update query
    if ($stmt->execute()) {
        // Log the update action
        $admin_id = $_SESSION['admin_id'] ?? 'unknown'; // Default to 'unknown' if not set
        $action_type = 'update_employee';
        $action_details = "Updated employee ID: $id, Name: $name";

        // Prepare SQL statement for logging
        $log_sql = "INSERT INTO audit_logs (user_type, user_id, action_type, action_details) VALUES ('admin', ?, ?, ?)";
        $log_stmt = $conn->prepare($log_sql);
        $log_stmt->bind_param("sss", $admin_id, $action_type, $action_details);
        $log_stmt->execute();

        // Redirect to the employee view page
        header("Location: view_employees.php");
        exit();
    } else {
        echo "Error updating record: " . $stmt->error;
    }

    // Close the prepared statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>