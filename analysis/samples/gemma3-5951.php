

<?php

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Sanitize the input
  $search_term = htmlspecialchars(trim($_POST["search_term"]));

  // You can add more validation here, such as checking for empty strings,
  // or limiting the length of the search term.

  // Perform the search (replace this with your actual search logic)
  $results = performSearch($search_term);

  // Display the results
  echo "<div class='search-results'>";
  if (empty($results)) {
    echo "<p>No results found for: " . $search_term . "</p>";
  } else {
    echo "<ul>";
    foreach ($results as $result) {
      echo "<li>" . $result . "</li>";
    }
    echo "</ul>";
  }
  echo "</div>";

}
?>
