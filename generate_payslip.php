<?php
$conn = new mysqli('localhost', 'root', '', 'SwiftPay');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $employee_id = $_POST['employee_id'];
    $amount = $_POST['amount'];
    $date = date('Y-m-d');

    $sql = "INSERT INTO payslips (employee_id, date, amount) VALUES ('$employee_id', '$date', '$amount')";

    if ($conn->query($sql) === TRUE) {
        echo "Payslip generated successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
