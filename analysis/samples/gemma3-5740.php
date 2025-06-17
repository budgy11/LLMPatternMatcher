    <input type="text" name="search_term" placeholder="Enter search term">
    <button type="submit">Search</button>
  </form>

</body>
</html>


<?php

// Assuming you have a database connection established (e.g., mysqli)
// Let's call this $db

// Example database connection setup (replace with your actual details)
// $db = new mysqli("localhost", "username", "password", "database_name");
// if ($db->connect_error) {
//     die("Connection failed: " . $db->connect_error);
// }

// Function to handle the search
function performSearch($search_term, $table_name, $columns = ['title', 'description']) {
  global $db; // Access the global database connection

  // Sanitize the search term to prevent SQL injection
  $search_term = $db->real_escape_string($search_term);

  // Build the SQL query
  $columns_str = implode(', ', $columns);
  $sql = "SELECT * FROM $table_name WHERE $columns_str LIKE '%" . $search_term . "%'";

  $result = $db->query($sql);

  if ($result->num_rows > 0) {
    // Output the results (adjust this part based on your desired output format)
    echo "<ul>";
    while ($row = $result->fetch_assoc()) {
      echo "<li>";
      foreach ($columns as $column) {
        echo htmlspecialchars($row[$column]); // Use htmlspecialchars to prevent XSS
        if ($column != $columns[count($columns) - 1]) {
          echo ", ";
        }
      }
      echo "</li>";
    }
    echo "</ul>";
  } else {
    echo "No results found.";
  }
}


// Example usage:  (This part will be in your HTML page)
?>
