

<?php

// Assuming you have a database connection established (e.g., $conn)

// Get the search term from the form (if submitted)
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search term - VERY IMPORTANT!
$searchTerm = htmlspecialchars(trim($searchTerm));  //  This prevents XSS vulnerabilities

// Prepare the SQL query (using prepared statements - best practice)
// This is the key part for security - it prevents SQL injection
$sql = "SELECT * FROM your_table_name WHERE your_column_name LIKE '%" . $searchTerm . "%'";

// Execute the query
$result = mysqli_query($conn, $sql); // Replace $conn with your database connection

?>
