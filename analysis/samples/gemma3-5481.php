

<?php

// This is a basic example, you'll likely need to adapt it to your specific database and application.

// Assume you have a database connection established and a $data array containing the data you want to search.
// This data could be from a database, a file, or any other source.

// Example Data (Replace this with your actual data source)
$data = [
    ['id' => 1, 'name' => 'Alice Smith', 'email' => 'alice.smith@example.com'],
    ['id' => 2, 'name' => 'Bob Johnson', 'email' => 'bob.johnson@example.com'],
    ['id' => 3, 'name' => 'Charlie Brown', 'email' => 'charlie.brown@example.com'],
];

// Check if the search term is provided
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $searchTerm = $_GET['search'];
    // Sanitize the search term to prevent XSS vulnerabilities
    $searchTerm = filter_var($searchTerm, FILTER_SANITIZE_STRING);

    // Perform the search
    $searchResults = [];
    foreach ($data as $item) {
        // Case-insensitive search
        $name = strtolower($item['name']);
        $email = strtolower($item['email']);
        $searchString = strtolower($searchTerm);

        if (strpos($name, $searchString) !== false || strpos($email, $searchString) !== false) {
            $searchResults[] = $item;
        }
    }
} else {
    $searchResults = []; // Empty array if no search term is provided.
}

?>
