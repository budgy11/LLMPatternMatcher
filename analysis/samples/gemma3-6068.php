
</body>
</html>


<?php
// Assuming you're using MySQLi
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// ... (rest of your search code) ...

$conn->close(); // Close the connection when you're done
?>
