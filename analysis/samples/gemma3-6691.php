

<?php
session_start(); // Start the session

// --- Example Cart Logic ---

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// --- Helper Functions ---

/**
 * Adds an item to the cart.
 *
 * @param string $product_id The ID of the product to add.
 * @param string $name The name of the product.
 * @param int $price The price of the product.
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
 * Removes an item from the cart.
 *
 * @param string $product_id The ID of the product to remove.
 */
function removeFromCart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

/**
 * Updates the quantity of an item in the cart.
 *
 * @param string $product_id The ID of the product to update.
 * @param int $newQuantity The new quantity.
 */
function updateQuantity($product_id, $newQuantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $newQuantity;
    }
}


/**
 * Gets the items in the cart.
 *
 * @return array An array of items in the cart.
 */
function getCartItems() {
    return $_SESSION['cart'];
}

/**
 * Calculates the total price of the items in the cart.
 *
 * @return float The total price.
 */
function calculateTotal() {
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total = $total + ($item['price'] * $item['quantity']);
    }
    return $total;
}

// --- Example Usage (Simulating a user adding items) ---

// Add some items to the cart
addToCart('product1', 'Laptop', 1200, 1);
addToCart('product2', 'Mouse', 25, 2);
addToCart('product3', 'Keyboard', 75, 1);

// Display the cart items
echo "<h2>Your Shopping Cart</h2>";
if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach (getCartItems() as $product_id => $item) {
        echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . $item['price'] * $item['quantity'] . "</li>";
    }
    echo "</ul>";
    echo "<p>Total: $" . calculateTotal() . "</p>";
}

// --- Example Removing an Item ---
//removeFromCart('product2');

// --- Example Updating Quantity ---
//updateQuantity('product1', 3);
//echo "<p>Updated quantity of product1 to 3.</p>";

?>
