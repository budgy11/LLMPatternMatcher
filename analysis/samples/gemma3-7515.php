
<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
</head>
<body>

    <h1>Shopping Cart</h1>

    <h2>Products</h2>
    <ul>
        <?php
        foreach ($products as $id => $product) {
            echo "<li>" . $product['name'] . " - $" . $product['price'] . " <button onclick=\"addToCart(" . $id . ",1)\">Add to Cart</button></li>";
        }
        ?>
