

<?php
session_start();

// --- Cart Initialization ---
// If a cart doesn't exist, initialize it as an empty array
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// --- Helper Functions ---

/**
 * Add an item to the cart.
 *
 * @param int $product_id The ID of the product to add.
 * @param int $quantity The quantity of the product to add.
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
 * Remove an item from the cart.
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
 * Update the quantity of an item in the cart.
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
 * Get the total number of items in the cart.
 *
 * @return int
 */
function get_cart_count() {
    $count = 0;
    foreach ($_SESSION['cart'] as $quantity) {
        $count += $quantity;
    }
    return $count;
}

/**
 * Get the cart contents (for display purposes).
 *
 * @return array
 */
function get_cart_items() {
    $items = array();
    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        // In a real application, you would fetch product details here
        // based on the $product_id
        // For demonstration, let's assume we have a dummy product data array
        $product = get_product_details($product_id); // Implement this function to fetch product details
        $items[] = array(
            'id' => $product_id,
            'name' => $product['name'],
            'quantity' => $quantity,
            'price' => $product['price'] // Assuming product has a 'price' attribute
        );
    }
    return $items;
}

// *** Dummy function for fetching product details (replace with your actual data source) ***
function get_product_details($product_id) {
    // In a real application, you'd fetch this from a database
    // Or a product data file.
    // This is just a mock for demonstration purposes.
    $product_data = array(
        1 => array('name' => 'T-Shirt', 'price' => 20),
        2 => array('name' => 'Jeans', 'price' => 50),
        3 => array('name' => 'Shoes', 'price' => 80)
    );
    if (isset($product_data[$product_id])) {
        return $product_data[$product_id];
    } else {
        return null; // Product not found
    }
}


// --- Example Usage (Illustrative - This would typically come from a form submission) ---

// Add a T-Shirt to the cart
add_to_cart(1); // Adds 1 T-Shirt to the cart

// Add 2 Jeans to the cart
add_to_cart(2, 2);

// Update the quantity of the T-Shirt to 3
update_cart_quantity(1, 3);

// Remove the Jeans from the cart
remove_from_cart(2);

// Display the cart contents
$cart_items = get_cart_items();

// Output the cart contents (for demonstration)
echo "<h2>Your Cart:</h2>";
if (empty($cart_items)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cart_items as $item) {
        echo "<li>" . $item['name'] . " - Quantity: " . $item['quantity'] . " - Price: $" . $item['price'] . "</li>";
    }
    echo "</ul>";
}

echo "<p>Total Items in Cart: " . get_cart_count() . "</p>";

?>
