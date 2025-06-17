    </select>
    <br>
    <button type="submit" name="action" value="remove">Remove from Cart</button>
</form>

<hr>

<h2>Update Quantity in Cart</h2>
<form method="GET">
    <label for="product_id">Product ID:</label>
    <select name="product_id" id="product_id">
        <?php
        foreach ($products as $id => $product) {
            echo "<option value='" . $id . "'>" . $id . "</option>";
        }
        ?>
