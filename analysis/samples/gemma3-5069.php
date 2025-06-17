
</body>
</html>


<?php
// Connect to your database
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Example data from a database (replace with your query)
$data = $conn->query("SELECT fruit_name FROM fruits"); // Assuming you have a 'fruits' table

// ... (rest of the code from above) ...

// Close the connection
$conn->close();
?>
