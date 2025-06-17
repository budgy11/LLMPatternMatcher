
</body>
</html>


$table_name = 'products';
$search_columns = ['name', 'description', 'category'];


<?php

// Assuming you have a data source (e.g., an array, database)
$data = [
    "apple",
    "banana",
    "orange",
    "grape",
    "watermelon",
    "pineapple",
    "strawberry",
];

// Function to handle the search
function searchData($searchTerm, $data) {
    $results = [];

    foreach ($data as $item) {
        if (stripos($item, $searchTerm) !== false) { // Use stripos for case-insensitive search
            $results[] = $item;
        }
    }

    return $results;
}

// Get the search term from the form (if submitted) or use a default
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';  // Get from GET request or empty string

// Perform the search
$searchResults = searchData($searchTerm, $data);

?>
