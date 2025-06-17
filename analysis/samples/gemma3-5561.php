
</body>
</html>


<?php

// Assuming you have a database connection established (e.g., mysqli)
// Let's call it $conn

// Function to handle the search query
function search_data($search_term, $table_name, $search_columns) {
  $query = "SELECT * FROM " . $table_name . " WHERE ";

  $conditions = [];
  foreach ($search_columns as $column) {
    $conditions[] = "`" . $column . "`" . " LIKE '%" . $search_term . "%'";
  }

  $query = implode(" OR ", $conditions);

  $result = $conn->query($query);

  if ($result->num_rows > 0) {
    return $result;
  } else {
    return false;
  }
}

// Example Usage:

// 1. Get the search term from the user (e.g., from a form submission)
$search_term = $_POST['search_term'] ?? ''; // Get from POST, default to empty string

// 2. Define the table name to search in.  Replace 'products' with your table name.
$table_name = 'products';

// 3. Define the columns to search in. Replace with your column names.
$search_columns = ['name', 'description', 'category'];  // Columns you want to search

// 4. Perform the search
$results = search_data($search_term, $table_name, $search_columns);

// 5. Display the results (e.g., in a table)
if ($results) {
  echo "<h2>Search Results:</h2>";
  echo "<table border='1'>";
  echo "<thead><tr>";
  foreach ($search_columns as $column) {
    echo "<th>" . ucwords($column) . "</th>";
  }
  echo "</tr></thead>";
  echo "<tbody>";
  while ($row = $results->fetch_assoc()) {
    echo "<tr>";
    foreach ($search_columns as $column) {
      echo "<td>" . htmlspecialchars($row[$column]) . "</td>"; // Important: Use htmlspecialchars for security
    }
    echo "</tr>";
  }
  echo "</tbody>";
  echo "</table>";
} else {
  echo "<p>No results found for '" . htmlspecialchars($search_term) . "'.</p>"; // Securely output search term
}

?>
