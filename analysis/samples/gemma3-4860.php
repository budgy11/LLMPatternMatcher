

<?php
if (isset($_GET['search']) && !empty($_GET['search'])) {
  $searchTerm = $_GET['search'];
} else {
  $searchTerm = ''; // Reset if not provided
}

//  Sanitize the search term (important - use a robust method!)
$searchTerm = trim($searchTerm);
$searchTerm = strip_tags($searchTerm, '<><a><b><i><script><img>');

// Your data source (replace with your actual data retrieval)
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

// Perform the search (same as before)
$searchResults = [];
if ($searchTerm) {
    foreach ($items as $item) {
        if (stripos($item, $searchTerm) !== false) {
            $searchResults[] = $item;
        }
    }
}

// Output the results
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
