  <?php endif; ?>

</body>
</html>


<?php

// Assuming you have a database connection established (e.g., $db)

// Function to handle the search query
function performSearch($search_term, $table_name, $search_columns) {
  global $db; // Access the database connection

  // Sanitize and escape the search term
  $search_term = $db->real_escape_string($search_term);

  // Build the SQL query
  $sql = "SELECT * FROM " . $table_name . " WHERE ";

  // Build the WHERE clause dynamically based on the search_columns
  $where_clause = "";
  $conditions = [];
  foreach ($search_columns as $column) {
    // Add the condition only if the column is not empty
    if (!empty($column)) {
      $where_clause .= " (" . $column . ") LIKE '%" . $search_term . "%' OR ";
      $conditions[] = "($column) LIKE '%" . $search_term . "%'";
    }
  }

  // Remove the trailing "OR"
  $where_clause = rtrim($where_clause, 'OR');

  // Add the WHERE clause to the SQL query
  $sql .= $where_clause . " LIMIT 10"; // Limit results for better performance - adjust as needed

  // Execute the query
  $result = $db->query($sql);

  // Process the results
  if ($result) {
    echo "<form method='get' action='search_results.php'>
            <input type='text' name='search' placeholder='Search...' value='" . htmlspecialchars($search_term) . "'/>
            <button type='submit'>Search</button>
          </form>";

    if ($result->num_rows > 0) {
      echo "<ul>";
      while ($row = $result->fetch_assoc()) {
        echo "<li>" . htmlspecialchars($row['id']) . ": " . htmlspecialchars($row['name']) . "</li>";
      }
      echo "</ul>";
    } else {
      echo "<p>No results found.</p>";
    }

  } else {
    echo "<p>Error executing query: " . $db->error . "</p>";
  }
}

// Example usage:
// Assuming you have a table named 'products' with columns 'id', 'name', and 'description'
// and you want to search across 'name' and 'description'
$table_name = 'products';
$search_columns = ['name', 'description'];
performSearch("laptop", $table_name, $search_columns);
?>
