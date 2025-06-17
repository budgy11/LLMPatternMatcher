  <input type="text" name="search" placeholder="Enter search term">
  <input type="submit" value="Search">
</form>


<?php

// ... (Database connection) ...

// Get the search term from the GET request
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

if (empty($searchTerm)) {
  echo "<p>Please enter a search term.</p>";
} else {
  // Construct the SQL query with placeholders
  $tableName = 'your_table_name';
  $columnName = 'your_column_name';
  $sql = "SELECT * FROM $tableName WHERE $columnName LIKE ?";

  // Prepare the statement
  $stmt = mysqli_prepare($conn, $sql);

  if ($stmt) {
    // Bind the parameter
    mysqli_stmt_bind_param($stmt, "s", $searchTerm);  // "s" indicates a string

    // Execute the query
    mysqli_stmt_execute($stmt);

    // Get the result
    $result = mysqli_stmt_get_result($stmt);

    // Display the results
    if ($result) {
      echo "<h2>Search Results for: '$searchTerm'</h2>";
      echo "<table border='1'>";
      echo "<tr><th>Column 1</th><th>Column 2</th></tr>";

      while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        foreach ($row as $value) {
          echo "<td>" . htmlspecialchars($value) . "</td>";
        }
        echo "</tr>";
      }

      echo "</table>";
    } else {
      echo "<p>Error executing the query: " . mysqli_error($conn) . "</p>";
    }

    // Close the statement
    mysqli_stmt_close($stmt);
  } else {
    echo "<p>Error preparing the statement: " . mysqli_error($conn) . "</p>";
  }
}

?>
