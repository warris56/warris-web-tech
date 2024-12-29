<?php
session_start();

$conn = new mysqli('localhost', 'root', '', 'SwiftPay');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $employee_id = $_POST['employee_id'];
    $username = $_POST['username'];
    $email = $_POST['email'];

    // Fetch user details from employees and employee_logins tables
    $sql = "SELECT e.id FROM employees e JOIN employee_logins el ON e.id = el.employee_id WHERE e.id = ? AND el.username = ? AND e.email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $employee_id, $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['reset_employee_id'] = $row['id'];
        echo "<script>alert('Details match. Redirecting to set new password.'); window.location.href = 'set_new_password.html';</script>";
    } else {
        echo "<script>alert('No user found with the provided information.'); window.location.href = 'forgot_password.html';</script>";
    }

    $stmt->close();
}

$conn->close();
?>
