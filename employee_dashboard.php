<?php
session_start();
if (!isset($_SESSION['employee'])) {
    header("Location: employee_login.html");
    exit();
}

$employee = $_SESSION['employee'];

$conn = new mysqli('localhost', 'root', '', 'SwiftPay');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Dashboard</title>
    <link rel="stylesheet" href="employee_dashboards.css">
</head>
<body>
<main class="dashboard-container">
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
    <section class="employee-info">
        <div class="card">
            <img src="" alt="Profile Picture">
            <h2><?php echo $employee['name']; ?></h2>
            <p>Position: <?php echo $employee['position']; ?></p>
            <p>Department: <?php echo $employee['department']; ?></p>
            <p>Salary: <?php echo $employee['salary']; ?></p>
            <button class="accent">View Details</button>
        </div>
    </section>
    <section class="pay-history">
        <h2>Pay History</h2>
        <div class="chart-container">
            <!-- Chart or graph goes here -->
        </div>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Fetch actual pay history
                $pay_history_sql = "SELECT date, amount FROM payslips WHERE employee_id=" . $employee['id'];
                $pay_history_result = $conn->query($pay_history_sql);

                if ($pay_history_result->num_rows > 0) {
                    while ($pay = $pay_history_result->fetch_assoc()) {
                        echo "<tr><td>" . $pay['date'] . "</td><td>" . $pay['amount'] . "</td></tr>";
                    }
                } else {
                    echo "<tr><td colspan='2'>No pay history found</td></tr>";
                }

                $conn->close();
                ?>
            </tbody>
        </table>
    </section>
</main>
</body>
</html>
