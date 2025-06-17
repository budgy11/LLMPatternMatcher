

<?php

// Assuming you're fetching data from a database or an array

// Example data (replace with your actual data source)
$data = [
    "apple",
    "banana",
    "orange",
    "grapefruit",
    "pear",
    "pineapple",
    "strawberry",
    "watermelon"
];

// Get the search term from the search bar (assuming it's a form input)
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search term - Important to prevent XSS and SQL injection
$searchTerm = htmlspecialchars($searchTerm);  // Escape HTML entities

// Perform the search
$searchResults = [];
foreach ($data as $item) {
    if (strpos($item, $searchTerm) !== false) {
        $searchResults[] = $item;
    }
}

?>
