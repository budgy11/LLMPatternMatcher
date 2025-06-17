    <label for="product_id">Product ID:</label>
    <input type="number" id="product_id" name="product_id" required>
    <br><br>

    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" required>
    <br><br>

    <input type="submit" value="Add to Cart">
</form>

<hr>

<h2>Your Cart</h2>
<?php
if (isset($_SESSION["cart"])) {
    echo "<ul>";
    foreach ($_SESSION["cart"] as $product_id => $qty) {
        if (isset($products[$product_id])) {
            echo "<li>" . $products[$product_id]["name"] . " - Quantity: " . $qty . " - Price: $" . number_format($products[$product_id]["price"], 2) . "</li>";
        }
    }
    echo "</ul>";
} else {
    echo "<p>Your cart is empty.</p>";
}
?>
