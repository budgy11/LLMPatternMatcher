

<?php

// This is a very basic example, meant to illustrate the core concepts.
// For a production application, you'd likely want a more robust and secure search system.

// 1.  Get the search term from the user.  This example uses $_GET for simplicity.
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// 2. Sanitize the search term (VERY IMPORTANT for security)
// This example uses simple trimming and stripping of HTML tags.  
// You *must* use a more robust sanitization method in a real-world application.
$searchTerm = trim($searchTerm);
$searchTerm = strip_tags($searchTerm, '<><a><b><i><script><img>'); // Remove HTML tags

// 3.  Set up some dummy data for demonstration. Replace this with your actual data source.
$items = [
    'apple',
    'banana',
    'orange',
    'grapefruit',
    'strawberry',
    'pineapple',
    'mango',
    'pear'
];

// 4.  Perform the search
$searchResults = [];
if ($searchTerm) {
    foreach ($items as $item) {
        // Case-insensitive search
        if (stripos($item, $searchTerm) !== false) {
            $searchResults[] = $item;
        }
    }
}

// 5.  Output the search form and results
echo '<form method="GET" action="search_results.php">';
echo 'Search: <input type="text" name="search" value="' . htmlspecialchars($searchTerm) . '">';
echo '<button type="submit">Search</button>';
echo '</form>';

if (empty($searchResults)) {
    echo "<p>No results found for '" . htmlspecialchars($searchTerm) . "'</p>";
} else {
    echo '<h2>Search Results:</h2>';
    echo '<ul>';
    foreach ($searchResults as $result) {
        echo '<li>' . htmlspecialchars($result) . '</li>';
    }
    echo '</ul>';
}

?>
