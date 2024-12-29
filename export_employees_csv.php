<?php
session_start();

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=employees.csv');

$output = fopen('php://output', 'w');
fputcsv($output, array('ID', 'Name', 'Position', 'Department', 'Salary', 'Phone Number', 'Address', 'Date of Birth'));

$conn = new mysqli('localhost', 'root', '', 'SwiftPay');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT id, name, position, department, salary, phone_number, address, date_of_birth FROM employees";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, $row);
    }
}

fclose($output);
$conn->close();
?>
