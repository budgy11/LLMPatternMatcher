<?php
require_once 'database.php';
require_once 'products.php'; //Include products.php

// Handle Search Query
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $searchTerm = $_GET['search'];

    $sql = "SELECT * FROM products WHERE product_name LIKE '%" . $searchTerm . "%' OR category LIKE '%" . $searchTerm . "%'";
    $result = executeQuery($conn, $sql);

    if ($result->num_rows > 0) {
        // Display Search Results
        echo "<h2>Search Results for: " . $searchTerm . "</h2>";
        //  Display search results here - similar logic as in products.php
    } else {
        echo "<p>No products found matching your search.</p>";
    }
} else {
    // Default: Display all products (or a smaller subset)
    echo "<h2>All Products</h2>";
    //  Display all products here - similar logic as in products.php
}

$conn->close();
?>

<form action="index.php" method="get">
    <input type="text" name="search" placeholder="Search products...">
    <button type="submit">Search</button>
</form>
