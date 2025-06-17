    </select><br><br>

    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" value="1"><br><br>

    <input type="submit" name="add_to_cart" value="Add to Cart">
</form>

<form method="post">
    <label for="remove_product_id">Product ID to Remove:</label>
    <select name="product_id" id="remove_product_id">
        <?php
        foreach ($products as $id => $product) {
            echo "<option value=\"$id\">$id - $product['name']</option>";
        }
        ?>
