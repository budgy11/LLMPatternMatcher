
</body>
</html>


<?php

// Assuming you have a database connection established (e.g., $db)
// and you want to search through a table named 'products'
// with a column named 'name'

// Get the search term from the input form
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search term (Important for security)
$searchTerm = htmlspecialchars($searchTerm);

// Escape the search term for SQL (Prevents SQL injection)
$searchTerm = $db->real_escape_string($searchTerm); // Assuming $db is your database connection object

// Build the SQL query
$sql = "SELECT * FROM products WHERE name LIKE '%" . $searchTerm . "%'";

// Execute the query
$result = $db->query($sql);

// Check if the query was successful
if ($result) {
  // Display the search results
  echo "<form method='get' action=''>";
  echo "<input type='text' name='search' value='" . $searchTerm . "' placeholder='Search products...'>";
  echo "<button type='submit'>Search</button>";
  echo "</form>";

  echo "<h2>Search Results:</h2>";
  if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
      echo "<p>" . $row['name'] . " - " . $row['description'] . "</p>"; // Adjust columns as needed
    }
  } else {
    echo "<p>No products found matching your search.</p>";
  }
} else {
  echo "<p>Error executing the query.</p>";
  // Log the error for debugging purposes
  error_log("Error: " . $db->error);
}

?>
