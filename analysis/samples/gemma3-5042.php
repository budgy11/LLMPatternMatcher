
<!DOCTYPE html>
<html>
<head>
  <title>Search Example</title>
</head>
<body>

  <h2>Search Products</h2>

  <form method="get" action="search_results.php">
    <input type="text" name="search_term" value="" placeholder="Search..."/>
    <button type="submit">Search</button>
  </form>

  <br>

  <!-- This is where the search results would be displayed -->
  <!-- (The code from the performSearch function would be inserted here) -->


</body>
</html>


<?php

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get the search term from the form
  $searchTerm = isset($_POST["search_term"]) ? trim($_POST["search_term"]) : '';

  // Sanitize the search term (important for security)
  $searchTerm = filter_var($searchTerm, FILTER_SANITIZE_STRING);

  // Validate the search term (optional, but recommended)
  if (empty($searchTerm)) {
    $message = "Please enter a search term.";
  } else {
    // Perform the search here (replace this with your actual search logic)
    $searchResults = performSearch($searchTerm); 
  }

} else {
  // If the form hasn't been submitted, initialize the search term to an empty string
  $searchTerm = "";
  $searchResults = [];  // Initialize the results array.
}

?>
