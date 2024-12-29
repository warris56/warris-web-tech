<?php
session_start();

$conn = new mysqli('localhost', 'root', '', 'SwiftPay');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$employee_id = $_POST['employee_id'];
$start_date = $_POST['start_date'];
$end_date = $_POST['end_date'];
$report_type = $_POST['report_type'];

if ($report_type == 'payslip') {
    $sql = "SELECT * FROM payslips 
            WHERE date BETWEEN '$start_date' AND '$end_date'";
    if ($employee_id != 'all') {
        $sql .= " AND employee_id='$employee_id'";
    }
} elseif ($report_type == 'attendance') {
    $sql = "SELECT * FROM attendance 
            WHERE date BETWEEN '$start_date' AND '$end_date'";
    if ($employee_id != 'all') {
        $sql .= " AND employee_id='$employee_id'";
    }
} elseif ($report_type == 'performance') {
    $sql = "SELECT * FROM performance 
            WHERE date BETWEEN '$start_date' AND '$end_date'";
    if ($employee_id != 'all') {
        $sql .= " AND employee_id='$employee_id'";
    }
} elseif ($report_type == 'notifications') {
    $sql = "SELECT * FROM notifications 
            WHERE date BETWEEN '$start_date' AND '$end_date'";
    if ($employee_id != 'all') {
        $sql .= " AND employee_id='$employee_id'";
    }
}

$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Report</title>
    <link rel="stylesheet" href="view_report.css">
</head>
<body>
    <div class="view-report-container">
        <h2>View Report</h2>
        <nav>
            <a href="admin_dashboard.php">Admin Dashboard</a> |
            <a href="logout.php">Logout</a>
        </nav>
        <table>
            <tr>
                <?php
                if ($report_type == 'payslip') {
                    echo "<th>ID</th><th>Employee ID</th><th>Amount</th><th>Date</th>";
                } elseif ($report_type == 'attendance') {
                    echo "<th>ID</th><th>Employee ID</th><th>Date</th><th>Status</th>";
                } elseif ($report_type == 'performance') {
                    echo "<th>ID</th><th>Employee ID</th><th>Date</th><th>Metric</th><th>Score</th>";
                } elseif ($report_type == 'notifications') {
                    echo "<th>ID</th><th>Employee ID</th><th>Date</th><th>Message</th><th>Status</th>";
                }
                ?>
            </tr>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    foreach ($row as $value) {
                        echo "<td>" . $value . "</td>";
                    }
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No records found</td></tr>";
            }
            ?>
        </table>
    </div>
</body>
</html>
