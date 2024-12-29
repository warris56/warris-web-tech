<?php
session_start();

$conn = new mysqli('localhost', 'root', '', 'SwiftPay');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['reset_employee_id'])) {
    header("Location: employee_login.html");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password === $confirm_password) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $employee_id = $_SESSION['reset_employee_id'];

        $sql = "UPDATE employee_logins SET password='$hashed_password' WHERE employee_id='$employee_id'";

        if ($conn->query($sql) === TRUE) {
            unset($_SESSION['reset_employee_id']);
            echo "<script>alert('Password updated successfully.'); window.location.href = 'employee_login.html';</script>";
        } else {
            echo "<script>alert('Error updating password: " . $conn->error . "'); window.location.href = 'set_new_password.php';</script>";
        }
    } else {
        echo "<script>alert('Passwords do not match.'); window.location.href = 'set_new_password.html';</script>";
    }
}

$conn->close();
?>
