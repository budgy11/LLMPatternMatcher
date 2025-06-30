```php
<?php
$servername = "localhost";
$username = "your_db_username";  //Replace with your MySQL username
$password = "your_db_password";  //Replace with your MySQL password
$dbname = "your_database_name"; //Replace with your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>
```