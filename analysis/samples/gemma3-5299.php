
</body>
</html>


<?php

// Assuming you have a database connection established ($conn)

// Get the search query from the input field
$search_term = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the input (important for security)
$search_term = htmlspecialchars(trim($search_term));

// Check if the search term is empty - don't perform the search
if (empty($search_term)) {
  echo "<p>Please enter a search term.</p>";
} else {
  // SQL query (adjust table and column names to match your database)
  $query = "SELECT * FROM products WHERE name LIKE '%$search_term%' OR description LIKE '%$search_term%'";

  // Execute the query
  $result = mysqli_query($conn, $query);

  // Check if the query was successful
  if (mysqli_num_rows($result) > 0) {
    // Display the results
    echo "<form method='get' action=''>"; // Form to allow reloading the search
    echo "<input type='text' name='search' value='$search_term' style='width:200px;'>";
    echo "<button type='submit'>Search</button>";
    echo "</form>";

    echo "<h2>Search Results for: $search_term</h2>";
    echo "<ul>";

    while ($row = mysqli_fetch_assoc($result)) {
      echo "<li>";
      echo "<strong>" . $row['name'] . "</strong> - " . $row['description'] . "</li>";
      // You can add other fields from the database to display
    }

    echo "</ul>";

  } else {
    echo "<p>No results found for: $search_term</p>";
  }
}

?>
