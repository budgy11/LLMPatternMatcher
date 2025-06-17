

<?php

// Check if the search form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get the search term from the form
  $searchTerm = htmlspecialchars($_POST["search_term"]); // Sanitize the input

  // Validate the search term (optional, but recommended)
  if (empty($searchTerm)) {
    $errorMessage = "Please enter a search term.";
  }

  // Perform your search logic here
  // Example:  Database query
  // Assuming you have a database connection established as $conn

  // $sql = "SELECT * FROM products WHERE name LIKE '%" . $searchTerm . "%'";
  // $result = $conn->query($sql);

  // If you don't have a database, you can just display the search term
  echo "<p>Searching for: " . $searchTerm . "</p>";

}
?>
