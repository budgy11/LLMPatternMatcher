

<?php

$host = "localhost";
$username = "your_db_username";
$password = "your_db_password";
$database = "your_database_name";

$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

?>
