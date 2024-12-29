<?php
header('Content-Type: application/json');

$conn = new mysqli('localhost', 'root', '', 'SwiftPay');

if ($conn->connect_error) {
    die(json_encode(['error' => 'Connection failed: ' . $conn->connect_error]));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'])) {
    $name = '%' . $conn->real_escape_string($_POST['name']) . '%';

    $sql = "SELECT id, name, email, department, salary FROM employees WHERE name LIKE ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        echo json_encode(['error' => 'Error preparing statement: ' . $conn->error]);
        exit();
    }
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $result = $stmt->get_result();

    $employees = [];
    while ($row = $result->fetch_assoc()) {
        $employees[] = $row;
    }

    echo json_encode($employees);

    $stmt->close();
} else {
    echo json_encode(['error' => 'Invalid request or missing name.']);
}

$conn->close();
?>