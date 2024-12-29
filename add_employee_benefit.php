<?php
session_start();

$conn = new mysqli('localhost', 'root', '', 'SwiftPay');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $employee_id = $_POST['employee_id'];
    $benefit_name = $_POST['benefit_name'];
    $benefit_value = $_POST['benefit_value'];

    // Verify employee ID
    $verify_sql = "SELECT id FROM employees WHERE id = '$employee_id'";
    $verify_result = $conn->query($verify_sql);

    if ($verify_result->num_rows > 0) {
        // Employee ID exists, proceed to add benefit
        $sql = "INSERT INTO employee_benefits (employee_id, benefit_name, benefit_value) 
                VALUES ('$employee_id', '$benefit_name', '$benefit_value')";

        if ($conn->query($sql) === TRUE) {
            $message = "Employee benefit added successfully.";
        } else {
            $message = "Error: " . $conn->error;
        }
    } else {
        $message = "Error: Employee ID does not exist.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Employee Benefit</title>
    <link rel="stylesheet" href="addse.css">
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar Navigation -->
        <nav>
            <h2>Admin Panel</h2>
            <a href="admin_dashboard.php" class="active">
                <i class="icon-dashboard"></i> Admin Dashboard
            </a>
            <a href="view_employees.php">
                <i class="icon-employees"></i> View Employees
            </a>
            <a href="create_employee_login.html">
                <i class="icon-create-login"></i> Create Employee Login
            </a>
            <a href="process_payroll.php">
                <i class="icon-payroll"></i> Pay Employee
            </a>
            <a href="search_employee.html">
                <i class="icon-employees-csv"></i> Search for Employees
            </a>
            <a href="activity_logs.php">
                <i class="icon-logs"></i> View Activity Logs
            </a>
            <a href="generate_report.php">
                <i class="icon-Generate-Custom-Report"></i> Generate Report
            </a>
            <a href="manage_tasks.php">
                <i class="icon-tasks"></i> Tasks
            </a>
            <a href="generate_payroll_report.php">
                <i class="icon-Generate-payroll-Report"></i> Payroll Report
            </a>
            <a href="view_employee_benefits.php">
                <i class="icon-employee-benefit"></i> View Employee Benefits
            </a>
            <a href="add_employee_deduction.php">
                <i class="icon-performance"></i> Employee Deduction
            </a>
            <a href="view_employee_deductions.php">
                <i class="icon-performance"></i> View Employee Deduction
            </a>
            <a href="view_feedback.php">
                <i class="icon-view-feedback"></i> View Feedback
            </a>
            <a href="send_notification.php">
                <i class="icon-send-notification"></i> Add Notification
            </a>
            <a href="notifications.php">
                <i class="icon-notifications"></i> View Notifications
            </a>
            <a href="logout.php" class="logout">
                <i class="icon-logout"></i> Logout
            </a>
        </nav>

        <!-- Main Content Area -->
        <main class="main-content">
            <div class="add-benefit-container">
                <h2>Add Employee Benefit</h2>
                <form method="POST" action="add_employee_benefit.php">
                    <label for="employee_id">Employee ID:</label>
                    <input type="number" id="employee_id" name="employee_id" required>
                    <label for="benefit_name">Benefit reason:</label>
                    <input type="text" id="benefit_name" name="benefit_name" required>
                    <label for="benefit_value">Benefit Value:</label>
                    <input type="number" step="0.01" id="benefit_value" name="benefit_value" required>
                    <button type="submit">Add Benefit</button>
                </form>
                <?php if (!empty($message)) { echo "<script>alert('$message');</script>"; } ?>
            </div>
        </main>
    </div>
</body>
</html>
