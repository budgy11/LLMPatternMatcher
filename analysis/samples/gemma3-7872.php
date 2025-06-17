    <label for="product_id">Product ID:</label>
    <select id="product_id" name="product_id">
        <?php
        foreach ($products as $id => $product) {
            echo "<option value=\"$id\">$id - " . $product['name'] . "</option>";
        }
        ?>
