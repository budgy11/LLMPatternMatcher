
<!DOCTYPE html>
<html>
<head>
    <title>Simple Shopping Cart</title>
</head>
<body>

<h1>Our Store</h1>

<h2>Products</h2>
<ul>
    <?php
    foreach ($products as $id => $product) {
        echo "<li>" . $product['name'] . " - $" . number_format($product['price'], 2) . " <button type='submit' name='product_id' value='" . $id . "'>Add to Cart</button></li>";
    }
    ?>
