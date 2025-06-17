

<?php
session_start();

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// --- Functions for Cart Operations ---

/**
 * Adds an item to the cart.
 *
 * @param int $product_id The ID of the product to add.
 * @param int $quantity The quantity of the product to add.
 * @return void
 */
function add_to_cart($product_id, $quantity = 1) {
    if (!isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = 0;  // Initialize count for new product
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
 * Returns the items in the cart.
 *
 * @return array An array containing the items in the cart.
 */
function get_cart_items() {
    return $_SESSION['cart'];
}


/**
 * Calculates the total price of the cart.
 *
 * @param array $cart_items The cart items array (e.g., from get_cart_items()).
 * @return float The total price.
 */
function calculate_total_price($cart_items) {
    $total = 0;
    foreach ($cart_items as $product_id => $quantity) {
        //  Assume you have a function or data source to get product prices
        $price = get_product_price($product_id); // Replace with your actual price retrieval
        $total_for_item = $price * $quantity;
        $total += $total_for_item;
    }
    return $total;
}

/**
 * Placeholder function to get product price.  Replace with your actual logic.
 * @param int $product_id
 * @return float
 */
function get_product_price($product_id) {
    // In a real application, this would fetch the price of the product
    // from a database or other source.  This is just a placeholder.

    //Example:
    if ($product_id == 1) return 10.00;
    if ($product_id == 2) return 25.00;
    if ($product_id == 3) return 5.00;
    return 0.00; // Return 0 if product isn't found or price isn't set
}


// --- Example Usage (Demonstration) ---

// Add a product to the cart
add_to_cart(1);
add_to_cart(2, 2);  // Add 2 of product 2

// Display the cart items
echo "<h2>Cart Items:</h2>";
echo "<ul>";
$cart_items = get_cart_items();
foreach ($cart_items as $product_id => $quantity) {
    echo "<li>Product ID: " . $product_id . ", Quantity: " . $quantity . "</li>";
}
echo "</ul>";


// Calculate and display the total price
$total = calculate_total_price($cart_items);
echo "<br><h2>Total Price: $" . $total . "</h2>";

// Update the quantity of an item
update_cart_quantity(1, 3); // Change quantity of product 1 to 3

// Display updated cart items
echo "<br><h2>Updated Cart Items:</h2>";
$cart_items = get_cart_items();
foreach ($cart_items as $product_id => $quantity) {
    echo "<li>Product ID: " . $product_id . ", Quantity: " . $quantity . "</li>";
}


//Remove an item
remove_from_cart(2);
echo "<br><h2>Cart Items after removing product 2:</h2>";
$cart_items = get_cart_items();
foreach ($cart_items as $product_id => $quantity) {
    echo "<li>Product ID: " . $product_id . ", Quantity: " . $quantity . "</li>";
}
?>
