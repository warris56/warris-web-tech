<?php
$conn = new mysqli('localhost', 'root', '', 'SwiftPay');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$filename = "data_export_" . date('Ymd') . ".csv";
header("Content-Type: text/csv");
header("Content-Disposition: attachment; filename=\"$filename\"");

$delimiter = ",";
$fields = array('ID', 'Name', 'Email', 'Department', 'Salary', 'Created At');
$fp = fopen('php://output', 'w');
fputcsv($fp, $fields, $delimiter);

$sql = "SELECT id, name, email, department, salary, created_at FROM employees";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        fputcsv($fp, $row, $delimiter);
    }
}

fclose($fp);
$conn->close();
exit();
?>
