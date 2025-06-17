
</body>
</html>


<?php

// Assuming you have a database connection established (e.g., $conn)

// Get the search term from the search bar
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize and escape the search term to prevent SQL injection
$searchTerm = htmlspecialchars($searchTerm);

// Handle empty search term
if (empty($searchTerm)) {
  $results = []; // Return an empty array if no search term is provided
} else {
  // Build the SQL query
  $sql = "SELECT * FROM your_table_name WHERE your_column_name LIKE '%" . $searchTerm . "%'";

  // Execute the query
  $result = mysqli_query($conn, $sql);

  if ($result) {
    // Process the results
    $results = [];
    while ($row = mysqli_fetch_assoc($result)) {
      $results[] = $row;
    }
  } else {
    // Handle query errors
    echo "Error executing query: " . mysqli_error($conn);
  }
}

?>
