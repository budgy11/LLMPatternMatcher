
</body>
</html>


<?php

// Assuming you have a database connection established (e.g., $db)
// and you want to search through a table named 'products'
// and a column named 'name'

// Get the search term from the form (or URL)
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search term to prevent SQL injection
$searchTerm = htmlspecialchars($searchTerm);

// If no search term is provided, redirect to the main page
if (empty($searchTerm)) {
  header("Location: index.php"); // Redirect to your main page
  exit;
}

// Build the SQL query
$sql = "SELECT * FROM products WHERE name LIKE '%" . $searchTerm . "%'";

// Execute the query
$result = mysqli_query($db, $sql);

?>
