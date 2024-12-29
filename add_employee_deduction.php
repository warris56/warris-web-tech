<?php
session_start();

$conn = new mysqli('localhost', 'root', '', 'SwiftPay');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $employee_id = $_POST['employee_id'];
    $deduction_name = $_POST['deduction_name'];

    // Verify employee ID and fetch current salary
    $verify_sql = "SELECT salary FROM employees WHERE id = ?";
    $stmt = $conn->prepare($verify_sql);
    $stmt->bind_param("i", $employee_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($current_salary);
        $stmt->fetch();

        // Calculate the deduction amount (15% of salary)
        $deduction_value = $current_salary * 0.15;
        $new_salary = $current_salary - $deduction_value;

        // Start transaction
        $conn->begin_transaction();

        try {
            // Update employee's salary
            $update_sql = "UPDATE employees SET salary = ? WHERE id = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("di", $new_salary, $employee_id);
            $update_stmt->execute();

            // Insert deduction details into employee_deductions table
            $insert_sql = "INSERT INTO employee_deductions (employee_id, deduction_name, deduction_value) VALUES (?, ?, ?)";
            $insert_stmt = $conn->prepare($insert_sql);
            $insert_stmt->bind_param("isd", $employee_id, $deduction_name, $deduction_value);
            $insert_stmt->execute();

            // Commit transaction
            $conn->commit();
            $message = "Employee deduction added and salary updated successfully.";
        } catch (Exception $e) {
            // Rollback transaction in case of error
            $conn->rollback();
            $message = "Error: " . $e->getMessage();
        }
    } else {
        $message = "Error: Employee ID does not exist.";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Employee Deduction</title>
    <link rel="stylesheet" href="add_deducts.css">
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
            <div class="add-deduction-container">
                <h2>Add Employee Deduction</h2>
                <form method="POST" action="add_employee_deduction.php">
                    <label for="employee_id">Employee ID:</label>
                    <input type="number" id="employee_id" name="employee_id" required>
                    <label for="deduction_name">Deduction reason:</label>
                    <select id="deduction_name" name="deduction_name" required>
                        <option value="Basic Task and snitts">Basic Task and snitts</option>
                    </select><br />
                    <button type="submit">Add Deduction</button>
                </form>
                <?php if (!empty($message)) { echo "<script>alert('$message');</script>"; } ?>
            </div>
        </main>
    </div>
</body>
</html>
