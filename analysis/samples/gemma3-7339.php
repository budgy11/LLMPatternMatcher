    </select>
    <br>
    <label for="quantity">Quantity:</label>
    <input type="number" name="quantity" id="quantity" value="1" min="1">
    <br>
    <button type="submit" name="action" value="update">Update Quantity</button>
</form>


</body>
</html>

<?php

// Helper Function to calculate total cart value.
function getTotalCartValue() {
    $total = 0;
    $cart_contents = getCartContents();
    foreach ($cart_contents as $product_id => $item) {
        $total += ($products[$product_id]['price'] * $item['quantity']);
    }
    return $total;
}

?>
