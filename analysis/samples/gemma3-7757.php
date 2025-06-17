
    <h2>Add to Cart</h2>
    <form method="post">
        <?php
        foreach ($products as $product_id => $product) {
            echo "<label for=\"product_id\">Product ID: " . $product['id'] . "</label><br>";
            echo "<input type=\"number\" id=\"product_id\" name=\"product_id\" value=\"" . $product['id'] . "\"><br>";
            echo "<label for=\"quantity\">Quantity:</label><br>";
            echo "<input type=\"number\" id=\"quantity\" name=\"quantity\" value=\"1\"><br><br>";
        }
        ?>
