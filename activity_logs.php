<?php
session_start();

// Secure database connection with error handling
try {
    $conn = new mysqli('localhost', 'root', '', 'SwiftPay');
    
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    // Prepared statement for logs retrieval
    $stmt = $conn->prepare("SELECT user_type, user_id, action_type AS action, timestamp FROM audit_logs ORDER BY timestamp DESC LIMIT 200");
    $stmt->execute();
    $result = $stmt->get_result();

} catch (Exception $e) {
    $error_message = $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SwiftPay - Activity Logs</title>
    <link rel="stylesheet" href="activity_lo.css">
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
            <a href="notifications.php">
                <i class="icon-notifications"></i> View Notifications
            </a>
            <a href="logout.php" class="logout">
                <i class="icon-logout"></i> Logout
            </a>
        </nav>

        <!-- Main Content Area -->
        <main class="main-content">
            <div class="activity-logs-container">
                <div class="logs-header">
                    <h2>Activity Logs</h2>
                </div>

                <?php if (isset($error_message)): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></div>
                <?php endif; ?>

                <input type="text" id="search-input" placeholder="Search logs..." />

                <div class="table-responsive">
                    <table id="activity-logs-table">
                        <thead>
                            <tr>
                                <th>User Type</th>
                                <th>User ID</th>
                                <th>Action</th>
                                <th>Timestamp</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result && $result->num_rows > 0) {
                                while($row = $result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($row['user_type']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['user_id']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['action']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['timestamp']) . "</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='4' class='no-data'>No activities found</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <script src="activity_log.js"></script> <!-- Include the JavaScript file -->
</body>
</html>

<?php 
// Close database connection
if (isset($conn)) {
    $conn->close();
}
?>
