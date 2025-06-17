    </select>
    <br><br>
    <label for="quantity">Quantity:</label>
    <input type="number" name="quantity" value="1" min="1">
    <br><br>
    <button type="submit" name="action">Add to Cart</button>
</form>

<?php
if (isset($_POST['action'])) {
    $product_id = $_POST['product_id'];
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;  // Ensure quantity is an integer
    
    if ($product_id && $quantity > 0) {
        add_to_cart($product_id, $quantity);
    }
}
?>
