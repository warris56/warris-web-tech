<?php
$conn = new mysqli('localhost', 'root', '', 'SwiftPay');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Example data to insert
$admin_id = 'admin123';
$security_code = password_hash('security123', PASSWORD_DEFAULT);
$password = password_hash('password123', PASSWORD_DEFAULT);

$sql = "INSERT INTO admin_security (admin_id, security_code, password) VALUES ('$admin_id', '$security_code', '$password')";

if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
