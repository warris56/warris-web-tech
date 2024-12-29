<?php
session_start();

$conn = new mysqli('localhost', 'root', '', 'SwiftPay');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $username = $conn->real_escape_string($username);

    // Fetch user details from employee_logins table
    $sql = "SELECT * FROM employee_logins WHERE username='$username'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();

        if (password_verify($password, $row['password'])) {
            session_regenerate_id(true);
            $_SESSION['user'] = $username;

            // Fetch employee details from employees table
            $employee_id = $row['employee_id'];
            $employee_sql = "SELECT * FROM employees WHERE id='$employee_id'";
            $employee_result = $conn->query($employee_sql);

            if ($employee_result && $employee_result->num_rows > 0) {
                $employee = $employee_result->fetch_assoc();
                $_SESSION['employee'] = $employee;

                // Record attendance
                $date = date("Y-m-d");
                $status = 'Present';
                
                $attendance_sql = "INSERT INTO attendance (employee_id, date, status)
                                   VALUES ('$employee_id', '$date', '$status')
                                   ON DUPLICATE KEY UPDATE status='$status'";

                if ($conn->query($attendance_sql) !== TRUE) {
                    echo "Error recording attendance: " . $conn->error;
                }

                // Log the login activity
                $user_id = $username; // or use $employee_id if that makes more sense
                $action_type = 'login';
                $action_details = 'Employee logged in';

                $log_sql = "INSERT INTO audit_logs (user_type, user_id, action_type, action_details) 
                            VALUES ('employee', ?, ?, ?)";
                $log_stmt = $conn->prepare($log_sql);
                $log_stmt->bind_param("sss", $user_id, $action_type, $action_details);
                $log_stmt->execute();
                $log_stmt->close();

                header("Location: employee_dashboard.php");
                exit();
            } else {
                echo "<script>alert('Employee details not found.'); window.location.href = 'employee_login.html';</script>";
            }
        } else {
            echo "<script>alert('Invalid password.'); window.location.href = 'employee_login.html';</script>";
        }
    } else {
        echo "<script>alert('No user found.'); window.location.href = 'employee_login.html';</script>";
    }
} else {
    echo "<script>alert('Invalid request method.'); window.location.href = 'employee_login.html';</script>";
}

$conn->close();
?>
