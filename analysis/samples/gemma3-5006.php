

<?php

// Function to handle the search
function searchProducts($searchTerm, $products) {
  $results = [];

  foreach ($products as $product) {
    // Convert both the search term and product name to lowercase for case-insensitive search
    $searchTermLower = strtolower($searchTerm);
    $productNameLower = strtolower($product['name']);

    // Check if the search term is present in the product name
    if (strpos($productNameLower, $searchTermLower) !== false) {
      $results[] = $product;
    }
  }

  return $results;
}

// Example Product Data (Replace with your actual data source)
$products = [
  ['id' => 1, 'name' => 'Laptop - Dell XPS 13'],
  ['id' => 2, 'name' => 'Smartphone - Samsung Galaxy S23'],
  ['id' => 3, 'name' => 'Wireless Headphones - Sony WH-1000XM5'],
  ['id' => 4, 'name' => 'Gaming Mouse - Logitech G502'],
  ['id' => 5, 'name' => 'Smartwatch - Apple Watch Series 8']
];


// Get the search term from the form (or URL)
$searchTerm = isset($_GET['search']) ? $_GET['search'] : ''; // Get from URL
// OR:  $searchTerm = isset($_POST['search']) ? $_POST['search'] : '';  // Get from form

// Perform the search
$searchResults = searchProducts($searchTerm, $products);

?>
