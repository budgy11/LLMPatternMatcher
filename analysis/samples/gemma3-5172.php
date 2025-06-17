
</body>
</html>


<?php

// Include your database connection file (adjust path as needed)
require_once 'db_connection.php';

// Get the search term from the form submission
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search term to prevent SQL injection
$searchTerm = htmlspecialchars($searchTerm);

// SQL query to search in the specified table (replace 'your_table' and 'column_to_search' with your actual table and column names)
$query = "SELECT * FROM your_table WHERE column_to_search LIKE '%$searchTerm%'";

// Execute the query
$result = mysqli_query($connection, $query);

?>
