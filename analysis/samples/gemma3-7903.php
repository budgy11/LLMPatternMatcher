
<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
</head>
<body>

<h1>Our Products</h1>

<?php
foreach ($products as $id => $product) {
    echo "<div class='product'>";
    echo "<form method='post'>";
    echo "<label for='product_id_{$id}'>Product: " . $product['name'] . " - $" . number_format($product['price'], 2) . "</label><br>";
    echo "<input type='number' id='product_id_{$id}' value='$id' name='product_id' min='1' max='$product['quantity']' style='width:50px;'>";
    echo "<br>";
    echo "<label for='quantity_{$id}'>Quantity:</label><br>";
    echo "<input type='number' id='quantity_{$id}' value='1' name='quantity' min='1' max='$product['quantity']' style='width:50px;'>";
    echo "<input type='submit' value='Add to Cart' name='action' value='add_to_cart'>";
    echo "</form>";
    echo "</div>";
}
?>
