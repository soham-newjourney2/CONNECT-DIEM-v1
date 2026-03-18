<?php
// db.php - Database connection for Connect project

$servername = "localhost";
$username = "root";      // XAMPP default
$password = "";          // XAMPP default
$database = "connect_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set character set to UTF-8
$conn->set_charset("utf8");

// Optional: enable error reporting for development
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
?>
