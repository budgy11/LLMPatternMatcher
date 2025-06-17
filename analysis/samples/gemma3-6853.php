        </select>

        <label for="quantity">Quantity:</label>
        <input type="number" name="quantity" value="1" min="1">

        <input type="hidden" name="action" value="add_to_cart">
        <button type="submit">Add to Cart</button>
    </form>


    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
