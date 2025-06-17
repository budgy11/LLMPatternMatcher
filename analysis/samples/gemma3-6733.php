

<?php
session_start(); // Start the session

// ---  Cart Initialization ---

// Initialize an empty cart array if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// --------------------- Helper Functions ---------------------

/**
 * Add an item to the cart.
 *
 * @param string $product_id The unique ID of the product to add.
 * @param string $name The name of the product.
 * @param float $price The price of the product.
 * @param int $quantity The quantity to add (default: 1).
 */
function addToCart($product_id, $name, $price, $quantity = 1) {
    if (!isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = [
            'name' => $name,
            'price' => $price,
            'quantity' => $quantity,
        ];
    } else {
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    }
}

/**
 * Update the quantity of an item in the cart.
 *
 * @param string $product_id The unique ID of the product to update.
 * @param int $quantity The new quantity.
 */
function updateQuantity($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    }
}

/**
 * Remove an item from the cart.
 *
 * @param string $product_id The unique ID of the product to remove.
 */
function removeFromCart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

/**
 * Get all items in the cart.
 *
 * @return array The cart items.
 */
function getCartItems() {
    return $_SESSION['cart'];
}

/**
 * Calculate the total cart value.
 *
 * @return float The total value.
 */
function calculateTotal() {
    $total = 0;
    $cartItems = getCartItems();
    foreach ($cartItems as $item) {
        $totalItemValue = $item['price'] * $item['quantity'];
        $total += $totalItemValue;
    }
    return $total;
}

// --------------------- Example Usage (Simulated) ---------------------

// --- Add items to the cart based on user actions (e.g., button clicks) ---
// In a real application, this would come from form submissions or AJAX requests

// Example 1: Add a product to the cart
addToCart('product1', 'Awesome T-Shirt', 20.00, 2);

// Example 2:  Update the quantity of an existing product
updateQuantity('product1', 3); // Increase quantity of 'product1' to 3

// Example 3: Remove an item from the cart
// removeFromCart('product1');

// --------------------- Display the Cart ---------------------

// Get cart items
$cart = getCartItems();

// Calculate total
$total = calculateTotal();

echo "<h2>Your Shopping Cart</h2>";

if (empty($cart)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cart as $product_id => $item) {
        echo "<li>";
        echo "<strong>Product:</strong> " . $item['name'] . "<br>";
        echo "<strong>Price:</strong> $" . number_format($item['price'], 2) . "<br>";
        echo "<strong>Quantity:</strong> " . $item['quantity'] . "<br>";
        echo "<strong>Total for this item:</strong> $" . number_format($item['price'] * $item['quantity'], 2) . "<br>";
        echo "</li>";
    }
    echo "</ul>";
    echo "<p><strong>Total Cart Value:</strong> $" . number_format($total, 2) . "</p>";
}


?>
