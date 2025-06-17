

</body>
</html>


<?php
session_start();

// --- Cart Functions ---

/**
 * Add an item to the cart.
 *
 * @param string $product_id The ID of the product to add.
 * @param string $name The name of the product.
 * @param int $price The price of the product.
 * @param int $quantity The quantity of the product to add (default: 1).
 */
function addToCart($product_id, $name, $price, $quantity = 1) {
    if (empty($_SESSION['cart'])) {
        $_SESSION['cart'] = []; // Initialize cart as an empty array if it doesn't exist
    }

    if (isset($_SESSION['cart'][$product_id])) {
        // Item already in cart, update quantity
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        // Add new item to cart
        $_SESSION['cart'][$product_id] = [
            'name' => $name,
            'price' => $price,
            'quantity' => $quantity
        ];
    }
}

/**
 * Remove an item from the cart.
 *
 * @param string $product_id The ID of the product to remove.
 */
function removeFromCart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}


/**
 * Update the quantity of an item in the cart.
 *
 * @param string $product_id The ID of the product to update.
 * @param int $quantity The new quantity of the product.
 */
function updateQuantity($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    }
}


/**
 * Get the cart contents.
 *
 * @return array The cart contents array.
 */
function getCart() {
    return $_SESSION['cart'] ?? []; // Use null coalesce operator for safety
}

/**
 * Calculate the total cart value.
 *
 * @return float The total cart value.
 */
function calculateTotal() {
    $total = 0;
    $cart = getCart();
    foreach ($cart as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}



// --- Example Usage (Simulate adding items to the cart) ---

// Add a product to the cart
addToCart('product1', 'T-Shirt', 20, 2);

// Add another product
addToCart('product2', 'Jeans', 50, 1);

// Update the quantity of product1
updateQuantity('product1', 3);

// Get the cart contents
$cart = getCart();
echo "<h2>Cart Contents:</h2>";
echo "<pre>";
print_r($cart);
echo "</pre>";

// Calculate and display the total
$total = calculateTotal();
echo "<p><strong>Total Cart Value: $" . number_format($total, 2) . "</strong></p>";

// Remove an item from the cart
removeFromCart('product2');

// Display the updated cart
echo "<p><strong>Updated Cart Contents:</strong></p>";
echo "<pre>";
print_r($cart);
echo "</pre>";
?>
