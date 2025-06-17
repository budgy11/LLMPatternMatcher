
<hr>

<h2>Add Items to Cart</h2>
<ul>
    <?php
    foreach ($products as $id => $product) {
        echo "<li>Product ID: " . $product['id'] . " - " . $product['name'] . " - Price: $" . $product['price'] . " <button onclick='addToCart(" . $product['id'] . ", 1)'>Add to Cart</button></li>";
    }
    ?>
