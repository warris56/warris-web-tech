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
$sql = "SELECT * FROM payslips WHERE employee_id='$employee_id' ORDER BY date DESC";
$payslips = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Payslips</title>
    <link rel="stylesheet" href="download_payslip.css">
    <script>
        function printPayslip(payslipId) {
            var content = document.getElementById('payslip_' + payslipId).innerHTML;
            var printWindow = window.open('', '_blank', 'width=800,height=600');
            printWindow.document.write('<html><head><title>Payslip</title>');
            printWindow.document.write('<style>body{font-family: Arial, sans-serif;}</style>');
            printWindow.document.write('</head><body>');
            printWindow.document.write(content);
            printWindow.document.write('</body></html>');
            printWindow.print();
            printWindow.close();
        }
    </script>
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
    <div class="view-payslips-container">
        <h2>View Payslips</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Amount</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
            <?php
            if ($payslips->num_rows > 0) {
                while($payslip = $payslips->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $payslip['id'] . "</td>";
                    echo "<td>" . $payslip['amount'] . "</td>";
                    echo "<td>" . $payslip['date'] . "</td>";
                    echo "<td><button onclick='printPayslip(" . $payslip['id'] . ")'>Print</button></td>";
                    echo "</tr>";
                    echo "<tr id='payslip_" . $payslip['id'] . "' style='display:none;'>";
                    echo "<td colspan='4'>";
                    echo "<div>";
                    echo "<h3>Payslip</h3>";
                    echo "<p><strong>Employee Name:</strong> " . $_SESSION['employee']['name'] . "</p>";
                    echo "<p><strong>Amount:</strong> " . $payslip['amount'] . "</p>";
                    echo "<p><strong>Date:</strong> " . $payslip['date'] . "</p>";
                    echo "</div>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No payslips found</td></tr>";
            }
            ?>
        </table>
    </div>
</body>
</html>
