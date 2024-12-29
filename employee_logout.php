<?php
session_start();
session_unset();
session_destroy();

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Ensure no output before header call
ob_start();

header("Location: employee_login.html");
exit();

// Flush the output buffer
ob_end_flush();
?>
