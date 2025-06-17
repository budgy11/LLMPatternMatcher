

<?php
// This file would typically fetch product information from a database, API, etc.
// For this example, we'll just simulate some product data.

$products = [
    1 => ['name' => 'T-Shirt', 'price' => 20.00],
    2 => ['name' => 'Jeans', 'price' => 50.00],
    3 => ['name' => 'Hat', 'price' => 15.00],
];

// Example:  Display a product by ID
if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];
    if (isset($products[$product_id])) {
        echo "<h2>Product Details</h2>";
        echo "<strong>" . $products[$product_id]['name'] . "</strong> - $" . $products[$product_id]['price'];
    } else {
        echo "<p>Product not found.</p>";
    }
}
?>
