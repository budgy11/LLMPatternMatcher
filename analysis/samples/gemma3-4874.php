
</body>
</html>


<?php

// Assuming you have a data source (e.g., an array, a database)
$data = [
    "apple",
    "banana",
    "orange",
    "grape",
    "watermelon",
    "pineapple",
    "strawberry",
    "kiwi",
    "mango",
    "pear"
];


// Function to perform the search
function searchData($searchTerm, $data) {
    $results = [];

    foreach ($data as $item) {
        if (stripos($item, $searchTerm) !== false) { // Case-insensitive search
            $results[] = $item;
        }
    }

    return $results;
}

// Get the search term from the user (e.g., from a form)
$searchTerm = isset($_GET['search']) ? $_GET['search'] : ''; // Get from URL parameters

// Perform the search
$searchResults = searchData($searchTerm, $data);

?>
