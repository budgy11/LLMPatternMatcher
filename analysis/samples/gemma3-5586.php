    <?php endif; ?>

</body>
</html>


<?php

// This example demonstrates a basic search bar with simple filtering.
// For more advanced search features (like full-text search, stemming, etc.), 
// you'll likely need a dedicated search library or a database with appropriate indexing.

// Sample Data (replace this with your actual data source - database, array, etc.)
$data = [
    ['id' => 1, 'name' => 'Apple', 'description' => 'A sweet fruit'],
    ['id' => 2, 'name' => 'Banana', 'description' => 'A yellow fruit'],
    ['id' => 3, 'name' => 'Orange', 'description' => 'A citrus fruit'],
    ['id' => 4, 'name' => 'Grape', 'description' => 'Small, sweet berries'],
    ['id' => 5, 'name' => 'Pineapple', 'description' => 'Tropical fruit with a crown'],
];

// Get the search term from the GET request (if provided)
$searchTerm = isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '';

// Perform the search
$searchResults = [];
foreach ($data as $item) {
    // Convert search term to lowercase for case-insensitive search
    $searchTermLower = strtolower($searchTerm);

    // Check if the search term is present in the name or description
    if (strpos($item['name'], $searchTermLower) !== false || strpos($item['description'], $searchTermLower) !== false) {
        $searchResults[] = $item;
    }
}

?>
