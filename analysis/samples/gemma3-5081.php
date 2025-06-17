

<?php

// Assuming you have a database connection established (e.g., $conn)

// Get the search term from the search bar (assuming it's named "search")
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search term to prevent XSS attacks
$searchTerm = htmlspecialchars($searchTerm);  // IMPORTANT for security

// Database query (example using a hypothetical 'products' table)
$sql = "SELECT * FROM products WHERE name LIKE '%" . $searchTerm . "%' OR description LIKE '%" . $searchTerm . "%'";
$result = $conn->query($sql);

?>
