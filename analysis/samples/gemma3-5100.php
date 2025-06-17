
</body>
</html>


<?php

// Sample data (replace with your actual data source)
$data = [
    "apple",
    "banana",
    "orange",
    "grape",
    "pineapple",
    "strawberry",
    "watermelon",
    "mango",
    "kiwi",
    "pear"
];


// Function to handle the search
function searchData($searchTerm, $data) {
    $results = [];

    // Convert search term to lowercase for case-insensitive search
    $searchTerm = strtolower($searchTerm);

    foreach ($data as $item) {
        // Convert item to lowercase for case-insensitive search
        $itemLower = strtolower($item);

        // Check if the search term is present in the item
        if (strpos($itemLower, $searchTerm) !== false) {
            $results[] = $item; // Or $itemLower, if you want the lowercase version
        }
    }

    return $results;
}


// Get the search term from the form (if it exists)
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';


// Perform the search
$searchResults = searchData($searchTerm, $data);


// Output the search bar and results
?>
