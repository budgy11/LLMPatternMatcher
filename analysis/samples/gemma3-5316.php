

<?php

// Assuming you have a database connection established (e.g., $db is your database object)

// Function to handle the search form and query the database
function handleSearch($searchTerm) {
  // Sanitize the search term to prevent SQL injection
  $searchTerm = mysqli_real_escape_string($db, $searchTerm);

  // Build the SQL query
  $query = "SELECT * FROM your_table_name WHERE your_column_name LIKE '%" . $searchTerm . "%'";

  // Execute the query
  $result = mysqli_query($db, $query);

  // Check for errors
  if (mysqli_errno($db)) {
    echo "Error: " . mysqli_error($db);
    return false;
  }

  // Display the results (or an empty message if no results are found)
  if (mysqli_num_rows($result) > 0) {
    echo "<form method='get' action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "'>";
    echo "<input type='text' name='search' value='" . htmlspecialchars($searchTerm) . "' placeholder='Search...'>";
    echo "<button type='submit'>Search</button>";
    echo "</form>";

    echo "<ul>";
    while ($row = mysqli_fetch_assoc($result)) {
      echo "<li>" . htmlspecialchars($row['your_column_name']) . "</li>"; // Replace 'your_column_name' with the actual column name
    }
    echo "</ul>";
  } else {
    echo "<p>No results found for '" . htmlspecialchars($searchTerm) . "'.</p>";
  }

  // Close the database connection (important!)
  mysqli_close($db);
}

// Example Usage (This is called when the form is submitted)
if (isset($_GET['search'])) {
  handleSearch($_GET['search']);
} else {
  // Show the search form if no search term is provided
  echo "<form method='get' action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "'>";
  echo "<input type='text' name='search' value='' placeholder='Search...'>";
  echo "<button type='submit'>Search</button>";
  echo "</form>";
}
?>
