<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.html");
    exit();
}

$user_role = $_SESSION['role'];
if ($user_role == 'admin') {
    header("Location: admin_dashboard.html");
} elseif ($user_role == 'employee') {
    header("Location: employee_dashboard.php");
}
?>
