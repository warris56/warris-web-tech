<?php
session_start();

function generateRandomID($conn) {
    // Generate a random number between a specified range
    $randomID = mt_rand(100000, 999999);
    
    // Check if the generated ID already exists in the database
    $check_sql = "SELECT id FROM employees WHERE id = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("i", $randomID);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        // If the ID exists, generate a new one
        return generateRandomID($conn);
    } else {
        return $randomID;
    }
}

// Establish database connection
$conn = new mysqli('localhost', 'root', '', 'SwiftPay');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $department = $_POST['department'];
    $basic_salary = $_POST['basic'];
    $rent_allowance = $_POST['rent_allowance'];
    $car_allowance = $_POST['car_allowance'];
    
    // Calculate the total salary
    $total_salary = $basic_salary + $rent_allowance + $car_allowance;
    
    // Generate a random ID for the new employee
    $randomID = generateRandomID($conn);

    // Prepare SQL statement for inserting a new employee
    $sql = "INSERT INTO employees (id, name, email, department, salary, created_at) 
            VALUES (?, ?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssd", $randomID, $name, $email, $department, $total_salary);

    // Execute the statement and check for success
    if ($stmt->execute()) {
        // Log the action if the employee was created successfully
        $admin_id = $_SESSION['admin_id'] ?? 'unknown'; // Get admin ID from session
        $action_type = 'create_employee';
        $action_details = "Created employee: $name, ID: $randomID";

        // Prepare SQL statement for logging
        $log_sql = "INSERT INTO audit_logs (user_type, user_id, action_type, action_details) 
                    VALUES ('admin', ?, ?, ?)";
        $log_stmt = $conn->prepare($log_sql);
        $log_stmt->bind_param("sss", $admin_id, $action_type, $action_details);
        $log_stmt->execute();

        // Set session message for success including the employee ID
        $_SESSION['message'] = "Employee registered successfully with ID: $randomID!";
        // Redirect to the success page
        header("Location: success.php");
        exit();
    } else {
        // Output error if the employee creation failed
        echo "Error: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>
