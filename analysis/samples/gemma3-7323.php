<!DOCTYPE html>
<html>
<head>
    <title>E-commerce Purchase</title>
</head>
<body>

<h1>Available Products</h1>

<ul>
    <?php
    foreach ($products as $product) {
        echo "<li>" . $product['name'] . " - $" . $product['price'] . "</li>";
    }
    ?>
