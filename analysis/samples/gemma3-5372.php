
</body>
</html>


<?php

// Assuming you have a database connection established (e.g., $conn)
// You'll need to replace this with your actual database connection

// Database connection (example - adapt to your setup)
// $conn = new mysqli("localhost", "username", "password", "database_name");
// if ($conn->connect_error) {
//   die("Connection failed: " . $conn->connect_error);
// }

// Get the search term from the input field (using $_GET or $_POST)
$search_term = isset($_GET['search']) ? $_GET['search'] : '';
// Alternatively, if you want to use $_POST:
// $search_term = isset($_POST['search']) ? $_POST['search'] : '';

// Sanitize the search term to prevent SQL injection
$search_term = htmlspecialchars(trim($search_term));

// Escape the search term for use in the query (important for security)
$search_term = $conn->real_escape_string($search_term);

// Get the table name to search (can be passed as a parameter or hardcoded)
$table_name = 'products'; // Replace with your table name

// Build the search query
$query = "SELECT * FROM " . $table_name . " WHERE ";

// Add the search clause based on the search term and the column to search
if (!empty($search_term)) {
  $query .= "`name` LIKE '%" . $search_term . "%'";  // Search in the 'name' column
  // You can modify this to search in other columns (e.g., `description`)
  // $query .= " OR `description` LIKE '%" . $search_term . "%'";
}


// Execute the query
$result = $conn->query($query);

// Display the results
echo "<form method='get' action='search_results.php'>
       <input type='text' name='search' value='" . $search_term . "' placeholder='Search...' >
       <button type='submit'>Search</button>
     </form>";

if ($result->num_rows > 0) {
  echo "<ul>";
  while($row = $result->fetch_assoc()) {
    echo "<li>" . $row['name'] . " - " . $row['description'] . "</li>"; // Adjust column names
  }
  echo "</ul>";
} else {
  echo "No results found for '" . $search_term . "'.";
}

// Close the database connection (optional, but good practice)
$conn->close();

?>
