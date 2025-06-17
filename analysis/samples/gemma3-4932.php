
</body>
</html>


<?php

// Sample search data (replace with your actual data source - database, array, etc.)
$products = [
  "apple", "banana", "orange", "grape", "kiwi",
  "red apple", "green apple", "sweet orange", "sour grape", "yellow kiwi"
];

// Get the search term from the form
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search term (important for security)
$searchTerm = htmlspecialchars(trim($searchTerm));  // Escape characters and remove leading/trailing whitespace

// Perform the search
$searchResults = [];
if ($searchTerm) {
  foreach ($products as $product) {
    // Case-insensitive comparison
    if (stripos($product, $searchTerm)) {
      $searchResults[] = $product;
      break; // Stop after the first match for efficiency
    }
  }
}


?>
