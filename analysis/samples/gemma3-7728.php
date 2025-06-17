
<!DOCTYPE html>
<html>
<head>
    <title>Simple Purchase Functionality</title>
</head>
<body>

<h1>Available Products</h1>

<ul>
    <?php
    foreach ($products as $id => $product) {
        echo "<li>" . $product['name'] . " - $" . $product['price'] . " <button onclick='addToCart(" . $id . ", 1)'>Add to Cart</button></li>";
    }
    ?>
