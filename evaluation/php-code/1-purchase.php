```php
<?php
// db_connect.php

$servername = "localhost";
$username = "your_db_username";
$password = "your_db_password";
$dbname = "ecommerce_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
```