
<!DOCTYPE html>
<html>
<head>
    <title>Simple E-commerce</title>
</head>
<body>

<h1>Our Products</h1>

<?php
foreach ($products as $id => $product) {
    echo "<p><strong>" . $product['name'] . " - $" . $product['price'] . "</strong><br>";
    echo "<form method='post'>";
    echo "<input type='hidden' name='product_id' value='" . $id . "'>";
    echo "<input type='number' name='quantity' value='1' min='1' style='width:50px;'>";
    echo "<button type='submit'>Add to Cart</button>";
    echo "</form></p>";
}
?>
