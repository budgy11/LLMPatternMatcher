
</body>
</html>


<?php

// Assuming you have a data source (e.g., an array, a database query result)
// called $data
//  $data = [
//      "apple", "banana", "orange", "grape", "watermelon"
//  ]; 

// Example data (replace with your actual data)
$data = [
    "apple", "banana", "orange", "grape", "watermelon",
    "pineapple", "kiwi", "mango", "pear"
];

// Get the search term from the form
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search term (important for security - prevent XSS)
$searchTerm = htmlspecialchars(trim($searchTerm));

// Case-insensitive search
$searchTerm = strtolower($searchTerm); // Convert to lowercase for case-insensitive matching

// Search the data
$results = [];
foreach ($data as $item) {
    $itemLower = strtolower($item);
    if (strpos($itemLower, $searchTerm) !== false) {
        $results[] = $item; // Return the original item (case-preserved)
    }
}

?>
