<?php
session_start();

$conn = new mysqli('localhost', 'root', '', 'SwiftPay');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$employees_sql = "SELECT * FROM employees";
$employees = $conn->query($employees_sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate Custom Report</title>
    <link rel="stylesheet" href="generate_repor.css">
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
            <div class="generate-report-container">
                <h2>Generate Custom Report</h2>
                <form id="generateReportForm" method="POST" action="view_report.php">
                    <label for="employee_id">Employee:</label>
                    <select id="employee_id" name="employee_id" required>
                        <option value="">-- Select an Employee --</option>
                        <?php
                        if ($employees->num_rows > 0) {
                            while ($employee = $employees->fetch_assoc()) {
                                echo "<option value='" . $employee['id'] . "'>" . $employee['name'] . "</option>";
                            }
                        }
                        ?>
                    </select>
                    
                    <label for="start_date">Start Date:</label>
                    <input type="date" id="start_date" name="start_date" required>
                    
                    <label for="end_date">End Date:</label>
                    <input type="date" id="end_date" name="end_date" required>
                    
                    <label for="report_type">Report Type:</label>
                    <select id="report_type" name="report_type" required>
                        <option value="payslip">Payslip Report</option>
                        <option value="attendance">Attendance Report</option>
                        <option value="performance">Performance Report</option>
                    </select>
                    
                    <button type="submit">Generate Report</button>
                </form>
            </div>
        </main>
    </div>

    <script>
        document.getElementById('generateReportForm').addEventListener('submit', function(event) {
            const employeeSelect = document.getElementById('employee_id');
            if (employeeSelect.value === "") {
                alert('Please select an employee before generating the report.');
                event.preventDefault(); // Prevent form submission
            }
        });
    </script>
</body>
</html>
