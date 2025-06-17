
</body>
</html>


<?php

// Assuming you have a database connection established (e.g., $db connection)

// Get the search term from the search bar (using $_GET or $_POST)
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search term to prevent SQL injection and other issues
$searchTerm = htmlspecialchars(trim($searchTerm));

// Basic input validation (optional, but recommended)
if (empty($searchTerm)) {
  //  You can display a message here if the search bar is empty.
  echo "<p>Please enter a search term.</p>";
}
else {
  // Prepare the SQL query
  $sql = "SELECT * FROM your_table_name WHERE your_column_name LIKE '%" . $searchTerm . "%'";

  // Execute the query
  $result = mysqli_query($db, $sql); // Replace $db with your actual database connection.
  
  // Check if the query was successful
  if ($result) {
    // Display the search results
    echo "<h2>Search Results for: " . $searchTerm . "</h2>";
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>Your Column 1</th><th>Your Column 2</th></tr>"; // Adjust column names to match your table

    while ($row = mysqli_fetch_assoc($result)) {
      echo "<tr>";
      echo "<td>" . $row['id'] . "</td>";  // Adjust column names
      echo "<td>" . $row['your_column_1'] . "</td>"; // Adjust column names
      echo "<td>" . $row['your_column_2'] . "</td>"; // Adjust column names
      echo "</tr>";
    }

    echo "</table>";
  } else {
    echo "<p>No results found for: " . $searchTerm . "</p>";
  }
}

?>
