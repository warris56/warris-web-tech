<?php
session_start();

$conn = new mysqli('localhost', 'root', '', 'SwiftPay');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$employee = null; // To store employee data after search
$message = ""; // To store message

// Handle search request
if (isset($_POST['search'])) {
    $search_id = $_POST['search_id'];
    $sql = "SELECT * FROM employees WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $search_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $employee = $result->fetch_assoc(); // Fetch employee data
        $message = "Employee found: " . htmlspecialchars($employee['name']);
    } else {
        $message = "No employee found with ID: " . htmlspecialchars($search_id);
    }
    $stmt->close();
}

// Handle task assignment
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['assign_task'])) {
    $employee_id = $_POST['employee_id'];
    
    // Validate employee ID
    $sql = "SELECT * FROM employees WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $employee_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // Proceed with task assignment
        $title = $_POST['title'];
        $description = $_POST['description'];
        $due_date = $_POST['due_date'];
        $priority = $_POST['priority'];

        $sql = "INSERT INTO tasks (employee_id, title, description, due_date, priority) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issss", $employee_id, $title, $description, $due_date, $priority);

        if ($stmt->execute()) {
            $message = "New task assigned successfully";
        } else {
            $message = "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $message = "Error: Employee ID does not exist.";
    }
    $stmt->close();
}

// Fetch all tasks for display
$sql = "SELECT * FROM tasks";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Tasks</title>
    <link rel="stylesheet" href="manage_task.css">
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
            <div class="tasks-container">
                <h2>Manage Tasks</h2>

                <!-- Search Form -->
                <form method="POST" action="manage_tasks.php">
                    <label for="search_id">Search Employee by ID:</label>
                    <input type="number" id="search_id" name="search_id" required>
                    <button type="submit" name="search">Search</button>
                </form>

                <!-- Task Assignment Form -->
                <form method="POST" action="manage_tasks.php">
                    <label for="employee_id">Employee ID:</label>
                    <input type="number" id="employee_id" name="employee_id" value="<?php echo isset($employee['id']) ? htmlspecialchars($employee['id']) : ''; ?>" required readonly>
                    <label for="title">Title:</label>
                    <input type="text" id="title" name="title" required>
                    <label for="description">Description:</label>
                    <textarea id="description" name="description" required></textarea>
                    <label for="due_date">Due Date:</label>
                    <input type="date" id="due_date" name="due_date" required>
                    <label for="priority">Priority:</label>
                    <select id="priority" name="priority" required>
                        <option value="Low">Low</option>
                        <option value="Medium">Medium</option>
                        <option value="High">High</option>
                    </select>
                    <button type="submit" name="assign_task">Assign Task</button>
                </form>

                <h3>Assigned Tasks</h3>
                <table>
                    <tr>
                        <th>ID</th>
                        <th>Employee ID</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Priority</th>
                        <th>Due Date</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['employee_id']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['description']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['priority']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['due_date']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['created_at']) . "</td>";
                            echo "<td>
                                    <a href='update_task_status.php?id=" . htmlspecialchars($row['id']) . "&status=In+Progress'>Start</a> | 
                                    <a href='update_task_status.php?id=" . htmlspecialchars($row['id']) . "&status=Completed'>Complete</a>
                                  </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='9'>No tasks assigned</td></tr>";
                    }
                    ?>
                </table>
                
                <!-- Pass PHP message to JavaScript for alert -->
                <?php if (!empty($message)) { ?>
                    <script>
                        alert("<?php echo $message; ?>");
                    </script>
                <?php } ?>
            </div>
        </main>
    </div>

    <script src="create_employee_login.js"></script>
</body>
</html>
