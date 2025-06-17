    </select><br><br>

    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" value="1"><br><br>

    <input type="submit" name="add_to_cart" value="Add to Cart">
</form>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
