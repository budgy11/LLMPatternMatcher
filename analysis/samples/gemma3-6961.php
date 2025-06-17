
<!DOCTYPE html>
<html>
<head>
    <title>Simple Purchase Functionality</title>
</head>
<body>

<h1>Online Store</h1>

<h2>Available Products</h2>
<ul>
    <?php
    foreach ($products as $id => $product) {
        echo "<li>" . $product['name'] . " - Price: $" . $product['price'] . " <button type='button' onclick='addToCart(" . $id . ", 1)'>Add to Cart</button></li>";
    }
    ?>
