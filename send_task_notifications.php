<?php
$conn = new mysqli('localhost', 'root', '', 'SwiftPay');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT t.id, e.username, t.title, t.description, t.priority, t.due_date
        FROM tasks t
        JOIN employees e ON t.employee_id = e.id
        WHERE t.notification_status = 'Pending'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Assuming you have a function to send notifications, e.g., via email
        $to = $row['username'] . "@example.com"; // Replace with the actual email address field
        $subject = "New Task Assigned: " . $row['title'];
        $message = "You have been assigned a new task.\n\n";
        $message .= "Title: " . $row['title'] . "\n";
        $message .= "Description: " . $row['description'] . "\n";
        $message .= "Priority: " . $row['priority'] . "\n";
        $message .= "Due Date: " . $row['due_date'] . "\n";

        // Send the email (you need to configure your email settings)
        mail($to, $subject, $message);

        // Update notification status
        $update_sql = "UPDATE tasks SET notification_status = 'Sent' WHERE id = " . $row['id'];
        $conn->query($update_sql);
    }
}

$conn->close();
?>
