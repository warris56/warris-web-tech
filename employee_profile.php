<?php
session_start();
if (!isset($_SESSION['employee'])) {
    header("Location: index.html");
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'SwiftPay');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$employee_id = $_SESSION['employee']['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $department = $_POST['department'];

    $sql = "UPDATE employees SET email=?, department=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $email, $department, $employee_id);

    if ($stmt->execute()) {
        // Update session data
        $_SESSION['employee']['email'] = $email;
        $_SESSION['employee']['department'] = $department;

        echo "Profile updated successfully";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

$sql = "SELECT id, name, email, department FROM employees WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $employee_id);
$stmt->execute();
$result = $stmt->get_result();
$employee = $result->fetch_assoc();

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Profile</title>
    <link rel="stylesheet" href="employee_profils.css">
</head>
<body>
    <header>
        <nav>
            <div class="nav-logo">
                <img src="" alt="Logo">
            </div>
            <div class="nav-links">
                <a href="employee_profile.php">Profile</a>
                <a href="employee_tasks.php">My Tasks</a>
                <a href="download_payslips.php">View Payslips</a>
                <a href="change_password.php">Change Password</a>
                <a href="submit_feedback.php">Submit Feedback</a>
                <a href="employeenoti.php"> View Notification</a>
                <a href="index.html">Logout</a>
            </div>
            <div class="nav-icons">
                <i class="fas fa-search"></i>
                <i class="fas fa-bell"></i>
            </div>
        </nav>
    </header>

    <div class="profile-container">
        <h2>My Profile</h2>
        <form method="POST" action="employee_profile.php">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($employee['name']); ?>" readonly>
            
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($employee['email']); ?>" required>
            
            <label for="department">Department:</label>
            <input type="text" id="department" name="department" value="<?php echo htmlspecialchars($employee['department']); ?>" required>
            
            <button type="submit">Update Profile</button>
        </form>
    </div>
</body>
</html>
