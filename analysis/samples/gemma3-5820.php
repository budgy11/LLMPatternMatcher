

<?php

// Assuming you have a data source (e.g., a database, an array, or a file)
// This example uses a simple array for demonstration.

$data = [
    'apple', 'banana', 'cherry', 'date', 'elderberry',
    'fig', 'grape', 'honeydew', 'kiwi', 'lemon'
];

// Check if the search term is provided
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $searchTerm = htmlspecialchars($_GET['search']); // Sanitize input

    // Perform the search
    $searchResults = [];
    foreach ($data as $item) {
        if (stripos($item, $searchTerm) !== false) { // Use stripos for case-insensitive search
            $searchResults[] = $item;
        }
    }
} else {
    $searchResults = []; // Reset if no search term is provided
}

?>
