

<?php
session_start();

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// ---------------------  Helper Functions ---------------------
/**
 * Adds an item to the shopping cart.
 *
 * @param int $product_id The ID of the product to add.
 * @param int $quantity  The quantity of the product to add.
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
 * Removes an item from the shopping cart.
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
 * @param int $quantity The new quantity of the product.
 * @return void
 */
function update_cart_item($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = $quantity;
    }
}

/**
 * Gets the items in the shopping cart.
 *
 * @return array The shopping cart contents.
 */
function get_cart_items() {
    return $_SESSION['cart'];
}

/**
 * Calculates the total number of items in the cart.
 *
 * @return int
 */
function cart_total() {
    $total = 0;
    foreach ($_SESSION['cart'] as $quantity) {
        $total += $quantity;
    }
    return $total;
}


// ---------------------  Example Usage (Simulated Products) ---------------------

// Let's assume we have some products with IDs 1, 2, and 3.

// Add some items to the cart
add_to_cart(1, 2);  // Add 2 of product 1
add_to_cart(2, 1);  // Add 1 of product 2
add_to_cart(1, 3);  // Add 3 of product 1
add_to_cart(3, 1);

// Display the cart contents
echo "<h2>Shopping Cart</h2>";
if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        // Simulate retrieving product details (replace with actual database lookup)
        $product_name = "Product " . $product_id;  // Dummy product name

        echo "<li>$product_name - Quantity: $quantity</li>";
    }
    echo "</ul>";
    echo "<p>Total items in cart: " . cart_total() . "</p>";
}


// Example of removing an item
//remove_from_cart(2);

//Example of updating an item
//update_cart_item(1, 5);

?>
