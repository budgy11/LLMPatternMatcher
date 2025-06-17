        <label for="cart_id">Cart ID:</label>
        <input type="text" id="cart_id" name="cart_id" required><br><br>

        <label for="customer_name">Customer Name:</label>
        <input type="text" id="customer_name" name="customer_name" required><br><br>

        <label for="customer_email">Customer Email:</label>
        <input type="email" id="customer_email" name="customer_email" required><br><br>

        <label for="payment_method">Payment Method:</label>
        <select id="payment_method" name="payment_method" required>
            <option value="credit_card">Credit Card</option>
            <option value="paypal">PayPal</option>
            <option value="stripe">Stripe</option>
        </select><br><br>

        <input type="submit" value="Place Order">
    </form>

</body>
</html>


<?php

// 1. Product Data (Simulated Database)
$products = [
    1 => ['id' => 1, 'name' => 'T-Shirt', 'price' => 20],
    2 => ['id' => 2, 'name' => 'Jeans', 'price' => 50],
    3 => ['id' => 3, 'name' => 'Hat', 'price' => 15],
];

// 2. Cart
$cart = [];

// 3. Add to Cart Function
function addToCart($product_id, $cart) {
    if (isset($products[$product_id])) {
        $product = $products[$product_id];
        $cart[] = $product;
        return $cart;
    } else {
        return $cart; // Product not found, return unchanged cart
    }
}

// 4. View Cart Function
function viewCart($cart) {
    echo "<h2>Your Shopping Cart</h2>";
    if (empty($cart)) {
        echo "<p>Your cart is empty.</p>";
    } else {
        echo "<ul>";
        foreach ($cart as $item) {
            echo "<li>" . $item['name'] . " - $" . $item['price'] . "</li>";
        }
        echo "</ul>";
    }
}

// 5. Checkout Function (Simulated)
function checkout($cart) {
    echo "<h2>Checkout</h2>";
    if (empty($cart)) {
        echo "<p>Your cart is empty.  Nothing to checkout.</p>";
    } else {
        echo "<p>Total: $" . calculateTotal($cart) . "</p>";
        echo "<p>Thank you for your purchase!</p>";
    }
}

// Helper function to calculate total
function calculateTotal($cart) {
    $total = 0;
    foreach ($cart as $item) {
        $total += $item['price'];
    }
    return $total;
}

// 6.  Example Usage (Simulated User Interaction)

// Add a product to the cart
addToCart(1, $cart);
addToCart(2, $cart);

// View the cart
viewCart($cart);

// Checkout
checkout($cart);
?>
