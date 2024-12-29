<?php
session_start();

$conn = new mysqli('localhost', 'root', '', 'SwiftPay');
if ($conn->connect_error) {
    die(json_encode(['status' => 'error', 'message' => 'Connection failed: ' . $conn->connect_error]));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $employee_name = $_POST['employee-name'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if employee exists
    $sql = "SELECT id FROM employees WHERE name=?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        echo json_encode(['status' => 'error', 'message' => 'Error preparing statement: ' . $conn->error]);
        exit();
    }
    $stmt->bind_param("s", $employee_name);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $employee_id = $row['id'];

        // Create login account
        $sql = "INSERT INTO employee_logins (employee_id, username, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            echo json_encode(['status' => 'error', 'message' => 'Error preparing insert statement: ' . $conn->error]);
            exit();
        }
        $stmt->bind_param("iss", $employee_id, $username, $password);

        if ($stmt->execute()) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Login account created successfully.'
            ]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error: ' . $stmt->error]);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Employee not found.']);
    }

    $stmt->close();
}

$conn->close();
?>
