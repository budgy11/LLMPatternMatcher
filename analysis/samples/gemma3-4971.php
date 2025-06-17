    <input type="text" name="search_term" placeholder="Enter fruit name">
    <button type="submit">Search</button>
</form>

</body>
</html>


<?php

// Assuming you have a database connection established (e.g., mysqli)
// Let's assume the connection variable is $conn

// Function to handle the search query
function performSearch($searchTerm, $table_name) {
  // Sanitize the search term to prevent SQL injection
  $searchTerm = $conn->real_escape_string($searchTerm);

  // Construct the SQL query
  $sql = "SELECT * FROM $table_name WHERE ";

  // Add the WHERE clause based on whether the search term is in any column
  $columns = array();
  $column_names = array();  // Store column names for dynamic WHERE clause

  // Dynamically detect columns with searchable fields.
  $result = $conn->query("SHOW COLUMNS FROM $table_name");
  while($row = $result->fetch_assoc()) {
    if (isset($row['Type'])) { // Check if column has a type
      $columns[] = $row['Field'];
      $column_names[] = $row['Field'];
    }
  }

  // Build the WHERE clause
  $whereClause = "";
  $whereParams = [];
  foreach ($columns as $column) {
    $whereClause .= "($column) LIKE '%" . $searchTerm . "%'";
    $whereParams[] = $column;
  }

  if ($whereClause != "") {
    $sql = $sql . $whereClause;
  } else {
    //  If no columns are found, return an empty result set.
    return [];
  }

  // Execute the query
  $result = $conn->query($sql);

  // Process the results
  if ($result->num_rows > 0) {
    $rows = [];
    while ($row = $result->fetch_assoc()) {
      $rows[] = $row;
    }
    return $rows;
  } else {
    return []; // No results found
  }
}


// Example usage (assuming you have a form with a search input)
if (isset($_GET['search']) && !empty($_GET['search'])) {
  $searchTerm = $_GET['search'];

  // Specify the table name
  $table_name = 'your_table_name'; // Replace with your actual table name

  // Perform the search
  $searchResults = performSearch($searchTerm, $table_name);

  // Display the results
  if (count($searchResults) > 0) {
    echo "<h2>Search Results for: " . $searchTerm . "</h2>";
    echo "<table border='1'>";
    echo "<thead><tr><th>Column 1</th><th>Column 2</th></tr></thead>"; // Adjust column names based on your table

    foreach ($searchResults as $row) {
      echo "<tr>";
      foreach ($row as $value) {
        echo "<td>" . htmlspecialchars($value) . "</td>"; // Escape for security
      }
      echo "</tr>";
    }
    echo "</table>";
  } else {
    echo "<p>No results found for '" . htmlspecialchars($searchTerm) . "'.</p>";
  }
}

?>
