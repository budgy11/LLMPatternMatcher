    <input type="text" name="search_term" placeholder="Enter search term">
    <input type="submit" value="Search">
  </form>

</body>
</html>


<?php

// Assuming you have a database connection established (e.g., $conn)
// and a table named 'products' with a 'name' column.

// Get the search term from the user (e.g., from a form input)
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search term to prevent SQL injection
$searchTerm = htmlspecialchars($searchTerm);

// Example query (replace with your actual table and column names)
$sql = "SELECT * FROM products WHERE name LIKE '%" . $searchTerm . "%'";

// Execute the query
$result = mysqli_query($conn, $sql); // Replace mysqli_query with your database library's equivalent

// Check for errors
if (mysqli_error($conn)) {
    echo "<p style='color: red;'>Error: " . mysqli_error($conn) . "</p>";
    exit;
}

// Display the search results
echo "<form method='get' action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "'>";
echo "<input type='text' name='search' value='" . $searchTerm . "' placeholder='Search Products'>";
echo "<button type='submit'>Search</button>";
echo "</form>";

echo "<h2>Search Results:</h2>";

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<p>" . htmlspecialchars($row['name']) . "</p>";
        // You can display other columns from the 'products' table here
    }
} else {
    echo "<p>No products found matching your search.</p>";
}

// Close the database connection (important!)
mysqli_close($conn);

?>
