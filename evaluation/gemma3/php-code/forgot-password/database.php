<?php

$host = "localhost";
$username = "your_db_username";
$password = "your_db_password";
$database = "ecommerce_db";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// If you need to perform database operations here, do it from this file.

?>
