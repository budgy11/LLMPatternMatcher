
</body>
</html>


<?php

// Assuming you have a database connection established (e.g., $db)

// Get the search term from the form
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search term (important for security)
$searchTerm = htmlspecialchars(trim($searchTerm));

// Escape special characters to prevent SQL injection
$searchTerm = $db->real_escape_string($searchTerm);

// SQL query for searching (example - adjust to your database schema)
$query = "SELECT * FROM your_table WHERE your_column LIKE '%" . $searchTerm . "%'";

// Execute the query
$result = $db->query($query);

// Check if the query was successful
if ($result) {
  // Display the search results
  echo '<form method="GET" action="your_script.php">
          <input type="text" name="search" placeholder="Search..." value="' . $searchTerm . '">
          <button type="submit">Search</button>
        </form>';

  echo '<table border="1">';
  echo '<thead><tr><th>ID</th><th>Column 1</th><th>Column 2</th></tr></thead>';

  while ($row = $result->fetch_assoc()) {
    echo '<tr>';
    echo '<td>' . $row['id'] . '</td>';
    echo '<td>' . $row['your_column_1'] . '</td>';
    echo '<td>' . $row['your_column_2'] . '</td>';
    echo '</tr>';
  }

  echo '</table>';

} else {
  // Display an error message if the query failed
  echo '<p>Error: ' . $db->error . '</p>';
}

?>
