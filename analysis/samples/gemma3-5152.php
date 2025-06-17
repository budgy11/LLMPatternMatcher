

// Example of using prepared statements (requires mysqli extension)
$sql = "SELECT * FROM $tableName WHERE LOWER(`name`) LIKE ?";
$search = strtolower($searchTerm); // Make sure search term is lowercase
$stmt = mysqli_stmt_prepare($db, $sql);
if ($stmt) {
    if (mysqli_stmt_bind_param($stmt, 's', $search)) {
        $result = mysqli_stmt_execute($stmt);
        while ($row = mysqli_fetch_assoc($result)) {
            // ... display results
        }
        mysqli_stmt_close($stmt);
    } else {
        // Handle errors in mysqli_stmt_bind_param
        echo "Error binding parameters: " . mysqli_error($db);
    }
}


<?php

// Assuming you have a database connection established (e.g., $db_connection)

// Get the search term from the form submission
$searchTerm = isset($_POST['search_term']) ? $_POST['search_term'] : '';

// Sanitize the search term (important for security)
$searchTerm = htmlspecialchars(trim($searchTerm));

// Define the table name where you want to search
$table_name = 'products'; // Replace with your actual table name

// Escape the search term to prevent SQL injection
$escapedSearchTerm = mysqli_real_escape_string($db_connection, $searchTerm);

// Build the SQL query
$query = "SELECT * FROM $table_name WHERE name LIKE '%" . $escapedSearchTerm . "%'"; 

// Execute the query
$result = mysqli_query($db_connection, $query);

?>
