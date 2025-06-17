

<?php

// Assuming you have a database connection established (e.g., $db)

// Get the search query from the form
$search_query = $_GET['search'];

// Sanitize and prepare the query to prevent SQL injection
if (empty($search_query)) {
    $search_query = ''; // Or handle the empty case differently (e.g., display no results)
}

// Escape the search query for use in the query (important for security)
$safe_query = mysqli_real_escape_string($db, $search_query); // Replace $db with your actual database connection

// Build the SQL query
$sql = "SELECT * FROM your_table_name WHERE your_column_name LIKE '%" . $safe_query . "%'";

// Execute the query
$result = mysqli_query($db, $sql);

// Display the results (simplified example - adapt to your needs)
if (mysqli_num_rows($result) > 0) {
    echo "<ul>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<li>" . $row['your_column_name'] . "</li>";
    }
    echo "</ul>";
} else {
    echo "No results found for '" . $search_query . "'.";
}

?>
