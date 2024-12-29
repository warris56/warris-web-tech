<?php
session_start();

// Ensure the user is an admin

$conn = new mysqli('localhost', 'root', '', 'SwiftPay');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM notifications ORDER BY created_at DESC";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Notifications</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="view-notifications-container">
        <h2>Admin Notifications</h2>
        <nav>
            <a href="admin_dashboard.html">Dashboard</a> |
            <a href="logout.php">Logout</a>
        </nav>
        <table>
            <tr>
                <th>ID</th>
                <th>User Type</th>
                <th>User ID</th>
                <th>Message</th>
                <th>Timestamp</th>
            </tr>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . $row['user_type'] . "</td>";
                    echo "<td>" . $row['user_id'] . "</td>";
                    echo "<td>" . $row['message'] . "</td>";
                    echo "<td>" . $row['created_at'] . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No notifications found</td></tr>";
            }
            ?>
        </table>
    </div>
</body>
</html>
