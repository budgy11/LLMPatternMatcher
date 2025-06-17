
</body>
</html>


<?php

// Simulate a database or data source (replace with your actual data source)
$products = [
    'Laptop - Dell XPS 13' => 'Dell XPS 13',
    'Gaming Mouse - Logitech G502' => 'Logitech G502',
    'Wireless Headphones - Sony WH-1000XM5' => 'Sony WH-1000XM5',
    'Smartphone - Samsung Galaxy S23' => 'Samsung Galaxy S23',
    'Tablet - Apple iPad Air' => 'Apple iPad Air',
    'Keyboard - Corsair K95 RGB' => 'Corsair K95 RGB',
];

// Function to search products
function searchProducts($searchTerm, $products) {
    $results = [];
    $searchTermLower = strtolower($searchTerm); // Convert search term to lowercase

    foreach ($products as $key => $productName) {
        $productNameLower = strtolower($productName);
        if (strpos($productNameLower, $searchTermLower) !== false) {
            $results[$key] = $productName;
        }
    }
    return $results;
}

// Get the search term from the form (using GET method - you can change this to POST)
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Perform the search
$searchResults = searchProducts($searchTerm, $products);

?>
