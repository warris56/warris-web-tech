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

$employee = $_SESSION['employee'];
$username = $employee['email']; // Assuming email is the unique identifier
$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Fetch user details from employee_logins table
    $sql = "SELECT password FROM employee_logins WHERE username='$username'";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();

        if (password_verify($current_password, $row['password'])) {
            if ($new_password === $confirm_password) {
                $new_password_hashed = password_hash($new_password, PASSWORD_DEFAULT);
                $update_sql = "UPDATE employee_logins SET password='$new_password_hashed' WHERE username='$username'";

                if ($conn->query($update_sql) === TRUE) {
                    $message = "Password changed successfully";

                    // Log the password change action
                    $employee_id = $employee['id'];
                    $action_type = 'change_password';
                    $action_details = 'Employee changed password';

                    $log_sql = "INSERT INTO audit_logs (user_type, user_id, action_type, action_details) 
                                VALUES ('employee', ?, ?, ?)";
                    $log_stmt = $conn->prepare($log_sql);
                    $log_stmt->bind_param("sss", $employee_id, $action_type, $action_details);
                    $log_stmt->execute();
                    $log_stmt->close();
                } else {
                    $message = "Error updating password: " . $conn->error;
                }
            } else {
                $message = "New passwords do not match.";
            }
        } else {
            $message = "Current password is incorrect.";
        }
    } else {
        $message = "User details not found.";
    }
    echo "<script>alert('$message'); window.location.href = 'change_password.php';</script>";
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <link rel="stylesheet" href="change_passwo.css">
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
    <div class="change-password-container">
        <h2>Change Password</h2>
        <form action="change_password.php" method="POST">
            <label for="current_password">Current Password:</label>
            <input type="password" id="current_password" name="current_password" required>
            <label for="new_password">New Password:</label>
            <input type="password" id="new_password" name="new_password" required>
            <label for="confirm_password">Confirm New Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
            <button type="submit">Change Password</button>
        </form>
    </div>
</body>
</html>
