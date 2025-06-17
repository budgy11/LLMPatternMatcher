

<?php

// Assuming you have a database connection established (e.g., mysqli)
// For demonstration purposes, we'll use a placeholder array instead of a real database.
$data = [
    ['id' => 1, 'name' => 'Apple', 'description' => 'A sweet fruit'],
    ['id' => 2, 'name' => 'Banana', 'description' => 'A yellow fruit'],
    ['id' => 3, 'name' => 'Orange', 'description' => 'A citrus fruit'],
    ['id' => 4, 'name' => 'Grape', 'description' => 'Small, juicy fruit'],
];

// Get the search term from the GET request (e.g., from a URL like ?search=apple)
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search term to prevent SQL injection (IMPORTANT!)
$searchTerm = htmlspecialchars(trim($searchTerm)); 

// Handle empty search term
if (empty($searchTerm)) {
    $searchResults = $data; // Show all results if nothing is searched
} else {
    $searchResults = [];
    foreach ($data as $item) {
        // Case-insensitive search
        if (stripos($item['name'], $searchTerm) !== false ||
            stripos($item['description'], $searchTerm) !== false) {
            $searchResults[] = $item;
        }
    }
}

?>
