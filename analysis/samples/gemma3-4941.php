

<?php

// Assuming you have a database connection established (e.g., $db connection)

// Check if the search form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get the search term from the form
  $searchTerm = $_POST["search_term"];

  // Sanitize and escape the search term to prevent SQL injection
  $searchTerm = $db->real_escape_string($searchTerm); 

  // Perform the database query (example - adapt to your database structure)
  $query = "SELECT * FROM your_table WHERE title LIKE '%" . $searchTerm . "%'";
  $result = $db->query($query);

  // Check if the query was successful
  if ($result) {
    // Display the search results
    echo "<h2>Search Results for: " . htmlspecialchars($searchTerm) . "</h2>";
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>Title</th><th>Content</th></tr>"; // Example table headers

    while ($row = $result->fetch_assoc()) {
      echo "<tr>";
      echo "<td>" . $row["id"] . "</td>";  // Adjust column names
      echo "<td>" . htmlspecialchars($row["title"]) . "</td>";
      echo "<td>" . htmlspecialchars($row["content"]) . "</td>";
      echo "</tr>";
    }

    echo "</table>";

  } else {
    echo "<p>No results found.</p>";
    echo "<p>Error: " . $db->error . "</p>";  // Helpful for debugging
  }
}

?>
