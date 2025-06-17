
<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart Example</title>
</head>
<body>

    <h2>Products</h2>
    <ul>
        <?php
        foreach ($products as $id => $product) {
            echo "<li>" . $product['name'] . " - $" . number_format($product['price'], 2) . "<button onclick='addToCart(" . $id . ", 1)'>Add to Cart</button></li>";
        }
        ?>
