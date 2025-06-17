    </select><br><br>

    <input type="submit" name="add_to_cart" value="Add to Cart">
  </form>

  <a href="checkout.php">Checkout</a> <!-- Example checkout link -->

</body>
</html>


<?php
session_start();

// This is a basic example, suitable for small carts. 
// For production environments, use a database-backed solution for cart persistence.

// Initialize the cart session if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
    if (!isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = [
            'name' => $product_name,
            'price' => $price,
            'quantity' => $quantity,
        ];
    } else {
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    }
}

// Function to remove an item from the cart
function removeCartItem($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// Function to update the quantity of an item in the cart
function updateCartQuantity($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    }
}

// Example usage (demonstration)
// Let's say we have the following products:
// Product ID: 1, Name: "Laptop", Price: 1200
// Product ID: 2, Name: "Mouse", Price: 25
// Product ID: 3, Name: "Keyboard", Price: 75

// Add a laptop to the cart
addToCart(1, 'Laptop', 1200);

// Add a mouse to the cart
addToCart(2, 'Mouse', 25, 2); // Add 2 of the mouse

// Update the quantity of the mouse
updateCartQuantity(2, 5); // Now we have 5 mice in the cart


// Display the cart contents
echo "<h2>Cart Contents:</h2>";
if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($_SESSION['cart'] as $product_id => $item) {
        echo "<li>" . $item['name'] . " - Price: $" . $item['price'] . " x " . $item['quantity'] . " = $" . ($item['price'] * $item['quantity']) . "</li>";
    }
    echo "</ul>";
}

// Remove the mouse (example)
// removeCartItem(2);
// echo "<p>Mouse removed from cart.</p>";


// Example of how to clear the cart (for demonstration)
// session_destroy();  // Comment this out to keep the cart data
// session_unset(); // Clears all session variables
?>
