

<?php

// Assuming you have a form to capture the search term
// This is a basic example, you might want to adapt it to your specific needs.

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get the search term from the form
  $searchTerm = $_POST["search_term"];

  // Sanitize the search term (important to prevent XSS vulnerabilities)
  $searchTerm = htmlspecialchars($searchTerm);

  //  You can now use $searchTerm for your search logic

  // Example: Display the search term
  echo "<p>You searched for: " . $searchTerm . "</p>";

  //  Example:  Search through an array (replace with your actual data source)
  $myArray = array(
    "apple", "banana", "orange", "grapefruit", "watermelon"
  );

  $results = array_filter($myArray, function($item) use ($searchTerm) {
    return stripos($item, $searchTerm) !== false; // Case-insensitive search
  });

  if (!empty($results)) {
    echo "<p>Search Results:</p>";
    echo "<ul>";
    foreach ($results as $result) {
      echo "<li>" . $result . "</li>";
    }
    echo "</ul>";
  } else {
    echo "<p>No results found for: " . $searchTerm . "</p>";
  }

}
?>
