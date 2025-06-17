

<?php
session_start();

// Check if the session is already started
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// --- Cart Functions ---

/**
 * Adds an item to the cart.
 *
 * @param int $product_id The ID of the product to add.
 * @param int $quantity The quantity to add.  Defaults to 1 if not provided.
 * @return void
 */
function add_to_cart(int $product_id, int $quantity = 1) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = ['quantity' => $quantity];
    }
}

/**
 * Updates the quantity of an item in the cart.
 *
 * @param int $product_id The ID of the product to update.
 * @param int $quantity The new quantity.
 * @return void
 */
function update_cart_quantity(int $product_id, int $quantity) {
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
function remove_from_cart(int $product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

/**
 * Gets the current cart contents.
 *
 * @return array The cart contents array.
 */
function get_cart_contents() {
    return $_SESSION['cart'] ?? []; // Use ?? to return an empty array if 'cart' is not set.
}

/**
 * Calculates the total price of the cart.
 *
 * @param array $cart_items  The cart items (array of product IDs and quantities).
 * @param array $product_prices An associative array where keys are product IDs and values are prices.
 * @return float The total price.
 */
function calculate_total(array $cart_items, array $product_prices) {
    $total = 0;
    foreach ($cart_items as $product_id => $quantity) {
        if (isset($product_prices[$product_id])) {
            $total += $product_prices[$product_id] * $quantity;
        }
    }
    return $total;
}


// --- Example Usage ---

// 1. Add a product to the cart
add_to_cart(123, 2); // Add 2 of product ID 123

// 2. Add another product
add_to_cart(456, 1);

// 3. Update the quantity of product 123 to 5
update_cart_quantity(123, 5);

// 4. Get the cart contents
$cart = get_cart_contents();
print_r($cart); // Output the cart array (for debugging)

// 5. Calculate the total
$product_prices = [
    123 => 10.00,
    456 => 25.00
];
$total = calculate_total($cart, $product_prices);
echo "Total price: $" . number_format($total, 2) . "<br>"; // Output the total with formatting

// 6. Remove a product
remove_from_cart(456);

// 7. Get the cart contents after removing an item
$cart_after_removal = get_cart_contents();
print_r($cart_after_removal); // Verify that item 456 is no longer in the cart.

?>
