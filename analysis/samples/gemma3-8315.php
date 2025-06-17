
<hr>

<!-- Add to Cart Button (Assuming you have a product listing) -->
<?php
// Example Product listing (Replace with your actual product data)
$products = [
    1 => ['name' => 'T-Shirt', 'price' => 20],
    2 => ['name' => 'Jeans', 'price' => 50],
    3 => ['name' => 'Hat', 'price' => 15]
];

echo "<h2>Add to Cart</h2>";
foreach ($products as $product_id => $product_data) {
    echo "<form method='post' action='cart.php'>";
    echo "<label for='product_" . $product_id . "'>Product: " . $product_data['name'] . " ($" . number_format($product_data['price'], 2) . ")</label><br>";
    echo "<input type='number' id='quantity_" . $product_id . "' name='quantity_" . $product_id . "' value='1' min='1' max='10'><br>"; //Added max to limit quantity
    echo "<input type='submit' value='Add to Cart'>";
    echo "</form><br>";
}
?>
