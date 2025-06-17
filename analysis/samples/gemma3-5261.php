

<?php

// Assuming you have a database connection established (e.g., mysqli)
// $conn = new mysqli("localhost", "username", "password", "database_name");

// Check connection
// if ($conn->connect_error) {
//   die("Connection failed: " . $conn->connect_error);
// }

// **Basic Search Function (Simple String Search)**
function search_data($search_term, $data_source) {
    $results = [];
    foreach ($data_source as $item) {
        // Normalize input (lowercase and remove non-alphanumeric)
        $search_term = strtolower(preg_replace('/[^a-zA-Z0-9\s]/', '', $search_term));
        
        // Check if either the item or the search term is empty (to avoid errors)
        if ($search_term === "" || $item === "") {
          continue; // Skip to the next item
        }

        // If search term is a substring of the item, it's a match
        if (strpos($item, $search_term) !== false) {
            $results[] = $item;
        }
    }
    return $results;
}

// Example Usage:  Let's assume $data_source is an array of strings.

// Simulate a database query result (replace with your actual query)
$data_source = [
    "apple",
    "banana",
    "orange juice",
    "grapefruit",
    "pineapple",
    "mango",
    "apricot"
];

// Get the search term from the form (or URL parameter)
$search_term = isset($_GET['search']) ? $_GET['search'] : ''; // Get from GET request

// Perform the search
$search_results = search_data($search_term, $data_source);

?>
