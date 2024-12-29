<?php
$conn = new mysqli('localhost', 'root', '', 'SwiftPay');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$totalEmployees = $conn->query("SELECT COUNT(*) AS count FROM employees")->fetch_assoc()['count'];
$totalPayroll = $conn->query("SELECT SUM(salary) AS sum FROM employees")->fetch_assoc()['sum'];
$pendingTasks = 0; // This could be calculated based on your specific logic

$response = array(
    "totalEmployees" => $totalEmployees,
    "totalPayroll" => $totalPayroll,
    "pendingTasks" => $pendingTasks
);

header('Content-Type: application/json');
echo json_encode($response);

$conn->close();
?>
