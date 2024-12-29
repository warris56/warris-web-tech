<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'SwiftPay');

if ($conn->connect_error) {
    die(json_encode(['error' => "Connection failed: " . $conn->connect_error]));
}

$response = ['success' => false, 'error' => 'Invalid admin ID.'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $admin_id = $_POST['admin_id'];

    // Verify the admin ID
    $sql = "SELECT * FROM admin_ids WHERE admin_id='$admin_id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $_SESSION['verified'] = true;
        $response = ['success' => true];
    }
}

echo json_encode($response);
$conn->close();
?>
