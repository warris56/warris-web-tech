<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'SwiftPay');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM admins WHERE username='$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            session_regenerate_id(true);
            $_SESSION['user'] = $username;
            $_SESSION['role'] = $row['role'];
            header("Location: admin_dashboard.php");
            exit();
        } else {
            $_SESSION['error'] = "Invalid password.";
            header("Location: admin_login.php");
            exit();
        }
    } else {
        $_SESSION['error'] = "No user found.";
        header("Location: admin_login.php");
        exit();
    }
}

$conn->close();
?>
