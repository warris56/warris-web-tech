<?php
session_start();

$conn = new mysqli('localhost', 'root', '', 'SwiftPay');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $department = $_POST['department'];
    $salary = $_POST['salary'];

    $sql = "UPDATE employees SET name=?, email=?, department=?, salary=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssii", $name, $email, $department, $salary, $id);

    if ($stmt->execute()) {
        echo "Employee updated successfully";
        header("Location: view_employees.php");
        exit();
    } else {
        echo "Error updating record: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
