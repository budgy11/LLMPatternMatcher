

<?php
session_start();

// Initialize the cart session if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// -------------------- Functions for Cart Management --------------------

/**
 * Adds an item to the cart.
 *
 * @param int $product_id The ID of the product to add.
 * @param int $quantity The quantity of the product to add.
 * @return void
 */
function add_to_cart($product_id, $quantity = 1) {
    if (!isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = 0;  // Initialize count if not already present
    }
    $_SESSION['cart'][$product_id] += $quantity;
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
 * @param int $quantity The new quantity of the product.
 * @return void
 */
function update_cart_quantity($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = $quantity;
    }
}

/**
 * Gets all items in the cart.
 *
 * @return array  An array containing the items in the cart.
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
    foreach($_SESSION['cart'] as $quantity => $qty) {
        $total = $total + $qty;
    }
    return $total;
}



// -------------------- Example Usage (Simulated Product Add/Remove) --------------------

// Example 1: Add a product to the cart
add_to_cart(123, 2); // Add 2 units of product with ID 123

// Example 2: Add another unit of the same product
add_to_cart(123, 1);

// Example 3:  Remove a product
// remove_from_cart(123);

// Example 4: Update the quantity of a product
// update_cart_quantity(123, 3);


// -------------------- Displaying the Cart --------------------

// Get the cart items
$cart_items = get_cart_items();

// Print the cart items
echo "<h2>Your Cart</h2>";
if (empty($cart_items)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cart_items as $product_id => $quantity) {
        //  In a real application, you'd retrieve product details here 
        //  based on the $product_id
        echo "<li>Product ID: $product_id, Quantity: $quantity</li>";
    }
    echo "</ul>";
    echo "<p>Cart Total: " . cart_total() . "</p>";
}


//  You would typically:
// 1.  Replace the product ID's with actual product IDs from your database.
// 2.  Retrieve product details (name, price, etc.) for each product in the cart.
// 3.  Implement a mechanism for users to view and modify the cart contents.
// 4.  Integrate this cart functionality with your shopping cart system.

?>
