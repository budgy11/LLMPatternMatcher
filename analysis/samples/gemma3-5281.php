

<?php

// Sample data (replace with your actual data source - e.g., database, array)
$data = [
    "apple", "banana", "cherry", "date", "fig", "grape", "kiwi"
];

// Get the search term from the form (if submitted)
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search term (important to prevent XSS and SQL injection)
$searchTerm = htmlspecialchars(trim($searchTerm));

// Perform the search
$searchResults = [];
if ($searchTerm) {
    foreach ($data as $item) {
        if (stripos($item, $searchTerm) !== false) { // case-insensitive search
            $searchResults[] = $item;
        }
    }
}

?>
