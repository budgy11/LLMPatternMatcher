

<?php

// Simple search bar example

// Get the search term from the GET request
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search term (Important for security)
$searchTerm = htmlspecialchars(trim($searchTerm));  // Prevents XSS

// Perform the search (example: case-insensitive search)
if ($searchTerm) {
  $results = [];
  // In a real application, you'd replace this with your database query
  // or other search logic.  This is just a demo.

  // Example: Search within an array of data
  $data = [
    "apple",
    "banana",
    "orange",
    "grapefruit",
    "mango",
    "pineapple"
  ];

  foreach ($data as $item) {
    if (stripos($item, $searchTerm) !== false) {
      $results[] = $item;
    }
  }


  // Display the results
  if (count($results)) {
    echo "<h3>Search Results for: " . htmlspecialchars($searchTerm) . "</h3>";
    echo "<ul>";
    foreach ($results as $result) {
      echo "<li>" . htmlspecialchars($result) . "</li>";
    }
    echo "</ul>";
  } else {
    echo "<p>No results found for: " . htmlspecialchars($searchTerm) . "</p>";
  }
}
?>
