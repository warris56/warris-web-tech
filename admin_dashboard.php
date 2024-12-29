<?php
session_start();

$conn = new mysqli('localhost', 'root', '', 'SwiftPay');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch total employees
$total_employees_sql = "SELECT COUNT(*) AS total_employees FROM employees";
$total_employees_result = $conn->query($total_employees_sql);
$total_employees = $total_employees_result->fetch_assoc()['total_employees'];

// Fetch total payroll
$total_payroll_sql = "SELECT SUM(salary) AS total_payroll FROM employees";
$total_payroll_result = $conn->query($total_payroll_sql);
$total_payroll = $total_payroll_result->fetch_assoc()['total_payroll'];

// Fetch pending tasks (assuming a tasks table with status)
$pending_tasks_sql = "SELECT COUNT(*) AS pending_tasks FROM tasks WHERE status = 'pending'";
$pending_tasks_result = $conn->query($pending_tasks_sql);
$pending_tasks = $pending_tasks_result->fetch_assoc()['pending_tasks'];

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="admin_dashboar.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar Navigation -->
        <nav>
            <h2>Admin Panel</h2>
            <a href="view_employees.php" class="active">
                <i class="icon-employees"></i> View Employees
            </a>
            <a href="create_employee_login.html">
                <i class="icon-create-login"></i> Create Employee Login
            </a>
            <a href="process_payroll.php">
                <i class="icon-payroll"></i> Pay Employee
            </a>
            <a href="search_employee.html">
                <i class="icon-employees-csv"></i> search for employees
            </a>
            <a href="activity_logs.php">
                <i class="icon-logs"></i> View Activity
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
                <i class="icon-employee-benefit"></i> View employee Benefits
            </a>
            <a href="add_employee_deduction.php">
                <i class="icon-performance"></i> employee deduction
            </a>
            <a href="view_employee_deductions.php">
                <i class="icon-performance"></i> View employee deduction
            </a>
            <a href="view_feedback.php">
                <i class="icon-view-feedback"></i> View Feedback
            </a>
            <a href="send_notification.php">
                <i class="icon-send-notification"></i> Add Notification
            </a>
            <a href="notifications.php">
                <i class="icon-notifications"></i>View Notifications
            </a>
            <a href="logout.php" class="logout">
                <i class="icon-logout"></i> Logout
            </a>
        </nav>

        <!-- Main Content Area -->
        <main class="main-content">
            <!-- Header -->
            <header class="main-header">
                <h1>Dashboard Overview</h1>
                <div class="user-info">
                    <span>Welcome, Admin</span>
                    <img src="IMG_5856 2.jpg" alt="Admin Avatar">
                </div>
            </header>

            <!-- Summary Cards -->
            <section class="summary-cards">
                <div class="card">
                    <h3>Total Employees</h3>
                    <p id="total-employees"><?php echo $total_employees; ?></p>
                </div>
                <div class="card">
                    <h3>Pending Tasks</h3>
                    <p id="pending-tasks"><?php echo $pending_tasks; ?></p>
                </div>
            </section>

            <!-- Charts Section -->
            <section class="charts-container">
                <div>
                    <h3>Payroll Expenses</h3>
                    <canvas id="payrollChart"></canvas>
                </div>
                <div>
                    <h3>Employee Distribution</h3>
                    <canvas id="departmentChart"></canvas>
                </div>
            </section>

            <!-- Forms Section -->
            <section class="forms-section">
                <form id="createEmployeeForm" action="create_employee.php" method="POST">
                    <h3>Create New Employee</h3>

                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" required>
                    
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                    
                    <label for="department">Department</label>
                    <select id="department" name="department" required>
                        <option value="">Select Department</option>
                        <option value="HR">Human Resources</option>
                        <option value="IT">Information Technology</option>
                        <option value="Finance">Finance</option>
                        <option value="Sales">Sales</option>
                    </select>
                    
                    <label for="basic">basic Salary</label>
                    <input type="number" id="basic" name="basic" required>

                    <label for="rent allowance">rent allowance</label>
                    <input type="number" id="rent_allowance" name="rent_allowance" required>

                    <label for="car allowance">car allowance</label>
                    <input type="number" id="car_allowance" name="car_allowance" required>
                    
                    <button type="submit">Create Employee</button>
                </form>
            </section>
        </main>
    </div>

    <script src="admin_dashboard.js"></script>
</body>
</html>
