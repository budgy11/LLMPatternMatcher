
</body>
</html>


<?php

// Assuming you have a database connection established (e.g., mysqli)
// Let's assume $db is your database connection object

// Function to handle the search query
function performSearch($search_term, $table_name, $columns) {
  // Sanitize the search term to prevent SQL injection
  $search_term = mysqli_real_escape_string($db, $search_term);

  // Build the SQL query
  $sql = "SELECT * FROM " . $table_name . " WHERE ";

  // Add the search clause to all columns in the $columns array
  foreach ($columns as $column) {
    $sql .= "{$column} LIKE '%" . $search_term . "%'";

    // Add an "OR" operator if it's not the last column
    if (count($columns) > $column) {
      $sql .= " OR ";
    }
  }

  $result = mysqli_query($db, $sql);

  if ($result) {
    return $result;
  } else {
    return false; // Indicate an error
  }
}


// --- Example Usage ---

// 1. Get the search term from the form (e.g., using an HTML form)
$search_term = isset($_POST['search_term']) ? $_POST['search_term'] : '';

// 2. Define the table and columns to search
$table_name = 'products'; // Change this to your table name
$columns = ['name', 'description', 'category']; // Columns to search in

// 3. Perform the search
$search_result = performSearch($search_term, $table_name, $columns);


// 4. Display the results (or an empty message if no results)
if ($search_result) {
  echo "<h2>Search Results for: " . htmlspecialchars($search_term) . "</h2>";
  echo "<table border='1'>";
  while ($row = mysqli_fetch_assoc($search_result)) {
    echo "<tr>";
    foreach ($row as $key => $value) {
      echo "<td>" . htmlspecialchars($value) . "</td>"; // Escape values for output
    }
    echo "</tr>";
  }
  echo "</table>";
} else {
  echo "<p>No results found for: " . htmlspecialchars($search_term) . "</p>";
}


?>
