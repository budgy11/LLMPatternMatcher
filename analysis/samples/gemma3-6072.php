

<?php

// Assuming you have a database connection established (e.g., mysqli)
// Let's call it $conn

// Check if the search form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get the search term from the form
  $searchTerm = $_POST["search_term"];

  // Sanitize the search term (important to prevent SQL injection)
  $searchTerm = $conn->real_escape_string($searchTerm);

  // Perform the search (replace with your actual database query)
  $sql = "SELECT * FROM products WHERE name LIKE '%" . $searchTerm . "%'"; // Example for a 'name' column
  $result = $conn->query($sql);

  // Display the search results
  if ($result->num_rows > 0) {
    echo "<h3>Search Results:</h3>";
    echo "<form method='post' action=''>";
    echo "<input type='text' name='search_term' value='" . htmlspecialchars($searchTerm) . "' placeholder='Enter search term'>";
    echo "<button type='submit'>Search</button>";
    echo "</form>";

    echo "<ul>";
    while ($row = $result->fetch_assoc()) {
      echo "<li>" . htmlspecialchars($row["name"]) . " - " . htmlspecialchars($row["description"]) . "</li>"; // Adjust based on your table columns
    }
    echo "</ul>";
  } else {
    echo "<p>No results found.</p>";
  }
}
?>
