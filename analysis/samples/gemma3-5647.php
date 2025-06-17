
</body>
</html>


<?php

// Assuming you have a database connection established (e.g., $conn)
// and you're working with a table named 'products' with a column 'name'

// Get the search term from the form
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize and escape the search term to prevent SQL injection
$searchTerm = htmlspecialchars($searchTerm);

// Query to search the products table
$sql = "SELECT * FROM products WHERE name LIKE '%" . $searchTerm . "%'";

// Execute the query
$result = mysqli_query($conn, $sql);

?>
