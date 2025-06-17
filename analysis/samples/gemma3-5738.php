

<?php

// Check if the search form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get the search term from the form
  $searchTerm = $_POST["search_term"];

  // Sanitize the search term (important for security!)
  $searchTerm = htmlspecialchars($searchTerm); // Prevents XSS

  // You can now use the $searchTerm for your search logic.
  // Example: Search through an array or database.

  // For demonstration purposes, let's display the search term:
  echo "<p>You searched for: " . $searchTerm . "</p>";

  // Add your search results here.  This is just a placeholder.
  // Example:
  // $results = searchDatabase($searchTerm);
  // echo "<pre>";
  // print_r($results);
  // echo "</pre>";
}
?>
