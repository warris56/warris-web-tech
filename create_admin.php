<?php
$conn = new mysqli('localhost', 'root', '', 'SwiftPay');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$username = 'admin';
$password = password_hash('adminpassword', PASSWORD_DEFAULT);

$sql = "INSERT INTO admins (username, password) VALUES ('$username', '$password')";

if ($conn->query($sql) === TRUE) {
    echo "Admin created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
