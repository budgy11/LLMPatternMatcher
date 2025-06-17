
<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
</head>
<body>

<h1>Our Products</h1>

<ul>
    <?php
    foreach ($products as $id => $product) {
        echo "<li>" . $product['name'] . " - $" . $product['price'] . " <form method='post'><input type='hidden' name='product_id' value='" . $id . "'>";
        echo "<label for='quantity_" . $id . "'>Quantity:</label> <input type='number' id='quantity_" . $id . "' value='1' min='1' name='quantity'><input type='submit' value='Add to Cart'>";
        echo "</form></li>";
    }
    ?>
