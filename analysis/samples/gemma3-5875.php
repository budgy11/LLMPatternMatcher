

<?php

// Assuming you have a database connection established (e.g., $conn)
// and you want to search within a table named 'products'
// with a column named 'name' that contains the search term.

// Get the search term from the form
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search term (important for security - prevent SQL injection)
$searchTerm = htmlspecialchars(trim($searchTerm));

// Prepare the SQL query
$sql = "SELECT * FROM products WHERE name LIKE '%" . $searchTerm . "%'";

// Execute the query
$result = mysqli_query($conn, $sql);

?>
