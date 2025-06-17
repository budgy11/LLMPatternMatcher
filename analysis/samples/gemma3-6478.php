

<?php
session_start();

// -----------------------------------------------------------------
// Cart Functions
// -----------------------------------------------------------------

/**
 * Adds a product to the cart.
 *
 * @param int $product_id The ID of the product to add.
 * @param int $quantity The quantity of the product to add (default: 1).
 * @return void
 */
function addToCart($product_id, $quantity = 1) {
    if (isset($_SESSION['cart'])) {
        // Item already in cart, increment quantity
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        // Item not in cart, add it
        $_SESSION['cart'][$product_id] = [
            'quantity' => $quantity,
            'product_id' => $product_id // Useful for later removal
        ];
    }
}

/**
 * Updates the quantity of an item in the cart.
 *
 * @param int $product_id The ID of the product to update.
 * @param int $quantity The new quantity.
 * @return void
 */
function updateCartQuantity($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    }
}

/**
 * Removes an item from the cart.
 *
 * @param int $product_id The ID of the product to remove.
 * @return void
 */
function removeCartItem($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

/**
 * Gets the cart contents.
 *
 * @return array The cart contents (array of product IDs and quantities).
 */
function getCartContents() {
    return $_SESSION['cart'] ?? []; // Return empty array if cart is empty
}

/**
 * Calculates the total price of the cart.
 *
 * @param array $cart  The cart contents array.
 * @param array $product_prices  An array mapping product IDs to their prices.
 * @return float  The total price.
 */
function calculateCartTotal($cart, $product_prices) {
    $total = 0;
    foreach ($cart as $product_id => $item) {
        if (isset($product_prices[$product_id])) {
            $total += $product_prices[$product_id] * $item['quantity'];
        }
    }
    return $total;
}


// -----------------------------------------------------------------
// Example Usage (Simulated Product Data)
// -----------------------------------------------------------------

$product_prices = [
    101 => 25.00,  // Product ID 101: $25.00
    102 => 15.50,  // Product ID 102: $15.50
    103 => 8.75   // Product ID 103: $8.75
];


// -----------------------------------------------------------------
// Example Interaction - Simulate a User Adding Items
// -----------------------------------------------------------------

// User adds 2 of product 101
addToCart(101, 2);

// User adds 1 of product 102
addToCart(102, 1);

// User updates quantity of product 101 to 3
updateCartQuantity(101, 3);

// -----------------------------------------------------------------
// Display Cart Contents
// -----------------------------------------------------------------

$cart_contents = getCartContents();
$cart_total = calculateCartTotal($cart_contents, $product_prices);

echo "<h2>Your Shopping Cart</h2>";

if (empty($cart_contents)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cart_contents as $product_id => $item) {
        echo "<li>Product ID: " . $product_id . ", Quantity: " . $item['quantity'] . ", Price: $" . $product_prices[$product_id] . "</li>";
    }
    echo "</ul>";
    echo "<p>Total: $" . $cart_total . "</p>";
}
?>
