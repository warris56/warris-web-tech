<?php
session_start();

// Ensure the user is an employee
if ($_SESSION['role'] != 'employee') {
    header("Location: login.php");
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'SwiftPay');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM notifications WHERE user_id='$user_id' ORDER BY created_at DESC";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Notifications</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="view-notifications-container">
        <h2>Employee Notifications</h2>
        <nav>
            <a href="employee_dashboard.php">Dashboard</a> |
            <a href="logout.php">Logout</a>
        </nav>
        <table>
            <tr>
                <th>ID</th>
                <th>Message</th>
                <th>Timestamp</th>
            </tr>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . $row['message'] . "</td>";
                    echo "<td>" . $row['created_at'] . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='3'>No notifications found</td></tr>";
            }
            ?>
        </table>
    </div>
</body>
</html>
