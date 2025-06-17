
<!DOCTYPE html>
<html>
<head>
    <title>Products</title>
</head>
<body>

<h1>Products</h1>

<?php
foreach ($product_data as $product_id => $product) {
    echo "<h2>" . $product['name'] . " - $" . number_format($product['price'], 2) . "</h2>";
    echo "<p>Stock: " . $product['stock'] . "</p>";
    echo "<form method='post'>";
    echo "<input type='hidden' name='product_id' value='" . $product_id . "'>";
    echo "<input type='number' name='quantity' value='1' min='1' max='" . $product_data[$product_id]['stock'] . "' >";
    echo "<button type='submit' name='add_to_cart'>Add to Cart</button>";
    echo "</form>";
    echo "<br>";
}
?>
