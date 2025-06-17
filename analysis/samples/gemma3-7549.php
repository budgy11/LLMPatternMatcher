        <label for="item">Item:</label>
        <select name="item" id="item">
            <option value="T-Shirt">T-Shirt</option>
            <option value="Jeans">Jeans</option>
            <option value="Hat">Hat</option>
            <option value="Shoes">Shoes</option>
        </select><br><br>

        <label for="quantity">Quantity:</label>
        <input type="number" name="quantity" id="quantity" min="1"><br><br>

        <button type="submit">Add to Cart</button>
    </form>

    <!--  Optional:  Remove Item Form  -->
    <h2>Remove Item</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
