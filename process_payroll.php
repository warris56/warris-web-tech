<?php
session_start();

// Database connection with error handling
try {
    $conn = new mysqli('localhost', 'root', '', 'SwiftPay');
    
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    // Prepare and validate POST data
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $employee_id = $conn->real_escape_string($_POST['employee_id']);
        $amount = $conn->real_escape_string($_POST['amount']);
        $date = date('Y-m-d');

        // Prepared statement for secure insertion
        $stmt = $conn->prepare("INSERT INTO payslips (employee_id, amount, date) VALUES (?, ?, ?)");
        $stmt->bind_param("ids", $employee_id, $amount, $date);

        if ($stmt->execute()) {
            $success_message = "Payroll processed successfully for Employee ID: $employee_id";

            // Log the payroll processing action
            $admin_id = $_SESSION['admin_id'] ?? 'unknown'; // Get admin ID from session
            $action_type = 'process_payroll';
            $action_details = "Processed payroll for Employee ID: $employee_id, Amount: $amount";

            // Prepare SQL statement for logging
            $log_stmt = $conn->prepare("INSERT INTO audit_logs (user_type, user_id, action_type, action_details) 
                                        VALUES ('admin', ?, ?, ?)");
            $log_stmt->bind_param("sss", $admin_id, $action_type, $action_details);
            $log_stmt->execute();
            $log_stmt->close();
        } else {
            $error_message = "Error processing payroll: " . $stmt->error;
        }
        $stmt->close();
    }

    // Retrieve employees with prepared statement
    $stmt = $conn->prepare("SELECT id, name FROM employees ORDER BY name");
    $stmt->execute();
    $employees = $stmt->get_result();

} catch (Exception $e) {
    $error_message = $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Process Payroll</title>
    <link rel="stylesheet" href="process_payrol.css">
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
            <a href="notifications.php">
                <i class="icon-notifications"></i> View Notifications
            </a>
            <a href="logout.php" class="logout">
                <i class="icon-logout"></i> Logout
            </a>
        </nav>

        <!-- Main Content Area -->
        <main class="main-content">
            <div class="process-payroll-container">
                <div class="payroll-header">
                    <h2>Process Payroll</h2>
                </div>

                <?php if (isset($success_message)): ?>
                    <div class="alert alert-success"><?php echo $success_message; ?></div>
                <?php endif; ?>

                <?php if (isset($error_message)): ?>
                    <div class="alert alert-danger"><?php echo $error_message; ?></div>
                <?php endif; ?>

                <form method="POST" action="process_payroll.php">
                    <div class="form-group">
                        <label for="employee_id">Select Employee:</label>
                        <select id="employee_id" name="employee_id" required>
                            <option value="">Choose an Employee</option>
                            <?php
                            if ($employees->num_rows > 0) {
                                while($employee = $employees->fetch_assoc()) {
                                    echo "<option value='" . htmlspecialchars($employee['id']) . "'>" 
                                         . htmlspecialchars($employee['name']) . "</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="amount">Payroll Amount:</label>
                        <input type="number" id="amount" name="amount" 
                               min="0" step="0.01" required 
                               placeholder="Enter payroll amount">
                    </div>

                    <button type="submit" class="btn btn-primary">Process Payroll</button>
                </form>
            </div>
        </main>
    </div>
</body>
</html>

<?php 
// Close database connection
if (isset($conn)) {
    $conn->close();
}
?>
