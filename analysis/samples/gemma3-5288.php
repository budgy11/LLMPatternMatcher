
</body>
</html>


<?php

// Check if the search form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Sanitize the input to prevent XSS attacks
  $searchTerm = htmlspecialchars(trim($_POST["search"]));

  //  Validate the input (optional but recommended)
  if (empty($searchTerm)) {
    $errorMessage = "Please enter a search term.";
  } else {
    // Perform the search (replace this with your actual search logic)
    $results = performSearch($searchTerm);

    // Set a success message (optional)
    $successMessage = "Search results for: " . $searchTerm;
  }
}
?>
