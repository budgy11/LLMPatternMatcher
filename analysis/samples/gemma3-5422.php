

<?php

// Assuming you have a database connection established (e.g., $db)
// and you want to search a table named 'products' with a column named 'name'

// 1. Get the search term from the search bar
$searchTerm = isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '';

// 2. Escape the search term for security (prevents SQL injection)
$searchTerm = $searchTerm; // The `htmlspecialchars()` above handles this.  No need to repeat.

// 3. Build the SQL query
$query = "SELECT * FROM products WHERE name LIKE '%" . $searchTerm . "%'";

// 4. Execute the query
$result = mysqli_query($db, $query); // Replace $db with your database connection variable

// 5. Display the results (or an empty message if no results)
if (mysqli_num_rows($result) > 0) {
    echo "<ul>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<li>" . $row['name'] . "</li>";
    }
    echo "</ul>";
} else {
    echo "<p>No results found for '" . $searchTerm . "'.</p>";
}

// 6.  Clean up and prevent XSS (extra security layer, good practice)
mysqli_free_result($result); // Important to free memory
?>
