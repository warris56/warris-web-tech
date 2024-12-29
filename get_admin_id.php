<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'SwiftPay');

if ($conn->connect_error) {
    die(json_encode(['error' => "Connection failed: " . $conn->connect_error]));
}

$response = ['success' => false, 'admin_id' => null];

if (isset($_SESSION['user'])) {
    $username = $_SESSION['user'];

    // Fetch the admin ID based on the username
    $sql = "SELECT admin_id FROM admin_ids WHERE username='$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $response = ['success' => true, 'admin_id' => $row['admin_id']];
    } else {
        $response['error'] = 'Admin ID not found.';
    }
}

echo json_encode($response);
$conn->close();
?>
