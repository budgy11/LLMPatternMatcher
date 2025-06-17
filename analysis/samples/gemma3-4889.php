
</body>
</html>


<?php

// Assuming you have a database connection established (e.g., mysqli)
// Let's assume $db is a mysqli connection object.

// Example setup for demonstration purposes
// Replace with your actual database connection details
$db = mysqli_connect("localhost", "username", "password", "database_name");
if (!$db) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get the search term from the form
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Escape the search term to prevent SQL injection
$searchTerm = mysqli_real_escape_string($db, $searchTerm);


// Prepare the search query
$query = "SELECT * FROM your_table_name WHERE your_column_name LIKE '%" . $searchTerm . "%'";  // Replace your_table_name and your_column_name with your actual names


// Execute the query
$result = mysqli_query($db, $query);

?>
