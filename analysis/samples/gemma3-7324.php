</ul>

<h2>Place Your Order</h2>

<form action="purchase.php" method="POST">
    <label for="product_id">Product ID:</label>
    <select name="product_id" id="product_id">
        <?php
        foreach ($products as $product) {
            echo "<option value=\"" . $product['id'] . "\">" . $product['name'] . "</option>";
        }
        ?>
