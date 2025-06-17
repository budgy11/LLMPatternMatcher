    <!-- Add items to cart -->
    <label for="item_name">Item Name:</label>
    <input type="text" id="item_name" name="item_name"><br><br>

    <label for="item_price">Price:</label>
    <input type="number" id="item_price" name="item_price" step="0.01"><br><br>

    <label for="item_quantity">Quantity:</label>
    <input type="number" id="item_quantity" name="item_quantity" value="1" min="1"><br><br>

    <button type="submit" name="add_to_cart">Add to Cart</button>
    <button type="reset">Reset</button>
</form>

<hr>

<!-- Cart view -->
<?php view_cart(); ?>
