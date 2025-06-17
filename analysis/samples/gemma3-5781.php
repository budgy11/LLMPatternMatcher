
</body>
</html>


<?php

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Sanitize the input
  $search_term = trim($_POST["search_term"]);

  // Escape the search term to prevent XSS attacks
  $search_term = htmlspecialchars($search_term);

  // Perform your search logic here
  // Example: Searching through an array of products
  $products = [
    "Apple iPhone 13",
    "Samsung Galaxy S22",
    "Google Pixel 6",
    "OnePlus 10 Pro",
    "Sony Bravia TV 65 inch"
  ];

  $results = [];
  foreach ($products as $product) {
    if (strpos($product, $search_term) !== false) {
      $results[] = $product;
    }
  }

  // Display the results
  if (empty($results)) {
    echo "<p>No results found for: " . $search_term . "</p>";
  } else {
    echo "<h2>Search Results for: " . $search_term . "</h2>";
    echo "<ul>";
    foreach ($results as $result) {
      echo "<li>" . $result . "</li>";
    }
    echo "</ul>";
  }
}
?>
