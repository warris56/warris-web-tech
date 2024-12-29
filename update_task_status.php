<?php
session_start();

$conn = new mysqli('localhost', 'root', '', 'SwiftPay');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['id']) && isset($_GET['status'])) {
    $id = $_GET['id'];
    $status = $_GET['status'];

    $sql = "UPDATE tasks SET status='$status' WHERE id='$id'";

    if ($conn->query($sql) === TRUE) {
        header("Location: manage_tasks.php");
    } else {
        echo "Error updating task status: " . $conn->error;
    }
}

$conn->close();
?>
