<?php
session_start();

$conn = new mysqli('localhost', 'root', '', 'SwiftPay');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $employee_id = $_SESSION['employee']['id'];
    $feedback_text = $_POST['feedback_text'];

    $sql = "INSERT INTO feedback (employee_id, feedback_text) 
            VALUES ('$employee_id', '$feedback_text')";

    if ($conn->query($sql) === TRUE) {
        $message = "Feedback submitted successfully";
    } else {
        $message = "Error: " . $conn->error;
    }

    // Output JavaScript to show alert message and redirect
    echo "<script>alert('$message'); window.location.href = 'submit_feedback.php';</script>";
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Feedback</title>
    <link rel="stylesheet" href="submit_feedba.css">
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
    <div class="submit-feedback-container">
        <h2>Submit Feedback</h2>
        <form method="POST" action="submit_feedback.php">
            <label for="feedback_text">Your Feedback:</label>
            <textarea id="feedback_text" name="feedback_text" required></textarea>
            <button type="submit">Submit Feedback</button>
        </form>
    </div>
</body>
</html>
