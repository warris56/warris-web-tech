<?php
session_start();

$conn = new mysqli('localhost', 'root', '', 'SwiftPay');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$employee_id = $_GET['id'];
$sql = "SELECT * FROM employees WHERE id='$employee_id'";
$result = $conn->query($sql);

if (!$result || $result->num_rows === 0) {
    die("Employee not found.");
}

$employee = $result->fetch_assoc();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Employee</title>
    <link rel="stylesheet" href="edi.css">
</head>
<body>
    <div class="edit-employee-container">
        <h2>Edit Employee</h2>
        <nav>
            <a href="admin_dashboard.php">Admin Dashboard</a> |
            <a href="logout.php">Logout</a>
        </nav>
        <form method="POST" action="update_employee.php">
            <input type="hidden" name="id" value="<?php echo $employee['id']; ?>">
            
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($employee['name']); ?>" required>
            
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($employee['email']); ?>" required>
            
            <label for="department">Department:</label>
            <input type="text" id="department" name="department" value="<?php echo htmlspecialchars($employee['department']); ?>" required>
            
            <label for="salary">Salary:</label>
            <input type="number" id="salary" name="salary" value="<?php echo htmlspecialchars($employee['salary']); ?>" required>
            
            <button type="submit">Update Employee</button>
        </form>
    </div>
</body>
</html>
