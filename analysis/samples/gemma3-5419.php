

<?php

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get the search term from the form
  $searchTerm = $_POST["search_term"];

  // Sanitize the input to prevent XSS vulnerabilities
  $searchTerm = htmlspecialchars($searchTerm);

  //  You can perform further validation here (e.g., check length, allowed characters)
  //  For example:
  //  if (strlen($searchTerm) > 50) {
  //    $searchTerm = ''; // Or display an error message
  //  }

  //  Display the search term for demonstration.  In a real application,
  //  you would use this in your database query, etc.
  echo "<p>You searched for: <strong>" . $searchTerm . "</strong></p>";

  //  Example:  Simulating a database search (replace with your actual query)
  //  This is just for demonstration.  Do NOT use this directly in a real application
  //  without proper sanitization and security measures.
  //  $results = searchDatabase($searchTerm);
  //  if ($results) {
  //    echo "<p>Search Results:</p>";
  //    echo "<ul class='results'>";
  //    foreach ($results as $result) {
  //      echo "<li>" . $result . "</li>";
  //    }
  //    echo "</ul>";
  //  } else {
  //    echo "<p>No results found.</p>";
  //  }
}
?>
