<?php
session_start();

if (!isset($_SESSION['message'])) {
    header("Location: admin_dashboard.php");
    exit();
}

$message = $_SESSION['message'];
unset($_SESSION['message']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Success</title>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            alert("<?php echo htmlspecialchars($message); ?>");
            window.location.href = "admin_dashboard.php";
        });
    </script>
</head>
<body>
</body>
</html>
