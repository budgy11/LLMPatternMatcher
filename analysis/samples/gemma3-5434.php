    <input type="text" name="search" placeholder="Enter search term">
    <button type="submit">Search</button>
  </form>

</body>
</html>


<?php

// Assuming you have a database connection established (e.g., using mysqli)
// Replace 'your_database_connection' with your actual database connection object.
// For example:
// $db = new mysqli("localhost", "username", "password", "database_name");

// Check for database connection errors
if (!$db) {
  die("Database connection failed");
}

// Get the search term from the form input
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search term to prevent SQL injection
$searchTerm = $db->real_escape_string($searchTerm);

// Escape special characters for use in SQL queries
$searchTerm = str_replace(["--", "/*", "//"], "", $searchTerm);

// Example: Search in a table named 'products' with a column named 'name'
// You'll need to adjust the query based on your specific table and column names.
$query = "SELECT * FROM products WHERE name LIKE '%" . $searchTerm . "%'";

// Execute the query
$result = $db->query($query);

// Check for query errors
if (!$result) {
    die("Query failed: " . $db->error);
}

?>
