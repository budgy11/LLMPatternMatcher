

<?php

// Assuming you have a database connection established (e.g., mysqli)
// and you're using a variable called $conn

// Get the search term from the user (using GET method)
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search term (important to prevent SQL injection)
$searchTerm = htmlspecialchars(trim($searchTerm));

// Escape the search term for use in the query
$searchTerm = $conn->real_escape_string($searchTerm); // Use real_escape_string for mysqli

//  Database table name (replace with your actual table name)
$tableName = 'your_table_name';

// Build the SQL query (example: searching in a column named 'name')
$sql = "SELECT * FROM $tableName WHERE name LIKE '%" . $searchTerm . "%'";

// Execute the query
$result = $conn->query($sql);

?>
