<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'SwiftPay');

if ($conn->connect_error) {
    die(json_encode(['error' => "Connection failed: " . $conn->connect_error]));
}

$response = ['success' => false, 'error' => 'Invalid security code or password.'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $security_code = $_POST['security_code'];
    $password = $_POST['password'];

    // Verify the security code and password
    $sql = "SELECT * FROM admin_security";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            if (password_verify($security_code, $row['security_code']) && password_verify($password, $row['password'])) {
                $_SESSION['verified'] = true;
                $response = ['success' => true];
                break;
            }
        }
    }
}

echo json_encode($response);
$conn->close();
?>
