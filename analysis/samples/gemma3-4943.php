

<?php

// This is a simplified example.  For a production environment,
// you'd want to use a more robust solution like a database query.

// Assume $search_term is a variable that holds the user's search input.
// This could be from a form submission, URL parameter, etc.

// Example:
// $search_term = $_GET['search'];
// Or:
// $search_term = $_POST['search'];

$search_term = isset($_GET['search']) ? $_GET['search'] : ''; // Get from GET request, default to empty string

// Sanitize and prepare the search term (important for security)
$search_term = htmlspecialchars(trim($search_term));

// Handle empty search term
if (empty($search_term)) {
    echo "<p>Please enter a search term.</p>";
} else {
    // Perform your search logic here.  This is a placeholder.

    // Example: Case-insensitive search through an array of items
    $items = [
        'Apple iPhone 14 Pro',
        'Samsung Galaxy S23',
        'Google Pixel 7',
        'Sony Bravia TV',
        'Sony WH-1000XM5 Headphones'
    ];

    $results = [];
    foreach ($items as $item) {
        if (stripos($item, $search_term)) { // Use stripos for case-insensitive search
            $results[] = $item;
        }
    }

    if (empty($results)) {
        echo "<p>No results found for '$search_term'.</p>";
    } else {
        echo "<h2>Search Results for: '$search_term'</h2>";
        echo "<ul>";
        foreach ($results as $result) {
            echo "<li>" . $result . "</li>";
        }
        echo "</ul>";
    }
}
?>
