

<?php

// Assuming you have a database connection established (e.g., $db connection)

// Get the search term from the form
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search term (important to prevent XSS)
$searchTerm = htmlspecialchars($searchTerm);

// Handle the search request
if ($searchTerm) {
  // Build the query (replace with your actual database query)
  $query = "SELECT * FROM your_table WHERE your_column LIKE '%" . $searchTerm . "%'";

  // Execute the query
  $result = mysqli_query($db, $query);  // Replace $db with your database connection

  // Display the search results
  if (mysqli_num_rows($result) > 0) {
    echo "<h2>Search Results for: " . $searchTerm . "</h2>";
    echo "<table border='1'>";
    while ($row = mysqli_fetch_assoc($result)) {
      echo "<tr>";
      foreach ($row as $key => $value) {
        echo "<td>" . $value . "</td>";
      }
      echo "</tr>";
    }
    echo "</table>";
  } else {
    echo "<p>No results found for: " . $searchTerm . "</p>";
  }
}
?>
