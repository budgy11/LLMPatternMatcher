

<?php

// Assuming you have a search form with an input field named 'search_term'
// and want to search through a dataset (e.g., an array, database)

// 1. Get the Search Term from the Form
$search_term = isset($_POST['search_term']) ? $_POST['search_term'] : '';  // Check if the form is submitted and get the value

// 2. Data to Search Through (Example)
$data = [
    'apple',
    'banana',
    'orange',
    'grapefruit',
    'pineapple',
    'strawberry',
    'watermelon'
];

// 3. Search Function
function searchData($data, $search_term) {
    $results = [];
    $search_term = strtolower($search_term); // Convert to lowercase for case-insensitive search

    foreach ($data as $item) {
        $item_lower = strtolower($item);
        if (strpos($item_lower, $search_term) !== false) {
            $results[] = $item;
        }
    }
    return $results;
}

// 4. Perform the Search
$search_results = searchData($data, $search_term);

// 5. Display the Results
echo "<form method='post' action=''>";
echo "<input type='text' name='search_term' value='" . htmlspecialchars($search_term) . "' placeholder='Enter search term'>";
echo "<button type='submit'>Search</button>";
echo "</form>";

if (!empty($search_results)) {
    echo "<h2>Search Results:</h2>";
    echo "<ul>";
    foreach ($search_results as $result) {
        echo "<li>" . htmlspecialchars($result) . "</li>";
    }
    echo "</ul>";
} else {
    echo "<p>No results found for '" . htmlspecialchars($search_term) . "'.</p>";
}

?>
