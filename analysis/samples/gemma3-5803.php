
</body>
</html>


<?php

// Simple search bar implementation with basic functionality

// Get the search term from the input field
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search term (optional, but recommended)
$searchTerm = htmlspecialchars(trim($searchTerm)); // Remove whitespace and escape HTML

// Handle the search action
if ($searchTerm) {
  //  You can do something with the search term here,
  //  e.g., query a database, search a string, etc.

  // Example: Display the search term
  echo "<p>Searching for: " . $searchTerm . "</p>";

  // Example:  Search within a simple array
  $data = ["apple", "banana", "orange", "grapefruit", "kiwi"];
  $results = array_filter($data, function ($item) use ($searchTerm) {
    return stripos($item, $searchTerm) !== false; // Case-insensitive search
  });

  if (!empty($results)) {
    echo "<p>Results:</p>";
    echo "<ul>";
    foreach ($results as $result) {
      echo "<li>" . $result . "</li>";
    }
    echo "</ul>";
  } else {
    echo "<p>No results found for '" . $searchTerm . "'.</p>";
  }

} else {
  // If the search term is empty, display a placeholder message
  echo "<p>Enter search term...</p>";
}

?>
