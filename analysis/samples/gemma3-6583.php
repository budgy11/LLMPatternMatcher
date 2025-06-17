    </select><br><br>
    <input type="submit" name="remove_from_cart" value="Remove from Cart">
</form>


<form method="post">
    <label for="update_product_id">Product ID to Update:</label>
    <select name="product_id" id="update_product_id">
        <?php
        foreach ($products as $id => $product) {
            echo "<option value=\"$id\">$id - $product['name']</option>";
        }
        ?>
