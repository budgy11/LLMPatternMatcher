
</body>
</html>


<?php

// Assuming you have a database connection established (e.g., $conn)

// Get the search term from the GET request (or from a form)
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search term to prevent XSS attacks and SQL injection
$searchTerm = htmlspecialchars($searchTerm);  // Crucial for security

// Example:  If you're searching a database table named 'products' with a 'name' column
// You'd typically use a WHERE clause in your SQL query.
// This is a simplified example; adjust the SQL to match your table structure.

// Example SQL query (adapt to your specific database)
$sql = "SELECT * FROM products WHERE name LIKE '%" . $searchTerm . "%'";

// Execute the query
$result = mysqli_query($conn, $sql); // Replace mysqli_query with your database query function

// Check for errors
if (mysqli_error($conn)) {
    echo "Database error: " . mysqli_error($conn);
    exit; // Stop execution if there's an error
}

// Display the search results
echo "<form method='get' action=''>";
echo "<input type='text' name='search' value='" . $searchTerm . "' placeholder='Search...'>";
echo "<button type='submit'>Search</button>";
echo "</form>";

echo "<h2>Search Results:</h2>";
if (mysqli_num_rows($result) > 0) {
    echo "<ul>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<li>" . $row['name'] . " - " . $row['description'] . "</li>"; // Replace with your actual column names
    }
    echo "</ul>";
} else {
    echo "<p>No results found.</p>";
}


// Important: Close the database connection when done
mysqli_close($conn);

?>
