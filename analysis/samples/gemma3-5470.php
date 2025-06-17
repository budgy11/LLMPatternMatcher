

<?php

// Assuming you have a database connection established (e.g., mysqli)
// You'll need to replace 'db_connection' with your actual connection details

// Sample Database Setup (replace with your actual details)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_pass = "your_password";


// Create a database connection
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>
