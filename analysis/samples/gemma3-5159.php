

<?php

// Assuming you have a database connection established (e.g., using mysqli)
// Replace these with your actual database connection details
$db_host = "localhost";
$db_user = "your_user";
$db_password = "your_password";
$db_name = "your_database";

// Function to sanitize the search term
function sanitizeSearchTerm($term) {
  $term = trim($term);
  $term = stripslashes($term);
  $term = htmlspecialchars($term, ENT_QUOTES, 'UTF-8');
  return $term;
}

// Check if the search form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $searchTerm = sanitizeSearchTerm($_POST["search_term"]);

  // Perform the database query
  $query = "SELECT * FROM your_table WHERE your_column LIKE '%" . $searchTerm . "%'";  // Replace your_table and your_column

  // Assuming you're using mysqli
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  $result = $conn->query($query);

  if ($result->num_rows > 0) {
    // Display the search results (example)
    echo "<h2>Search Results for: " . $searchTerm . "</h2>";
    echo "<table border='1'>";
    while ($row = $result->fetch_assoc()) {
      echo "<tr>";
      foreach ($row as $key => $value) {
        echo "<td>" . $value . "</td>";
      }
      echo "</tr>";
    }
    echo "</table>";
  } else {
    echo "<p>No results found for: " . $searchTerm . "</p>";
  }

  $conn->close();
}

?>
