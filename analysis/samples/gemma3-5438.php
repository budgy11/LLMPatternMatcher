

<?php

// Assuming you have a database connection established (e.g., mysqli)
// and you want to search through a table named 'products' with a column named 'name'

// Example database connection (replace with your actual credentials)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Get the search term from the GET request
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Escape the search term to prevent SQL injection
$searchTerm = $conn->real_escape_string($searchTerm);

// Build the SQL query
$sql = "SELECT * FROM products WHERE name LIKE '%" . $searchTerm . "%'";

// Execute the query
$result = $conn->query($sql);

?>
