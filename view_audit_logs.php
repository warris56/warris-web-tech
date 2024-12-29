<?php
session_start();

$conn = new mysqli('localhost', 'root', '', 'SwiftPay');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM audit_logs ORDER BY timestamp DESC";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Audit Logs</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="view-audit-logs-container">
        <h2>View Audit Logs</h2>
        <nav>
            <a href="admin_dashboard.html">Admin Dashboard</a> |
            <a href="logout.php">Logout</a>
        </nav>
        <table>
            <tr>
                <th>ID</th>
                <th>User Type</th>
                <th>User ID</th>
                <th>Action Type</th>
                <th>Action Details</th>
                <th>Timestamp</th>
            </tr>
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . $row['user_type'] . "</td>";
                    echo "<td>" . $row['user_id'] . "</td>";
                    echo "<td>" . $row['action_type'] . "</td>";
                    echo "<td>" . $row['action_details'] . "</td>";
                    echo "<td>" . $row['timestamp'] . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No audit logs found</td></tr>";
            }
            ?>
        </table>
    </div>
</body>
</html>
