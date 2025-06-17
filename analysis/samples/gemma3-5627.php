
</body>
</html>


<?php

// Simple Search Bar Implementation in PHP

// Check if the search form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get the search term from the form
  $searchTerm = $_POST["search_term"];

  // Sanitize the input (important for security)
  $searchTerm = filter_var($searchTerm, FILTER_SANITIZE_STRING); 

  // Escape the search term (to prevent XSS)
  $searchTerm = htmlspecialchars($searchTerm);

  // Perform the search (replace this with your actual search logic)
  $searchResults = performSearch($searchTerm); 

  // Store the search term and results in the session
  session_start();
  $_SESSION["search_term"] = $searchTerm;
  $_SESSION["search_results"] = $searchResults;
}

// If the form hasn't been submitted, get the search term from the session
if (!isset($_SESSION["search_term"]) || empty($_SESSION["search_term"])) {
  // If there's no search term in the session, initialize it to an empty string
  $_SESSION["search_term"] = "";
  $_SESSION["search_results"] = [];
}

?>
