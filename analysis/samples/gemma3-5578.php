  <?php endif; ?>

</body>
</html>


<?php

// Sample data - Replace this with your actual data source (e.g., database query)
$data = [
    ['id' => 1, 'name' => 'Apple', 'description' => 'A crisp fruit.'],
    ['id' => 2, 'name' => 'Banana', 'description' => 'A yellow fruit.'],
    ['id' => 3, 'name' => 'Orange', 'description' => 'A citrus fruit.'],
    ['id' => 4, 'name' => 'Grape', 'description' => 'Small, juicy fruit.'],
    ['id' => 5, 'name' => 'Strawberry', 'description' => 'Red berry, delicious.'],
];

// Function to search the data
function searchData($searchTerm, $data) {
    $results = [];
    foreach ($data as $item) {
        // Case-insensitive search
        $nameSearch = stripos($item['name'], $searchTerm);
        $descriptionSearch = stripos($item['description'], $searchTerm);

        if ($nameSearch !== false || $descriptionSearch !== false) {
            $results[] = $item;
        }
    }
    return $results;
}

// Get the search term from the user (e.g., from a form submission)
$searchTerm = isset($_GET['search']) ? $_GET['search'] : ''; //  Get from GET request, default to empty string

// Perform the search
$searchResults = searchData($searchTerm, $data);

?>
