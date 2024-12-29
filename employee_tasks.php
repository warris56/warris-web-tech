<?php
session_start();
if (!isset($_SESSION['employee'])) {
    header("Location: employee_login.html");
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'SwiftPay');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$employee_id = $_SESSION['employee']['id'];
$sql = "SELECT * FROM tasks WHERE employee_id='$employee_id'";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Tasks</title>
    <link rel="stylesheet" href="employee_task.css">
</head>
<body>
    <header>
        <nav>
            <div class="nav-logo">
                <img src="" alt="Logo">
            </div>
            <div class="nav-links">
                <a href="employee_profile.php">Profile</a>
                <a href="employee_tasks.php">My Tasks</a>
                <a href="download_payslips.php">View Payslips</a>
                <a href="change_password.php">Change Password</a>
                <a href="submit_feedback.php">Submit Feedback</a>
                <a href="employeenoti.php"> View Notification</a>
                <a href="index.html">Logout</a>
            </div>
            <div class="nav-icons">
                <i class="fas fa-search"></i>
                <i class="fas fa-bell"></i>
            </div>
        </nav>
    </header>
    <div class="tasks-container">
        <h2>My Tasks</h2>
        <table>
            <tr>
                <th>ID</th>
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
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . $row['title'] . "</td>";
                    echo "<td>" . $row['description'] . "</td>";
                    echo "<td>" . $row['status'] . "</td>";
                    echo "<td>" . $row['priority'] . "</td>";
                    echo "<td>" . $row['due_date'] . "</td>";
                    echo "<td>" . $row['created_at'] . "</td>";
                    echo "<td><a href='update_task_status.php?id=" . $row['id'] . "&status=Completed'>Complete</a></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='8'>No tasks assigned</td></tr>";
            }
            ?>
        </table>
    </div>
</body>
</html>
