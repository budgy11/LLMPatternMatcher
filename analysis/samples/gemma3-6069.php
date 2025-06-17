

<?php

// Assuming you have a database connection established (e.g., $conn)

// Get the search query from the form
$search_query = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search query (important to prevent XSS)
$search_query = htmlspecialchars($search_query);

// Escape the search query for the database (to prevent SQL injection)
$search_query = $conn->real_escape_string($search_query);


// Build the SQL query
$sql = "SELECT * FROM your_table_name WHERE your_column_name LIKE '%" . $search_query . "%'";

// Execute the query
$result = $conn->query($sql);

?>
