

function searchData($query) {
    // *** DO NOT USE ECHO/SQL IN THIS FUNCTION - USE PARAMETERIZED QUERIES ***
    // This is for illustration only.

    $db = new PDO('mysql:host=localhost;dbname=your_database', 'username', 'password');
    $stmt = $db->prepare("SELECT * FROM products WHERE name LIKE :search OR description LIKE :search");
    $stmt->execute(['search' => '%' . $query . '%']); // Use '%' for wildcard

    $results = [];
    foreach ($stmt->fetchAll(PDO::FETCH_COLUMN) as $row) {
        $results[] = $row;
    }

    return $results;
}


<?php

// Sample search data (replace with your actual data source - e.g., database)
$products = [
    ['id' => 1, 'name' => 'Laptop'],
    ['id' => 2, 'name' => 'Mouse'],
    ['id' => 3, 'name' => 'Keyboard'],
    ['id' => 4, 'name' => 'Monitor'],
    ['id' => 5, 'name' => 'Tablet'],
];

// Get the search term from the search bar (using $_GET)
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search term (important to prevent XSS)
$searchTerm = htmlspecialchars($searchTerm);

// Perform the search
$searchResults = [];
foreach ($products as $product) {
    // Case-insensitive comparison
    if (stripos($product['name'], $searchTerm) !== false) {  //Use stripos for case-insensitive search
        $searchResults[] = $product;
    }
}

?>
