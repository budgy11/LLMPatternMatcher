
</body>
</html>


<?php

// Sample data - Replace this with your actual data source
$items = [
    "apple",
    "banana",
    "orange",
    "grape",
    "watermelon",
    "pineapple",
    "kiwi",
];

// Function to handle the search
function searchItems($searchTerm, $items) {
    $results = [];

    // Convert search term to lowercase for case-insensitive search
    $searchTerm = strtolower($searchTerm);

    foreach ($items as $item) {
        // Convert item to lowercase for case-insensitive search
        $itemLower = strtolower($item);

        if (strpos($itemLower, $searchTerm) !== false) {
            $results[] = $item;
        }
    }

    return $results;
}


// Get the search term from the form (or URL parameter)
$search_term = isset($_GET['search']) ? $_GET['search'] : '';

// Perform the search
$searchResults = searchItems($search_term, $items);

?>
