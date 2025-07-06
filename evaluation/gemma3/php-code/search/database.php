<?php

$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_ecommerce_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to execute queries
function executeQuery($conn, $sql) {
    $result = $conn->query($sql);
    if (!$result) {
        echo "Error executing query: " . $conn->error;
        return false;
    }
    return $result;
}

?>
