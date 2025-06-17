

<?php

// Assuming you have a form to receive the search query

// Define the search term
$search_term = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the input (IMPORTANT - prevents XSS)
$sanitized_search_term = htmlspecialchars(trim($search_term));

// Perform the search - This is just a placeholder.  Replace this with your actual search logic.
// For example, you might search a database, a file, or an array.
// This example demonstrates searching through an array.
$data = [
    'apple',
    'banana',
    'orange',
    'grape',
    'pear',
    'pineapple'
];

$results = [];
foreach ($data as $item) {
    if (stripos($item, $sanitized_search_term) !== false) { // Case-insensitive search
        $results[] = $item;
    }
}


?>
