
<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
</head>
<body>

<h1>Shopping Cart Example</h1>

<!-- Product Listing (Example) -->
<h2>Available Products</h2>
<ul>
    <?php
    foreach ($products as $id => $product) {
        echo "<li>" . $product['name'] . " - $" . $product['price'] . "<button onclick=\"addToCart(" . $id . ", 1)\" class=\"add-to-cart\">Add to Cart</button></li>";
    }
    ?>
