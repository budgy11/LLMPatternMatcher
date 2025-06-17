
<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
</head>
<body>
    <h1>Shopping Cart</h1>

    <!-- Product Listing -->
    <h2>Available Products</h2>
    <ul>
        <?php
        foreach ($products as $id => $product) {
            echo "<li>" . $product['name'] . " - $" . $product['price'] . " <button type='button' onclick='addToCart(" . $id . ", 1)'>Add to Cart</button></li>";
        }
        ?>
