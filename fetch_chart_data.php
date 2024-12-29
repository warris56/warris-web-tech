<?php
$conn = new mysqli('localhost', 'root', '', 'SwiftPay');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Payroll Data
$payrollResult = $conn->query("SELECT MONTHNAME(date) AS month, SUM(amount) AS total FROM payslips GROUP BY month ORDER BY date");
$payrollData = array();
$payrollLabels = array();
while ($row = $payrollResult->fetch_assoc()) {
    $payrollLabels[] = $row['month'];
    $payrollData[] = $row['total'];
}

// Department Data
$departmentResult = $conn->query("SELECT department, COUNT(*) AS count FROM employees GROUP BY department");
$departmentData = array();
$departmentLabels = array();
$departmentColors = array();
$colors = ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40'];
$i = 0;
while ($row = $departmentResult->fetch_assoc()) {
    $departmentLabels[] = $row['department'];
    $departmentData[] = $row['count'];
    $departmentColors[] = $colors[$i % count($colors)];
    $i++;
}

$response = array(
    "payroll" => array(
        "labels" => $payrollLabels,
        "values" => $payrollData
    ),
    "department" => array(
        "labels" => $departmentLabels,
        "values" => $departmentData,
        "colors" => $departmentColors
    )
);

header('Content-Type: application/json');
echo json_encode($response);

$conn->close();
?>
