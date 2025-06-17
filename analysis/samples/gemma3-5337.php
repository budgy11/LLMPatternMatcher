

<?php

// Basic Search Bar Implementation

// Get the search query from the form
$search_query = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the input (important for security - prevents XSS)
$search_query = htmlspecialchars(trim($search_query));

// Perform the search (replace this with your actual database query or logic)
$results = searchData($search_query); // Example function defined below

// Output the search bar form
echo '<form action="search_results.php" method="get">';
echo 'Search: <input type="text" name="search" value="' . $search_query . '">';
echo '<input type="submit" value="Search">';
echo '</form>';

// Display the search results (only if any results were found)
if (!empty($results)) {
    echo '<h2>Search Results:</h2>';
    echo '<ul>';
    foreach ($results as $result) {
        echo '<li>' . $result . '</li>';
    }
    echo '</ul>';
} else {
    echo '<p>No results found for: ' . $search_query . '</p>';
}

// Example function to simulate searching data
function searchData($query) {
    // Replace this with your actual database query logic
    // This is just an example to demonstrate the output.

    $data = [
        'Product 1',
        'Product 2 - Amazing Features',
        'Product 3 - Excellent Value',
        'Product 4 - Highly Recommended',
        'Product 5 - Great Price'
    ];

    $results = [];
    foreach ($data as $item) {
        if (strpos($item, $query) !== false) {
            $results[] = $item;
        }
    }

    return $results;
}

?>
