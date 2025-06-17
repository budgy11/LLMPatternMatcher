

<?php

// Example PHP code for a simple search bar

// Check if the search form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get the search term from the form
  $searchTerm = $_POST["search_term"];

  // Sanitize the search term (very important to prevent XSS attacks)
  $searchTerm = htmlspecialchars(trim($searchTerm));

  // Perform your search logic here (e.g., database query, array search, etc.)
  //  For this example, we'll just display the search term
  echo "<p>You searched for: " . $searchTerm . "</p>";

  // Optional:  Redirect to a results page or display results
  // echo "<a href='results.php?search=" . $searchTerm . "'>View Results</a>";

}
?>
