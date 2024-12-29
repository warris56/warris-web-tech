<?php
session_start();

$conn = new mysqli('localhost', 'root', '', 'SwiftPay');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $employee_id = $_POST['employee_id'];
    $metric_name = $_POST['metric_name'];
    $metric_value = $_POST['metric_value'];

    $sql = "INSERT INTO performance_metrics (employee_id, metric_name, metric_value) 
            VALUES ('$employee_id', '$metric_name', '$metric_value')";

    if ($conn->query($sql) === TRUE) {
        echo "Performance metric added successfully";
    } else {
        echo "Error: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Performance Metric</title>
    <link rel="stylesheet" href="add_performance.css">
</head>
<body>
    <div class="add-performance-container">
        <h2>Add Performance Metric</h2>
        <nav>
            <a href="admin_dashboard.html">Admin Dashboard</a> |
            <a href="logout.php">Logout</a>
        </nav>
        <form method="POST" action="add_performance.php">
            <label for="employee_id">Employee ID:</label>
            <input type="number" id="employee_id" name="employee_id" required>
            <label for="metric_name">Metric Name:</label>
            <input type="text" id="metric_name" name="metric_name" required>
            <label for="metric_value">Metric Value:</label>
            <input type="number" step="0.01" id="metric_value" name="metric_value" required>
            <button type="submit">Add Metric</button>
        </form>
    </div>
</body>
</html>
