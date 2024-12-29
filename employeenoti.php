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
    <link rel="stylesheet" href="employeenoti.css">
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
</body>
</html>

<?php
$conn->close();
?>
