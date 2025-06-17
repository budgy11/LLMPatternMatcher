

<?php

// Sample data (replace with your actual data source)
$products = [
  "apple", "banana", "orange", "grape", "kiwi",
  "red apple", "green apple", "apple pie", "banana bread"
];

// Handle the search form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $searchTerm = trim($_POST["search_term"]);

  if (!empty($searchTerm)) {
    // Perform the search
    $searchResults = [];
    foreach ($products as $product) {
      if (stripos($product, $searchTerm) !== false) { // Case-insensitive search
        $searchResults[] = $product;
      }
    }

    // Display the results
    if (empty($searchResults)) {
      echo "<p>No results found for: " . htmlspecialchars($searchTerm) . "</p>";
    } else {
      echo "<h2>Search Results for: " . htmlspecialchars($searchTerm) . "</h2>";
      echo "<ul>";
      foreach ($searchResults as $result) {
        echo "<li>" . htmlspecialchars($result) . "</li>";
      }
      echo "</ul>";
    }
  }
}
?>
