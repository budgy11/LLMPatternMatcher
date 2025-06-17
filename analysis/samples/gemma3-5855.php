

<?php

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get the search term from the form
  $searchTerm = trim($_POST["search_term"]);

  // Sanitize the search term to prevent XSS attacks
  $searchTerm = htmlspecialchars($searchTerm);

  // You can now use the $searchTerm for your search logic

  // Example:  Print the search term
  echo "<p>You searched for: " . $searchTerm . "</p>";

  // Example:  Perform a search on a dataset (replace with your actual data)
  // Let's assume you have an array called $data
  // $data = array(
  //   "item1", "item2", "item3", "item4", "item5"
  // );
  // $searchResults = array_filter($data, function($item) use ($searchTerm) {
  //   return stripos($item, $searchTerm) !== false; // Case-insensitive search
  // }, STR_ICALUE_IGNORE);
  //
  // if (empty($searchResults)) {
  //   echo "<p>No results found for: " . $searchTerm . "</p>";
  // } else {
  //   echo "<ul>";
  //   foreach ($searchResults as $result) {
  //     echo "<li>" . $result . "</li>";
  //   }
  //   echo "</ul>";
  // }



}
?>
