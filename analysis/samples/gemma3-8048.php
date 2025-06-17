
<!DOCTYPE html>
<html>
<head>
    <title>Simple Purchase Functionality</title>
</head>
<body>

<h1>Shop</h1>

<h2>Available Products</h2>
<ul>
    <?php
    foreach ($products as $id => $product) {
        echo "<li>" . $product['name'] . " - $" . number_format($product['price'], 2) . "<br>";
        echo "<form method='post'>";
        echo "<input type='hidden' name='product_id' value='" . $id . "'>";
        echo "<input type='number' name='quantity' value='1' min='1'>";
        echo "<button type='submit'>Add to Cart</button>";
        echo "</form></li>";
    }
    ?>
