

<?php

// Define the search term (you can also get this from a form)
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search term - VERY IMPORTANT to prevent XSS vulnerabilities
$searchTerm = htmlspecialchars($searchTerm);

// Perform the search (this is a basic example; adapt for your data)
$searchResults = [];

// Example: Searching through an array of products
$products = [
    'Apple iPhone 14' => 'Red',
    'Samsung Galaxy S23' => 'Black',
    'Google Pixel 7' => 'Blue',
    'OnePlus 11' => 'White',
];

if ($searchTerm) {
    foreach ($products as $key => $value) {
        if (stripos($key, $searchTerm) !== false || stripos($value, $searchTerm) !== false) {
            $searchResults[$key] = $value;
        }
    }
}

?>
