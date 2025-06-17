

<?php
session_start();

// Initialize the cart as an empty array if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// --- Helper Functions ---

/**
 * Adds an item to the cart.
 *
 * @param int $product_id The ID of the product being added.
 * @param int $quantity  The quantity of the product being added. Defaults to 1.
 * @return void
 */
function add_to_cart($product_id, $quantity = 1) {
    if (!isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = $quantity;
    } else {
        $_SESSION['cart'][$product_id] += $quantity;
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
 * Updates the quantity of an item in the cart.
 *
 * @param int $product_id The ID of the product to update.
 * @param int $quantity  The new quantity.
 * @return void
 */
function update_cart_item($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = $quantity;
    }
}

/**
 * Gets the contents of the cart.
 *
 * @return array The cart contents.
 */
function get_cart() {
    return $_SESSION['cart'];
}

/**
 * Clears the cart.
 *
 * @return void
 */
function clear_cart() {
    unset($_SESSION['cart']);
}

// --- Example Usage (Illustrative - Replace with your actual product data) ---

// Simulate a product database (for demonstration only)
$products = array(
    1 => array('name' => 'Laptop', 'price' => 1200),
    2 => array('name' => 'Mouse', 'price' => 25),
    3 => array('name' => 'Keyboard', 'price' => 75)
);

// -------------------- Cart Interaction --------------------

// Example: Add a product to the cart
add_to_cart(1); // Add 1 Laptop to the cart
add_to_cart(2, 3); // Add 3 Mice to the cart
add_to_cart(3); // Add 1 Keyboard to the cart

// Print the current cart contents
echo "<h2>Cart Contents:</h2>";
echo "<pre>";
print_r(get_cart());
echo "</pre>";

// Example: Update the quantity of a product
update_cart_item(2, 5); // Increase the quantity of Mice to 5

// Print the updated cart contents
echo "<h2>Cart Contents (Updated):</h2>";
echo "<pre>";
print_r(get_cart());
echo "</pre>";

// Example: Remove an item from the cart
remove_from_cart(1);

// Print the cart contents after removing the Laptop
echo "<h2>Cart Contents (After Removing Laptop):</h2>";
echo "<pre>";
print_r(get_cart());
echo "</pre>";

// Example: Clear the cart
//clear_cart();
//echo "<h2>Cart Contents (Cleared):</h2>";
//echo "<pre>";
//print_r(get_cart());
//echo "</pre>";
?>
