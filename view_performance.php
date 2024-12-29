<?php
session_start();

$conn = new mysqli('localhost', 'root', '', 'SwiftPay');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT pm.id, e.name, pm.metric_name, pm.metric_value, pm.recorded_at 
        FROM performance_metrics pm 
        JOIN employees e ON pm.employee_id = e.id";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Performance Metrics</title>
    <link rel="stylesheet" href="view_performance.css">
</head>
<body>
    <div class="view-performance-container">
        <h2>View Performance Metrics</h2>
        <nav>
            <a href="admin_dashboard.html">Admin Dashboard</a> |
            <a href="logout.php">Logout</a>
        </nav>
        <table>
            <tr>
                <th>ID</th>
                <th>Employee Name</th>
                <th>Metric Name</th>
                <th>Metric Value</th>
                <th>Recorded At</th>
            </tr>
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . $row['name'] . "</td>";
                    echo "<td>" . $row['metric_name'] . "</td>";
                    echo "<td>" . $row['metric_value'] . "</td>";
                    echo "<td>" . $row['recorded_at'] . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No performance metrics found</td></tr>";
            }
            ?>
        </table>
    </div>
</body>
</html>
