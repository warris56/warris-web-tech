<?php
session_start();

$conn = new mysqli('localhost', 'root', '', 'SwiftPay');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM notifications ORDER BY timestamp DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
    <link rel="stylesheet" href="notification.css">
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
            <a href="add_employee_benefit.php">
                <i class="icon-employee-benefit"></i> Employee Benefits
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
            <a href="logout.php" class="logout">
                <i class="icon-logout"></i> Logout
            </a>
        </nav>

        <!-- Main Content Area -->
        <main class="main-content">
            <div class="notifications-container">
                <h2>Notifications</h2>
                <ul>
                    <?php
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<li>" . $row['message'] . " - " . $row['timestamp'] . "</li>";
                        }
                    } else {
                        echo "<li>No notifications found</li>";
                    }
                    ?>
                </ul>
            </div>
        </main>
    </div>
</body>
</html>

<?php
$conn->close();
?>
