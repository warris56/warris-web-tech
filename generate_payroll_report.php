<?php
session_start();

$conn = new mysqli('localhost', 'root', '', 'SwiftPay');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch employees for the dropdown
$employees = [];
$employee_result = $conn->query("SELECT id, name FROM employees");
if ($employee_result) {
    while ($row = $employee_result->fetch_assoc()) {
        $employees[] = $row;
    }
}

$result = null;
$error_message = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $employee_id = $_POST['employee'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    // Validate employee selection
    if (empty($employee_id)) {
        $error_message = "Please select an employee.";
    } elseif ($start_date > $end_date) {
        $error_message = "Start date cannot be later than end date.";
    } else {
        // Use prepared statements to prevent SQL injection
        $stmt = $conn->prepare("SELECT e.name, p.date, p.amount 
                                 FROM payslips p 
                                 JOIN employees e ON p.employee_id = e.id 
                                 WHERE p.employee_id = ? AND p.date BETWEEN ? AND ?");
        $stmt->bind_param("iss", $employee_id, $start_date, $end_date);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate Payroll Report</title>
    <link rel="stylesheet" href="generate_payroll_repor.css">
    <style>
        .error-message {
            color: red;
        }
    </style>
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
            <div class="generate-payroll-report-container">
                <h2>Generate Payroll Report</h2>
                <form id="report-form" method="POST" action="generate_payroll_report.php">
                    <label for="employee">Select Employee:</label>
                    <select id="employee" name="employee" required>
                        <option value="">Select Employee</option>
                        <?php foreach ($employees as $employee): ?>
                            <option value="<?php echo $employee['id']; ?>"><?php echo htmlspecialchars($employee['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <span id="employee-error" class="error-message"></span>

                    <label for="start_date">Start Date:</label>
                    <input type="date" id="start_date" name="start_date" required>
                    <label for="end_date">End Date:</label>
                    <input type="date" id="end_date" name="end_date" required>
                    <button type="submit">Generate Report</button>
                </form>

                <?php if (isset($error_message)): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></div>
                <?php elseif (isset($result)): ?>
                    <h3>Payroll Report</h3>
                    <table>
                        <tr>
                            <th>Employee Name</th>
                            <th>Date</th>
                            <th>Amount</th>
                        </tr>
                        <?php
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['date']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['amount']) . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='3'>No payroll records found for the selected period</td></tr>";
                        }
                        ?>
                    </table>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <script>
        document.getElementById('report-form').onsubmit = function() {
            const employeeSelect = document.getElementById("employee");
            const employeeError = document.getElementById("employee-error");
            let isValid = true;

            // Clear previous error message
            employeeError.textContent = "";

            // Check if an employee is selected
            if (employeeSelect.value === "") {
                employeeError.textContent = "Please select an employee.";
                isValid = false;
            }

            return isValid; // Return true to allow form submission, false to prevent it
        };
    </script>
</body>
</html>
