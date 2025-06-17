    </select>
    <br>
    <label for="quantity">Quantity:</label>
    <input type="number" name="quantity" id="quantity" value="1" min="1">
    <br>
    <button type="submit" name="action" value="add">Add to Cart</button>
</form>

<hr>

<h2>Remove from Cart</h2>
<form method="GET">
    <label for="product_id">Product ID:</label>
    <select name="product_id" id="product_id">
        <?php
        foreach ($products as $id => $product) {
            echo "<option value='" . $id . "'>" . $id . "</option>";
        }
        ?>
