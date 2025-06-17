

<?php

// Assuming you're working in a web environment (e.g., Apache with PHP)

// Initialize an empty array to hold the search results (for demonstration)
$searchResults = [];

// Function to handle the search submission
function handleSearch($searchTerm) {
  // Sanitize the search term to prevent XSS vulnerabilities
  $searchTerm = htmlspecialchars(trim($searchTerm));

  // Basic example: Search in a simple array of products
  $products = [
    "Laptop X1",
    "Tablet Z2",
    "Smartphone Y5",
    "Wireless Mouse",
    "Keyboard K3",
  ];

  // Convert search term to lowercase for case-insensitive search
  $searchTermLower = strtolower($searchTerm);

  $searchResults = []; // Reset results for each search

  foreach ($products as $product) {
    $productLower = strtolower($product);
    if (strpos($productLower, $searchTermLower) !== false) { // strpos returns position or false if not found
      $searchResults[] = $product;
    }
  }
}

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get the search term from the form
  $searchTerm = $_POST["search_term"];

  // Handle the search
  handleSearch($searchTerm);
}

?>
