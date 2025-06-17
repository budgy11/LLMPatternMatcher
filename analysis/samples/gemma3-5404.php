
</body>
</html>


<?php

// Assuming you have a database connection established (e.g., $db)
// and have a table named 'products' with a 'name' column.

// Get the search term from the search box
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize and escape the search term to prevent SQL injection
$searchTerm = htmlspecialchars($searchTerm);

// Build the SQL query
$sql = "SELECT * FROM products WHERE name LIKE '%" . $searchTerm . "%'";

// Execute the query
$result = mysqli_query($db, $sql); // Replace $db with your database connection

?>
