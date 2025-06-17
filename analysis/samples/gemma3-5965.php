

<?php

// Assuming you have a database connection established (e.g., mysqli)
// and a table named 'products' with a column named 'name'

// Check if the search form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get the search term from the form
  $searchTerm = $_POST["search_term"];

  // Sanitize the search term (important for security)
  $searchTerm = htmlspecialchars($searchTerm);  // Escape HTML entities

  // Perform the search
  $sql = "SELECT * FROM products WHERE name LIKE '%" . $searchTerm . "%'";

  // Example using mysqli
  $conn = new mysqli("your_db_host", "your_db_user", "your_db_password", "your_db_name");

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    // Output the search results
    echo "<h2>Search Results for: " . $searchTerm . "</h2>";
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>Name</th><th>Description</th></tr>"; // Example headers
    while ($row = $result->fetch_assoc()) {
      echo "<tr>";
      echo "<td>" . $row["id"] . "</td>";
      echo "<td>" . $row["name"] . "</td>";
      echo "<td>" . $row["description"] . "</td>"; // Adapt to your table schema
      echo "</tr>";
    }
    echo "</table>";
  } else {
    echo "<p>No results found for: " . $searchTerm . "</p>";
  }

  $conn->close(); // Close the database connection
}
?>
