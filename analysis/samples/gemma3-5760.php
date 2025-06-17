
</body>
</html>


<?php

// Sample data (replace this with your actual data source)
$products = [
    "Laptop - 15 inch",
    "Smartphone - Galaxy S23",
    "Tablet - iPad Air",
    "Headphones - Sony WH-1000XM5",
    "Gaming Mouse - Logitech G502",
    "Wireless Keyboard - Keychron K2",
];

// Function to search for products
function searchProducts($query, $products) {
    $results = [];
    $query = strtolower($query); // Convert to lowercase for case-insensitive search

    foreach ($products as $product) {
        if (strpos($product, $query) !== false) {  // Use strpos for efficiency
            $results[] = $product;
        }
    }

    return $results;
}

// Get the search query from the user (e.g., from a form submission)
$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';  // Get from GET request, default to empty string

// Perform the search
$searchResults = searchProducts($searchQuery, $products);

?>
