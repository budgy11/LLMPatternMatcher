    </select>
    <br><br>
    <label for="quantity">Quantity:</label>
    <input type="number" name="quantity" id="quantity" value="1" min="1">
    <br><br>
    <button type="submit">Add to Cart</button>
</form>

<br><br>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
