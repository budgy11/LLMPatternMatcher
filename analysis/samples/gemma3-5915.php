

<?php

// Assuming you have a database connection established (e.g., $conn)

// Get the search term from the GET request
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search term (important for security)
$searchTerm = htmlspecialchars(trim($searchTerm));

// Example: Searching in a table called 'products' with a column 'name'
// You'll likely need to adapt this query to your specific database structure and needs

// Build the SQL query
$sql = "SELECT * FROM products WHERE name LIKE '%" . $searchTerm . "%'";

// Execute the query
$result = $conn->query($sql);

// Display the results (or a message if no results are found)
?>
