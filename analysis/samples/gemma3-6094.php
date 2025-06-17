    <input type="text" name="search_term" placeholder="Enter search term">
    <button type="submit">Search</button>
  </form>

</body>
</html>


<?php

// Assuming you have a database connection established (e.g., using mysqli)
// For this example, we'll use a placeholder connection.  Replace with your actual connection.
$db = new mysqli("localhost", "username", "password", "database_name");

// Check connection
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Get the search term from the form
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize and prepare the search term for the database query
$searchTerm = $db->real_escape_string($searchTerm); // Escape for SQL injection

// Build the SQL query
$query = "SELECT * FROM your_table_name WHERE your_column_name LIKE '%" . $searchTerm . "%'";
// Replace 'your_table_name' and 'your_column_name' with your actual table and column names.
// 'LIKE' with '%' allows for partial matches.


// Execute the query
$result = $db->query($query);

?>
