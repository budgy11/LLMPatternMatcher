
    <form method="post" action="">
        <label for="product_id">Product ID:</label>
        <input type="number" id="product_id" name="product_id" required>
        <br><br>

        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" name="quantity" required>
        <br><br>

        <button type="submit">Add to Cart</button>
    </form>

    <br>
    <a href="cart.php">View Cart</a>

</body>
</html>


<?php
session_start();

// Check if the cart is empty
if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<h2>Shopping Cart</h2>";
    echo "<ul>";
    foreach ($_SESSION['cart'] as $item) {
        echo "<li>Product ID: " . $item['product_id'] . ", Quantity: " . $item['quantity'] . ", Price: $" . $item['price'] . "</li>";
    }
    echo "</ul>";
}

// Button to remove item (implement this)
echo "<br><br>";
echo "<a href='remove_from_cart.php'>Remove from Cart</a>";
?>
