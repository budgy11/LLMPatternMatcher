

<?php

session_start();

// Initialize the cart array if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// --- Functions to handle the cart ---

/**
 * Adds an item to the cart.
 *
 * @param int $product_id The ID of the product to add.
 * @param int $quantity The quantity of the product to add.
 * @return void
 */
function addToCart($product_id, $quantity = 1) {
    // Check if the product is already in the cart
    if (isset($_SESSION['cart'][$product_id])) {
        // Product exists, increment the quantity
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        // Product doesn't exist, add it to the cart with quantity 1
        $_SESSION['cart'][$product_id] = array('quantity' => $quantity);
    }
}

/**
 * Removes an item from the cart.
 *
 * @param int $product_id The ID of the product to remove.
 * @return void
 */
function removeFromCart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

/**
 * Updates the quantity of an item in the cart.
 *
 * @param int $product_id The ID of the product to update.
 * @param int $quantity The new quantity of the product.
 * @return void
 */
function updateQuantity($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    }
}


/**
 * Gets the contents of the cart.
 *
 * @return array The cart contents.
 */
function getCartContents() {
    return $_SESSION['cart'];
}

// --- Example Usage (Demonstration) ---

// Add some items to the cart
addToCart(101, 2); // Product ID 101, quantity 2
addToCart(102, 1); // Product ID 102, quantity 1
addToCart(101, 3); // Product ID 101, quantity 3


// Display the cart contents
echo "<h2>Cart Contents:</h2>";
echo "<ul>";
$cart_items = getCartContents();
if (empty($cart_items)) {
    echo "<li>Cart is empty.</li>";
} else {
    foreach ($cart_items as $product_id => $item) {
        echo "<li>Product ID: " . $product_id . ", Quantity: " . $item['quantity'] . "</li>";
    }
}
echo "</ul>";


// Update the quantity of a product
updateQuantity(101, 5);

// Display the updated cart contents
echo "<br><h2>Updated Cart Contents:</h2>";
$cart_items = getCartContents();
if (empty($cart_items)) {
    echo "<li>Cart is empty.</li>";
} else {
    foreach ($cart_items as $product_id => $item) {
        echo "<li>Product ID: " . $product_id . ", Quantity: " . $item['quantity'] . "</li>";
    }
}

// Remove an item from the cart
removeFromCart(102);

// Display the cart contents after removal
echo "<br><h2>Cart Contents After Removal:</h2>";
$cart_items = getCartContents();
if (empty($cart_items)) {
    echo "<li>Cart is empty.</li>";
} else {
    foreach ($cart_items as $product_id => $item) {
        echo "<li>Product ID: " . $product_id . ", Quantity: " . $item['quantity'] . "</li>";
    }
}
?>
