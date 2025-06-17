
</body>
</html>


<?php

// Assuming you have a database connection established (e.g., $db)

// Get the search term from the form
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search term
$searchTerm = htmlspecialchars(trim($searchTerm));

// Build the SQL query (example for a 'products' table)
$tableName = 'products'; // Change this to your table name
$columnName = 'name'; // Change this to the column you want to search in

$sql = "SELECT * FROM $tableName WHERE $columnName LIKE '%$searchTerm%'";

// Execute the query
$result = mysqli_query($db, $sql);

// Display the search results (example)
?>
