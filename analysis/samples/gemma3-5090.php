

<?php

// Assuming you have a database connection established (e.g., $conn)
// and a table named 'products' with a 'name' column.

// 1. Get the search term from the input field
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// 2. Sanitize and Escape the search term
$searchTerm = htmlspecialchars($searchTerm); // Important for security

// 3. Build the SQL query
$sql = "SELECT * FROM products WHERE name LIKE '%" . $searchTerm . "%'";

// 4. Execute the query
$result = mysqli_query($conn, $sql);

?>
