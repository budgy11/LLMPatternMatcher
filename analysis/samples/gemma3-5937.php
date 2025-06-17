

<?php

// Sample data (replace with your actual data source)
$items = [
    "apple",
    "banana",
    "orange",
    "grape",
    "watermelon",
    "pineapple",
    "strawberry",
    "blueberry",
    "cherry",
    "mango",
];

// Get the search term from the form submission
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search term (important for security)
$searchTerm = htmlspecialchars(trim($searchTerm));

// Perform the search
$searchResults = [];
foreach ($items as $item) {
    if (stripos($item, $searchTerm) !== false) {
        $searchResults[] = $item;
    }
}

// Output the search bar and results
?>
