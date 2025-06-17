

<?php

// Sample data (replace with your actual data source)
$products = [
    'apple' => ['name' => 'Red Apple', 'price' => 1.00],
    'banana' => ['name' => 'Yellow Banana', 'price' => 0.50],
    'orange' => ['name' => 'Navel Orange', 'price' => 0.75],
    'grape' => ['name' => 'Green Grape', 'price' => 1.25],
    'pear' => ['name' => 'Green Pear', 'price' => 0.90],
];

// Search term (initially empty)
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Perform the search
$searchResults = [];

if ($searchTerm) {
    foreach ($products as $key => $product) {
        // Case-insensitive search
        if (stripos($product['name'], $searchTerm)) {
            $searchResults[$key] = $product;
        }
    }
}

// Output the search form and results
echo '<form method="get" action="' . htmlspecialchars($_SERVER['PHP_SELF']) . '">';
echo 'Search: <input type="text" name="search" value="' . htmlspecialchars($searchTerm) . '" /> ';
echo '<input type="submit" value="Search" />';
echo '</form>';

if (empty($searchTerm)) {
    echo '<p>No search term entered.</p>';
} else {
    if (empty($searchResults)) {
        echo '<p>No results found for "' . htmlspecialchars($searchTerm) . '".</p>';
    } else {
        echo '<h2>Search Results for "' . htmlspecialchars($searchTerm) . '"</h2>';
        echo '<ul>';
        foreach ($searchResults as $key => $product) {
            echo '<li>' . htmlspecialchars($product['name']) . ' - $' . htmlspecialchars($product['price']) . '</li>';
        }
        echo '</ul>';
    }
}

?>
