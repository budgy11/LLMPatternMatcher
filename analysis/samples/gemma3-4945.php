

<?php
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database_name";

$db = mysqli_connect($host, $username, $password, $database);

if (!$db) {
    die("Connection failed: " . mysqli_connect_error());
}

// Your other code here, including the search bar logic.
?>
