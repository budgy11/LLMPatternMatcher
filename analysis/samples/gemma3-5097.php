
... (rest of the HTML and display code) ...


<?php

// Assuming you have a database connection established and $search_term is the value entered in the search bar.

// **Important:** This code assumes you have a database connection ($db) and a table named 'products' (or whatever your data table is called).  Replace these with your actual connection details and table name.

// Example database connection (replace with your actual credentials)
$db = new mysqli("localhost", "your_username", "your_password", "your_database");

// Check connection
if ($db->connect_error) {
  die("Connection failed: " . $db->connect_error);
}

// Get the search term from the search bar (assuming it's in a hidden input field named 'search')
$search_term = $_POST['search'];

// Sanitize the search term to prevent SQL injection
$search_term = $db->real_escape_string($search_term);

// Construct the SQL query
$query = "SELECT * FROM products WHERE name LIKE '%" . $search_term . "%'";  // Case-insensitive search
//  OR  Use  `LOWER()` for case-insensitive comparison
//  $query = "SELECT * FROM products WHERE name LIKE '%" . mb_strtolower($search_term, 'UTF-8') . "%'";

// Execute the query
$result = $db->query($query);

?>
