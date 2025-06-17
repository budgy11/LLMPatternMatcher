

<?php

session_start();

// --- Cart Session Variables ---
$cart = array();
$cart_id = "shopping_cart";

// --- Helper Functions ---

/**
 * Add an item to the cart.
 *
 * @param string $product_id The unique ID of the product.
 * @param string $name The name of the product.
 * @param int    $price  The price of the product.
 * @param int    $quantity  The quantity to add.
 */
function add_to_cart(string $product_id, string $name, float $price, int $quantity = 1)
{
    if (!isset($_SESSION[$cart_id][$product_id])) {
        $_SESSION[$cart_id][$product_id] = array(
            'name' => $name,
            'price' => $price,
            'quantity' => $quantity,
            'total' => $price * $quantity
        );
    } else {
        // Item already in cart, update quantity
        $_SESSION[$cart_id][$product_id]['quantity'] += $quantity;
        $_SESSION[$cart_id][$product_id]['total'] = $_SESSION[$cart_id][$product_id]['price'] * $_SESSION[$cart_id][$product_id]['quantity'];
    }
}


/**
 * Update the quantity of an item in the cart.
 *
 * @param string $product_id The unique ID of the product.
 * @param int    $new_quantity The new quantity for the product.
 */
function update_cart_quantity(string $product_id, int $new_quantity)
{
    if (isset($_SESSION[$cart_id][$product_id])) {
        $_SESSION[$cart_id][$product_id]['quantity'] = $new_quantity;
        $_SESSION[$cart_id][$product_id]['total'] = $_SESSION[$cart_id][$product_id]['price'] * $_SESSION[$cart_id][$product_id]['quantity'];
    }
}


/**
 * Remove an item from the cart.
 *
 * @param string $product_id The unique ID of the product to remove.
 */
function remove_from_cart(string $product_id)
{
    unset($_SESSION[$cart_id][$product_id]);
}

/**
 * Get the contents of the cart.
 *
 * @return array The cart array.
 */
function get_cart_contents()
{
    return $_SESSION[$cart_id];
}

/**
 * Calculate the total cost of the cart.
 *
 * @return float The total cost.
 */
function calculate_cart_total()
{
    $total = 0;
    if (isset($_SESSION[$cart_id])) {
        foreach ($_SESSION[$cart_id] as $item) {
            $total_item = $item['price'] * $item['quantity'];
            $total += $total_item;
        }
    }
    return $total;
}

/**
 * Clear the entire cart.
 */
function clear_cart() {
    unset($_SESSION[$cart_id]);
}

// --- Example Usage ---

// 1. Add some items to the cart
add_to_cart("product_1", "T-Shirt", 20.00, 2);
add_to_cart("product_2", "Jeans", 50.00);
add_to_cart("product_3", "Hat", 15.00, 1);

// 2. Update the quantity of an item
update_cart_quantity("product_1", 3);

// 3. Remove an item from the cart
// remove_from_cart("product_2");

// 4. Get the cart contents
$cart_contents = get_cart_contents();
print_r($cart_contents);

// 5. Calculate the total
$total = calculate_cart_total();
echo "Total cart value: $" . number_format($total, 2) . "<br>";

// 6. Clear the cart
// clear_cart();

?>
