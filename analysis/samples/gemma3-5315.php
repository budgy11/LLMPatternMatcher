

<?php

// Sample data (replace with your actual data source - database, array, etc.)
$data = [
    "apple", "banana", "orange", "grape", "watermelon", "kiwi",
    "strawberry", "blueberry", "raspberry", "pineapple"
];

// Function to handle the search
function searchData($searchTerm, $data) {
    $results = [];
    $searchTerm = strtolower($searchTerm); // Case-insensitive search

    foreach ($data as $item) {
        $itemLower = strtolower($item);
        if (strpos($itemLower, $searchTerm) !== false) {  //strpos returns the position of the substring
            $results[] = $item;
            break; // Stop searching after the first match
        }
    }

    return $results;
}

// Check if the search term is submitted
if (isset($_POST['search_term']) && !empty($_POST['search_term'])) {
    $searchTerm = $_POST['search_term'];
    $searchResults = searchData($searchTerm, $data);

    // Output the results
    if (empty($searchResults)) {
        echo "<p>No results found for: " . htmlspecialchars($searchTerm) . "</p>";
    } else {
        echo "<h2>Search Results:</h2>";
        echo "<ol>";
        foreach ($searchResults as $result) {
            echo "<li>" . htmlspecialchars($result) . "</li>";
        }
        echo "</ol>";
    }

} else {
    // Display the search bar if no search term is submitted
    echo "<form method='post'>";
    echo "Search: <input type='text' name='search_term' value=''>";
    echo "<button type='submit'>Search</button>";
    echo "</form>";
}

?>
