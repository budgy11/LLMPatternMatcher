

<?php

// Simulate a database or data source (replace with your actual data source)
$products = [
    'apple',
    'banana',
    'orange',
    'grape',
    'kiwi',
    'mango',
    'pineapple',
    'strawberry'
];

// Function to handle the search
function searchProducts($searchTerm, $products) {
    $results = [];

    // Convert search term to lowercase for case-insensitive search
    $searchTerm = strtolower($searchTerm);

    foreach ($products as $product) {
        // Convert product name to lowercase for case-insensitive comparison
        $productLower = strtolower($product);

        if (strpos($productLower, $searchTerm) !== false) {  // strpos returns the position of the substring or false if not found
            $results[] = $product;
        }
    }

    return $results;
}

// Get the search term from the form (using GET or POST method)
$searchTerm = isset($_GET['search']) ? $_GET['search'] : ''; // Get from URL
// OR
// $searchTerm = isset($_POST['search']) ? $_POST['search'] : ''; // Get from form

// Perform the search
$searchResults = searchProducts($searchTerm, $products);

?>
