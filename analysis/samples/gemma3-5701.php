

<?php

// Assuming you have a database connection established (e.g., $conn)

// Get the search term from the form
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search term (important for security)
$searchTerm = htmlspecialchars(trim($searchTerm));

// Escape the search term for use in the query (prevents SQL injection)
$escapedSearchTerm = $conn->real_escape_string($searchTerm);


// Perform the search (example using a simple table called 'products')
$sql = "SELECT * FROM products WHERE name LIKE '%" . $escapedSearchTerm . "%'"; //Using LIKE for partial matches
$result = $conn->query($sql);

?>
