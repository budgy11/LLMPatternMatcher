

<?php

// Assuming you have a database connection established (e.g., $db is your connection object)

// Get the search term from the input field
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search term (important for security and preventing SQL injection)
$searchTerm = htmlspecialchars(trim($searchTerm));

// Escape the search term for use in the database query
$searchTerm = $db->real_escape_string($searchTerm); // Assuming $db is your database connection object


// You can add more filtering/validation here if needed,
// such as allowing only alphanumeric characters or limiting the search
// to specific fields.

// SQL Query (Example - Adjust based on your database schema)
$sql = "SELECT * FROM your_table_name WHERE your_column_name LIKE '%" . $searchTerm . "%'";

// Execute the query
$result = $db->query($sql);

// Check if the query was successful
if ($result) {
  // Display the search results
  echo "<form method='get' action='your_script.php'>
         <input type='text' name='search' value='" . $searchTerm . "' placeholder='Search...'>
         <button type='submit'>Search</button>
       </form>";
  echo "<br>";
  echo "<h2>Search Results:</h2>";

  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      echo "<div>";
      // Display the data from each row
      echo "<p>" . htmlspecialchars($row['your_column_name']) . "</p>";  // Escape the output as well
      echo "</div>";
    }
  } else {
    echo "<p>No results found for '" . $searchTerm . "'.</p>";
  }

} else {
  // Handle query errors
  echo "<p>Error executing query: " . $db->error . "</p>";
}

?>
