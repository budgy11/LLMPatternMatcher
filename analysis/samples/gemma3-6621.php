

<?php

// Include the session handling library (if needed, often built-in)
session_start();

// Define the session variables
$session_id = session_id(); // Get the current session ID
$cart = array(); // Initialize an empty cart array
$total_items = 0;


// --- Functions for Cart Management ---

/**
 * Adds an item to the cart.
 *
 * @param int $product_id  The ID of the product to add.
 * @param int $quantity The quantity of the product to add (default: 1).
 * @return void
 */
function add_to_cart(int $product_id, int $quantity = 1) {
    global $cart, $total_items;

    // Check if the product is already in the cart
    if (isset($cart[$product_id])) {
        $cart[$product_id] += $quantity; // Increment quantity if exists
    } else {
        $cart[$product_id] = $quantity; // Add to cart if it doesn't exist
    }
    $total_items = array_sum($cart);  // Recalculate total
}


/**
 * Removes an item from the cart.
 *
 * @param int $product_id The ID of the product to remove.
 * @return void
 */
function remove_from_cart(int $product_id) {
    global $cart;
    if (isset($cart[$product_id])) {
        unset($cart[$product_id]);
        $total_items = array_sum($cart); // Recalculate total
    }
}


/**
 * Updates the quantity of an item in the cart.
 *
 * @param int $product_id The ID of the product to update.
 * @param int $new_quantity The new quantity of the product.
 * @return void
 */
function update_quantity(int $product_id, int $new_quantity) {
    global $cart;

    if (isset($cart[$product_id])) {
        $cart[$product_id] = $new_quantity;
        $total_items = array_sum($cart); // Recalculate total
    }
}



/**
 * Clears the entire cart.
 * @return void
 */
function clear_cart() {
    global $cart;
    $cart = array();
    $total_items = 0;
}



// --- Example Usage (Demonstration) ---

// Simulate adding items to the cart
add_to_cart(101, 2); // Add 2 of product ID 101
add_to_cart(102, 1); // Add 1 of product ID 102
add_to_cart(101, 3); // Add 3 of product ID 101

// Display the current cart contents
echo "<h2>Cart Contents:</h2>";
if (empty($cart)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cart as $product_id => $quantity) {
        echo "<li>Product ID: " . $product_id . ", Quantity: " . $quantity . "</li>";
    }
    echo "</ul>";
}


echo "<p>Total Items in Cart: " . $total_items . "</p>";

// Example of removing an item
//remove_from_cart(102);

// Example of updating quantity
//update_quantity(101, 5);

// Example of clearing the cart
//clear_cart();


?>
