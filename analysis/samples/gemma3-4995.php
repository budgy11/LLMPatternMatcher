

<?php

// Assuming you have a database connection established (e.g., $conn)
// and you want to search within a table called 'products'

// Get the search term from the form input
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize and escape the search term to prevent SQL injection
$searchTerm = htmlspecialchars(trim($searchTerm));

// Database connection (replace with your actual connection)
// $conn = new mysqli("localhost", "username", "password", "database");
// if ($conn->connect_error) {
//   die("Connection failed: " . $conn->connect_error);
// }


// Example:  Assuming the 'products' table has columns 'id', 'name', 'description'
// The following query demonstrates basic searching.  Adjust column names and
// the WHERE clause for your specific table structure.

$sql = "SELECT id, name, description FROM products WHERE name LIKE '%" . $searchTerm . "%' OR description LIKE '%" . $searchTerm . "%'";

$result = $conn->query($sql);

?>
