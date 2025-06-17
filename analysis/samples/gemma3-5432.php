

<?php

// Check if the search form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Sanitize the search term
  $searchTerm = filter_input(INPUT_POST, "search", FILTER_SANITIZE_STRING);

  // Escape the search term to prevent XSS vulnerabilities
  $searchTerm = htmlspecialchars($searchTerm);

  // Store the search term in a session variable for persistence
  session_start();
  $_SESSION["search_term"] = $searchTerm;

  // Redirect to the results page (replace this with your actual logic)
  header("Location: search_results.php?search=" . urlencode($searchTerm));
  exit(); // Important to stop further script execution
}

?>
