
<!DOCTYPE html>
<html>
<head>
    <title>Simple Purchase Functionality</title>
</head>
<body>

<h1>Shopping Cart</h1>

<h2>Available Products</h2>
<ul>
    <?php
    foreach ($products as $id => $product) {
        echo "<li>" . $product['name'] . " - $" . number_format($product['price'], 2) . "</li>";
    }
    ?>
