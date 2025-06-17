

<?php
session_start();

// --------------------- Cart Logic ---------------------

// Initialize the cart as an array if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// --------------------- Helper Functions ---------------------

/**
 * Adds an item to the cart.
 *
 * @param string $product_id The unique identifier of the product.
 * @param string $product_name The name of the product.
 * @param int    $quantity    The quantity of the product to add.
 * @param float  $price       The price of the product.
 *
 * @return void
 */
function add_to_cart(string $product_id, string $product_name, int $quantity, float $price) {
    // Check if the product is already in the cart.
    if (isset($_SESSION['cart'][$product_id])) {
        // If it is, increment the quantity.
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        // If not, create a new entry in the cart.
        $_SESSION['cart'][$product_id] = [
            'name'      => $product_name,
            'quantity'  => $quantity,
            'price'     => $price,
        ];
    }
}


/**
 * Updates the quantity of an item in the cart.
 *
 * @param string $product_id The unique identifier of the product.
 * @param int    $new_quantity The new quantity for the product.
 *
 * @return void
 */
function update_cart_quantity(string $product_id, int $new_quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
    }
}

/**
 * Removes an item from the cart.
 *
 * @param string $product_id The unique identifier of the product to remove.
 *
 * @return void
 */
function remove_from_cart(string $product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

/**
 * Calculates the total cart value.
 *
 * @return float The total cart value.
 */
function calculate_cart_total() {
    $total = 0.0;
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['quantity'] * $item['price'];
    }
    return $total;
}

/**
 * Gets the cart items.
 *
 * @return array The cart items.
 */
function get_cart_items() {
    return $_SESSION['cart'];
}

// --------------------- Example Usage (for demonstration) ---------------------
//  This is for illustrating how to use the functions.  In a real application,
//  you'd typically handle this from a form submission.

// Add a product to the cart
add_to_cart('product1', 'Awesome Widget', 2, 19.99);

// Update the quantity of a product
update_cart_quantity('product1', 5);

// Add another product
add_to_cart('product2', 'Super Gadget', 1, 29.99);

// Display the cart items
echo "<h2>Cart Items</h2>";
echo "<ul>";
$cart_items = get_cart_items();
if (count($cart_items) > 0) {
    foreach ($cart_items as $product_id => $item) {
        echo "<li>" . $item['name'] . " - Quantity: " . $item['quantity'] . " - Price: $" . $item['price'] . "</li>";
    }
} else {
    echo "<li>Your cart is empty.</li>";
}
echo "</ul>";

// Calculate and display the total
echo "<p><strong>Total Cart Value: $" . calculate_cart_total() . "</strong></p>";

// Remove a product from the cart (example)
// remove_from_cart('product1');
?>
