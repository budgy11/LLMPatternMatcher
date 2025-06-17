

<?php

// Sample data (replace with your actual data source)
$items = [
    "apple",
    "banana",
    "orange",
    "grape",
    "strawberry",
    "pineapple",
    "watermelon",
    "mango",
];

// Function to handle the search
function searchItems($searchTerm, $items) {
    $results = [];
    $searchTerm = strtolower($searchTerm); // Convert to lowercase for case-insensitive search

    foreach ($items as $item) {
        $itemLower = strtolower($item);
        if (strpos($itemLower, $searchTerm) !== false) {
            $results[] = $item;
        }
    }

    return $results;
}

// Get the search term from the form (using $_GET for demonstration)
$searchTerm = isset($_GET['search']) ? $_GET['search'] : ''; // Check if the search parameter is set

// Perform the search
$searchResults = searchItems($searchTerm, $items);

?>
