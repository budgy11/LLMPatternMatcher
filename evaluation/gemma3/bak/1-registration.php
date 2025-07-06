<?php

$host = "localhost"; // Database host
$username = "your_username"; // Your MySQL username
$password = "your_password"; // Your MySQL password
$database = "ecommerce_db"; // Database name

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Optional:  Establish a persistent connection (for slightly better performance)
// $conn->set_type("resource");  // This is important for persistence.
?>
