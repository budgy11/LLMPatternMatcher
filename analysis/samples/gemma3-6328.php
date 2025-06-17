

<?php

// Start the session if it doesn't exist
session_start();

// Initialize the cart session data if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// ------------------------------------------------------------------
//  Helper Functions (for adding to and viewing the cart)
// ------------------------------------------------------------------

/**
 * Adds an item to the cart.
 *
 * @param int $product_id The ID of the product to add.
 * @param int $quantity The quantity of the product to add.
 * @return void
 */
function add_to_cart($product_id, $quantity = 1) {
    if (!isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = 0; // Initialize count if not already present
    }
    $_SESSION['cart'][$product_id] += $quantity;
}

/**
 * Updates the quantity of an item in the cart.
 *
 * @param int $product_id The ID of the product to update.
 * @param int $quantity The new quantity.
 * @return void
 */
function update_cart_quantity($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = $quantity;
    }
}

/**
 * Removes an item from the cart.
 *
 * @param int $product_id The ID of the product to remove.
 * @return void
 */
function remove_from_cart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

/**
 * Gets the contents of the cart.
 *
 * @return array The cart contents (an array of product IDs and their quantities).
 */
function get_cart_contents() {
    return $_SESSION['cart'];
}

// ------------------------------------------------------------------
//  Example Usage (Simulated Product Information - Replace with Database Queries)
// ------------------------------------------------------------------

$products = [
    1 => ['name' => 'T-Shirt', 'price' => 20],
    2 => ['name' => 'Jeans', 'price' => 50],
    3 => ['name' => 'Hat', 'price' => 15],
];

// ------------------------------------------------------------------
//  Simulate User Interactions (e.g., adding to cart)
// ------------------------------------------------------------------

// Example: User adds one T-Shirt to the cart
if (isset($_POST['add_tshirt'])) {
    add_to_cart(1);
}

// Example: User adds two Jeans to the cart
if (isset($_POST['add_jeans'])) {
    add_to_cart(2, 2);
}

// Example: User updates the quantity of a T-Shirt
if (isset($_POST['update_tshirt_qty'])) {
    $new_qty = intval($_POST['tshirt_qty']);
    update_cart_quantity(1, $new_qty);
}

// Example: User removes a Hat from the cart
if (isset($_POST['remove_hat'])) {
    remove_from_cart(3);
}

// ------------------------------------------------------------------
//  Displaying the Cart Contents
// ------------------------------------------------------------------

// Get the cart contents
$cart_contents = get_cart_contents();

// Calculate the total price
$total_price = 0;
foreach ($cart_contents as $product_id => $quantity) {
    $product = $products[$product_id];
    $total_price += $product['price'] * $quantity;
}

echo "<h2>Your Cart</h2>";

if (empty($cart_contents)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cart_contents as $product_id => $quantity) {
        $product = $products[$product_id];
        echo "<li>" . $product['name'] . " - Quantity: " . $quantity . " - Price: $" . $product['price'] . "</li>";
    }
    echo "</ul>";
    echo "<p><strong>Total: $" . $total_price . "</strong></p>";
}
?>
