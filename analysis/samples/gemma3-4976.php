

<?php

// Simple search bar implementation in PHP

// Get the search term from the GET request (e.g., from a URL like /search.php?q=keyword)
$searchTerm = isset($_GET['q']) ? $_GET['q'] : '';

// Sanitize the search term to prevent XSS attacks
$searchTerm = htmlspecialchars($searchTerm);

// ---  Example Data (replace this with your actual data source) ---
$data = [
    ['id' => 1, 'name' => 'Apple iPhone 15'],
    ['id' => 2, 'name' => 'Samsung Galaxy S23'],
    ['id' => 3, 'name' => 'Google Pixel 8'],
    ['id' => 4, 'name' => 'OnePlus 11'],
    ['id' => 5, 'name' => 'Xiaomi 14'],
];

// --- Search Logic ---
if ($searchTerm) {
    $searchResults = [];
    foreach ($data as $item) {
        // Convert both the item's name and the search term to lowercase for case-insensitive search
        $itemName = strtolower($item['name']);
        $searchTerm = strtolower($searchTerm);

        // Check if the search term is present in the item's name
        if (strpos($itemName, $searchTerm) !== false) {
            $searchResults[] = $item;
        }
    }
} else {
    $searchResults = []; // Empty array if no search term is provided
}

// --- Output HTML ---
?>
