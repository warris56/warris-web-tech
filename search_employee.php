<?php
header('Content-Type: application/json');

$conn = new mysqli('localhost', 'root', '', 'SwiftPay');

// Check connection
if ($conn->connect_error) {
    die(json_encode(array('error' => 'Connection failed: ' . $conn->connect_error)));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];
    error_log("Received ID: $id"); // Log the received ID

    // Prepare and execute the query
    $sql = "SELECT name, email, department, salary FROM employees WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        error_log('Error preparing statement: ' . $conn->error); // Log preparation errors
        echo json_encode(array('error' => 'Error preparing statement: ' . $conn->error));
        exit();
    }

    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $employee = $result->fetch_assoc();
        error_log("Employee Found: " . json_encode($employee)); // Log the found employee
        echo json_encode($employee);
    } else {
        error_log("No employee found with ID: $id"); // Log if no employee is found
        echo json_encode(null);
    }

    $stmt->close();
} else {
    error_log('Invalid request or missing ID'); // Log invalid request or missing ID
    echo json_encode(array('error' => 'Invalid request or missing ID.'));
}

$conn->close();
?>
