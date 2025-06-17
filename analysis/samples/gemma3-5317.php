

<?php

// Assuming you have a database connection established (e.g., $db is your database object)

// Function to handle the search form and query the database using prepared statements
function handleSearch($searchTerm) {
  // Sanitize the search term (important, but prepared statements provide a base layer of defense)
  $searchTerm = mysqli_real_escape_string($db, $searchTerm);

  // Build the SQL query using a placeholder
  $query = "SELECT * FROM your_table_name WHERE your_column_name LIKE ?";

  // Prepare the statement
  $stmt = mysqli_prepare($db, $query);

  if ($stmt === false) {
    echo "Error preparing statement: " . mysqli_error($db);
    return false;
  }

  // Bind the parameter
  mysqli_stmt_bind_param($stmt, "s", $searchTerm);  // "s" indicates a string parameter

  // Execute the query
  mysqli_stmt_execute($stmt);

  // Get the result set
  $result = mysqli_stmt_get_result($stmt);

  // Close the statement
  mysqli_stmt_close($stmt);

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
